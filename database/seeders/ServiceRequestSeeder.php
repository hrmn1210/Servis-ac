<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceRequest;
use App\Models\Payment;
use App\Models\User;

class ServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'andi@gmail.com')->first();

        if ($user) {
            $request = ServiceRequest::create([
                'user_id' => $user->id,
                'service_type' => 'AC Service & Cleaning',
                'description' => 'AC tidak dingin dan berisik',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'phone_number' => '081200000004',
                'status' => 'completed',
                'preferred_date' => now()->subDays(5),
                'preferred_time' => '10:00',
                'price' => 150000,
                'admin_notes' => 'AC sudah dibersihkan dan ditambahkan freon'
            ]);

            Payment::create([
                'service_request_id' => $request->id,
                'amount' => 150000,
                'status' => 'paid',
                'payment_method' => 'transfer',
                'paid_at' => now()->subDays(3)
            ]);
        }
    }
}
