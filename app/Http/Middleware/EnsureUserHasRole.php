<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! auth()->check()) {
            Alert::warning('Akses Ditolak', 'Anda harus login terlebih dahulu.');

            return redirect()->route('auth.login');
        }

        $user = auth()->user();

        if (! $user->hasAnyRole($roles)) {
            Alert::error('Akses Ditolak', 'Anda tidak memiliki izin untuk mengakses halaman ini.');

            // Redirect based on user role
            if ($user->hasRole('student')) {
                return redirect('/'); // Students shouldn't access admin panel
            }

            return redirect()->route('dashboard.home');
        }

        return $next($request);
    }
}
