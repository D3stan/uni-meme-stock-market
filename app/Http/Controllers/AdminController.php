<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Show admin panel dashboard.
     */
    public function index(): View
    {
        return view('pages.admin.index');
    }

    /**
     * Show transaction ledger.
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
     * Show notifications list.
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
     * Show market communications list.
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
}
