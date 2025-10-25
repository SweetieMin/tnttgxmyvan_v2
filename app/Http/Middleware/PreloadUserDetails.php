<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreloadUserDetails
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Nếu đã đăng nhập và có quan hệ 'details' => preload
        if (Auth::check()) {
            $user = Auth::user();

            // Chỉ load nếu chưa được load
            if (! $user->relationLoaded('details')) {
                $user->load('details');
            }
        }

        return $next($request);
    }
}
