<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ServisAC - Panel Pengguna')</title>

    {{-- Tailwind & Font Awesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Alpine.js untuk menu mobile --}}
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <style>
        /* Style kustom untuk link sidebar aktif */
        .sidebar-link.active {
            background-color: #EFF6FF;
            /* bg-blue-50 */
            color: #2563EB;
            /* text-blue-600 */
            font-weight: 600;
        }

        .sidebar-link.active .sidebar-icon {
            color: #2563EB;
            /* text-blue-600 */
        }

        /* Menambahkan font yang lebih modern, opsional */
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
    </style>
</head>

<body class="bg-gray-100">

    <div x-data="{ sidebarOpen: false }">

        <aside
            class="fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">

            <div class="p-6 flex items-center justify-center border-b border-gray-200">
                <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-3">
                    <i class="fas fa-wind text-blue-600 text-3xl"></i>
                    <span class="text-2xl font-bold text-gray-800">ServisAC</span>
                </a>
            </div>

            <nav class="mt-4 px-4">
                <a href="{{ route('user.dashboard') }}"
                    class="sidebar-link flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="sidebar-icon fas fa-tachometer-alt w-6 text-center text-gray-400 mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('user.bookings') }}"
                    class="sidebar-link flex items-center px-4 py-3 mt-2 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('user.bookings*') ? 'active' : '' }}">
                    <i class="sidebar-icon fas fa-calendar-alt w-6 text-center text-gray-400 mr-3"></i>
                    <span>Booking Saya</span>
                </a>
                <a href="{{ route('user.payments') }}"
                    class="sidebar-link flex items-center px-4 py-3 mt-2 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('user.payments*') ? 'active' : '' }}">
                    <i class="sidebar-icon fas fa-wallet w-6 text-center text-gray-400 mr-3"></i>
                    <span>Pembayaran Saya</span>
                </a>
                <a href="{{ route('user.profile') }}"
                    class="sidebar-link flex items-center px-4 py-3 mt-2 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('user.profile*') ? 'active' : '' }}">
                    <i class="sidebar-icon fas fa-user-circle w-6 text-center text-gray-400 mr-3"></i>
                    <span>Profil</span>
                </a>
            </nav>

            <div class="absolute bottom-4 left-4 right-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center px-4 py-3 text-gray-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                        <i class="fas fa-sign-out-alt w-6 text-center mr-3"></i>
                        <span class="font-medium">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Overlay untuk mobile (saat sidebar terbuka) --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black opacity-50 lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col lg:ml-64">

            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
                <div class="flex items-center justify-between h-16 px-6">
                    <button @click.stop="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <div class="hidden lg:block">
                        <h1 class="text-xl font-semibold text-gray-800">@yield('header-title', 'Dashboard')</h1>
                    </div>

                    <div class="flex items-center ml-auto">
                        <span class="text-gray-700 font-medium hidden sm:block">Selamat datang, {{ Auth::user()->name }}!</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6 lg:p-10">

                {{-- Notifikasi --}}
                @if(session('success'))
                <div class_alert="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 shadow">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class_alert="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 shadow">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
                @endif

                @if ($errors->any())
                <div class_alert="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 shadow">
                    <p class="font-bold">Oops! Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Judul Halaman (Mobile) --}}
                <div class="lg:hidden mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">@yield('header-title', 'Dashboard')</h1>
                </div>

                @yield('content')
            </main>

            <footer class="bg-white border-t mt-auto">
                <div class="max-w-7xl mx-auto px-6 py-4 text-center text-gray-600">
                    <p>&copy; {{ date('Y') }} ServisAC. Hak cipta dilindungi.</p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('._alert');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>

</html>