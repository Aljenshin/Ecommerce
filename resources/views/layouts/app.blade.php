<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Uni-H-Pen - E-commerce')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                                <img src="{{ asset('images/pen.jpg') }}" alt="Uni-H-Pen" class="h-10 w-auto">
                                <span class="text-2xl font-bold text-blue-600">Uni-H-Pen Admin</span>
                            </a>
                        @elseif(auth()->user()->hasRole('hr'))
                            <a href="{{ route('hr.dashboard') }}" class="flex items-center space-x-2">
                                <img src="{{ asset('images/pen.jpg') }}" alt="Uni-H-Pen" class="h-10 w-auto">
                                <span class="text-2xl font-bold text-blue-600">Uni-H-Pen HR</span>
                            </a>
                        @elseif(auth()->user()->hasRole('staff'))
                            <a href="{{ route('staff.dashboard') }}" class="flex items-center space-x-2">
                                <img src="{{ asset('images/pen.jpg') }}" alt="Uni-H-Pen" class="h-10 w-auto">
                                <span class="text-2xl font-bold text-blue-600">Uni-H-Pen Staff</span>
                            </a>
                        @else
                            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                                <img src="{{ asset('images/pen.jpg') }}" alt="Uni-H-Pen" class="h-10 w-auto">
                                <span class="text-2xl font-bold text-blue-600">Uni-H-Pen</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <img src="{{ asset('images/pen.jpg') }}" alt="Uni-H-Pen" class="h-10 w-auto">
                            <span class="text-2xl font-bold text-blue-600">Uni-H-Pen</span>
                        </a>
                    @endauth
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            <a href="{{ route('admin.users.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Users</a>
                            <a href="{{ route('admin.products.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Products</a>
                            <a href="{{ route('admin.categories.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Categories</a>
                            
                            <!-- Notifications Icon for Admin -->
                            @php
                                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            <a href="{{ route('notifications.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium relative" title="Notifications">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                                @endif
                            </a>
                        @elseif(auth()->user()->hasRole('hr'))
                            <a href="{{ route('hr.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            <a href="{{ route('hr.employees.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Employees</a>
                            <a href="{{ route('hr.announcements.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Announcements</a>
                            <a href="{{ route('hr.leaves.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Leaves</a>
                            <a href="{{ route('hr.payroll.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Payroll</a>
                            
                            <!-- Notifications Icon for HR -->
                            @php
                                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            <a href="{{ route('notifications.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium relative" title="Notifications">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                                @endif
                            </a>
                        @elseif(auth()->user()->hasRole('staff'))
                            <a href="{{ route('staff.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            <a href="{{ route('admin.products.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Products</a>
                            <a href="{{ route('staff.attendance.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Attendance</a>
                            <a href="{{ route('staff.leaves.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Leaves</a>
                            <a href="{{ route('staff.payroll.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Payroll</a>
                            
                            <!-- Notifications Icon for Staff -->
                            @php
                                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            <a href="{{ route('notifications.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium relative" title="Notifications">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                                @endif
                            </a>
                        @else
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Products</a>
                            <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium relative" title="Shopping Cart" id="cart-link">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                @php
                                    $cartCount = auth()->user()->cartItems()->count();
                                @endphp
                                @if($cartCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-semibold" id="cart-badge">
                                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">My Orders</a>
                            
                            <!-- Notifications Icon -->
                            @php
                                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            <a href="{{ route('notifications.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium relative" title="Notifications">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                                @endif
                            </a>
                            
                            <!-- Help Icon -->
                            <a href="{{ route('help.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium" title="Help">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </a>
                            
                            <!-- Language Selector -->
                            <form method="POST" action="{{ route('language.change') }}" class="inline" id="language-form">
                                @csrf
                                <select name="language" onchange="document.getElementById('language-form').submit()" class="text-gray-700 border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="en" {{ auth()->user()->language == 'en' ? 'selected' : '' }}>🇺🇸 EN</option>
                                    <option value="fil" {{ auth()->user()->language == 'fil' ? 'selected' : '' }}>🇵🇭 FIL</option>
                                    <option value="ru" {{ auth()->user()->language == 'ru' ? 'selected' : '' }}>🇷🇺 RU</option>
                                    <option value="ja" {{ auth()->user()->language == 'ja' ? 'selected' : '' }}>🇯🇵 JA</option>
                                </select>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Logout</button>
                        </form>
                        <span class="text-gray-600 text-sm">{{ auth()->user()->name }}</span>
                    @else
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Products</a>
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Register</a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-blue-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Users</a>
                        <a href="{{ route('admin.products.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Products</a>
                        <a href="{{ route('admin.categories.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Categories</a>
                    @elseif(auth()->user()->hasRole('hr'))
                        <a href="{{ route('hr.dashboard') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                        <a href="{{ route('hr.employees.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Employees</a>
                        <a href="{{ route('hr.announcements.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Announcements</a>
                        <a href="{{ route('hr.leaves.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Leaves</a>
                        <a href="{{ route('hr.payroll.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Payroll</a>
                    @elseif(auth()->user()->hasRole('staff'))
                        <a href="{{ route('staff.dashboard') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                        <a href="{{ route('admin.products.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Products</a>
                        <a href="{{ route('staff.attendance.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Attendance</a>
                        <a href="{{ route('staff.leaves.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Leaves</a>
                        <a href="{{ route('staff.payroll.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Payroll</a>
                    @else
                        <a href="{{ route('home') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Home</a>
                        <a href="{{ route('products.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Products</a>
                        <a href="{{ route('cart.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium relative" id="mobile-cart-link">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Cart
                            </span>
                            @php
                                $cartCount = auth()->user()->cartItems()->count();
                            @endphp
                            @if($cartCount > 0)
                                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-semibold">
                                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('orders.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">My Orders</a>
                    @endif
                    
                    <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-blue-600 text-base font-medium">Logout</button>
                    </form>
                    <span class="block text-gray-600 text-sm px-3 py-2">{{ auth()->user()->name }}</span>
                @else
                    <a href="{{ route('home') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Home</a>
                    <a href="{{ route('products.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Products</a>
                    <a href="{{ route('login') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Login</a>
                    <a href="{{ route('register') }}" class="block bg-blue-600 text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Toast Notifications -->
    @if(session('success'))
        <div id="toast-success" class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden transform transition-all duration-500 ease-in-out">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900">Success!</p>
                        <p class="mt-1 text-sm text-gray-500">{{ session('success') }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button onclick="document.getElementById('toast-success').remove()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="toast-error" class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden transform transition-all duration-500 ease-in-out">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900">Error!</p>
                        <p class="mt-1 text-sm text-gray-500">{{ session('error') }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button onclick="document.getElementById('toast-error').remove()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Uni-H-Pen</h3>
                    <p class="text-gray-400">Your one-stop shop for quality apparel and accessories.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-gray-400 hover:text-white">Products</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <p class="text-gray-400">Email: info@uni-h-pen.com</p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Uni-H-Pen. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });

        // Auto-dismiss toast notifications after 5 seconds
        const toastSuccess = document.getElementById('toast-success');
        const toastError = document.getElementById('toast-error');
        
        if (toastSuccess) {
            setTimeout(() => {
                toastSuccess.style.opacity = '0';
                toastSuccess.style.transform = 'translateX(100%)';
                setTimeout(() => toastSuccess.remove(), 500);
            }, 5000);
        }
        
        if (toastError) {
            setTimeout(() => {
                toastError.style.opacity = '0';
                toastError.style.transform = 'translateX(100%)';
                setTimeout(() => toastError.remove(), 500);
            }, 5000);
        }

        // Real-time notification polling (every 30 seconds)
        @auth
        function updateNotificationCount() {
            fetch('{{ route("notifications.count") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                const notificationLinks = document.querySelectorAll('a[href="{{ route("notifications.index") }}"]');
                notificationLinks.forEach(notificationLink => {
                    let badge = notificationLink.querySelector('.bg-red-500');
                    if (data.unread_count > 0) {
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center';
                            notificationLink.appendChild(badge);
                        }
                        badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    } else if (badge) {
                        badge.remove();
                    }
                });
            })
            .catch(error => console.error('Error fetching notifications:', error));
        }

        // Update cart badge count
        function updateCartBadge() {
            fetch('{{ route("cart.count") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                const cartCount = data.count || 0;
                
                // Update desktop cart link
                const cartLink = document.getElementById('cart-link');
                if (cartLink) {
                    let badge = cartLink.querySelector('#cart-badge');
                    if (cartCount > 0) {
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-semibold';
                            badge.id = 'cart-badge';
                            cartLink.appendChild(badge);
                        }
                        badge.textContent = cartCount > 99 ? '99+' : cartCount;
                    } else if (badge) {
                        badge.remove();
                    }
                }
                
                // Update mobile cart link
                const mobileCartLink = document.getElementById('mobile-cart-link');
                if (mobileCartLink) {
                    let badge = mobileCartLink.querySelector('.bg-red-500');
                    if (cartCount > 0) {
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'absolute top-2 right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-semibold';
                            mobileCartLink.appendChild(badge);
                        }
                        badge.textContent = cartCount > 99 ? '99+' : cartCount;
                    } else if (badge) {
                        badge.remove();
                    }
                }
            })
            .catch(error => console.error('Error updating cart badge:', error));
        }

        // Update immediately and then every 30 seconds
        updateNotificationCount();
        setInterval(updateNotificationCount, 30000);
        
        // Update cart badge on page load and after cart actions
        @if(!auth()->user()->isAdmin() && !auth()->user()->hasRole('hr') && !auth()->user()->hasRole('staff'))
        updateCartBadge();
        // Listen for cart updates
        document.addEventListener('cartUpdated', updateCartBadge);
        // Also update after a delay to catch server-side changes
        setInterval(updateCartBadge, 30000);
        @endif
        @endauth
    </script>
</body>
</html>

