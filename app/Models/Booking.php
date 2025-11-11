<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'technician_id',
        'address',
        'booking_date',
        'notes',
        'status',
        'total_price',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'datetime', // Otomatis cast ke Carbon instance
            'total_price' => 'decimal:2',
        ];
    }

    // === RELASI ===

    /**
     * Relasi ke User (Pelanggan).
     * Satu booking dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User (Tukang Servis).
     * Satu booking ditugaskan ke satu teknisi.
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Relasi Many-to-Many ke Service.
     * Satu booking bisa memiliki banyak layanan.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_service')
            ->withPivot('quantity', 'price'); // Ambil data ekstra dari pivot
    }

    // Tambahkan relasi payment
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
