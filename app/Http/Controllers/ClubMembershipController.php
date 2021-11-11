<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

use BenSampo\Enum\Rules\EnumValue;
use App\Enums\Role;

class ClubMembershipController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($language, Club $club)
    {
        $members = $club->members()->get();
        Log::info('preparing select2 club membership list.', ['club-id'=>$club->id, 'count' => count($members)] );

        $response = array();

        foreach ($members as $member) {
            $response[] = array(
                "id" => $member->id,
                "text" => $member->name
            );
        }

        return Response::json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function create($language, Club $club)
    {
        Log::info('create new club member',['club-id'=>$club->id]);
        return view('member/member_new', ['entity' => $club, 'entity_type' => Club::class]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Club  $club
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, Club $club, Member $member)
    {
        $data = $request->validate([
            'selRole' => ['required', new EnumValue(Role::class, false)],
            'function'  => 'nullable|max:40',
            'email'     => 'nullable|max:60|email:rfc,dns',
        ]);
        Log::info('club membership form data validated OK.', ['club-id'=>$club->id, 'member-id'=>$member->id]);

        // create a new membership
        $ms = $club->memberships()->create([
            'role_id' => $data['selRole'],
            'member_id' => $member->id,
            'function' => $data['function'],
            'email' => $data['email']
        ]);
        Log::notice('new club membership created.', ['club-id'=>$club->id, 'member-id'=>$member->id]);

        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Club  $club
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Club $club, Member $member)
    {
        $data = $request->validate([
            'member_id' => 'required|exists:members,id'
        ]);
        Log::info('club membership form data validated OK.', ['club-id'=>$club->id, 'member-id'=>$member->id]);

        // get all current memberships
        $mships = $member->memberships->where('membership_type', Club::class)->where('membership_id', $club->id);
        $member_new = Member::findOrFail($data['member_id']);

        foreach ($mships as $ms) {
            //Log::debug($role);
            $ms->update(['member_id' => $member_new->id]);
        }
        Log::notice('club membership updated.', ['club-id'=>$club->id, 'member-id-old'=>$member->id, 'member-id-new'=>$member_new->id]);

        // check if old member is w/out memberships, if so delete
        if ($member_new->memberships->count() == 0) {
            $member_new->delete();
        }

        return redirect()->action(
            'ClubController@dashboard',
            ['language' => app()->getLocale(), 'club' => $club->id]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Club $club
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Club $club, Member $member)
    {
        // Log::debug(print_r($membership,true));
        // delete all club related memberships
        $mships = $club->memberships()->where('member_id', $member->id)->get();
        foreach ($mships as $ms) {
            $ms->delete();
            Log::notice('club membership deleted.', ['club-id'=>$club->id, 'member-id'=>$member->id]);
        }

        return redirect()->back();
    }
}
