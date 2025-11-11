<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Debug informasi user
        \Log::info('User Role Check', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'required_role' => $role,
            'email' => $user->email
        ]);

        if ($user->role !== $role) {
            \Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'required_role' => $role,
                'route' => $request->route()->getName()
            ]);

            abort(403, 'Unauthorized action. Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
