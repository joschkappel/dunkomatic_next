<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class CheckApproved
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
      if (!auth()->user()->approved_at) {
          Log::warning('[ACCESS DENIED] user not approved.', ['user-id'=>auth()->user()->id]);
          return redirect()->route('approval',app()->getLocale());
      }

      return $next($request);
    }
}
