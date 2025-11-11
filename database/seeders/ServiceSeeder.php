<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service; // <-- Import model Service
use Illuminate\Support\Facades\Schema; // <-- Import Schema (PENTING UNTUK PERBAIKAN)

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. MATIKAN FOREIGN KEY CHECK (PERBAIKAN)
        // Ini untuk menghindari error saat truncate tabel services
        Schema::disableForeignKeyConstraints();

        // 2. JALANKAN TRUNCATE
        Service::truncate();

        // 3. NYALAKAN KEMBALI FOREIGN KEY CHECK (PERBAIKAN)
        Schema::enableForeignKeyConstraints();

        // 4. BUAT DATA LAYANAN

        Service::create([
            'name' => 'Cuci AC 0.5 - 1 PK',
            'description' => 'Pembersihan unit indoor, outdoor, dan saluran pembuangan.',
            'price' => 75000,
            'estimated_duration_minutes' => 45,
        ]);

        Service::create([
            'name' => 'Tambah Freon R32 (Per PSI)',
            'description' => 'Penambahan freon R32 sesuai kebutuhan.',
            'price' => 25000,
            'estimated_duration_minutes' => 20,
        ]);

        Service::create([
            'name' => 'Bongkar Pasang AC',
            'description' => 'Jasa bongkar AC di satu lokasi dan pasang di lokasi baru.',
            'price' => 350000,
            'estimated_duration_minutes' => 120,
        ]);

        Service::create([
            'name' => 'Servis Perbaikan (Cek Saja)',
            'description' => 'Pengecekan awal kerusakan. Harga belum termasuk sparepart.',
            'price' => 50000,
            'estimated_duration_minutes' => 30,
        ]);
    }
}
