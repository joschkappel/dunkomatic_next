<?php

namespace App\Http\Controllers\Auth;

use Bouncer;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use App\Models\User;
use App\Models\Region;
use App\Models\Member;
use App\Enums\Role;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewUser;
use Illuminate\Support\Facades\Crypt;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'region_id' => ['required', 'exists:regions,id'],
            'reason_join' => ['required', 'string'],
            'locale' => ['required', 'string', 'max:2'],
            'invited_by' => ['sometimes']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'region_id' => $data['region_id'],
                'reason_join' => $data['reason_join'],
                'locale' => $data['locale']
        ]);

        $member = Member::where('email1', $user->email)->orWhere('email2', $user->email)->first();
        if (isset($member)){
            // link user and member
            $member->user()->save($user);
            // RBAC - set access to clubs
            $clubs = $member->clubs->unique();
            foreach ($clubs as $c) {
                Bouncer::allow($user)->to('manage', $c);
            }
            // RBAC - set access to league
            $leagues = $member->leagues->unique();
            foreach ($leagues as $l) {
                Bouncer::allow($user)->to('manage', $l);
            }
            // RBAC - set access to region
            $regions = $member->region;
            foreach ($regions as $r) {
                Bouncer::allow($user)->to('manage', $r);
            }

            //RBAC - set user role
            if ($user->isregionadmin){
                Bouncer::assign('regionadmin')->to($user);
            } elseif ($user->isrole(Role::ClubLead())){
                Bouncer::assign('clubadmin')->to($user);
            } elseif ($user->isrole(Role::LeagueLead())){
                Bouncer::assign('leagueadmin')->to($user);
            } else {
                Bouncer::assign('user')->to($user);
            }

        } else {
            // RBAC set guest role
            Bouncer::assign('guest')->to($user);
        }

      if (isset($data['invited_by']) and (Crypt::decryptString( $data['invited_by'] ) == $data['email'])){
          // invited users are auto-approved
          $user->update(['approved_at'=>now()]);
      } else {

        if ( Region::find($data['region_id'])->regionadmin->first()->user->exists() ) {
            $radmin = Region::find($data['region_id'])->regionadmin->first()->user()->first();
            $radmin->notify(new NewUser($user));
        } else {
            Log::error('regionadmin is null');
        }
      }

      return $user;
    }

    /**
     * Show the application registration form for invited users.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationFormInvited($language, Member $member, Region $region, User $inviting_user, $invited_by)
    {
        return view('auth.register_invited',['language'=>$language, 'member'=>$member, 'user'=>$inviting_user, 'invited_by'=>$invited_by, 'region'=>$region]);
    }
}
