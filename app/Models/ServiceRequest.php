<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_type',
        'description',
        'address',
        'phone_number',
        'status',
        'preferred_date',
        'preferred_time',
        'price',
        'admin_notes'
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
