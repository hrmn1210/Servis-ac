<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;

/*
|--------------------------------------------------------------------------
| Rute Halaman Depan (Guest)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Rute Autentikasi
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rute Dashboard Berdasarkan Role
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role == 'user') {
            return redirect()->route('user.dashboard');
        }
        return redirect('/');
    })->name('home');

    /*
    |--------------------------------------------------------------------------
    | Rute ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'dashboard')->name('dashboard');

            // Users
            Route::get('/users', 'users')->name('users.index');
            Route::get('/users/create', 'createUser')->name('users.create');
            Route::post('/users', 'storeUser')->name('users.store');
            Route::get('/users/{user}/edit', 'editUser')->name('users.edit');
            Route::put('/users/{user}', 'updateUser')->name('users.update');
            Route::delete('/users/{user}', 'deleteUser')->name('users.delete');
            Route::get('/users/{user}', 'showUser')->name('users.show');

            // Bookings
            Route::get('/bookings', 'bookings')->name('bookings.index');
            Route::get('/bookings/{id}', 'showBooking')->name('bookings.show');
            Route::post('/bookings/{id}/status', 'updateBookingStatus')->name('bookings.updateStatus');
            Route::post('/bookings/{id}/approve', 'approvePendingBooking')->name('bookings.approve');
            Route::post('/bookings/{id}/reject', 'rejectPendingBooking')->name('bookings.reject');

            // Services
            Route::get('/services', 'services')->name('services.index');
            Route::get('/services/create', 'createService')->name('services.create');
            Route::post('/services', 'storeService')->name('services.store');
            Route::get('/services/{service}/edit', 'editService')->name('services.edit');
            Route::put('/services/{service}', 'updateService')->name('services.update');
            Route::delete('/services/{service}', 'deleteService')->name('services.delete');

            // Payments
            Route::get('/payments', 'payments')->name('payments.index');
            Route::post('/payments/{payment}/verify', 'verifyPayment')->name('payments.verify');
            Route::get('/payments/verification', 'paymentVerification')->name('payments.verification');

            // Reports
            Route::get('/reports', 'reports')->name('reports.index');
        });
    }); // Akhir Rute Admin

    /*
    |--------------------------------------------------------------------------
    | Rute USER
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

        // Profile
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

        // Bookings
        Route::get('/bookings', [UserController::class, 'bookings'])->name('bookings');
        Route::get('/bookings/create', [UserController::class, 'createBooking'])->name('bookings.create');
        Route::post('/bookings', [UserController::class, 'storeBooking'])->name('bookings.store');
        Route::get('/bookings/{id}', [UserController::class, 'showBooking'])->name('bookings.show');
        Route::post('/bookings/{id}/cancel', [UserController::class, 'cancelBooking'])->name('bookings.cancel');

        // [PERBAIKAN] Rute untuk submit rating DITAMBAHKAN DI SINI
        Route::post('/bookings/{id}/rate', [UserController::class, 'submitRating'])->name('bookings.rate');

        // Payments
        Route::get('/payments', [UserController::class, 'payments'])->name('payments');
        Route::get('/payments/{id}', [UserController::class, 'showPayment'])->name('payments.show');
        Route::post('/payments/{id}/process', [UserController::class, 'processPayment'])->name('payments.process');
    }); // Akhir Rute User

}); // Akhir Rute Auth

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
