<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
use Closure;

class SetLanguage
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

        if ( in_array($request->language, config('app.supported_languages'))){
          App::setLocale($request->language);
        }

        return $next($request);
    }
}
