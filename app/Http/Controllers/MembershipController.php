<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Member;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;


class MembershipController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function store(Request $request)
    {
        $entity_type = $request['entity_type'];
        unset($request['entity_type']);
        $entity_id = $request['entity_id'];
        unset($request['entity_id']);

        $data = $request->validate([
            'member_id' => 'required|exists:members,id',
            'role_id' => ['required', new EnumValue(Role::class, false)],
            'function'  => 'nullable|max:40',
            'email'     => 'nullable|max:60|email:rfc,dns'
        ]);
        Log::info('membership form data validated OK.');

        $member = Member::findOrFail($data['member_id']);

        if ($entity_type == Club::class) {
            $club = Club::findOrFail($entity_id);
            $ms = $club->memberships()->create($data);
            Log::notice('club membership created.', ['club-id'=> $club->id, 'member-id'=>$member->id, 'membership-id'=>$ms->id]);
            // return redirect()->action('ClubController@dashboard', ['language' => app()->getLocale(), 'club' => $club]);
        } elseif ($entity_type == League::class) {
            $league = League::findOrFail($entity_id);
            $ms = $league->memberships()->create($data);
            Log::notice('league membership created.', ['league-id'=> $league->id, 'member-id'=>$member->id, 'membership-id'=>$ms->id]);
            //  return redirect()->action('LeagueController@dashboard', ['language' => app()->getLocale(), 'league' => $league]);
        } elseif ($entity_type == Region::class) {
            $region = Region::findOrFail($entity_id);
            $ms = $region->memberships()->create($data);
            Log::notice('region membership created.', ['region-id'=> $region->id, 'member-id'=>$member->id, 'membership-id'=>$ms->id]);
        }
        return Response::json(['success' => 'all good'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Membership $membership)
    {
        // delete role
        $membership->delete();
        Log::notice('membership deleted.',['membership-id'=>$membership->id]);

        return Response::json(['success'=>'all good'], 200);
    }

    /**
     * Add  the specified resource to storage.
     *
     * @param Request $request
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function update(Request $request, Membership $membership)
    {
        $data = $request->validate([
            'function'  => 'nullable|max:40',
            'email'     => 'nullable|max:60|email:rfc,dns',
        ]);
        Log::info('membership form data validated OK.');

        $check = $membership->update($data);
        Log::notice('membership updated.',['membership-id'=>$membership->id]);

        return redirect()->back();
    }
}
