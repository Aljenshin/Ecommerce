@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' - Winbreaker')

@section('content')
<h1 class="text-3xl font-bold mb-6">Order #{{ $order->order_number }}</h1>

<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="font-semibold text-lg mb-2">Order Status</h3>
            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold 
                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                @else bg-red-100 text-red-800
                @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        <div>
            <h3 class="font-semibold text-lg mb-2">Order Date</h3>
            <p>{{ $order->created_at->format('F d, Y h:i A') }}</p>
        </div>
    </div>
    
    <div class="border-t pt-6">
        <h3 class="font-semibold text-lg mb-4">Shipping Address</h3>
        <p>{{ $order->shipping_address }}</p>
        <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</p>
        <p>{{ $order->shipping_country }}</p>
        @if($order->phone)
            <p class="mt-2">Phone: {{ $order->phone }}</p>
        @endif
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-6">Order Items</h2>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($order->orderItems as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-16 w-16 object-cover rounded">
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                @if($item->size || $item->color)
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($item->size)
                                            Size: {{ $item->size }}
                                        @endif
                                        @if($item->color)
                                            <span class="ml-2">Color: {{ $item->color }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${{ number_format($item->price, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->quantity }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ${{ number_format($item->quantity * $item->price, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right text-sm font-semibold">Total:</td>
                    <td class="px-6 py-4 text-sm font-bold text-lg">${{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">← Back to Orders</a>
</div>
@endsection

