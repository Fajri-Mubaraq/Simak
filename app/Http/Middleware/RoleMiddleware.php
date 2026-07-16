<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: RoleMiddleware::class . ':admin,dosen'
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $userRole = session('user_role', 'staff');

        if (!in_array($userRole, $roles)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        return $next($request);
    }
}
