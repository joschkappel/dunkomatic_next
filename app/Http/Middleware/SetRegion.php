<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

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

        if (($request->region) !== null) {
            if ($request->query('region') !== null){
                $region = Region::find($request->query('region'));
                $request->session()->put('cur_region', $region );
                return redirect(route(RouteServiceProvider::HOME, Auth::user()->locale));
            } else {
                $request->session()->put('cur_region', $request->region );
            }
        }

        return $next($request);
    }
}
