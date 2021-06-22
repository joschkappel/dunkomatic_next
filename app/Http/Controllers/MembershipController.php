<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Member;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class MembershipController extends Controller
{

   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      Log::debug(print_r($request->all(),true));

      $entity_type = $request['entity_type'];
      unset($request['entity_type']);
      $entity_id = $request['entity_id'];
      unset($request['entity_id']);

      $data = $request->validate( [
        'member_id' => 'required|exists:members,id',
        'role_id' => ['required', new EnumValue(Role::class, false)],
        'function'  => 'nullable|max:40',
        'email'     => 'nullable|max:60|email:rfc,dns'
      ] );

      $member = Member::find($data['member_id']);

      if ($entity_type == Club::class){
        $club = Club::find($entity_id);
        $club->memberships()->create($data);
        return redirect()->action('ClubController@dashboard', ['language' => app()->getLocale(), 'club' => $club]);
      } elseif ($entity_type == League::class){
        $league = League::find($entity_id);
        $league->memberships()->create($data);
        return redirect()->action('LeagueController@dashboard', ['language' => app()->getLocale(), 'league' => $league]);
      } elseif ($entity_type == Region::class){        
        $region = Region::find($entity_id);
        $region->memberships()->create($data);
      }

    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function destroy( Membership $membership)
    {
        // Log::debug(print_r($membership,true));
        $member = Member::find($membership->member_id);
        // Log::debug(print_r($member,true));
        // delete role
        $check = Membership::where('id', $membership->id)->delete();

        // get left roles for member
        $other_roles = $member->memberships()->get();
        Log::debug(print_r(count($other_roles),true));
        if ( count($other_roles) == 0){
          // delete member as well
          Member::find($membership->member_id)->delete();
        }

        return true;
    }

    /*
     * Add  the specified resource to storage.
     *
     * @param  \App\Membership  $member
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, Membership $membership)
    {
      $data = $request->validate([
        'function'  => 'nullable|max:40',
        'email'     => 'nullable|max:60|email:rfc,dns',
        ]);
        // Log::debug(print_r($membership,true));
      $check = $membership->update($data);

      return redirect()->back();
    }
}
