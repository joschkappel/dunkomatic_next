<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Region;
use App\Models\Member;
use App\Notifications\NewUser;
use Illuminate\Auth\Events\Registered;
use App\Models\Invitation;
use App\Notifications\ApproveUser;

use App\Traits\UserAccessManager;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Cookie;
class SocialAuthController extends Controller
{
    use UserAccessManager, RedirectsUsers;

        /**
     * Where to redirect users after login.
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
     * redirect oauth request to the provider
     *
     * @param string $provider
     * @param \App\Models\Invitation|null $invitation
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function redirectToOauth(string $provider, Invitation $invitation)
    {
        $invitation->update(['provider'=>$provider]);
        Log::info('redirecting oauth request to provider', ['provider' => $provider]);
        Cookie::queue('oauth_register', 'jochen', 60);
        return Socialite::driver($provider)->redirect();
    }

    /**
     * callback from the oauth provider
     *
     * @param Request $request
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerFromOauth(Request $request, string $provider)
    {
        $oauth_user = Socialite::driver($provider)->user();
        Log::info('user authenticagted by oauth provider', ['provider' => $provider]);
        Log::debug('user data received', ['oauth-user' => $oauth_user]);
        Log::debug('cookie set?', ['cookie-value'=> $request->cookie('oauth_register')]);

        /**
         * GOOGLE response contains
         *
         * token, refreshToken, expiresIn
         * approvedScopes
         * name, email, id, nickname, locale, avatar, email_Verified
         */

        $user = User::where(['email' => $oauth_user->getEmail()])->first();

        if ($user) {
            // user is already registered, this is a login
            App::setLocale($user->locale);
            Auth::login($user);

            return redirect()->intended($this->redirectPath());

        } else {
            // this is a registration of a new acccount
            $user = User::create([
                'name'      => $oauth_user->getName(),
                'email'     => $oauth_user->getEmail(),
                'avatar'    => $oauth_user->getAvatar(),
                'provider'  => $provider,
                'provider_id' => $oauth_user->getId(),
                'locale'    => $oauth_user->user['locale'] ?? 'de',
                'email_verified_at' => $oauth_user->user['email_verified'] ? now() : null,
            ]);

            if (Invitation::where('email_invitee',$user->email)->where('provider',$provider)->exists() ){
                // this user must have been invited
                $invitation = Invitation::where('email_invitee',$user->email)->first();
                $user->update(['approved_at' => now()]);
                $member = $invitation->member;
                $member->user()->save($user);

                // so auto-approve
                $this->approveUser($user);
                $this->cloneMemberAccessRights($user);
                Log::notice('user approved.', ['user-id' => $user->id]);
                $user->notify(new ApproveUser($invitation->region));

                $invitation->delete();
                Auth::login($user);
                return redirect()->intended($this->redirectPath());
            } else {
                $this->setInitialAccessRights($user);
                Log::notice('user created', ['provider' => $provider, 'user' => $user->id]);
                return redirect()->route('show.apply', ['language' => $user->locale ?? 'de', 'user' => $user]);
            }
        }
    }

    /**
     * apply for access approval
     *
     * @param string $language
     * @param \App\Models\User $user
     * @return \Illuminate\View\View
     */
    public function showApplyForm(string $language, User $user)
    {
        Log::info('showing application form for user',['user'=>$user, 'abilities'=>$user->getAbilities()]);
        return view('auth.apply', ['user' => $user]);
    }

    /**
     * apply for access approval
     *
     * @param Request $request
     * @param string $language
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apply(Request $request, $language, User $user)
    {
        $data = $request->validate([
            'region_id' => ['required', 'exists:regions,id'],
            'reason_join' => ['required', 'string'],
        ]);
        Log::info('user application form data validated OK.');

        $user->update(['reason_join' => $data['reason_join']]);
        $region = Region::find($data['region_id']);
        $this->setInitialAccessRights($user, $region);

        $member = Member::with('memberships')->where('email1', $user->email)->orWhere('email2', $user->email)->first();
        if (isset($member)) {
            // link user and member
            $member->user()->save($user);
        }

        // self-registration notify region admin for approval
        $radmins = User::whereIs('regionadmin')->get();
        foreach ($radmins as $radmin) {
            if ($radmin->can('access', $region)) {
                $radmin->notify(new NewUser($user));
            }
        }
        event(new Registered($user));
        Auth::login($user);

        return redirect($this->redirectPath());
    }

        /**
     * Get the post login redirect path.
     *
     * @return string
     */
    protected function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        if (Auth::check()){
            $returnPath = '/'.Auth::user()->locale.'/'.$this->redirectTo;
        } else {
            $returnPath = '/'.app()->getLocale().'/'.$this->redirectTo;
        }
        return $returnPath;
    }
}
