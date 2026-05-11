<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }
        
        // Redirect ke dashboard sesuai role
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        } elseif ($user->hasRole('chairman')) {
            return redirect()->route('chairman.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }
        
        return redirect()->route('member.profile')
            ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}
