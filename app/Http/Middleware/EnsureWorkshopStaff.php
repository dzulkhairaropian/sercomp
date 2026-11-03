<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkshopStaff
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('teknisi.login')
                ->withErrors(['tech_id' => 'Silakan masuk terlebih dahulu dengan akun teknisi atau admin workshop.']);
        }

        $user = auth()->user();

        if (!in_array($user->role, ['technician', 'admin']) || !$user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Workshop staff access only.'], 403);
            }

            return redirect()->route('teknisi.login')
                ->withErrors(['tech_id' => 'Akses ditolak. Halaman ini hanya untuk staf teknisi dan admin workshop aktif.']);
        }

        return $next($request);
    }
}
