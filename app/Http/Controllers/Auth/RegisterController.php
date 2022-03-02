<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use App\Models\User;
use App\Models\Region;
use App\Models\Member;
use App\Models\Invitation;
use App\Notifications\ApproveUser;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewUser;
use App\Traits\UserAccessManager;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;


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

    use RegistersUsers, UserAccessManager;

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
            'reason_join' => $data['reason_join'],
            'locale' => 'de',
        ]);

        $region = Region::find($data['region_id']);

        $this->setInitialAccessRights($user, $region);

        $member = Member::with('memberships')->where('email1', $user->email)->orWhere('email2', $user->email)->first();
        if (isset($member)) {
            // link user and member
            $member->user()->save($user);
        }
        // self-registrâtion ntoify region admin for approval
        $radmins = User::whereIs('regionadmin')->get();
        foreach ($radmins as $radmin) {
            if ($radmin->can('access', $region)) {
                $radmin->notify(new NewUser($user));
            }
        }

        return $user;
    }

    /**
     * Create a new user instance after a valid registration based on an invitation.
     *
     * @param  Request $request
     * @return
     */
    protected function register_invitee(Request $request)
    {
        $data = $request->validate([

        ]);

        if ( $request->cookie('_i') == null){
            abort(404);
        } else {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'reason_join' => $data['reason_join'],
                'locale' => 'de',
            ]);

            $invitation = Invitation::find($request->cookie('_i'));

            $this->setInitialAccessRights($user, $invitation->region);

            // invited users are auto-approved
            $user->update(['approved_at' => now()]);
            $member = $invitation->member;
            $member->user()->save($user);
            $invitation->delete();
            Cookie::queue( Cookie::forget('_i'));

            $this->approveUser($user);
            $this->cloneMemberAccessRights($user);

            Log::notice('user approved.', ['user-id' => $user->id]);
            $user->notify(new ApproveUser($invitation->region));
        }

        return redirect($this->redirectPath());
    }


    /**
     * Show the application registration form for invited users.
     *
     * @param string $language
     * @param \App\Models\Invitation $invitation
     * @return \Illuminate\View\View
     */
    public function showRegistrationFormInvited(string $language, Invitation $invitation): View
    {
        Cookie::queue('_i', $invitation->id ?? null, 60);
        return view('auth.register_invited', [
            'language' => $language,
            'invitation' => $invitation
        ]);
    }
}
