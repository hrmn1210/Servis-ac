<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'ratings';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'booking_id',
        'user_id',
        'rating',
        'review',
    ];

    /**
     * Tentukan tipe data untuk kolom tertentu.
     */
    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Relasi: Rating ini milik siapa (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Rating ini untuk Booking yang mana
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
