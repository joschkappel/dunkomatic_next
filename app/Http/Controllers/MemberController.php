<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use App\Models\Region;
use App\Models\Club;
use App\Models\User;
use App\Enums\Role;

use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;

use App\Notifications\ApproveUser;

class MemberController extends Controller
{

      public function index()
      {
        return view('member/member_list');
      }

      public function datatable(Region $region )
      {
        $members = $region->clubs()->with('members')->get()->pluck('members.*.id')->flatten()->concat(
                      $region->leagues()->with('members')->get()->pluck('members.*.id')->flatten()
                    )->concat(
                      $region->members->pluck('id')->flatten()
                    )->unique();
        $mlist = datatables()::of(Member::whereIn('id', $members)->get());
        return $mlist
              ->addColumn('name', function ($data) {
                      return $data->lastname.', '.$data->firstname;
                  })
              ->addColumn('clubs', function ($data) {
                      return $data->memberofclubs;
                  })
              ->addColumn('leagues', function ($data) {
                      return $data->memberofleagues;
                  })
              ->addColumn('regions', function ($data) {
                      return $data->memberofregions;
                  })
              ->addColumn('user_account', function ($data) {
                      return $data->isuser;
                  })
              ->make(True);
      }

      /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function sb_region( Region $region)
      {
        Log::info('members for region '.$region->name);
         $members = Membership::whereIn('membership_id', $region->clubs()->pluck('id'))
                              ->where('membership_type',Club::class)
                              ->with('member')
                              ->get()
                              ->sortBy('member.lastname')
                              ->pluck('member.name','member.id');
        //Log::debug('got members '.count($members));

        $response = array();

        foreach($members as $k => $v){
            $response[] = array(
                  "id"=>$k,
                  "text"=>$v
                );
        }

        return Response::json($response);
      }

    /**
     * Display the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show($language, Member $member)
    {
      //Log::debug(print_r($member,true));
      return Response::json($member);
    }

    public function store(Request $request)
    {
        Log::info(print_r($request->all(),true));
        $data = Validator::make($request->all(), Member::$createRules)
                          ->validateWithBag('err_member');

        $member = Member::create($data);

        // check user account creation
        if ($request->input('user_account') == 'on')
        {
          Log::info('user account required');
          // create user account (status approved, email needs to be verified)
          $user = User::create([
                'name' => $member->name,
                'email' => $member->email1,
                'password' => Hash::make('password'),
                'region_id' => session('cur_region')->id,
                'reason_join' => 'Created as member by '.Auth::user()->name,
          ]);

          $member->user()->save($user);
          if ( isset( $request['club_id']) ){
            $member->clubs()->attach([$request->input('club_id')], array('role_id' => Role::User));
          } elseif ( isset( $request['league_id']) ) {
            $member->leagues()->attach([$request->input('league_id')], array('role_id' => Role::User));
          }
          $user->notify(new ApproveUser(Auth::user(), $user));

        }
        return redirect()->back()->with('member', $member);

      }

    public function update(Request $request, Member $member)
    {
        Log::debug(print_r($request->all(),true));
        $data = Validator::make($request->all(), Member::$createRules)
                           ->validateWithBag('err_member');

        $member->update($data);

        // check user account creation
        if ($request['user_account'] == 'on')
        {
          if (!$member->user()->exists() ) {
            Log::info('user account required');
            // create user account (status approved, email needs to be verified)
            $user = User::create([
                  'name' => $member->name,
                  'email' => $member->email1,
                  'password' => Hash::make('password'),
                  'region_id' => session('cur_region')->id,
                  'reason_join' => 'Created as member by '.Auth::user()->name,
            ]);

            $member->user()->save($user);
            if ( isset( $request['club_id']) ){
              $member->clubs()->attach([$request->input('club_id')], array('role_id' => Role::User));
            } elseif ( isset( $request['league_id']) ) {
              $member->leagues()->attach([$request->input('league_id')], array('role_id' => Role::User));
            }
            $user->notify(new ApproveUser(Auth::user(), $user));

          }
        } else {
          if ($member->user()->exists() ) {
            Log::debug('remove user account');
            $member->user()->delete();
          }
        }

        return redirect()->back()->with('member_mod', $member);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
     public function destroy(Member $member)
     {
         $check = $member->delete();

         return Response::json($check);
     }
}
