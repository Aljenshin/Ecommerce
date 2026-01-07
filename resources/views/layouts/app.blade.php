<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Winbreaker - E-commerce')</title>
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
                            <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-blue-600">Winbreaker Admin</a>
                        @elseif(auth()->user()->hasRole('hr'))
                            <a href="{{ route('hr.dashboard') }}" class="text-2xl font-bold text-blue-600">Winbreaker HR</a>
                        @elseif(auth()->user()->hasRole(['staff', 'uploader']))
                            <a href="{{ route('staff.dashboard') }}" class="text-2xl font-bold text-blue-600">Winbreaker Staff</a>
                        @else
                            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">Winbreaker</a>
                        @endif
                    @else
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">Winbreaker</a>
                    @endauth
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            <a href="{{ route('admin.products.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Products</a>
                            <a href="{{ route('admin.categories.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Categories</a>
                        @elseif(auth()->user()->hasRole('hr'))
                            <a href="{{ route('hr.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            <a href="{{ route('hr.employees.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Employees</a>
                            <a href="{{ route('hr.announcements.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Announcements</a>
                            <a href="{{ route('hr.leaves.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Leaves</a>
                            <a href="{{ route('hr.payroll.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Payroll</a>
                        @elseif(auth()->user()->hasRole(['staff', 'uploader']))
                            <a href="{{ route('staff.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            <a href="{{ route('staff.attendance.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Attendance</a>
                            <a href="{{ route('staff.leaves.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Leaves</a>
                            <a href="{{ route('staff.payroll.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Payroll</a>
                        @else
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Products</a>
                            <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium relative">
                                Cart
                                @if(auth()->user()->cartItems()->count() > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ auth()->user()->cartItems()->count() }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">My Orders</a>
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
                        <a href="{{ route('admin.products.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Products</a>
                        <a href="{{ route('admin.categories.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Categories</a>
                    @elseif(auth()->user()->hasRole('hr'))
                        <a href="{{ route('hr.dashboard') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                        <a href="{{ route('hr.employees.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Employees</a>
                        <a href="{{ route('hr.announcements.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Announcements</a>
                        <a href="{{ route('hr.leaves.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Leaves</a>
                        <a href="{{ route('hr.payroll.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Payroll</a>
                    @elseif(auth()->user()->hasRole(['staff', 'uploader']))
                        <a href="{{ route('staff.dashboard') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                        <a href="{{ route('staff.attendance.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Attendance</a>
                        <a href="{{ route('staff.leaves.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Leaves</a>
                        <a href="{{ route('staff.payroll.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Payroll</a>
                    @else
                        <a href="{{ route('home') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Home</a>
                        <a href="{{ route('products.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Products</a>
                        <a href="{{ route('cart.index') }}" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium">Cart</a>
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

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
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
                    <h3 class="text-xl font-bold mb-4">Winbreaker</h3>
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
                    <p class="text-gray-400">Email: info@winbreaker.com</p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Winbreaker. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>
</body>
</html>

