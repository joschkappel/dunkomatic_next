<?php

namespace App\Http\Middleware;

use Closure;

class CheckRegionAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->user()->regionadmin) {
          return redirect()->route('home',app()->getLocale());
        }
        return $next($request);
    }
}
