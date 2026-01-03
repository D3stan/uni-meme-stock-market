<?php

namespace App\Notifications;

use App\Models\Market\Meme;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class MemeProposedNotification extends Notification implements ShouldQueue
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
        return [
            'type' => 'meme_proposed',
            'title' => 'New Meme Pending Approval',
            'message' => sprintf(
                'User %s proposed a new meme: %s ($%s)',
                $this->meme->creator->name,
                $this->meme->title,
                $this->meme->ticker
            ),
            'meme_id' => $this->meme->id,
            'ticker' => $this->meme->ticker,
            'creator_name' => $this->meme->creator->name,
        ];
    }
}
