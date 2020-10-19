<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Response;

class MembershipController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  \App\Membership  $memberRole
     * @return \Illuminate\Http\Response
     */
    public function show(Membership $memberRole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Membership  $memberRole
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

        return redirect()->back();
    }
}
