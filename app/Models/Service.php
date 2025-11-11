<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'estimated_duration_minutes',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2', // Otomatis cast ke tipe data yang benar
        ];
    }

    /**
     * Relasi Many-to-Many ke Booking.
     * Satu layanan bisa ada di banyak booking.
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_service')
            ->withPivot('quantity', 'price'); // Ambil data ekstra dari pivot
    }
}
