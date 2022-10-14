<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (isset($request->scope) and ($request->scope == Club::class)) {
            $roles[] = Role::coerce('ClubLead');
            $roles[] = Role::coerce('RefereeLead');
            $roles[] = Role::coerce('RegionTeam');
            $roles[] = Role::coerce('JuniorsLead');
            $roles[] = Role::coerce('GirlsLead');
        } elseif (isset($request->scope) and ($request->scope == League::class)) {
            $roles[] = Role::coerce('LeagueLead');
        } elseif (isset($request->scope) and ($request->scope == Region::class)) {
            $roles[] = Role::coerce('RegionLead');
            $roles[] = Role::coerce('RegionTeam');
        } elseif (isset($request->scope) and ($request->scope == Team::class)) {
            $roles[] = Role::coerce('TeamCoach');
        } else {
            $roles = Role::getInstances();
        }

        Log::info('preparing select2 role list.', ['count' => count($roles)]);
        $response = [];

        foreach ($roles as $role) {
            $response[] = [
                'id' => $role->value,
                'text' => $role->description,
            ];
        }

        return Response::json($response);
    }
}
