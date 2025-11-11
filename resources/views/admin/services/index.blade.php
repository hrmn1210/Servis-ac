@extends('admin.layout')

@section('title', 'Manajemen Layanan')
@section('header', 'Manajemen Layanan')
@section('subheader', 'Kelola semua layanan yang ditawarkan')

{{-- [PERBAIKAN] Tombol "Tambah" dipindah ke header layout --}}
@section('header-actions')
<a href="{{ route('admin.services.create') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200 shadow-sm">
    <i class="fas fa-plus mr-2"></i>Tambah Layanan Baru
</a>
@endsection

@section('content')

{{-- [DESAIN BARU] Grid 2 Kolom untuk Kartu Layanan --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    @forelse($services as $service)
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden flex flex-col">

        <div class="p-5 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-tools text-blue-500 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $service->name }}</h3>
                </div>
            </div>
        </div>

        <div class="p-5 flex-grow space-y-4">
            <p class="text-sm text-gray-600 leading-relaxed">
                {{ $service->description ?? 'Tidak ada deskripsi.' }}
            </p>

            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-xs font-medium text-gray-500">Harga</p>
                    <p class="text-base font-bold text-green-600">
                        Rp {{ number_format($service->price, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Estimasi Durasi</p>
                    <p class="text-base font-semibold text-gray-800">
                        {{ $service->estimated_duration_minutes ? $service->estimated_duration_minutes . ' Menit' : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-4 border-t border-gray-200 flex justify-end space-x-3">
            <form action="{{ route('admin.services.delete', $service->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                    Hapus
                </button>
            </form>
            <a href="{{ route('admin.services.edit', $service->id) }}"
                class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 shadow-sm transition-colors">
                Edit Layanan
            </a>
        </div>
    </div>
    @empty
    <div class="lg:col-span-2 text-center bg-white rounded-xl shadow p-12 border border-gray-100">
        <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-800">Belum Ada Layanan</h3>
        <p class="text-gray-500 mt-2 mb-6">Belum ada layanan yang Anda buat. Silakan tambahkan layanan baru.</p>
        <a href="{{ route('admin.services.create') }}"
            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition duration-150 font-semibold shadow-md hover:shadow-lg">
            <i class="fas fa-plus mr-2"></i>Buat Layanan Pertama Anda
        </a>
    </div>
    @endforelse
</div>

@if($services->hasPages())
<div class="mt-8">
    {{ $services->links() }}
</div>
@endif

@endsection