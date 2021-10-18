<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;

use Bouncer;
use Silber\Bouncer\Database\Role;

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
                 if (Bouncer::can('update-users')){
                    $btn = '<button type="button" id="blockUser" name="blockUser" class="btn btn-outline-primary btn-sm" data-user-id="'.$data->id.'"
                        data-user-name="'.$data->name.'" data-toggle="modal" data-target="#modalBlockUser" '.$state.'><i class="fas fa-ban"></i></button>  ';
                 } else {
                     $btn = '';
                 }
                 if (Bouncer::can('create-users')){
                    $btn .= '<button type="button" id="deleteUser" name="deleteUser" class="btn btn-outline-danger btn-sm" data-user-id="'.$data->id.'"
                       data-user-name="'.$data->name.'" data-toggle="modal" data-target="#modalDeleteUser" ><i class="fa fa-trash"></i></button>';
                 };
                 return $btn;
          })
          ->editColumn('name', function ($userlist) use($language) {
              if ($userlist->approved_at == null) {
                return '<i class="fas fa-exclamation-triangle text-warning"></i>  '.$userlist->name;
              } else {
                  if (Bouncer::can('update-users')) {
                    return '<a href="' . route('admin.user.edit', ['language'=>$language, 'user'=>$userlist->id]) .'">'.$userlist->name.'</a>';
                  } else {
                      return $userlist->name;
                  }
              };
              })
            ->addColumn('roles', function ($u) {
                $roles = '';
                foreach ( $u->getRoles() as $ur){
                    $roles .= __('auth.user.role.'.$ur).', ';
                }
                return substr($roles,0,-2);
            })
          ->addColumn('clubs', function ($userlist) {
              $ca = $userlist->getAbilities()->where('entity_type', Club::class)->pluck('entity_id');
              return Club::whereIn('id', $ca)->pluck('shortname')->implode(', ');
            })
          ->addColumn('leagues', function ($userlist) {
                $la = $userlist->getAbilities()->where('entity_type', League::class)->pluck('entity_id');
                return League::whereIn('id', $la)->pluck('shortname')->implode(', ');
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
        $member = $user->member;
        Log::debug(print_r($member,true));

        return view('auth/user_profile', ['user'=>$user, 'member'=>$member]);
      }

  public function edit($language, User $user)
      {

          if ( $user->approved_at == null){
              $member = $user->member;

              $c = $user->getAbilities()->where('entity_type', Club::class)->pluck('entity_id');
              $abilities['clubs'] = Club::whereIn('id',$c)->get()->unique()->implode('shortname',', ');
              $l = $user->getAbilities()->where('entity_type', League::class)->pluck('entity_id');
              $abilities['leagues'] = League::whereIn('id', $l)->get()->unique()->implode('shortname',', ');
              $r = $user->getAbilities()->where('entity_type', Region::class)->pluck('entity_id');
              $abilities['regions'] = Region::whereIn('id', $r)->get()->unique()->implode('name',', ');


            return view('auth/user_approve', ['user'=>$user, 'member'=>$member, 'abilities'=>$abilities] );
          } else {
                $c = $user->getAbilities()->where('entity_type', Club::class)->pluck('entity_id');
                $user['clubs'] = Club::whereIn('id', $c)->pluck('id','shortname');
                $l = $user->getAbilities()->where('entity_type', League::class)->pluck('entity_id');
                $user['leagues'] = League::whereIn('id', $l)->pluck('leagues.id','leagues.shortname');
                $r = $user->getAbilities()->where('entity_type', Region::class)->pluck('entity_id');
                $user['regions'] = Region::whereIn('id', $r)->pluck('regions.id','regions.name');

            return view('auth/user_edit', ['user'=>$user]);
          }

      }

  public function update(Request $request, User $user)
      {

          Log::debug(print_r($request->all(),true));
          $data = $request->validate( [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'locale' => ['required', 'string', 'max:2', ]
          ]);

          $old_email = $user->email;
          if ( $data['email'] != $user->email){
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
              'region_ids' => 'sometimes|required|array',
              'region_ids.*' => 'nullable|exists:regions,id',
              'club_ids' => 'sometimes|required|array',
              'club_ids.*' => 'nullable|exists:clubs,id',
              'league_ids' => 'sometimes|required|array',
              'league_ids.*' => 'nullable|exists:leagues,id',
              'member_id' => 'sometimes|required|exists:members,id',
              'regionrole' => 'sometimes|required|exists:roles,id',
              'clubrole' => 'sometimes|required|exists:roles,id',
              'leaguerole' => 'sometimes|required|exists:roles,id',
          ]);

          if ( $request->approved == 'on'){
            $user->update(['approved_at' => now()]);

            // RBAC - enable region access
            if ( isset($data['region_ids']) ){
                foreach ($data['region_ids'] as $r) {
                    Bouncer::allow($user)->to('manage', Region::find($r));
                }
                };

            // RBAC - enable club access
            if ( isset($data['club_ids']) ){
              foreach ($data['club_ids'] as $c) {
                Bouncer::allow($user)->to('manage', Club::find($c));
              }
            };

            // RBAC - enable league access
            if ( isset($data['league_ids'] )){
              foreach ($data['league_ids'] as $l) {
                Bouncer::allow($user)->to('manage', League::find($l));
              }
            };

            // RBAC set roles

            if (isset($data['regionrole']) ) {
                Bouncer::retract('guest')->from($user);
                Bouncer::assign( Role::find($data['regionrole'])->name )->to($user);
            }
            if (isset($data['clubrole']) ) {
                Bouncer::retract('guest')->from($user);
                Bouncer::assign( Role::find($data['clubrole'])->name )->to($user);
            }
            if (isset($data['leaguerole']) ) {
                Bouncer::retract('guest')->from($user);
                Bouncer::assign( Role::find($data['leaguerole'])->name )->to($user);
            }


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
    $data = $request->validate( [
        'region_ids' => 'sometimes|required|array',
        'region_ids.*' => 'nullable|exists:regions,id',
        'club_ids' => 'sometimes|required|array',
        'club_ids.*' => 'nullable|exists:clubs,id',
        'league_ids' => 'sometimes|required|array',
        'league_ids.*' => 'nullable|exists:leagues,id',
        'regionrole' => 'sometimes|required|exists:roles,id',
        'clubrole' => 'sometimes|required|exists:roles,id',
        'leaguerole' => 'sometimes|required|exists:roles,id',
    ]);

    // RBAC need to add remove abilities
    $user->abilities()->detach();
    if ( isset($data['region_ids']) ){
        foreach ($data['region_ids'] as $r) {
          Bouncer::allow($user)->to('manage', Region::find($r));
        }
      };
    if ( isset($data['club_ids']) ){
        foreach ($data['club_ids'] as $c) {
          Bouncer::allow($user)->to('manage', Club::find($c));
        }
      };
    if ( isset($data['league_ids'] )){
        foreach ($data['league_ids'] as $l) {
            Bouncer::allow($user)->to('manage', League::find($l));
        }
    };
    // RBAC set roles
    Bouncer::retract( $user->getRoles()  )->from($user);
    if (isset($data['regionrole']) ) {
        Bouncer::assign( Role::find($data['regionrole'])->name )->to($user);
    }
    if (isset($data['clubrole']) ) {
        Bouncer::assign( Role::find($data['clubrole'])->name )->to($user);
    }
    if (isset($data['leaguerole']) ) {
        Bouncer::assign( Role::find($data['leaguerole'])->name )->to($user);
    }
    if ( (! isset($data['regionrole'])) and (! isset($data['clubrole'])) and (! isset($data['leaguerole'])) ) {
        Bouncer::assign( 'guest' )->to($user);
    }

    return redirect()->route('admin.user.index', app()->getLocale());
  }

  public function destroy(Request $request, User $user)
  {

    $user->delete();
    return redirect()->route('admin.user.index', app()->getLocale());
  }

  public function block(Request $request, User $user)
  {
    $user->update(['approved_at'=> null]);
    // RBAC remove old roles and set guest
    Bouncer::retract( Role::all() )->from($user);
    Bouncer::assign('candidate')->to($user);

    return redirect()->route('admin.user.index', app()->getLocale());
  }

}
