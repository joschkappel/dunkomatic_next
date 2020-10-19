<?php

namespace App\Http\Controllers;

use App\Enums\Role;
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

      if ( isset($request->scope) and ($request->scope == 'LEAGUE')){
        $roles[] = Role::coerce('LeagueLead');
      } else {
        $roles = Role::getInstances();
      };

      Log::debug('got roles '.count($roles));
      $response = array();

      foreach($roles as $role){
          $response[] = array(
                "id"=>$role->value,
                "text"=>$role->description
              );
      }

      return Response::json($response);
    }

}
