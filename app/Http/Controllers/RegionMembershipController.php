<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        return view('member/member_new', ['entity' => $region,  'entity_type' => Region::class]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, Region $region, Member $member)
    {
        $data = $request->validate([
            'selRole' => ['required', new EnumValue(Role::class, false)],
            'function'  => 'nullable|max:40',
            'email'     => 'nullable|max:60|email:rfc,dns',
            ]);

         // create a new membership
         $region->memberships()->create(['role_id' => $data['selRole'],
                                        'member_id' => $member->id,
                                        'function' => $data['function'],
                                          'email' => $data['email']
                                        ]);

         return redirect()->back();
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
      ]);

      // get all current memberships
      $mships = $member->memberships->where('membership_type', Region::class)->where('membership_id', $region->id);
      $member_new = Member::find($data['member_id']);

      foreach ( $mships as $ms ){
        //Log::debug($role);
        $ms->update(['member_id' => $member_new->id]);
      }

      // check if old member is w/out memberships, if so delete
      if ( $member_new->memberships->count() == 0 ){
        $member_new->delete();
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
