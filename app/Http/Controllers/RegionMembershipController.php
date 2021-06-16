<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Member;

use App\Notifications\InviteUser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use BenSampo\Enum\Rules\EnumValue;
use App\Enums\Role;

class RegionMembershipController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function create($language, Region $region)
    {

        $members = $region->members()->get();

      return view('member/membership_region_new', ['region' => $region,  'members' => $members->unique()->sortBy('lastname')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Region $region)
    {
      $data = $request->validate( [
          'member_id' => 'required|exists:members,id',
          'selRole' => ['required', new EnumValue(Role::class, false)],
          'function'  => 'nullable|max:40',
          'email'     => 'nullable|max:60|email:rfc,dns',
      ]);

      Log::debug(print_r($data['selRole'],true));

      $member = Member::find($data['member_id']);

      $new_mrole = $region->memberships()->create(['role_id' => $data['selRole'],
                                         'member_id' => $member->id]);

      // invite to join as user
      if (! $member->is_user ){
        $member->notify(new InviteUser(Auth::user()));
      }

      return redirect()->action(
            'RegionController@index', ['language'=>app()->getLocale(),'region' => $region]
      );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Region  $region
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region, Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Region  $region
     * @param  \App\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function edit($language, Region $region, Member $member )
    {
      $memberships = $region->memberships()->where('member_id', $member->id)->get();
      Log::debug(print_r($memberships,true));
      $members = $region->members()->get();

      //Log::debug(print_r($member,true));
      return view('member/membership_region_edit', ['member' => $member, 'membership' => $memberships, 'region' => $region,'members' => $members->unique()->sort()]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region, Member $member)
    {
      Log::debug(print_r($request->all(),true));
      $data = $request->validate( [
        'member_id' => 'required|exists:members,id',
        'selRole' => ['required', new EnumValue(Role::class, false)],
        'function'  => 'nullable|max:40',
        'email'     => 'nullable|max:60|email:rfc,dns',
      ]);

      $member_new = Member::find($data['member_id']);

      // delete current membership
      $region->memberships()->where('member_id',$member->id)->delete();

      // create new membership
      $new_mrole = $region->memberships()->create(['role_id' => $data['selRole'],
                                         'member_id' => $member_new->id]);

      // invite to join as user
      if (! $member_new->is_user ){
        $member_new->notify(new InviteUser(Auth::user()));
      }

      return redirect()->action(
            'RegionController@index', ['language'=>app()->getLocale(), 'region' => $region->id]
      );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Region $region
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region, Member $member)
    {
        // Log::debug(print_r($membership,true));
        // delete all league related memberships
        $region->memberships()->where('member_id',$member->id)->delete();

        $member->refresh();
        // now check if there are any other memberships for this member
        if ( $member->memberships()->count() == 0){
          // none, delete member as well
          $member->delete();
        }

        return redirect()->back();
    }

}
