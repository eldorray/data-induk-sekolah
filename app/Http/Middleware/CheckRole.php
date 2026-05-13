<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Batasi akses route berdasarkan role user yang sedang login.
 *
 * Usage (alias "role"):
 *   Route::get(...)->middleware('role:admin');
 *   Route::get(...)->middleware('role:admin,guru'); // boleh salah satu
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! empty($roles) && ! in_array($user->role, $roles, true)) {
            // Guru yang mencoba akses route admin → redirect ke halaman yang diizinkan
            if ($user->role === \App\Models\User::ROLE_GURU) {
                return redirect()->route('nilai-ijazah.index')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}