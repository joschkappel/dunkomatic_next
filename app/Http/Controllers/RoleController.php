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
        $roles = collect();

        if (isset($request->scope) and ($request->scope == Club::class)) {
            $roles->push(Role::ClubLead());
            $roles->push(Role::RefereeLead());
            $roles->push(Role::RegionTeam());
            $roles->push(Role::JuniorsLead());
            $roles->push(Role::GirlsLead());
        } elseif (isset($request->scope) and ($request->scope == League::class)) {
            $roles->push(Role::LeagueLead());
        } elseif (isset($request->scope) and ($request->scope == Region::class)) {
            $roles->push(Role::RegionLead());
            $roles->push(Role::RegionTeam());
        } elseif (isset($request->scope) and ($request->scope == Team::class)) {
            $roles->push(Role::TeamCoach());
        } else {
            $roles = collect(Role::getInstances())->flatten();
        }

        Log::info('preparing select2 role list.', ['count' => count($roles)]);

        $roles->transform(function ($role) {
            return [
                'id' => $role->value,
                'text' => $role->description,
            ];
        });

        return Response::json($roles->toArray());
    }
}
