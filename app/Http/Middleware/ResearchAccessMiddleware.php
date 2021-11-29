<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

class ResearchAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->user_type_id == 1) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
