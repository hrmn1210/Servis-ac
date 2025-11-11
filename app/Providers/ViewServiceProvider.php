<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment; // <-- Import model Payment

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // [PERBAIKAN UTAMA]
        // Kirim data ke view 'admin.layout' setiap kali view itu di-render
        View::composer('admin.layout', function ($view) {

            // Kita cek dulu apakah user sudah login dan rolenya admin
            // agar query ini tidak berjalan untuk user biasa atau tamu
            if (Auth::check() && Auth::user()->role == 'admin') {
                $pendingCount = Payment::where('status', 'pending_verification')->count();
                $view->with('pendingVerificationCount', $pendingCount);
            } else {
                // Jika bukan admin, kirim 0
                $view->with('pendingVerificationCount', 0);
            }
        });
    }
}
