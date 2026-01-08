<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use App\Services\NotificationDispatcher;
use App\Models\Market\Meme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    protected AdminService $adminService;
    protected NotificationDispatcher $notificationDispatcher;

    public function __construct(AdminService $adminService, NotificationDispatcher $notificationDispatcher)
    {
        $this->adminService = $adminService;
        $this->notificationDispatcher = $notificationDispatcher;
    }

    /**
     * Prepares and renders the main administration dashboard view with current system statistics.
     *
     * @return View
     */
    public function index(): View
    {
        $stats = $this->adminService->getDashboardStats();
        return view('pages.admin.index', $stats);
    }

    /**
     * Retrieves and displays the system-wide transaction ledger, optionally filtered by transaction type.
     *
     * @param Request $request
     * @return View
     */
    public function ledger(Request $request): View
    {
        $type = $request->get('type', 'all');
        
        $transactions = $this->adminService->getTransactions($type);
        $stats = $this->adminService->getTransactionStats();

        return view('pages.admin.ledger', [
            'transactions' => $transactions,
            'stats' => $stats,
            'currentType' => $type,
        ]);
    }

    /**
     * Displays a list of system notifications, allowing filtering by status or type.
     *
     * @param Request $request
     * @return View
     */
    public function notifications(Request $request): View
    {
        $filter = $request->get('filter', 'all');
        
        $notifications = $this->adminService->getNotifications($filter);
        $stats = $this->adminService->getNotificationStats();

        return view('pages.admin.notifications', [
            'notifications' => $notifications,
            'stats' => $stats,
            'currentFilter' => $filter,
        ]);
    }

    /**
     * Lists market communications and events with their current status and statistics.
     *
     * @param Request $request
     * @return View
     */
    public function events(Request $request): View
    {
        $filter = $request->get('filter', 'all');
        
        $communications = $this->adminService->getMarketCommunications($filter);
        $stats = $this->adminService->getMarketCommunicationStats();

        return view('pages.admin.events', [
            'communications' => $communications,
            'stats' => $stats,
            'currentFilter' => $filter,
        ]);
    }

    /**
     * Updates an existing market communication entry.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function updateEvent(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $this->adminService->updateMarketCommunication($id, [
            'message' => $validated['message'],
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.events')->with('success', 'Evento aggiornato con successo');
    }

    /**
     * Creates a new market communication initiated by the currently authenticated admin.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function createEvent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $this->adminService->createMarketCommunication([
            'admin_id' => Auth::id(),
            'message' => $validated['message'],
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.events')->with('success', 'Evento creato con successo');
    }

    /**
     * Displays the meme moderation interface, showing pending submissions and approval statistics.
     *
     * @param Request $request
     * @return View
     */
    public function moderation(Request $request): View
    {
        $filter = $request->get('filter', 'all');
        
        $memes = $this->adminService->getMemes($filter);
        $stats = $this->adminService->getMemeStats();

        return view('pages.admin.moderation', [
            'memes' => $memes,
            'stats' => $stats,
            'currentFilter' => $filter,
        ]);
    }

    /**
     * Approves a specific meme, optionally updating its alternative text, and dispatches an approval notification.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function approveMeme(Request $request, int $id): RedirectResponse
    {
        if ($request->has('text_alt')) {
            $meme = Meme::findOrFail($id);
            $meme->text_alt = $request->input('text_alt');
            $meme->save();
        }
        
        $this->adminService->approveMeme($id, Auth::id());
        $meme = Meme::findOrFail($id);
        $this->notificationDispatcher->memeApproved($meme);

        return redirect()->route('admin.moderation')->with('success', 'Meme approvato con successo');
    }

    /**
     * Rejects a meme submission and notifies the creator.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function rejectMeme(int $id): RedirectResponse
    {
        $this->adminService->rejectMeme($id);
        $meme = Meme::findOrFail($id);
        $this->notificationDispatcher->memeRejected($meme);

        return redirect()->route('admin.moderation')->with('success', 'Meme rifiutato con successo');
    }
}
