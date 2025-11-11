@extends('layouts.user')

@section('title', 'My Profile - ServisAC')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">My Profile</h2>

        <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ $user->email }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" disabled>
                    <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('phone_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <div class="px-3 py-2 bg-gray-100 rounded-md">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium capitalize">
                            {{ $user->role }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" id="address" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $user->address) }}</textarea>
                @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('user.dashboard') }}"
                    class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-150">
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    <!-- Account Info -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Account Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <i class="fas fa-calendar-alt text-gray-500 mr-3"></i>
                <div>
                    <p class="text-sm text-gray-500">Member Since</p>
                    <p class="font-medium text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
                </div>
            </div>
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <i class="fas fa-envelope text-gray-500 mr-3"></i>
                <div>
                    <p class="text-sm text-gray-500">Email Status</p>
                    <p class="font-medium text-gray-900">
                        @if($user->email_verified_at)
                        <span class="text-green-600">Verified</span>
                        @else
                        <span class="text-yellow-600">Not Verified</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection