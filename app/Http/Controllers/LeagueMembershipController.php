<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\League;
use App\Models\Member;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeagueMembershipController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $language
     * @param  \App\Models\League  $league
     * @return \Illuminate\View\View
     */
    public function create($language, League $league)
    {
        Log::info('create new league member', ['league-id' => $league->id]);

        return view('member/member_new', ['entity' => $league, 'entity_type' => League::class]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request, League $league, Member $member)
    {
        $data = $request->validate([
            'selRole' => ['required', new EnumValue(Role::class, false)],
            'function' => 'nullable|max:40',
            'email' => 'nullable|email:rfc,dns',
        ]);
        Log::info('league membership form data validated OK.', ['league-id' => $league->id, 'member-id' => $member->id]);

        // create a new membership
        $league->memberships()->create([
            'role_id' => $data['selRole'],
            'member_id' => $member->id,
            'function' => $data['function'],
            'email' => $data['email'],
        ]);
        Log::notice('new league membership created.', ['league-id' => $league->id, 'member-id' => $member->id]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\League  $league
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(League $league, Member $member)
    {
        $mships = $league->memberships()->where('member_id', $member->id)->get();
        foreach ($mships as $ms) {
            $ms->delete();
            Log::notice('league membership deleted.', ['league-id' => $league->id, 'member-id' => $member->id]);
        }

        return redirect()->back();
    }
}
