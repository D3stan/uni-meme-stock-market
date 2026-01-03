<?php

namespace App\Notifications;

use App\Models\Market\Meme;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class MemeApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Meme $meme
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray(object $notifiable): array
    {
        $tradingStartsAt = $this->meme->approved_at->addHours(8);
        
        return [
            'type' => 'meme_approved',
            'title' => 'Your Meme Was Approved! 🚀',
            'message' => sprintf(
                'Your meme %s ($%s) has been approved! Trading starts at %s.',
                $this->meme->title,
                $this->meme->ticker,
                $tradingStartsAt->format('Y-m-d H:i')
            ),
            'meme_id' => $this->meme->id,
            'ticker' => $this->meme->ticker,
            'trading_starts_at' => $tradingStartsAt->toIso8601String(),
        ];
    }
}
