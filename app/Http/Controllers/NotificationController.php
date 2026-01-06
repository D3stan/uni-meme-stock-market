<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get notifications list (partial HTML for slide panel).
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $notifications = $this->notificationService->getNotificationsGrouped($user);
        $unreadCount = $this->notificationService->getUnreadCount($user);

        return view('pages.notification.partials.list', [
            'unread' => $notifications['unread'],
            'read' => $notifications['read'],
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Get unread count (for badge updates).
     *
     * @return JsonResponse
     */
    public function unreadCount(): JsonResponse
    {
        $user = Auth::user();
        $count = $this->notificationService->getUnreadCount($user);

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Get a single notification detail.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = Auth::user();
        $notification = $this->notificationService->getNotification($id, $user);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notifica non trovata',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'notification' => [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'is_read' => $notification->is_read,
                'created_at' => $notification->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Mark a notification as read.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $user = Auth::user();
        $success = $this->notificationService->markAsRead($request->id, $user);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Notifica non trovata',
            ], 404);
        }

        $newCount = $this->notificationService->getUnreadCount($user);

        return response()->json([
            'success' => true,
            'unreadCount' => $newCount,
        ]);
    }

    /**
     * Mark all notifications as read.
     *
     * @return JsonResponse
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::user();
        $count = $this->notificationService->markAllAsRead($user);

        return response()->json([
            'success' => true,
            'marked' => $count,
            'unreadCount' => 0,
        ]);
    }
}
