<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rating; // <-- [DITAMBAHKAN] Pastikan Anda meng-import model Rating

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
            'booking_date' => 'datetime',
            'total_price' => 'decimal:2',
        ];
    }

    // === RELASI ===

    /**
     * Relasi ke User (Pelanggan).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User (Teknisi).
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Relasi Many-to-Many ke Service.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_service')
            ->withPivot('quantity', 'price');
    }

    /**
     * Relasi One-to-One ke Payment.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * [PERBAIKAN]
     * Relasi One-to-One ke Rating.
     * Satu booking hanya memiliki satu rating.
     */
    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    // === METHOD LAIN ===

    /**
     * Cek apakah booking sudah selesai.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
