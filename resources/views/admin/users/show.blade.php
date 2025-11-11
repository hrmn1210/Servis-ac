@extends('admin.layout')

@section('title', 'User Details - ServisAC')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start">
            <div class="flex items-center space-x-4">
                @if($user->avatar)
                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover">
                @else
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <div class="flex items-center space-x-2 mt-2">
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            @if($user->role == 'admin') bg-purple-100 text-purple-800
                            @elseif($user->role == 'technician') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->email_verified_at)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Verified
                        </span>
                        @else
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>Unverified
                        </span>
                        @endif
                        @if($user->is_active)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Active
                        </span>
                        @else
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Suspended
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user->id) }}"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-150">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-150"
                        onclick="return confirm('Are you sure you want to delete this user?')">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_bookings'] }}</div>
                    <div class="text-gray-600 text-sm">Total Bookings</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['completed_bookings'] }}</div>
                    <div class="text-gray-600 text-sm">Completed</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_bookings'] }}</div>
                    <div class="text-gray-600 text-sm">Pending</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-purple-600">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</div>
                    <div class="text-gray-600 text-sm">Total Spent</div>
                </div>
            </div>

            <!-- User Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">User Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Full Name</label>
                        <p class="mt-1 text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Username</label>
                        <p class="mt-1 text-gray-900">{{ $user->username ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email Address</label>
                        <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Phone Number</label>
                        <p class="mt-1 text-gray-900">{{ $user->phone_number ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600">Address</label>
                        <p class="mt-1 text-gray-900">{{ $user->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>
                <div class="space-y-3">
                    @forelse($user->bookings as $booking)
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div>
                            <p class="font-medium text-gray-900">
                                @foreach($booking->services as $service)
                                {{ $service->name }}@if(!$loop->last), @endif
                                @endforeach
                            </p>
                            <p class="text-sm text-gray-500">{{ $booking->booking_date->format('M d, Y H:i') }}</p>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-medium 
                            @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status == 'completed') bg-green-100 text-green-800
                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No bookings found</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar - Quick Actions -->
        <div class="space-y-6">
            <!-- Account Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Account Actions</h3>
                <div class="space-y-3">
                    @if(!$user->email_verified_at)
                    <form action="{{ route('admin.users.verify', $user->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-150">
                            <i class="fas fa-check-circle mr-2"></i>Verify Email
                        </button>
                    </form>
                    @endif

                    @if($user->is_active)
                    <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-150">
                            <i class="fas fa-pause mr-2"></i>Suspend User
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-150">
                            <i class="fas fa-play mr-2"></i>Activate User
                        </button>
                    </form>
                    @endif

                    <!-- Role Management -->
                    <div class="pt-3 border-t">
                        <h4 class="font-medium text-gray-700 mb-2">Change Role</h4>
                        <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST" class="space-y-2">
                            @csrf
                            @method('PUT')
                            <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                <option value="technician" {{ $user->role == 'technician' ? 'selected' : '' }}>Technician</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <button type="submit"
                                class="w-full px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition duration-150">
                                Update Role
                            </button>
                        </form>
                    </div>

                    <!-- Password Reset -->
                    <div class="pt-3 border-t">
                        <h4 class="font-medium text-gray-700 mb-2">Reset Password</h4>
                        <button onclick="showPasswordModal()"
                            class="w-full text-left px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-150">
                            <i class="fas fa-key mr-2"></i>Reset Password
                        </button>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Account Information</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Member Since:</span>
                        <span class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="text-gray-900">{{ $user->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email Verified:</span>
                        <span class="{{ $user->email_verified_at ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y') : 'Not Verified' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Reset Modal -->
<div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reset Password</h3>

            <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePasswordModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showPasswordModal() {
        document.getElementById('passwordModal').classList.remove('hidden');
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('passwordModal');
        if (event.target === modal) {
            closePasswordModal();
        }
    }
</script>
@endsection