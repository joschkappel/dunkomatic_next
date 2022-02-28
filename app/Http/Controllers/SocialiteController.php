<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Region;
use App\Models\Member;
use App\Notifications\NewUser;
use Illuminate\Auth\Events\Registered;

use App\Traits\UserAccessManager;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class SocialiteController extends Controller
{
    use UserAccessManager;

    /**
     * redirect oauth request to the provider
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function redirectToOauth(string $provider)
    {
        Log::info('redirecting oauth request to provider', ['provider' => $provider]);
        return Socialite::driver($provider)->redirect();
    }

    /**
     * callback from the oauth provider
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerFromOauth(string $provider)
    {
        $oauth_user = Socialite::driver($provider)->user();
        Log::info('user authenticagted by oauth provider', ['provider' => $provider]);
        Log::debug('user data received', ['oauth-user' => $oauth_user]);

        /**
         * GOOGLE response contains
         *
         * token, refreshToken, expiresIn
         * approvedScopes
         * name, email, id, nickname, locale, avatar
         */
        $user = User::where(['email' => $oauth_user->getEmail()])->first();

        if ($user) {
            // user is already registered, this is a login
            Auth::login($user);
            return redirect()->route('home', app()->getLocale());
        } else {
            // this is a registration of a new acccount
            $user = User::create([
                'name'      => $oauth_user->getName(),
                'email'     => $oauth_user->getEmail(),
                'avatar'    => $oauth_user->getAvatar(),
                'provider'  => $provider,
                'provider_id' => $oauth_user->getId(),
                'locale'    => $oauth_user->user->locale ?? 'de',
            ]);
            $this->setInitialAccessRights($user);
            Log::notice('user created', ['provider' => $provider, 'user' => $user->id]);
            Auth::login($user);
            return redirect()->route('show.apply', ['language' => $user->locale ?? 'de', 'user' => $user]);
        }
    }

    /**
     * apply for access approval
     *
     * @param string $language
     * @param \App\Models\User $user
     * @return \Illuminate\View\View
     */
    public function showApply(string $language, User $user)
    {
        Log::info('showing application form for user',['user'=>$user, 'abilities'=>$user->getAbilities()]);
        return view('auth.apply', ['user' => $user]);
    }

    /**
     * apply for access approval
     *
     * @param Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apply(Request $request, User $user)
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

        return redirect()->route('home', $user->locale ?? 'de');
    }
}
