<?php

namespace App\Services;

use App\Models\Market\Meme;

class NotificationDispatcher
{
    /**
     * Create a new NotificationDispatcher instance.
     */
    public function __construct(private NotificationService $notificationService) {}

    /**
     * Send notification when a meme is approved.
     *
     * @param Meme $meme
     * @return void
     */
    public function memeApproved(Meme $meme): void
    {
        $this->notificationService->createNotification(
            $meme->creator,
            'Il tuo Meme è stato Approvato! 🚀',
            sprintf(
                'Il tuo meme "%s" ($%s) è stato approvato! Il trading inizierà a breve.',
                $meme->title,
                $meme->ticker
            )
        );
    }
}
