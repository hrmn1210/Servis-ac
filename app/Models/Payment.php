<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'booking_id',
        'amount',
        'status',
        'payment_type',
        'down_payment_amount',
        'remaining_amount',
        'payment_method',
        'transaction_id',
        'payment_proof',
        'admin_notes',
        'verification_status',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Helper methods
    public function isVerified()
    {
        return $this->verification_status === 'approved';
    }

    public function isPendingVerification()
    {
        return $this->verification_status === 'pending';
    }

    public function isRejected()
    {
        return $this->verification_status === 'rejected';
    }

    public function getServiceAttribute()
    {
        if ($this->booking_id) {
            return $this->booking;
        } elseif ($this->service_request_id) {
            return $this->serviceRequest;
        }
        return null;
    }
}
