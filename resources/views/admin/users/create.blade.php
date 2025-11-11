@extends('admin.layout')

@section('title', 'Add New User - ServisAC')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Add New User</h2>
            <p class="text-gray-600">Create a new user account</p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Personal Information -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Personal Information</h3>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('phone_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Account Security</h3>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address') }}</textarea>
                    @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('avatar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Max file size: 2MB. Allowed formats: JPEG, PNG, JPG, GIF</p>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Account Settings</h3>

                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="email_verified" id="email_verified"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="email_verified" class="ml-2 block text-sm text-gray-700">
                            Mark email as verified
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="suspend_user" id="suspend_user"
                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="suspend_user" class="ml-2 block text-sm text-gray-700">
                            Suspend user account (user won't be able to login)
                        </label>
                    </div>
                </div>
            </div>
            <!-- Tambahkan di bagian Account Settings -->
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">User Role *</label>
                <select name="role" id="role" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="technician" {{ old('role') == 'technician' ? 'selected' : '' }}>Technician</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users') }}"
                    class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-150">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Password strength indicator (optional enhancement)
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthIndicator = document.getElementById('password-strength');

        if (!strengthIndicator) {
            const indicator = document.createElement('div');
            indicator.id = 'password-strength';
            indicator.className = 'text-sm mt-1';
            this.parentNode.appendChild(indicator);
        }

        const indicator = document.getElementById('password-strength');
        let strength = 'Weak';
        let color = 'text-red-500';

        if (password.length >= 8) {
            strength = 'Medium';
            color = 'text-yellow-500';
        }
        if (password.length >= 12 && /[A-Z]/.test(password) && /[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password)) {
            strength = 'Strong';
            color = 'text-green-500';
        }

        indicator.innerHTML = `Password strength: <span class="${color} font-medium">${strength}</span>`;
    });
</script>
@endsection