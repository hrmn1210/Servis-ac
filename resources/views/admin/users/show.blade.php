@extends('admin.layout')

@section('title', 'User Details - ServisAC')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start">
            <div class="flex items-center space-x-4">
                {{-- [DIPERBAIKI] Disederhanakan untuk menghindari error '$user->avatar' --}}
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <div class="flex items-center space-x-2 mt-2">
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            @if($user->role == 'admin') bg-purple-100 text-purple-800
                            @elseif($user->role == 'technician') bg-indigo-100 text-indigo-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                        {{-- [DIHAPUS] Bagian 'is_active' dihapus untuk menghindari error --}}
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user->id) }}"
                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-150">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
                {{-- [DIHAPUS] Tombol Reset Password dihapus karena rute tidak ada --}}
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">User Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <p class="mt-1 text-lg text-gray-900">{{ $user->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                <p class="mt-1 text-lg text-gray-900">{{ $user->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                <p class="mt-1 text-lg text-gray-900">{{ $user->phone_number ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <p class="mt-1 text-lg text-gray-900">{{ ucfirst($user->role) }}</p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <p class="mt-1 text-lg text-gray-900">{{ $user->address ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Member Since</label>
                <p class="mt-1 text-lg text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email Status</label>
                <p class="mt-1 text-lg">
                    @if($user->email_verified_at)
                    <span class="text-green-600">Verified</span>
                    @else
                    <span class="text-red-600">Not Verified</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- [DIHAPUS] Bagian User Stats dan Recent Bookings dihapus total karena akan menyebabkan error "Undefined variable" --}}

</div>

{{-- [DIHAPUS] Modal Reset Password dihapus --}}

@endsection