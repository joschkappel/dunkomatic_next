<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;

use App\Enums\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

use App\Notifications\RejectUser;
use App\Notifications\ApproveUser;
use Carbon\Carbon;


class UserController extends Controller
{
  public function index($language)
      {
          return view('auth/user_list');
      }

  public function datatable($language)
      {
        $users = session('cur_region')->users()->get();
        //Log::debug(print_r($users,true));
        $userlist = datatables()::of($users);

        return $userlist
          ->addIndexColumn()
          ->rawColumns(['name','action'])
          ->addColumn('action', function($data){
                 $state = ($data->approved_at == null) ? 'disabled' : '';
                 $btn = '<button type="button" id="blockUser" name="blockUser" class="btn btn-outline-primary btn-sm" data-user-id="'.$data->id.'"
                    data-user-name="'.$data->name.'" data-toggle="modal" data-target="#modalBlockUser" '.$state.'><i class="fas fa-ban"></i></button>  ';
                  $btn .= '<button type="button" id="deleteUser" name="deleteUser" class="btn btn-outline-danger btn-sm" data-user-id="'.$data->id.'"
                       data-user-name="'.$data->name.'" data-toggle="modal" data-target="#modalDeleteUser" ><i class="fa fa-trash"></i></button>';
                  return $btn;
          })
          ->editColumn('name', function ($userlist) {
              if ($userlist->approved_at == null) {
                return '<i class="fas fa-exclamation-triangle text-warning"></i>  '.$userlist->name;
              } else {
                return '<a href="' . route('admin.user.edit', ['language'=>app()->getLocale(),'user'=>$userlist->id]) .'">'.$userlist->name.'</a>';
              };
              })
          ->addColumn('clubs', function ($userlist) {
              if ( $userlist->member != null ){
                  return $userlist->member()->first()->clubs()->pluck('shortname')->implode(', ');
              } else {
                  return '';
              }
              })
          ->addColumn('leagues', function ($userlist) {
              if ( $userlist->member != null ){
                  return $userlist->member()->first()->leagues()->pluck('shortname')->implode(', ');
                } else {
                  return '';
                }
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

          $users = session('cur_region')->users()
                       ->whereNull('approved_at')
                       ->whereNull('rejected_at')
                       ->get();
        //  dd(DB::getQueryLog());
          return view('auth/users', compact('users'));
      }

  public function show($language, User $user)
      {
        $member = $user->member()->first();
        Log::debug(print_r($member,true));

        return view('auth/user_profile', ['user'=>$user, 'member'=>$member]);
      }

  public function edit($language, User $user)
      {
          //$user = User::findOrFail($user_id);

          if ( $user->approved_at == null){
            // check if user is linked to a member
            $member = Member::where('email1', $user->email)->orWhere('email2', $user->email)->first();
            if ( isset($member)){
                $member['clubs'] = $member->clubs()->get()->implode('shortname',', ');
                $member['leagues'] = $member->leagues()->get()->implode('shortname',', ');
            }

            return view('auth/user_approve', ['user'=>$user, 'member'=>$member] );
          } else {
            $user['clubs'] = $user->member->clubs()->pluck('clubs.id','clubs.shortname');
            $user['leagues'] = $user->member->leagues()->pluck('leagues.id','leagues.shortname');
            return view('auth/user_edit', ['user'=>$user]);
          }

      }

  public function update(Request $request, User $user)
      {

          Log::debug(print_r($request->all(),true));
          //$user = User::findOrFail($user_id);
          $old_email = $user->email;

          $data = $request->validate( [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'locale' => ['required', 'string', 'max:2', ]
          ]);

          if ( $data['email'] != $old_email){
            $data['email_verified_at']=null;
          }

          Log::debug(print_r($data,true));
          $user->update($data);
          \App::setLocale($data['locale']);

          if ( $data['email'] != $old_email){
            $user->sendEmailVerificationNotification();
            //Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login', app()->getLocale())->with(Auth::logout());

          }

          return redirect()->route('home', app()->getLocale());
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
              'member_id' => 'sometimes|required|exists:members,id'
          ]);

          if ( $request->approved == 'on'){
            $user->update(['approved_at' => now()]);

            if ( isset($data['member_id']) ){
                $member = Member::find($data['member_id']);
            } else {
                // else create the member witha  role = user
                $member = new Member(['lastname'=> $user->name, 'email1'=>$user->email]);
                $member->save();
            }
            $user->member()->associate($member);
            $user->save();

            if ( isset($data['club_ids']) ){
              $member->clubs()->attach($data['club_ids'], array('role_id' => Role::User));
            };
            if ( isset($data['league_ids'] )){
              $member->leagues()->attach($data['league_ids'], array('role_id' => Role::User));
            };

            $user->notify(new ApproveUser(Auth::user(), $user));
          } else {
            $user->update(['rejected_at' => now(), 'approved_at' => null, 'reason_reject' => $data['reason_reject']]);
            $user->notify(new RejectUser(Auth::user(), $user));
          }

          return redirect()->route('admin.user.index.new', app()->getLocale())->withMessage('User approved successfully');
      }

  public function allowance(Request $request, User $user)
  {
    Log::debug(print_r($request->all(),true));
    //$u = User::find($user);

    $member = Member::find($user->member->id);
    $member->memberships()->delete();
    $member->clubs()->attach($request['club_ids'], array('role_id' => Role::User));
    $member->leagues()->attach($request['league_ids'], array('role_id' => Role::User));

    return redirect()->route('admin.user.index', app()->getLocale());
  }

  public function destroy(Request $request, User $user)
  {

    if ( User::find($user->id)->member->memberships()->isNotRole(Role::User)->count() == 0 ) {
      // delete user, member and memberships - he is "Only" a user
      $member = Member::find($user->member->id);
      $member->memberships()->delete();
      $member->delete();
    } else {
      // delete only the user and detach from member
      $member = Member::find($user->member->id);
      $member->wherePivot('role_id', Role::User)->delete();
      $member->detach($user);
    }
    $user->delete();
    return redirect()->route('admin.user.index', app()->getLocale());
  }

  public function block(Request $request, User $user)
  {
    $user->update(['approved_at'=> null]);
    return redirect()->route('admin.user.index', app()->getLocale());
  }

}
