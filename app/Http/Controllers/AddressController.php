<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Member;
use App\Models\Region;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AddressController extends Controller
{
    /**
     * View for address list
     *
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @param  int  $role
     * @return \Illuminate\View\View
     */
    public function index_byrole(string $language, Region $region, int $role): View
    {
        return view('address.address_role_list', ['role' => $role, 'region' => $region]);
    }

    /**
     * datatable for addresses by region and role (eg all clubleads for region 2)
     *
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @param  int  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_byrole_dt(string $language, Region $region, int $role)
    {
        $role = Role::coerce(intval($role));
        Log::debug('role', ['role' => $role]);
        $all = collect();
        $filtered = collect();

        if ($role->in([Role::ClubLead, Role::GirlsLead, Role::JuniorsLead, Role::RefereeLead])) {
            $all = Member::with('clubs')
                ->whereHas('clubs', function (Builder $q) use ($region) {
                    $q->where('region_id', $region->id);
                })
                ->get();

            $filtered = $all->filter(function ($v, $k) use ($role) {
                return collect($v['clubs'])->contains('pivot.role_id', $role->value);
            });
        } elseif ($role->in([Role::LeagueLead])) {
            $all = Member::with('leagues')
                ->whereHas('leagues', function (Builder $q) use ($region) {
                    $q->where('region_id', $region->id);
                })
                ->get();

            $filtered = $all->filter(function ($v, $k) use ($role) {
                return collect($v['leagues'])->contains('pivot.role_id', $role->value);
            });
        } elseif ($role->in([Role::RegionLead, Role::RegionTeam])) {
            $all = Member::with('region')
                ->whereHas('region', function (Builder $q) use ($region) {
                    $q->where('membership_id', $region->id);
                })
                ->get();

            $filtered = $all->filter(function ($v, $k) use ($role) {
                return collect($v['region'])->contains('pivot.role_id', $role->value);
            });
        }

        Log::notice('all members filtered', ['all' => $all->count(), 'filtered' => $filtered->count()]);
        $adrlist = datatables()::of($filtered);

        return $adrlist
            ->addColumn('name', function ($m) {
                return $m->name;
            })
            ->addColumn('email', function ($m) {
                return $m->email1 ?? $m->email2;
            })
            ->addColumn('phone', function ($m) {
                return $m->mobile ?? $m->phone;
            })
            ->make(true);
    }
}
