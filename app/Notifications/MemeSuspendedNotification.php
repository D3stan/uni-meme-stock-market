<?php

namespace App\Notifications;

use App\Models\Market\Meme;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class MemeSuspendedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Meme $meme,
        public string $reason
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
        return [
            'type' => 'meme_suspended',
            'title' => 'Trading Suspended ⚠️',
            'message' => sprintf(
                'Trading for %s ($%s) has been suspended. Reason: %s',
                $this->meme->title,
                $this->meme->ticker,
                $this->reason
            ),
            'meme_id' => $this->meme->id,
            'ticker' => $this->meme->ticker,
            'reason' => $this->reason,
        ];
    }
}
