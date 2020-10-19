<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Club;
use App\Models\League;
use App\Models\Member;
use App\Models\MemberRole;

use App\Enums\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

use App\Notifications\RejectUser;
use App\Notifications\ApproveUser;
use Datatables;
use Carbon\Carbon;


class UserController extends Controller
{
  public function index($language)
      {
          return view('auth/user_list');
      }

  public function datatable($language)
      {
        $users = User::region(Auth::user()->region)->get();
        //Log::debug(print_r($users,true));
        $userlist = datatables::of($users);

        return $userlist
          ->addIndexColumn()
          ->addColumn('clubs', function ($userlist) {
                  return $userlist->member()->first()->clubs()->pluck('shortname')->implode(', ');;
              })
          ->addColumn('leagues', function ($userlist) {
                  return $userlist->member()->first()->leagues()->pluck('shortname')->implode(', ');;
              })
          ->editColumn('created_at', function ($userlist) use ($language) {
                  if ($userlist->created_at){
                    return array('display' => Carbon::parse($userlist->created_at)->locale( $language )->isoFormat('LLL'),
                                 'ts'=>Carbon::parse($userlist->created_at)->timestamp,
                                 'filter' => Carbon::parse($userlist->created_at)->locale( $language )->isoFormat('LLL'));
                  } else {
                    return array('display' => null,
                                 'ts'=>0,
                                 'filter' => null);
                  }
              })
          ->editColumn('email_verified_at', function ($userlist) use ($language) {
                  if ($userlist->email_verified_at){
                    return array('display' => Carbon::parse($userlist->email_verified_at)->locale( $language )->isoFormat('LLL'),
                                 'ts'=>Carbon::parse($userlist->email_verified_at)->timestamp,
                                 'filter' => Carbon::parse($userlist->email_verified_at)->locale( $language )->isoFormat('LLL'));
                   } else {
                     return array('display' => null,
                                  'ts'=> 0,
                                  'filter' => null);
                   }
              })
          ->editColumn('approved_at', function ($userlist) use ($language) {
                  if ($userlist->approved_at){
                    return array('display' => Carbon::parse($userlist->approved_at)->locale( $language )->isoFormat('LLL'),
                                 'ts'=>Carbon::parse($userlist->approved_at)->timestamp,
                                 'filter' => Carbon::parse($userlist->approved_at)->locale( $language )->isoFormat('LLL'));
                   } else {
                     return array('display' => null,
                                  'ts'=>0,
                                  'filter' => null);
                   }
              })
          ->editColumn('rejected_at', function ($userlist) use ($language) {
                  if ($userlist->rejected_at){
                    return array('display' => Carbon::parse($userlist->rejected_at)->locale( $language )->isoFormat('LLL'),
                                 'ts'=>Carbon::parse($userlist->rejected_at)->timestamp,
                                 'filter' => Carbon::parse($userlist->rejected_at)->locale( $language )->isoFormat('LLL'));
                   } else {
                     return array('display' => null,
                                  'ts'=>0,
                                  'filter' => null);
                   }
              })

          ->make(true);
      }

  public function index_new($language)
      {
        //  DB::enableQueryLog(); // Enable query log

          $users = User::where('region', Auth::user()->region)->whereNull('approved_at')->get();
        //  dd(DB::getQueryLog());
          return view('auth/users', compact('users'));
      }

  public function show($language, User $user)
      {
        $member = $user->member()->first();

        return view('auth/user_edit', ['user'=>$user, 'member'=>$member]);
      }

  public function edit($language, User $user)
      {
          //$user = User::findOrFail($user_id);

          return view('auth/user_approve', compact('user'));
      }

  public function update(Request $request, $user_id)
      {

          Log::debug(print_r($request->all(),true));
          $user = User::findOrFail($user_id);
          $data = $request->validate( [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user_id)]
          ]);

          $data['email_verified_at']=null;
          Log::debug(print_r($data,true));
          $check = $user->update($data);
          if ( $data['email']){
            $user->sendEmailVerificationNotification();
            //Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login', app()->getLocale())->with(Auth::logout());

          }

          return redirect()->back();
      }

  public function approve(Request $request)
      {

          Log::debug(print_r($request->all(),true));
          $user = User::findOrFail($request->user_id);
          Log::info('approve request for user '.$user->email);

          $data = $request->validate( [
              'reason_reject' =>  'exclude_if:approved,"on"|required|string',
              'club_ids' => Rule::requiredIf(function () use ($request) {
                              return (($request->approved == 'on') and (!isset($request->league_ids)));
                              }),
              'league_ids' => Rule::requiredIf(function () use ($request) {
                              return (($request->approved == 'on') and (!isset($request->club_ids)));
                              }),
          ]);

          if ( $request->approved == 'on'){
            $user->update(['approved_at' => now()]);
            // create the member witha  role = user
            $member = new Member(['lastname'=> $user->name, 'email1'=>$user->email, 'user_id'=>$user->id]);
            $member->save();

            foreach ($data['club_ids'] as $c_id){
                $club = Club::find($c_id);
                $new_mship = MemberRole::create(['role_id' => Role::User, 'member_id' => $member->id ] );
                $club->memberships()->save($new_mship);
            }
            foreach ($data['league_ids'] as $l_id){
                $league = League::find($l_id);
                $new_mship = MemberRole::create(['role_id' => Role::User, 'member_id' => $member->id ] );
                $league->memberships()->save($new_mship);
            }

            $user->notify(new ApproveUser(Auth::user(), $user));




          } else {
            $user->update(['rejected_at' => now(), 'approved_at' => null, 'reason_reject' => $data['reason_reject']]);
            $user->notify(new RejectUser(Auth::user(), $user));
          }

          return redirect()->route('admin.user.index.new', app()->getLocale())->withMessage('User approved successfully');
      }

}
