@extends('admin.layout')

@section('title', 'Edit User - ServisAC')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit User</h2>
            <p class="text-gray-600">Update user account information</p>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Personal Information -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Personal Information</h3>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('phone_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Additional Information</h3>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>

                    @if($user->avatar)
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 mb-2">Current avatar:</p>
                        <img src="{{ Storage::url($user->avatar) }}" alt="Current avatar" class="w-20 h-20 rounded-full object-cover">
                    </div>
                    @endif

                    <input type="file" name="avatar" id="avatar" accept="image/*"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('avatar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    @if($user->avatar)
                    <div class="mt-2">
                        <input type="checkbox" name="remove_avatar" id="remove_avatar" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="remove_avatar" class="ml-2 text-sm text-gray-700">Remove current avatar</label>
                    </div>
                    @endif

                    <p class="text-xs text-gray-500 mt-1">Max file size: 2MB. Allowed formats: JPEG, PNG, JPG, GIF</p>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Account Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <p class="text-sm text-gray-900 bg-gray-100 px-3 py-2 rounded-md capitalize">{{ $user->role }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Status</label>
                        <p class="text-sm {{ $user->email_verified_at ? 'text-green-600' : 'text-yellow-600' }} bg-gray-100 px-3 py-2 rounded-md">
                            {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Status</label>
                        <p class="text-sm {{ $user->is_active ? 'text-green-600' : 'text-red-600' }} bg-gray-100 px-3 py-2 rounded-md">
                            {{ $user->is_active ? 'Active' : 'Suspended' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Member Since</label>
                        <p class="text-sm text-gray-900 bg-gray-100 px-3 py-2 rounded-md">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.show', $user->id) }}"
                    class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-150">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection