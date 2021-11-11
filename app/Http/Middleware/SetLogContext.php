<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SetLogContext
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
        $requestId = (string) Str::uuid();

        Log::withContext([
            // 'request-id' => $requestId,
            'user-id' => Auth::user()->id ?? 'not logged in',
            'region-id' => session('cur_region')->id ?? 'no region set'
        ]);

        return $next($request); // ->header('Request-Id', $requestId);
    }
}
