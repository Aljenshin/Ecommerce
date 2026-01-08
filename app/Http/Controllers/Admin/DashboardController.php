<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // High-level analytics and monitoring stats
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_staff' => User::whereIn('role', ['hr', 'staff'])->count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'monthly_revenue' => Order::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
            'today_sales' => Order::where('status', '!=', 'cancelled')
                ->whereDate('created_at', today())
                ->sum('total_amount'),
            'total_orders_count' => Order::count(), // For analytics only, not displayed in orders table
        ];

        // Product performance - Top selling products
        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        // Low stock alerts
        $lowStockProducts = Product::where('stock', '<', 10)->orderBy('stock', 'asc')->get();

        // Recent system activity (placeholder for future implementation)
        $recentActivity = [];

        return view('admin.dashboard', compact('stats', 'topProducts', 'lowStockProducts', 'recentActivity'));
    }
}
