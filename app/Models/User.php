<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder; // Penting untuk scope

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'google_id',
        'role',
        'phone_number',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // === RELASI ===

    /**
     * Relasi untuk User (pelanggan) yang memiliki banyak booking.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Relasi untuk User (tukang servis) yang ditugaskan ke banyak booking.
     */
    public function tasks()
    {
        return $this->hasMany(Booking::class, 'technician_id');
    }

    /**
     * Relasi untuk User (pelanggan) yang memiliki banyak service requests.
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'user_id');
    }

    // === SCOPE ===

    /**
     * Scope untuk memfilter user yang hanya teknisi/tukang servis.
     * Cara panggil: User::isTechnician()->get()
     */
    public function scopeIsTechnician(Builder $query): void
    {
        $query->where('role', 'technician');
    }

    /**
     * Scope untuk memfilter user yang hanya admin.
     */
    public function scopeIsAdmin(Builder $query): void
    {
        $query->where('role', 'admin');
    }
}
