<?php

namespace App\Jobs;

use Throwable;
use App\Models\Market\Meme;
use App\Services\GeminiService;
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
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying.
     */
    public int $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Meme $meme
    ) {}

    /**
     * Execute the job.
     */
    public function handle(GeminiService $geminiService): void
    {
        // Skip if Gemini is not configured
        if (!$geminiService->isConfigured()) {
            Log::info('Skipping AI processing - Gemini not configured', [
                'meme_id' => $this->meme->id
            ]);
            return;
        }

        // Build the image path
        $imagePath = "data/{$this->meme->creator_id}/{$this->meme->image_path}";

        Log::info('Starting AI meme analysis', [
            'meme_id' => $this->meme->id,
            'image_path' => $imagePath
        ]);

        // Call Gemini API
        $result = $geminiService->analyzeMeme($imagePath);

        // If analysis failed, meme stays in pending
        if (!$result) {
            Log::warning('AI analysis failed, meme stays pending', [
                'meme_id' => $this->meme->id
            ]);
            return;
        }

        // Update meme with results
        $updateData = [
            'text_alt' => $result['alt_text'] ?? null,
        ];

        // Approve if successful
        if (!empty($result['is_appropriate']) && $result['is_appropriate'] === true) {
            $updateData['status'] = 'approved';
            $updateData['approved_at'] = now();
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
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('ProcessMemeWithAI job failed', [
            'meme_id' => $this->meme->id,
            'error' => $exception->getMessage()
        ]);
    }
}
