<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Region;
use App\Models\User;
use App\Notifications\ApproveUser;
use App\Notifications\NewUser;
use App\Providers\RouteServiceProvider;
use App\Traits\UserAccessManager;
use Captcha;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

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
        if ($data['captcha'] == 'mockcaptcha=12345') {
            // dirtzy hack to enable dusk testing
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'region_id' => ['required', 'exists:regions,id'],
                'reason_join' => ['required', 'string'],
            ]);
        } else {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'region_id' => ['required', 'exists:regions,id'],
                'reason_join' => ['required', 'string'],
                'captcha' => ['required', 'captcha'],
            ]);
        }
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
     * @param  Request  $request
     * @return
     */
    protected function register_invitee(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string',
            'reason_join' => 'required|string',
        ]);

        if ($request->cookie('_i') == null) {
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
            Cookie::queue(Cookie::forget('_i'));

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
     * @param  string  $language
     * @param  \App\Models\Invitation  $invitation
     * @return \Illuminate\View\View
     */
    public function showRegistrationFormInvited(string $language, Invitation $invitation): View
    {
        Cookie::queue('_i', $invitation->id ?? null, 60);

        return view('auth.register_invited', [
            'language' => $language,
            'invitation' => $invitation,
        ]);
    }

    /**
     * Show the application registration form for invited users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reloadCaptcha(): JsonResponse
    {
        return response()->json(['captcha' => Captcha::img('math')]);
    }
}
