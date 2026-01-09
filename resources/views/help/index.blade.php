@extends('layouts.app')

@section('title', 'Help Center - Uni-H-Pen')

@section('content')
<div class="mb-8">
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg p-8 md:p-12 mb-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Help Center</h1>
        <p class="text-xl mb-6">How can we help you today?</p>
        <div class="relative max-w-2xl">
            <input type="text" placeholder="Search for help..." class="w-full px-6 py-4 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
            <button class="absolute right-2 top-2 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Search
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('help.show', 'faq') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow">
            <div class="text-4xl mb-4">❓</div>
            <h3 class="text-xl font-bold mb-2">Frequently Asked Questions</h3>
            <p class="text-gray-600 text-sm">Find answers to common questions</p>
        </a>

        <a href="{{ route('help.show', 'shipping') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow">
            <div class="text-4xl mb-4">🚚</div>
            <h3 class="text-xl font-bold mb-2">Shipping & Delivery</h3>
            <p class="text-gray-600 text-sm">Learn about shipping options and delivery times</p>
        </a>

        <a href="{{ route('help.show', 'returns') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow">
            <div class="text-4xl mb-4">↩️</div>
            <h3 class="text-xl font-bold mb-2">Returns & Refunds</h3>
            <p class="text-gray-600 text-sm">How to return items and get refunds</p>
        </a>

        <a href="{{ route('help.show', 'payments') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow">
            <div class="text-4xl mb-4">💳</div>
            <h3 class="text-xl font-bold mb-2">Payments</h3>
            <p class="text-gray-600 text-sm">Payment methods and security</p>
        </a>

        <a href="{{ route('help.show', 'account') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow">
            <div class="text-4xl mb-4">👤</div>
            <h3 class="text-xl font-bold mb-2">Account & Orders</h3>
            <p class="text-gray-600 text-sm">Manage your account and track orders</p>
        </a>

        <a href="{{ route('help.show', 'contact') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow">
            <div class="text-4xl mb-4">📞</div>
            <h3 class="text-xl font-bold mb-2">Contact Us</h3>
            <p class="text-gray-600 text-sm">Get in touch with our support team</p>
        </a>
    </div>

    <!-- Quick Links -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4">Quick Links</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="font-semibold mb-2">Shopping Help</h3>
                <ul class="space-y-1 text-sm text-gray-600">
                    <li><a href="{{ route('help.show', 'shipping') }}" class="hover:text-blue-600">How to place an order</a></li>
                    <li><a href="{{ route('help.show', 'payments') }}" class="hover:text-blue-600">Payment options</a></li>
                    <li><a href="{{ route('help.show', 'returns') }}" class="hover:text-blue-600">Return policy</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Account Help</h3>
                <ul class="space-y-1 text-sm text-gray-600">
                    <li><a href="{{ route('help.show', 'account') }}" class="hover:text-blue-600">Track your order</a></li>
                    <li><a href="{{ route('help.show', 'account') }}" class="hover:text-blue-600">Change password</a></li>
                    <li><a href="{{ route('help.show', 'contact') }}" class="hover:text-blue-600">Contact support</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

