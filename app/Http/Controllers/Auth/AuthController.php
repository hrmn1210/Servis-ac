<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    // --- REGISTRASI ---

    /**
     * Menampilkan form registrasi.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Memproses data dari form registrasi.
     */
    public function register(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Buat user baru - default role: user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role saat registrasi manual
        ]);

        // Login-kan user
        Auth::login($user);

        // Arahkan ke dashboard berdasarkan role
        return $this->redirectToDashboard($user);
    }

    // --- LOGIN ---

    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses data dari form login.
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Terapkan logika Email atau Username
        $loginInput = $request->input('login');
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Siapkan kredensial untuk dicoba
        $credentials = [
            $field => $loginInput,
            'password' => $request->input('password')
        ];

        // Coba lakukan login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Dapatkan user yang login
            $user = Auth::user();

            // Debug informasi user
            \Log::info('LOGIN SUCCESS', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => $user->role
            ]);

            // Arahkan ke dashboard berdasarkan role
            return $this->redirectToDashboard($user);
        }

        // Jika gagal
        return back()->withErrors([
            'login' => 'Kombinasi email/username dan password tidak cocok.',
        ])->onlyInput('login');
    }

    /**
     * Redirect ke dashboard berdasarkan role user
     */
    private function redirectToDashboard($user)
    {
        // Debug sebelum redirect
        \Log::info('REDIRECT TO DASHBOARD', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_email' => $user->email
        ]);

        if ($user->role === 'admin') {
            \Log::info('REDIRECTING ADMIN TO ADMIN DASHBOARD');
            return redirect()->route('admin.dashboard');
        } else {
            \Log::info('REDIRECTING USER TO USER DASHBOARD');
            return redirect()->route('user.dashboard');
        }
    }

    // --- LOGOUT ---

    /**
     * Memproses logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
