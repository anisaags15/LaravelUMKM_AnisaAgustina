<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Belum login → ke halaman login
        if (!Auth::check()) {
            return redirect('login');
        }

        $userRole = Auth::user()->role;

        // 2. Role cocok → lanjut
        if ($userRole === $role) {
            return $next($request);
        }

        // 3. Role tidak cocok → arahkan sesuai role aslinya
        if ($userRole === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard'); // pelanggan
    }
}