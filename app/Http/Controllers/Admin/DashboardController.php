<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\OrderStatus;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index()
    {
        $today = Carbon::today();
        $weekAgo = Carbon::now()->subDays(7);

        $stats = [
            'totalBooks' => Book::count(),
            'totalUsers' => User::count(),
            'totalOrders' => Order::count(),
            'totalRevenue' => Order::where('status', OrderStatus::COMPLETED)->sum('total_amount'),
            'todayRevenue' => Order::where('status', OrderStatus::COMPLETED)
                ->whereDate('updated_at', $today)
                ->sum('total_amount'),
            'weekRevenue' => Order::where('status', OrderStatus::COMPLETED)
                ->where('updated_at', '>=', $weekAgo)
                ->sum('total_amount'),
            'pendingOrders' => Order::where('status', OrderStatus::UNPAID)->count(),
            'packingOrders' => Order::where('status', OrderStatus::PACKING)->count(),
            'shippedOrders' => Order::where('status', OrderStatus::SHIPPED)->count(),
            'completedOrders' => Order::where('status', OrderStatus::COMPLETED)->count(),
            'cancelledOrders' => Order::where('status', OrderStatus::CANCELLED)->count(),
            'returnedOrders' => Order::where('status', OrderStatus::RETURNED)->count(),
            'recentOrders' => Order::with('user')->latest()->take(8)->get(),
            'todayOrders' => Order::whereDate('created_at', $today)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
