<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Member;
use App\Models\MemberRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\Role;

class ClubMemberRoleController extends Controller
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
                "text"=>$member->firstname.' '.$member->lastname
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
      return view('member/memberrole_club_new', ['club' => $club]);
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
        $new_mrole = MemberRole::create(['role_id' => $role, 'member_id' => $member->id ] );
        //Log::debug(print_r($new_mrole,true));
        $club->member_roles()->save($new_mrole);
      }

      return redirect()->action(
            'ClubController@dashboard', ['language' => app()->getLocale(), 'id' => $club->id]
      );
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Club  $club
     * @param  \App\MemberRole  $memberrole
     * @return \Illuminate\Http\Response
     */
    public function edit($language, Club $club, MemberRole $memberrole)
    {
      $data = MemberRole::with('member')->find($memberrole->id);
      $member = $data['member'];
      $member_roles = $data;
      Log::debug(print_r($member_roles,true));
      //Log::debug(print_r($member,true));
      return view('member/memberrole_club_edit', ['member' => $member, 'member_roles' => $member_roles, 'club' => $club]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Club  $club
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Club $club, Member $memberrole)
    {
              $data = $request->validate( [
                  'selRole'   => ['required', new EnumValue(Role::class, false)],
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
              Log::debug(print_r($request->all(),true));


              if ( $data['selRole'] != $request['old_role_id'] ){
                // first delete all roles that are not in scope anymore
                $check = MemberRole::where('member_id', $member->id)->whereAnd('role_id', $request['old_role_id'])->delete();
                //Log::debug($role);
                $new_mrole = MemberRole::updateOrCreate(['member_id' => $member->id, 'role_id' => $data['selRole'] ]);
                //Log::debug(print_r($new_mrole,true));

                //Log::debug(print_r($club,true));
                $club->member_roles()->save($new_mrole);

              }

              // eliminate non model props
              unset($data['selRole']);

              $check = Member::where('id', $member->id)->update($data);
              return redirect()->action(
                    'ClubController@dashboard', ['language'=>app()->getLocale(),'id' => $club->id]
              );
    }

}
