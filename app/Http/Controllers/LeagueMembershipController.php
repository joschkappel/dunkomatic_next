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
      Log::debug(print_r($league->region,true));
      $clublist = Club::where('region', $league->region)->with('memberships')->get();
      Log::debug('got clubs '.print_r(count($clublist),true));

      $mroles = array();
      foreach( $clublist as $club){
        foreach( $club->memberships as $mr){
          $mroles[] = $mr->id;
        }
      }

      Log::debug('got memberships '.print_r(count($mroles),true));
      $members = Member::whereIn('id', $mroles)->orderBy('lastname','ASC','firstname','ASC')->get();
      Log::debug('got members '.count($members));

      $response = array();

      foreach($members as $member){
          $response[] = array(
                "id"=>$member->id,
                "text"=>$member->lastname.', '.$member->firstname
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
      return view('member/membership_league_new', ['league' => $league]);
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
      Log::debug(print_r($request->all(),true));

      $data = $request->validate( [
          'selMember' => 'nullable|exists:members,id',
          'selRole'   => 'required|array|min:1',
          'function'  => 'max:40',
          'firstname' => 'required|max:20',
          'lastname' => 'required|max:60',
          'zipcode' => 'required|max:10',
          'city' => 'required|max:40',
          'street' => 'required|max:40',
          'mobile' => 'required_without:phone1|max:40',
          'phone1' => 'required_without:mobile|max:40',
          'phone2' => 'max:40',
          'fax1' => 'max:40',
          'fax2' => 'max:40',
          'email1' => 'required|max:40|email:rfc,dns',
          'email2' => 'nullable|max:40|email:rfc,dns',
      ]);

      Log::debug(print_r($data['selRole'],true));

      if ( isset($data['selMember'])){
        Log::info('use existing member '.$data['selMember']);
        $member = Member::find($data['selMember']);
      } else {
        Log::info('create a new member');
        $member = Member::create($data);
      }

      foreach ( $data['selRole'] as $k => $role ){
        //Log::debug($role);
        $new_mrole = Membership::create(['role_id' => $role,
                                         'member_id' => $member->id,
                                         'membershipable_id' => $league->id ,
                                         'membershipable_type' => 'App\Models\League']);
        //Log::debug(print_r($new_mrole,true));
        //$league->memberships()->save($new_mrole);
      }

      return redirect()->action(
            'LeagueController@dashboard', ['language'=>app()->getLocale(),'id' => $league->id]
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
    public function edit($language, League $league, Membership $membership)
    {
      $data = Membership::with('member')->find($membership->id);
      $member = $data['member'];
      $memberships = $data;
      Log::debug(print_r($memberships,true));
      //Log::debug(print_r($member,true));
      return view('member/membership_league_edit', ['member' => $member, 'member_roles' => $memberships, 'league' => $league]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, League $league, Member $membership)
    {
      Log::debug(print_r($request->all(),true));
      $data = $request->validate( [
          'firstname' => 'required|max:20',
          'lastname' => 'required|max:60',
          'zipcode' => 'required|max:10',
          'city' => 'required|max:40',
          'street' => 'required|max:40',
          'mobile' => 'required_without:phone1|max:40',
          'phone1' => 'required_without:mobile|max:40',
          'phone2' => 'max:40',
          'fax1' => 'max:40',
          'fax2' => 'max:40',
          'email1' => 'required|max:40|email:rfc,dns',
          'email2' => 'nullable|max:40|email:rfc,dns',
      ]);

      $member = $membership;

      //Log::info(print_r($data, true));

      // eliminate non model props
      unset($data['old_role_id']);

      $check = Member::where('id', $member->id)->update($data);
      return redirect()->action(
            'LeagueController@dashboard', ['language'=>app()->getLocale(), 'id' => $league->id]
      );
    }

}
