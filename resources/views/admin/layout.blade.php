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
            background-color: #4f46e5;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link.active {
            color: #4f46e5;
            font-weight: 600;
        }

        /* Sidebar styles */
        .sidebar {
            width: 280px;
            transition: transform 0.3s ease;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 40;
                height: 100vh;
            }

            .sidebar-open {
                transform: translateX(0);
            }

            .main-content {
                width: 100%;
                padding-left: 0;
            }
        }

        @media (min-width: 1024px) {
            .main-content {
                width: calc(100% - 280px);
                margin-left: 280px;
            }
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            color: #4b5563;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }

        .sidebar-link:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        .sidebar-link.active {
            background-color: #6366f1;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .sidebar-link .icon {
            width: 1.25rem;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 1rem;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex">

        <aside
            class="sidebar fixed top-0 left-0 h-screen bg-white border-r border-gray-200 p-6 shadow-lg lg:translate-x-0"
            id="sidebar">
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <i class="fas fa-wind text-purple-600 text-3xl"></i>
                    <span class="text-2xl font-bold text-gray-900">ServisAC</span>
                </a>
                <button class="lg:hidden" id="closeSidebarBtn">
                    <i class="fas fa-times text-xl text-gray-600"></i>
                </button>
            </div>

            <nav>
                <ul>
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="icon fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        {{-- [PERBAIKAN] Pastikan ini 'admin.bookings.index' --}}
                        <a href="{{ route('admin.bookings.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                            <i class="icon fas fa-calendar-alt"></i>
                            <span>Bookings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.payments.verification') }}"
                            class="sidebar-link {{ request()->routeIs('admin.payments.verification') ? 'active' : '' }}">
                            <i class="icon fas fa-check-circle"></i>
                            <span>Verification</span>

                            @if(isset($pendingVerificationCount) && $pendingVerificationCount > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">
                                {{ $pendingVerificationCount }}
                            </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="icon fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.services.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                            <i class="icon fas fa-tools"></i>
                            <span>Services</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.payments.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.payments.index') ? 'active' : '' }}">
                            <i class="icon fas fa-wallet"></i>
                            <span>Payments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="icon fas fa-chart-line"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="absolute bottom-6 left-6 right-6">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center py-2.5 px-4 rounded-lg text-gray-600 hover:bg-red-50 hover:text-red-600 transition duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="main-content flex-1">
            <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center justify-between h-20 px-6 lg:px-10">
                    <button class="lg:hidden" id="openSidebarBtn">
                        <i class="fas fa-bars text-xl text-gray-600"></i>
                    </button>

                    <div class="flex-1">
                        <h1 class="text-xl font-semibold text-gray-900">@yield('header')</h1>
                        <p class="text-sm text-gray-600">@yield('subheader')</p>
                    </div>

                    <div classs="flex items-center space-x-4">
                        @yield('header-actions')
                    </div>
                </div>
            </header>

            <main class="p-6 lg:p-10">
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

                @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <p class="font-bold text-red-800">Oops! Ada kesalahan:</p>
                    <ul class="list-disc list-inside text-red-700">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif


                @yield('content')
            </main>

            <footer class="bg-white border-t mt-12">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <p class="text-gray-500 text-sm">&copy; 2024 ServisAC. All rights reserved.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-500 hover:text-gray-700 text-sm">Privacy Policy</a>
                            <a href="#" class="text-gray-500 hover:text-gray-700 text-sm">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const openSidebarBtn = document.getElementById('openSidebarBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');

        if (openSidebarBtn) {
            openSidebarBtn.addEventListener('click', () => {
                sidebar.classList.add('sidebar-open');
            });
        }

        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', () => {
                sidebar.classList.remove('sidebar-open');
            });
        }

        // Close sidebar on click outside
        document.addEventListener('click', function(event) {
            if (sidebar && !sidebar.contains(event.target) && openSidebarBtn && !openSidebarBtn.contains(event.target)) {
                sidebar.classList.remove('sidebar-open');
            }
        });
    </script>

    @stack('scripts')

</body>

</html>