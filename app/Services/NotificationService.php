<?php

namespace App\Services;

use App\Models\User;
use App\Models\Utility\Notification;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Get a single notification by ID (for modal detail).
     */
    public function getNotification(int $notificationId, User $user): ?Notification
    {
        return Notification::forUser($user->id)
            ->where('id', $notificationId)
            ->first();
    }

    /**
     * Get all notifications for a user, separated by read status.
     *
     * @return array{unread: Collection, read: Collection}
     */
    public function getNotificationsGrouped(User $user): array
    {
        $notifications = Notification::forUser($user->id)
            ->orderByDesc('created_at')
            ->get();

        return [
            'unread' => $notifications->where('is_read', false)->values(),
            'read' => $notifications->where('is_read', true)->values(),
        ];
    }

    /**
     * Get unread notifications count for a user.
     */
    public function getUnreadCount(User $user): int
    {
        return Notification::forUser($user->id)->unread()->count();
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(int $notificationId, User $user): bool
    {
        $notification = $this->getNotification($notificationId, $user);

        if (! $notification) {
            return false;
        }

        $notification->update(['is_read' => true]);

        return true;
    }

    /**
     * Mark all notifications as read for a user.
     *
     * @return int Number of notifications marked as read
     */
    public function markAllAsRead(User $user): int
    {
        return Notification::forUser($user->id)
            ->unread()
            ->update(['is_read' => true]);
    }

    /**
     * Create a notification for a user.
     */
    public function createNotification(User $user, string $title, string $message): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);
    }
}
