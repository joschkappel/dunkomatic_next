<?php

namespace App\Http\Controllers;

use App\League;
use App\Member;
use App\MemberRole;
use App\Club;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class LeagueMemberRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\League  $league
     * @return \Illuminate\Http\Response
     */
    public function index($language, League $league)
    {
      Log::debug(print_r($league->region,true));
      $clublist = Club::where('region', $league->region)->with('member_roles')->get();
      Log::debug('got clubs '.print_r(count($clublist),true));

      $mroles = array();
      foreach( $clublist as $club){
        foreach( $club->member_roles as $mr){
          $mroles[] = $mr->id;
        }
      }

      Log::debug('got memberroles '.print_r(count($mroles),true));
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
     * @param  \App\League  $league
     * @return \Illuminate\Http\Response
     */
    public function create($language, League $league)
    {
      return view('member/memberrole_league_new', ['league' => $league]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\League  $league
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, League $league)
    {
      Log::debug(print_r($request->all(),true));

      $data = $request->validate( [
          'selMember' => 'nullable|exists:members,id',
          'selRole'   => 'required|array|min:1|exists:roles,id',
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
        $new_mrole = MemberRole::create(['role_id' => $role, 'member_id' => $member->id ] );
        //Log::debug(print_r($new_mrole,true));
        $league->member_roles()->save($new_mrole);
      }

      return redirect()->action(
            'LeagueController@dashboard', ['language'=>app()->getLocale(),'id' => $league->id]
      );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\League  $league
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
     * @param  \App\League  $league
     * @param  \App\MemberRole  $memberrole
     * @return \Illuminate\Http\Response
     */
    public function edit($language, League $league, MemberRole $memberrole)
    {
      $data = MemberRole::with('member')->find($memberrole->id);
      $member = $data['member'];
      $member_roles = $data;
      Log::debug(print_r($member_roles,true));
      //Log::debug(print_r($member,true));
      return view('member/memberrole_league_edit', ['member' => $member, 'member_roles' => $member_roles, 'league' => $league]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\League  $league
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, League $league, Member $memberrole)
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

      $member = $memberrole;

      //Log::info(print_r($data, true));

      // eliminate non model props
      unset($data['old_role_id']);

      $check = Member::where('id', $member->id)->update($data);
      return redirect()->action(
            'LeagueController@dashboard', ['language'=>app()->getLocale(), 'id' => $league->id]
      );
    }

}
