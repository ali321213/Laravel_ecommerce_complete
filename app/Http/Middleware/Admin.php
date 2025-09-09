<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
    public function handle($request, Closure $next)
    {
        if ($request->user()->role === 'admin') {
            return $next($request);
        }
        $request->session()->flash('error', 'You do not have permission to access this page');
        return match ($request->user()->role) {
            'user' => redirect()->route('user.dashboard'),
            default => abort(403),
        };
    }
}
