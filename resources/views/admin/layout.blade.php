<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin ServisAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #8b5cf6, #ec4899);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-500 p-2 rounded-lg mr-3">
                        <i class="fas fa-tools text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">ServisAC</h1>
                        <p class="text-xs text-gray-500">Admin Panel</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active text-purple-600 font-semibold' : 'text-gray-700 hover:text-purple-600' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active text-purple-600 font-semibold' : 'text-gray-700 hover:text-purple-600' }}">
                        <i class="fas fa-users mr-2"></i>Users
                    </a>
                    <a href="{{ route('admin.bookings') }}" class="nav-link {{ request()->routeIs('admin.requests') ? 'active text-purple-600 font-semibold' : 'text-gray-700 hover:text-purple-600' }}">
                        <i class="fas fa-tools mr-2"></i>Booking
                    </a>
                    <a href="{{ route('admin.payments') }}" class="nav-link {{ request()->routeIs('admin.payments') ? 'active text-purple-600 font-semibold' : 'text-gray-700 hover:text-purple-600' }}">
                        <i class="fas fa-credit-card mr-2"></i>Payments
                    </a>
                    <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active text-purple-600 font-semibold' : 'text-gray-700 hover:text-purple-600' }}">
                        <i class="fas fa-chart-bar mr-2"></i>Reports
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Button -->
    <div class="md:hidden bg-white border-b">
        <div class="flex overflow-x-auto space-x-4 px-4 py-2">
            <a href="{{ route('admin.dashboard') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'active text-purple-600 font-semibold' : 'text-gray-700' }}">
                <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('admin.users') ? 'active text-purple-600 font-semibold' : 'text-gray-700' }}">
                <i class="fas fa-users mr-1"></i>Users
            </a>
            <a href="{{ route('admin.bookings') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('admin.requests') ? 'active text-purple-600 font-semibold' : 'text-gray-700' }}">
                <i class="fas fa-tools mr-1"></i>Booking
            </a>
            <a href="{{ route('admin.payments') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('admin.payments') ? 'active text-purple-600 font-semibold' : 'text-gray-700' }}">
                <i class="fas fa-credit-card mr-1"></i>Payments
            </a>
            <a href="{{ route('admin.reports') }}" class="nav-link whitespace-nowrap {{ request()->routeIs('admin.reports') ? 'active text-purple-600 font-semibold' : 'text-gray-700' }}">
                <i class="fas fa-chart-bar mr-1"></i>Reports
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">@yield('header')</h1>
                    <p class="text-gray-600 mt-2">@yield('subheader')</p>
                </div>
                <div class="flex space-x-3">
                    @yield('header-actions')
                </div>
            </div>
        </div>

        <!-- Notifications -->
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <div class="flex items-center">
                <div class="bg-red-100 p-2 rounded-full mr-3">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <p class="text-gray-500 text-sm">&copy; 2024 ServisAC. All rights reserved.</p>
                <div class="flex space-x-4">
                    <span class="text-sm text-gray-500">Welcome, {{ Auth::user()->name }}</span>
                    <span class="text-sm text-purple-600 font-medium">Admin</span>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>