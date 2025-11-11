<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect to Google for authentication
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email Google
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update informasi user jika diperlukan
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Buat user baru
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)), // Password random
                    'role' => 'user', // Default role untuk login Google
                    'email_verified_at' => now(), // Email sudah terverifikasi oleh Google
                ]);
            }

            // Login user
            Auth::login($user, true); // true untuk "remember me"

            // Redirect ke dashboard berdasarkan role
            return $this->redirectToDashboard($user);
        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage());

            return redirect('/login')->withErrors([
                'login' => 'Login dengan Google gagal. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Redirect ke dashboard berdasarkan role user
     */
    private function redirectToDashboard($user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'technician' => redirect()->route('technician.dashboard'),
            'user' => redirect()->route('user.dashboard'),
            default => redirect('/dashboard')
        };
    }
}
