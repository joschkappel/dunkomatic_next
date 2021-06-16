<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Member;
use App\Models\Membership;
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
    public function index( $language, Club $club)
    {
      $members = $club->members()->get();
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
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function create($language, Club $club)
    {
      $members = $club->members()->get();
      return view('member/membership_club_new', ['club' => $club, 'members' => $members]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Club $club)
    {
      Log::debug(print_r($request->all(),true));

      $data = $request->validate( [
          'member_id' => 'required|exists:members,id',
          'selRole'   => 'required|array|min:1',
          'selRole.*' => ['required', new EnumValue(Role::class, false)],
          'function'  => 'nullable|max:40',
          'email'     => 'nullable|max:60|email:rfc,dns'
      ] );

      Log::debug(print_r($data['selRole'],true));

      $member = Member::find($data['member_id']);

      foreach ( $data['selRole'] as $k => $role ){
        //Log::debug($role);
        $new_mrole = $club->memberships()->create(['role_id' => $role,
                                         'member_id' => $member->id]);
      }

      return redirect()->action(
            'ClubController@dashboard', ['language' => app()->getLocale(), 'club' => $club]
      );
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Club  $club
     * @param  \App\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function edit($language, Club $club, Member $member)
    {
      $memberships = $club->memberships()->where('member_id', $member->id)->get();
      Log::debug(print_r($memberships,true));
      $members = $club->members()->get();

      //Log::debug(print_r($member,true));
      return view('member/membership_club_edit', ['member' => $member, 'membership' => $memberships, 'club' => $club,'members' => $members]);

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
                  'member_id' => 'required|exists:members,id',
                  'selRole'   => 'required|array|min:1',
                  'selRole.*' => ['required', new EnumValue(Role::class, false)],
                  'function'  => 'nullable|max:40',
                  'email'     => 'nullable|max:60|email:rfc,dns'
              ] );

              //Log::info(print_r($data, true));
              Log::debug(print_r($request->all(),true));
              $member_new = Member::find($data['member_id']);

              // delete current roles
              $club->memberships()->where('member_id',$member->id)->delete();

              foreach ( $data['selRole'] as $k => $role ){
                //Log::debug($role);
                $new_mrole = $club->memberships()->create(['role_id' => $role,
                                                 'member_id' => $member_new->id]);
              }

              return redirect()->action(
                    'ClubController@dashboard', ['language'=>app()->getLocale(),'club' => $club->id]
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
        $club->memberships()->where('member_id',$member->id)->delete();
        $member->refresh();
        // now check if there are any other memberships for this member
        if ( $member->memberships()->count() == 0){
          // none, delete member as well
          $member->delete();
        }

        return redirect()->back();
    }

}
