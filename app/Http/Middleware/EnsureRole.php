<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('teknisi.login')
                ->withErrors(['tech_id' => 'Silakan masuk terlebih dahulu dengan akun staf.']);
        }

        $user = auth()->user();

        if (!$user->is_active || (!empty($roles) && !in_array($user->role, $roles))) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Role akses tidak diizinkan.'], 403);
            }

            // Arahkan ke dashboard yang sesuai role jika sudah login
            if ($user->role === 'technician') {
                return redirect()->route('teknisi.dashboard')
                    ->withErrors(['error' => 'Akses ditolak. Halaman tersebut khusus Admin Workshop.']);
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('teknisi.login')
                ->withErrors(['tech_id' => 'Akses ditolak untuk peran akun Anda.']);
        }

        return $next($request);
    }
}
