<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\OrderStatus;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index()
    {
        $stats = [
            'totalBooks' => Book::count(),
            'totalUsers' => User::where('role', 'user')->count(),
            'totalOrders' => Order::count(),
            'totalRevenue' => Order::where('status', OrderStatus::COMPLETED)->sum('total_amount'),
            'pendingOrders' => Order::where('status', OrderStatus::UNPAID)->count(),
            'recentOrders' => Order::latest()->take(10)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
