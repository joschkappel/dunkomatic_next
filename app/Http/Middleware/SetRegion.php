<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetRegion
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

        if (($request->region) === null) {
            return redirect()->route('home', app()->getLocale() )->withErrors(['error' => __('Please select a customer/tenant before making this request.')]);
        }

        return $next($request);
    }
}
