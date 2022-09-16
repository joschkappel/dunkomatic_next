<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Member;
use App\Enums\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use BenSampo\Enum\Rules\EnumValue;


class TeamMembershipController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @param string $language
     * @param  \App\Models\Team  $team
     * @return \Illuminate\View\View
     *
     */
    public function create($language, Team $team)
    {
        Log::info('create new team member',['team-id'=>$team->id]);
        return view('member/member_new', ['entity' => $team, 'entity_type' => Team::class]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function add(Request $request, Team $team, Member $member)
    {
        $data = $request->validate([
            'selRole' => ['required', new EnumValue(Role::class, false)],
            'function'  => 'nullable|max:40',
            'email'     => 'nullable|max:60|email:rfc,dns',
        ]);
        Log::info('team membership form data validated OK.', ['team-id'=>$team->id, 'member-id'=>$member->id]);

        // create a new membership
        $ms = $team->memberships()->create([
            'role_id' => $data['selRole'],
            'member_id' => $member->id,
            'function' => $data['function'],
            'email' => $data['email']
        ]);
        Log::notice('new team membership created.', ['team-id'=>$team->id, 'member-id'=>$member->id]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team $team
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function destroy(Team $team, Member $member)
    {
        // Log::debug(print_r($membership,true));
        // delete all club related memberships
        $mships = $team->memberships()->where('member_id', $member->id)->get();
        foreach ($mships as $ms) {
            $ms->delete();
            Log::notice('team membership deleted.', ['team-id'=>$team->id, 'member-id'=>$member->id]);
        }

        return redirect()->back();
    }
}
