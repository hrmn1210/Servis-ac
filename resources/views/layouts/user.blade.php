<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ServisAC - User Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <div class="bg-blue-500 p-3 rounded-lg mr-4">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">User Dashboard</h1>
                    <p class="text-gray-600">ServisAC Customer Panel</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">Welcome, {{ Auth::user()->name }}!</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex space-x-8">
                <a href="{{ route('user.dashboard') }}" class="py-4 px-2 text-gray-500 hover:text-blue-500 transition duration-150 {{ request()->routeIs('user.dashboard') ? 'border-b-2 border-blue-500 text-blue-500 font-medium' : '' }}">Dashboard</a>
                <a href="{{ route('user.bookings') }}" class="py-4 px-2 text-gray-500 hover:text-blue-500 transition duration-150 {{ request()->routeIs('user.bookings*') ? 'border-b-2 border-blue-500 text-blue-500 font-medium' : '' }}">Bookings</a>
                <a href="{{ route('user.payments') }}" class="py-4 px-2 text-gray-500 hover:text-blue-500 transition duration-150 {{ request()->routeIs('user.payments*') ? 'border-b-2 border-blue-500 text-blue-500 font-medium' : '' }}">Payments</a>
                <a href="{{ route('user.profile') }}" class="py-4 px-2 text-gray-500 hover:text-blue-500 transition duration-150 {{ request()->routeIs('user.profile*') ? 'border-b-2 border-blue-500 text-blue-500 font-medium' : '' }}">Profile</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        <!-- Notifications -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-8">
        <div class="max-w-7xl mx-auto px-4 py-4 text-center text-gray-600">
            <p>&copy; {{ date('Y') }} ServisAC. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>
</body>

</html>