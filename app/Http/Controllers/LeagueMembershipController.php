<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Club;

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
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function index($language, League $league)
    {
      $members = $league->members()->get();
      //Log::debug('got members '.count($members));

      $response = array();

      foreach($members as $member){
          $response[] = array(
                "id"=>$member->id,
                "text"=>$member->name
              );
      }

      return Response::json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function create($language, League $league)
    {
      $region_leagues = $league->region->leagues()->get();

      $members = collect();
      foreach ($region_leagues as $l){
        $members = $members->merge($l->members()->get());
      }

      return view('member/membership_league_new', ['league' => $league,  'members' => $members->unique()->sortBy('lastname')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, League $league)
    {
      $data = $request->validate( [
          'member_id' => 'required|exists:members,id',
          'selRole' => ['required', new EnumValue(Role::class, false)],
          'function'  => 'nullable|max:40',
      ]);

      Log::debug(print_r($data['selRole'],true));

      $member = Member::find($data['member_id']);

      $new_mrole = $league->memberships()->create(['role_id' => $data['selRole'],
                                         'member_id' => $member->id]);

      return redirect()->action(
            'LeagueController@dashboard', ['language'=>app()->getLocale(),'league' => $league]
      );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\League  $league
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(League $league, Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\League  $league
     * @param  \App\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function edit($language, League $league, Member $member )
    {
      $memberships = $league->memberships()->where('member_id', $member->id)->get();
      Log::debug(print_r($memberships,true));
      $region_leagues = $league->region->leagues()->get();
      $members = collect();
      foreach ($region_leagues as $l){
        $members = $members->merge($l->members()->get());
      }

      //Log::debug(print_r($member,true));
      return view('member/membership_league_edit', ['member' => $member, 'membership' => $memberships, 'league' => $league,'members' => $members->unique()->sort()]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, League $league, Member $member)
    {
      Log::debug(print_r($request->all(),true));
      $data = $request->validate( [
        'member_id' => 'required|exists:members,id',
        'selRole' => ['required', new EnumValue(Role::class, false)],
        'function'  => 'nullable|max:40',
      ]);

      $member_new = Member::find($data['member_id']);

      // delete current membership
      $league->memberships()->where('member_id',$member->id)->delete();

      // create new membership
      $new_mrole = $league->memberships()->create(['role_id' => $data['selRole'],
                                         'member_id' => $member_new->id]);

      return redirect()->action(
            'LeagueController@dashboard', ['language'=>app()->getLocale(), 'league' => $league->id]
      );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\League $league
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(League $league, Member $member)
    {
        // Log::debug(print_r($membership,true));
        // delete all league related memberships
        $league->memberships()->where('member_id',$member->id)->delete();

        $member->refresh();
        // now check if there are any other memberships for this member
        if ( $member->memberships()->count() == 0){
          // none, delete member as well
          $member->delete();
        }

        return redirect()->back();
    }

}
