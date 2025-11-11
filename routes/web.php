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

    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Rute ADMIN - Complete User Management
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Complete User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::post('/users/{user}/verify', [AdminController::class, 'verifyUser'])->name('users.verify');
        Route::post('/users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('users.suspend');
        Route::post('/users/{user}/activate', [AdminController::class, 'activateUser'])->name('users.activate');
        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('/users/bulk-actions', [AdminController::class, 'bulkActions'])->name('users.bulk-actions');
        Route::get('/users/export', [AdminController::class, 'exportUsers'])->name('users.export');
        Route::put('/users/{user}/update-role', [AdminController::class, 'updateRole'])->name('users.update-role');
        // Bookings Management
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
        Route::get('/bookings/{id}', [AdminController::class, 'showBooking'])->name('bookings.show');
        Route::put('/bookings/{id}', [AdminController::class, 'updateBooking'])->name('bookings.update');
        Route::post('/bookings/{id}/assign-technician', [AdminController::class, 'assignTechnician'])->name('bookings.assign-technician');
        Route::delete('/bookings/{id}', [AdminController::class, 'deleteBooking'])->name('bookings.delete');

        // Payments
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::get('/payments/{id}', [AdminController::class, 'getPaymentData'])->name('payments.data');
        Route::put('/payments/{id}', [AdminController::class, 'updatePayment'])->name('payments.update');
        Route::post('/payments/{id}/refund', [AdminController::class, 'refundPayment'])->name('payments.refund');
        Route::get('/payments/verification', [AdminController::class, 'paymentVerification'])->name('payments.verification');
        Route::post('/payments/{id}/verify', [AdminController::class, 'verifyPayment'])->name('payments.verify');
        // Reports
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    });

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

        // Bookings (Hanya bookings, service requests dihapus)
        Route::get('/bookings', [UserController::class, 'bookings'])->name('bookings');
        Route::get('/bookings/create', [UserController::class, 'createBooking'])->name('bookings.create');
        Route::post('/bookings', [UserController::class, 'storeBooking'])->name('bookings.store');
        Route::get('/bookings/{id}', [UserController::class, 'showBooking'])->name('bookings.show');
        Route::post('/bookings/{id}/cancel', [UserController::class, 'cancelBooking'])->name('bookings.cancel');

        // Payments
        Route::get('/payments', [UserController::class, 'payments'])->name('payments');
        Route::get('/payments/{id}', [UserController::class, 'showPayment'])->name('payments.show');
        Route::post('/payments/{id}/process', [UserController::class, 'processPayment'])->name('payments.process');
    });
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
