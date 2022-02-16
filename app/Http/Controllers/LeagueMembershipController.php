<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

use BenSampo\Enum\Rules\EnumValue;
use App\Enums\Role;

class LeagueMembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $language
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function index($language, League $league)
    {
        $members = $league->members()->get();
        Log::info('preparing select2 league membership list.', ['league-id' => $league->id, 'count' => count($members)]);

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
     * @param string $language
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
     *
     */
    public function add(Request $request, League $league, Member $member)
    {
        $data = $request->validate([
            'selRole' => ['required', new EnumValue(Role::class, false)],
            'function'  => 'nullable|max:40',
            'email'     => 'nullable|max:60|email:rfc,dns',
        ]);
        Log::info('league membership form data validated OK.', ['league-id' => $league->id, 'member-id' => $member->id]);

        // create a new membership
        $league->memberships()->create([
            'role_id' => $data['selRole'],
            'member_id' => $member->id,
            'function' => $data['function'],
            'email' => $data['email']
        ]);
        Log::notice('new league membership created.', ['league-id'=>$league->id, 'member-id'=>$member->id]);

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function update(Request $request, League $league, Member $member)
    {
        $data = $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);
        Log::info('league membership form data validated OK.', ['league-id'=>$league->id, 'member-id'=>$member->id]);

        // get all current memberships
        $mships = $member->memberships->where('membership_type', League::class)->where('membership_id', $league->id);
        $member_new = Member::find($data['member_id']);

        foreach ($mships as $ms) {
            //Log::debug($role);
            $ms->update(['member_id' => $member_new->id]);
        }
        Log::notice('league membership updated.', ['league-id'=>$league->id, 'member-id-old'=>$member->id, 'member-id-new'=>$member_new->id]);

        // check if old member is w/out memberships, if so delete
        if ($member_new->memberships->count() == 0) {
            $member_new->delete();
        }

        return redirect()->action(
            'LeagueController@dashboard',
            ['language' => app()->getLocale(), 'league' => $league->id]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\League $league
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function destroy(League $league, Member $member)
    {
        $mships = $league->memberships()->where('member_id', $member->id)->get();
        foreach ($mships as $ms) {
            $ms->delete();
            Log::notice('league membership deleted.', ['league-id'=>$league->id, 'member-id'=>$member->id]);
        }

        return redirect()->back();
    }
}
