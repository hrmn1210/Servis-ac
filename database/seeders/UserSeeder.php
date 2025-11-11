<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. MATIKAN FOREIGN KEY CHECK
        Schema::disableForeignKeyConstraints();

        // 2. TRUNCATE TABLE
        DB::table('users')->truncate();

        // 3. NYALAKAN KEMBALI FOREIGN KEY CHECK
        Schema::enableForeignKeyConstraints();

        // 4. BUAT DATA PENGGUNA - HANYA 2 ROLE: admin dan user

        // 1. ADMIN 
        User::create([
            'name' => 'Admin ServisAC',
            'username' => 'admin',
            'email' => 'admin@servisac.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone_number' => '081200000001',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. ADMIN LAIN UNTUK TESTING
        User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@servisac.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone_number' => '081200000005',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. USER (Pelanggan)
        User::create([
            'name' => 'Andi Pelanggan',
            'username' => 'andi_user',
            'email' => 'andi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone_number' => '081200000004',
            'address' => 'Jl. Merdeka No. 10, Jakarta',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. USER LAIN
        User::create([
            'name' => 'Sari Pelanggan',
            'username' => 'sari_user',
            'email' => 'sari@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone_number' => '081200000006',
            'address' => 'Jl. Sudirman No. 25, Jakarta',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('=== ADMIN ACCOUNTS ===');
        $this->command->info('Admin: admin@servisac.com / password');
        $this->command->info('Super Admin: superadmin@servisac.com / password123');
        $this->command->info('=== USER ACCOUNTS ===');
        $this->command->info('User: andi@gmail.com / password');
        $this->command->info('User: sari@gmail.com / password');
    }
}
