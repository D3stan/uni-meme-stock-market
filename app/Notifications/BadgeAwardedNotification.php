<?php

namespace App\Notifications;

use App\Models\Gamification\Badge;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BadgeAwardedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Badge $badge
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
            'type' => 'badge_awarded',
            'title' => 'New Badge Unlocked! 🏆',
            'message' => sprintf(
                'Congratulations! You earned the "%s" badge: %s',
                $this->badge->name,
                $this->badge->description
            ),
            'badge_id' => $this->badge->id,
            'badge_name' => $this->badge->name,
            'icon_path' => $this->badge->icon_path,
        ];
    }
}
