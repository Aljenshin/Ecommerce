@extends('layouts.app')

@section('title', 'Admin Dashboard - Uni-H-Pen')

@section('content')
<h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>
<p class="text-gray-600 mb-8">High-level analytics, monitoring, and system management</p>

<!-- Key Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Revenue</h3>
        <p class="text-3xl font-bold text-green-600">${{ number_format($stats['total_revenue'], 2) }}</p>
        <p class="text-xs text-gray-500 mt-1">All time</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-gray-600 text-sm font-semibold mb-2">Monthly Revenue</h3>
        <p class="text-3xl font-bold text-blue-600">${{ number_format($stats['monthly_revenue'], 2) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ now()->format('F Y') }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-gray-600 text-sm font-semibold mb-2">Today's Sales</h3>
        <p class="text-3xl font-bold text-purple-600">${{ number_format($stats['today_sales'], 2) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ today()->format('M d, Y') }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Products</h3>
        <p class="text-3xl font-bold text-yellow-600">{{ $stats['total_products'] }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $stats['active_products'] }} active</p>
    </div>
</div>

<!-- Secondary Metrics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Customers</h3>
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['total_customers'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-gray-600 text-sm font-semibold mb-2">Staff Accounts</h3>
        <p class="text-2xl font-bold text-pink-600">{{ $stats['total_staff'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Orders</h3>
        <p class="text-2xl font-bold text-teal-600">{{ $stats['total_orders_count'] }}</p>
        <p class="text-xs text-gray-500 mt-1">For analytics only</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Top Performing Products -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Top Performing Products</h2>
        @if($topProducts->count() > 0)
            <div class="space-y-3">
                @foreach($topProducts as $index => $product)
                <div class="flex justify-between items-center border-b pb-3">
                    <div class="flex items-center">
                        <span class="text-gray-400 font-bold mr-3">#{{ $index + 1 }}</span>
                        <div>
                            <p class="font-semibold">{{ $product->name }}</p>
                            <p class="text-sm text-gray-600">{{ $product->order_items_count }} orders</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">${{ number_format($product->price, 2) }}</p>
                        <p class="text-xs text-gray-500">Stock: {{ $product->stock }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No product performance data yet.</p>
        @endif
    </div>

    <!-- Low Stock Alerts -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Low Stock Alerts</h2>
        @if($lowStockProducts->count() > 0)
            <div class="space-y-3">
                @foreach($lowStockProducts as $product)
                <div class="flex justify-between items-center border-b pb-3">
                    <div>
                        <p class="font-semibold">{{ $product->name }}</p>
                        <p class="text-sm text-gray-600">Current stock: <span class="font-semibold text-red-600">{{ $product->stock }}</span></p>
                    </div>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:underline text-sm font-semibold">
                        Restock
                    </a>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">All products are well stocked. ✅</p>
        @endif
    </div>
</div>

<!-- Sales Analytics Section -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-2xl font-bold mb-4">Sales Analytics</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center p-4 bg-blue-50 rounded-lg">
            <p class="text-sm text-gray-600 mb-2">Average Order Value</p>
            <p class="text-2xl font-bold text-blue-600">
                ${{ $stats['total_orders_count'] > 0 ? number_format($stats['total_revenue'] / $stats['total_orders_count'], 2) : '0.00' }}
            </p>
        </div>
        <div class="text-center p-4 bg-green-50 rounded-lg">
            <p class="text-sm text-gray-600 mb-2">Conversion Rate</p>
            <p class="text-2xl font-bold text-green-600">
                {{ $stats['total_customers'] > 0 ? number_format(($stats['total_orders_count'] / $stats['total_customers']) * 100, 1) : '0' }}%
            </p>
        </div>
        <div class="text-center p-4 bg-purple-50 rounded-lg">
            <p class="text-sm text-gray-600 mb-2">Products Sold</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['total_orders_count'] }}</p>
        </div>
    </div>
</div>

<!-- Customer Reviews Section (Placeholder) -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-2xl font-bold mb-4">Customer Reviews</h2>
    <div class="text-center py-8">
        <p class="text-gray-600 mb-2">Review management system coming soon</p>
        <p class="text-sm text-gray-500">This section will display customer reviews, ratings, and feedback management</p>
    </div>
</div>

<!-- System Activity Overview -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-2xl font-bold mb-4">System Activity Overview</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold mb-3">Product Status</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Active Products</span>
                    <span class="font-semibold">{{ $stats['active_products'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Inactive Products</span>
                    <span class="font-semibold">{{ $stats['total_products'] - $stats['active_products'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Low Stock Items</span>
                    <span class="font-semibold text-red-600">{{ $lowStockProducts->count() }}</span>
                </div>
            </div>
        </div>
        <div>
            <h3 class="font-semibold mb-3">User Management</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Customers</span>
                    <span class="font-semibold">{{ $stats['total_customers'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Staff Members</span>
                    <span class="font-semibold">{{ $stats['total_staff'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Users</span>
                    <span class="font-semibold">{{ $stats['total_customers'] + $stats['total_staff'] + 1 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.users.index') }}" class="bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700 text-center">
            Manage Users & Roles
        </a>
        <a href="{{ route('admin.products.index') }}" class="bg-green-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-green-700 text-center">
            Manage Products
        </a>
        <a href="{{ route('admin.categories.index') }}" class="bg-purple-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-purple-700 text-center">
            Manage Categories
        </a>
        <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-indigo-700 text-center">
            Create Staff Account
        </a>
    </div>
</div>
@endsection
