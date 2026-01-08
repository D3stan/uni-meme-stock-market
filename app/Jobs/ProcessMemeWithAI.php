<?php

namespace App\Jobs;

use Throwable;
use App\Models\Market\Meme;
use App\Services\GeminiService;
use App\Services\NotificationDispatcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessMemeWithAI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maximum number of retry attempts if the job fails.
     */
    public int $tries = 3;

    /**
     * Number of seconds to wait between retry attempts.
     */
    public int $backoff = 10;

    /**
     * Initialize the job with the meme to be analyzed.
     *
     * @param Meme $meme
     */
    public function __construct(
        public Meme $meme
    ) {}

    /**
     * Process meme through AI content analysis and auto-moderation.
     * 
     * Analyzes meme image via Gemini API to generate alt text and determine content appropriateness.
     * If AI deems content appropriate, auto-approves and dispatches approval notification.
     * Otherwise, meme remains pending for manual review. Gracefully handles API unavailability.
     *
     * @param GeminiService $geminiService
     * @param NotificationDispatcher $notificationDispatcher
     * @return void
     */
    public function handle(GeminiService $geminiService, NotificationDispatcher $notificationDispatcher): void
    {
        if (!$geminiService->isConfigured()) {
            Log::info('Skipping AI processing - Gemini not configured', [
                'meme_id' => $this->meme->id
            ]);
            return;
        }

        $imagePath = "data/{$this->meme->creator_id}/{$this->meme->image_path}";

        Log::info('Starting AI meme analysis', [
            'meme_id' => $this->meme->id,
            'image_path' => $imagePath
        ]);

        $result = $geminiService->analyzeMeme($imagePath);

        if (!$result) {
            Log::warning('AI analysis failed, meme stays pending', [
                'meme_id' => $this->meme->id
            ]);
            return;
        }

        $updateData = [
            'text_alt' => $result['alt_text'] ?? null,
        ];

        if (!empty($result['is_appropriate']) && $result['is_appropriate'] === true) {
            $updateData['status'] = 'approved';
            $updateData['approved_at'] = now();
            $notificationDispatcher->memeApproved($this->meme);
            Log::info('Meme auto-approved by AI', [
                'meme_id' => $this->meme->id
            ]);
        } else {
            Log::info('Meme flagged by AI, pending manual review', [
                'meme_id' => $this->meme->id,
                'motivation' => $result['motivation'] ?? 'unknown'
            ]);
        }

        $this->meme->update($updateData);
    }

    /**
     * Log detailed failure information when job exhausts all retry attempts.
     *
     * @param Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception): void
    {
        Log::error('ProcessMemeWithAI job failed', [
            'meme_id' => $this->meme->id,
            'error' => $exception->getMessage()
        ]);
    }
}
