@extends('layouts.guest')

@section('title', 'Register - CoolPro')

@section('content')

<!-- Header -->
<div class="text-center mb-8">
    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl mb-4 shadow-lg">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
    </div>
    <h2 class="text-3xl font-extrabold text-gray-900">Buat Akun Baru</h2>
    <p class="text-gray-500 mt-2">Bergabunglah dan nikmati layanan AC profesional</p>
</div>

<!-- Error Messages -->
@if ($errors->any())
<div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-semibold text-red-800">Oops! Ada yang salah:</p>
            <ul class="mt-2 text-sm text-red-700 list-inside space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<!-- Register Form -->
<form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    <!-- Name Field -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Nama
        </label>
        <input id="name"
            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150"
            type="text"
            name="name"
            value="{{ old('name') }}"
            placeholder="Masukkan nama lengkap"
            required
            autofocus />
    </div>

    <!-- Email Field -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email
        </label>
        <input id="email"
            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150"
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="contoh@email.com"
            required />
    </div>

    <!-- Username Field -->
    <div>
        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
            Username <span class="text-gray-400 text-xs">(Opsional)</span>
        </label>
        <input id="username"
            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150"
            type="text"
            name="username"
            value="{{ old('username') }}"
            placeholder="username_anda" />
    </div>

    <!-- Password Field -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            Password
        </label>
        <input id="password"
            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150"
            type="password"
            name="password"
            placeholder="Minimal 8 karakter"
            required />
    </div>

    <!-- Confirm Password Field -->
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            Konfirmasi Password
        </label>
        <input id="password_confirmation"
            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150"
            type="password"
            name="password_confirmation"
            placeholder="Ketik ulang password"
            required />
    </div>

    <!-- Register Button -->
    <button type="submit"
        class="w-full flex justify-center items-center py-3.5 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
        Register
    </button>

    <!-- Login Link -->
    <div class="text-center pt-4">
        <p class="text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors">
                Log in di sini
            </a>
        </p>
    </div>
</form>

@endsection