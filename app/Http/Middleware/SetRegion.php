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

        if ( $request->new_region !== null) {
            $request->session()->put('cur_region', Region::find($request->new_region) );
        } else {
            if (($request->region != session('cur_region')) and ($request->region !== null)) {
                $region = Region::find($request->region->id) ;
                $request->session()->put('cur_region', $region);
                session()->put('cur_region', $region );
            }
            if ( (session('cur_region') == null) and (Auth::check()) ){
                session()->put('cur_region', Auth::user()->regions()->first() );
            }
        }
        /* else {

            if ($request->query('new_region') !== null){
                $region = Region::find($request->query('new_region'));
                $request->session()->put('cur_region', $region );
                return redirect(route(RouteServiceProvider::HOME, Auth::user()->locale));
            } else {
                if (Auth::user()){
                    $request->session()->put('cur_region', Auth::user()->regions()->first() );
                }
            }
        } */

        return $next($request);
    }
}
