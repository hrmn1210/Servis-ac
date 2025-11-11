@extends('admin.layout')

@section('title', 'Manajemen Booking - ServisAC')
@section('header', 'Manajemen Booking')
@section('subheader', 'Kelola semua booking yang masuk ke sistem')

@section('content')

{{-- [BARU] Filter Status --}}
<div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
    <div class="flex flex-wrap items-center gap-2 md:gap-4">
        <span class="text-sm font-medium text-gray-600">Filter Status:</span>
        <a href="{{ route('admin.bookings.index') }}"
            class="px-3 py-1 rounded-full text-sm font-medium {{ !request('status') ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Semua
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'pending_verification']) }}"
            class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'pending_verification' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Verifikasi
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'assigned']) }}"
            class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'assigned' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Ditugaskan
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'completed']) }}"
            class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'completed' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Selesai
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}"
            class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'cancelled' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Dibatalkan
        </a>
    </div>
</div>

{{-- [PERBAIKAN] Container tabel tanpa overflow-x-auto --}}
<div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Booking</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teknisi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($bookings as $booking)
            <tr class="hover:bg-gray-50 transition-colors duration-150">

                {{-- Pelanggan --}}
                <td class="px-6 py-4 align-top">
                    <div class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'User Dihapus' }}</div>
                    <div class="text-sm text-gray-500">{{ $booking->user->phone_number ?? '-' }}</div>
                </td>

                {{-- Tgl. Booking (Dibuat vertikal) --}}
                <td class="px-6 py-4 align-top">
                    <div class="text-sm text-gray-800">{{ $booking->booking_date->format('d M Y') }}</div>
                    <div class="text-sm text-gray-500">{{ $booking->booking_date->format('H:i') }} WIB</div>
                </td>

                {{-- Layanan (Dibuat bisa wrap) --}}
                <td class="px-6 py-4 align-top">
                    <div class="flex flex-wrap gap-1 max-w-xs"> {{-- max-w-xs akan memaksa wrap --}}
                        @foreach($booking->services as $service)
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $service->name }} ({{ $service->pivot->quantity }})
                        </span>
                        @endforeach
                    </div>
                </td>

                {{-- Teknisi --}}
                <td class="px-6 py-4 align-top text-sm text-gray-700">
                    @if($booking->technician)
                    {{ $booking->technician->name }}
                    @else
                    <span class="text-gray-400 italic">Belum Ditugaskan</span>
                    @endif
                </td>

                {{-- Status (Badge sudah bagus) --}}
                <td class="px-6 py-4 align-top">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($booking->status == 'pending' || $booking->status == 'pending_verification') bg-yellow-100 text-yellow-800
                        @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                        @elseif($booking->status == 'assigned') bg-indigo-100 text-indigo-800
                        @elseif($booking->status == 'in_progress') bg-purple-100 text-purple-800
                        @elseif($booking->status == 'completed') bg-green-100 text-green-800
                        @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                    </span>
                </td>

                {{-- Total Harga --}}
                <td class="px-6 py-4 align-top text-sm font-semibold text-gray-900">
                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                </td>

                {{-- Aksi (Tombol dibuat lebih jelas) --}}
                <td class="px-6 py-4 align-top text-right">
                    <a href="{{ route('admin.bookings.show', $booking->id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-xs font-medium shadow-sm">
                        <i class="fas fa-eye text-white-300"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                    <p>Data booking tidak ditemukan.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($bookings->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection