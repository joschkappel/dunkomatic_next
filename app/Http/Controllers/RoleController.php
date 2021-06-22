<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\League;
use App\Models\Region;
use App\Models\Club;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      Log::debug(print_r($request->all(),true));

      if ( isset($request->scope) and ($request->scope == Club::class )){
        $roles[] = Role::coerce('ClubLead');
        $roles[] = Role::coerce('RefereeLead');
        $roles[] = Role::coerce('RegionTeam');
        $roles[] = Role::coerce('JuniorsLead');
        $roles[] = Role::coerce('GirlsLead');

      } elseif ( isset($request->scope) and ($request->scope == League::class )){
        $roles[] = Role::coerce('LeagueLead');
      } elseif ( isset($request->scope) and ($request->scope == Region::class )){
        $roles[] = Role::coerce('RegionLead');
      } else {
        $roles = Role::getInstances();
      };

      Log::debug('got roles '.count($roles));
      $response = array();

      foreach($roles as $role){
          if ($role->is(Role::User)){
            $response[] = array(
                  "id"=>$role->value,
                  "text"=>$role->description,
                  "disabled"=>true
                );
          } else {
            $response[] = array(
              "id"=>$role->value,
              "text"=>$role->description,
            );
          }
      }

      return Response::json($response);
    }

}
