@extends('admin.layout')

@section('title', 'Users Management - ServisAC')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Users Management</h2>
    <a href="{{ route('admin.users.create') }}"
        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-150">
        <i class="fas fa-plus mr-2"></i>Add User
    </a>
</div>

<!-- Filters and Stats -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-4">
        <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}"
            class="text-center p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition duration-150 {{ !request('status') ? 'border-blue-500 bg-blue-50' : '' }}">
            <div class="text-2xl font-bold text-gray-800">{{ $userStats['total'] }}</div>
            <div class="text-sm text-gray-600">All Users</div>
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'with_bookings']) }}"
            class="text-center p-3 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition duration-150 {{ request('status') == 'with_bookings' ? 'border-green-500 bg-green-50' : '' }}">
            <div class="text-2xl font-bold text-gray-800">{{ $userStats['with_bookings'] }}</div>
            <div class="text-sm text-gray-600">With Bookings</div>
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'without_bookings']) }}"
            class="text-center p-3 border border-gray-200 rounded-lg hover:border-yellow-300 hover:bg-yellow-50 transition duration-150 {{ request('status') == 'without_bookings' ? 'border-yellow-500 bg-yellow-50' : '' }}">
            <div class="text-2xl font-bold text-gray-800">{{ $userStats['without_bookings'] }}</div>
            <div class="text-sm text-gray-600">No Bookings</div>
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'verified']) }}"
            class="text-center p-3 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition duration-150 {{ request('status') == 'verified' ? 'border-purple-500 bg-purple-50' : '' }}">
            <div class="text-2xl font-bold text-gray-800">{{ $userStats['verified'] }}</div>
            <div class="text-sm text-gray-600">Verified</div>
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'unverified']) }}"
            class="text-center p-3 border border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 transition duration-150 {{ request('status') == 'unverified' ? 'border-orange-500 bg-orange-50' : '' }}">
            <div class="text-2xl font-bold text-gray-800">{{ $userStats['unverified'] }}</div>
            <div class="text-sm text-gray-600">Unverified</div>
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'recent']) }}"
            class="text-center p-3 border border-gray-200 rounded-lg hover:border-red-300 hover:bg-red-50 transition duration-150 {{ request('status') == 'recent' ? 'border-red-500 bg-red-50' : '' }}">
            <div class="text-2xl font-bold text-gray-800">{{ $userStats['recent'] }}</div>
            <div class="text-sm text-gray-600">Recent</div>
        </a>
    </div>

    <!-- Search and Filters -->
    <form method="GET" action="{{ route('admin.users') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search users by name, email, or phone..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex gap-4">
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-150">
                Filter
            </button>
            <a href="{{ route('admin.users') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-150">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->phone_number ?? '-' }}</div>
                        <div class="text-sm text-gray-500">{{ $user->address ? Str::limit($user->address, 30) : '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <span class="font-semibold">{{ $user->bookings_count }}</span> total
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $user->completed_bookings_count }} completed, {{ $user->pending_bookings_count }} pending
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col space-y-1">
                            @if($user->email_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Verified
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Unverified
                            </span>
                            @endif

                            @if($user->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Suspended
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.users.show', $user->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                            class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                onclick="return confirm('Are you sure you want to delete this user?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="bg-white px-6 py-3 border-t border-gray-200">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection