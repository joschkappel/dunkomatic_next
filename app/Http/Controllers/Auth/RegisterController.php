<?php

namespace App\Http\Controllers\Auth;

use Silber\Bouncer\BouncerFacade as Bouncer;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use App\Models\User;
use App\Models\Region;
use App\Models\Member;
use App\Notifications\ApproveUser;

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
        if (isset($member)) {
            // link user and member
            $member->user()->save($user);
            // RBAC - set access to clubs
            $clubs = $member->clubs->unique();
            foreach ($clubs as $c) {
                Bouncer::allow($user)->to('access', $c);
            }
            // RBAC - set access to league
            $leagues = $member->leagues->unique();
            foreach ($leagues as $l) {
                Bouncer::allow($user)->to('access', $l);
            }
        }

        //RBAC - set user role and region
        Bouncer::assign('candidate')->to($user);
        Bouncer::allow($user)->to('access', Region::find($data['region_id']));

        if (isset($data['invited_by']) and (Crypt::decryptString($data['invited_by']) == $data['email'])) {
            // invited users are auto-approved
            $user->update(['approved_at' => now()]);
            Log::notice('user approved.', ['user-id' => $user->id]);
            $user->retract('candidate');
            $user->assign('guest');
            $user->notify(new ApproveUser(Region::find($data['region_id'])->regionadmin->first()->user, Region::find($data['region_id'])));
        } else {

            if (Region::find($data['region_id'])->regionadmin->first()->user->exists()) {
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
        return view('auth.register_invited', ['language' => $language, 'member' => $member, 'user' => $inviting_user, 'invited_by' => $invited_by, 'region' => $region]);
    }
}
