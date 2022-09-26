<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Club;
use App\Models\Member;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClubMembershipController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $language
     * @param  \App\Models\Club  $club
     * @return \Illuminate\View\View
     */
    public function create($language, Club $club)
    {
        Log::info('create new club member', ['club-id' => $club->id]);

        return view('member/member_new', ['entity' => $club, 'entity_type' => Club::class]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Club  $club
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request, Club $club, Member $member)
    {
        $data = $request->validate([
            'selRole' => ['required', new EnumValue(Role::class, false)],
            'function' => 'nullable|max:40',
            'email' => 'nullable|max:60|email:rfc,dns',
        ]);
        Log::info('club membership form data validated OK.', ['club-id' => $club->id, 'member-id' => $member->id]);

        // create a new membership
        $ms = $club->memberships()->create([
            'role_id' => $data['selRole'],
            'member_id' => $member->id,
            'function' => $data['function'],
            'email' => $data['email'],
        ]);
        Log::notice('new club membership created.', ['club-id' => $club->id, 'member-id' => $member->id]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Club  $club
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Club $club, Member $member)
    {
        // Log::debug(print_r($membership,true));
        // delete all club related memberships
        $mships = $club->memberships()->where('member_id', $member->id)->get();
        foreach ($mships as $ms) {
            $ms->delete();
            Log::notice('club membership deleted.', ['club-id' => $club->id, 'member-id' => $member->id]);
        }

        return redirect()->back();
    }
}
