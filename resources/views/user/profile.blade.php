@extends('layouts.user')

@section('title', 'Profil Saya - ServisAC')
@section('header-title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('user.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Informasi Pribadi</h2>
                <p class="text-gray-600">Perbarui data personal dan alamat Anda.</p>
            </div>

            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-user text-gray-400"></i>
                            </span>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </span>

                            {{-- [PERBAIKAN DI SINI] Atribut 'disabled' dihapus --}}
                            <input type="email" name="email" id="email" value="{{ $user->email }}"
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                {{ $user->google_id ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                {{ $user->google_id ? 'readonly' : '' }} required>
                            {{-- [AKHIR PERBAIKAN] --}}

                        </div>
                        @if($user->google_id)
                        <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah (Login via Google).</p>
                        @endif
                        @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp (Wajib) *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fab fa-whatsapp text-gray-400"></i>
                        </span>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="cth: 08123456789" required>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Pastikan ini adalah nomor WhatsApp yang aktif untuk komunikasi.</p>
                    @error('phone_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <div class="relative">
                        <span class="absolute top-3.5 left-0 flex items-center pl-3">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                        </span>
                        <textarea name="address" id="address" rows="3"
                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan alamat lengkap Anda...">{{ old('address', $user->address) }}</textarea>
                    </div>
                    @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Keamanan Akun</h2>
                <p class="text-gray-600">Ubah kata sandi Anda. Biarkan kosong jika tidak ingin mengubah.</p>
            </div>

            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi Baru</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-lock text-gray-400"></i>
                            </span>
                            <input type="password" name="password" id="password"
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi Baru</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-lock text-gray-400"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 font-semibold shadow-md hover:shadow-lg">
                    Perbarui Profil
                </button>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Informasi Akun</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-calendar-alt text-blue-500 fa-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Bergabung Sejak</p>
                    <p class="font-semibold text-gray-900">{{ $user->created_at->format('d F Y') }}</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="p-3 rounded-full mr-4
                    @if($user->email_verified_at) bg-green-100
                    @else bg-yellow-100 @endif">

                    @if($user->email_verified_at)
                    <i class="fas fa-check-circle text-green-500 fa-lg"></i>
                    @else
                    <i class="fas fa-exclamation-triangle text-yellow-500 fa-lg"></i>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status Email</p>
                    <p class="font-semibold">
                        @if($user->email_verified_at)
                        <span class="text-green-600">Terverifikasi</span>
                        @else
                        <span class="text-yellow-600">Belum Terverifikasi</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection