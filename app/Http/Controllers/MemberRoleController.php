<?php

namespace App\Http\Controllers;

use App\Models\MemberRole;
use App\Models\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Response;

class MemberRoleController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  \App\MemberRole  $memberRole
     * @return \Illuminate\Http\Response
     */
    public function show(MemberRole $memberRole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MemberRole  $memberRole
     * @return \Illuminate\Http\Response
     */
    public function destroy( MemberRole $memberrole)
    {
        // Log::debug(print_r($memberrole,true));
        $member = Member::find($memberrole->member_id);
        // Log::debug(print_r($member,true));
        // delete role
        $check = MemberRole::where('id', $memberrole->id)->delete();

        // get left roles for member
        $other_roles = $member->member_roles()->get();
        Log::debug(print_r(count($other_roles),true));
        if ( count($other_roles) == 0){
          // delete member as well
          Member::find($memberrole->member_id)->delete();
        }

        return redirect()->back();
    }
}
