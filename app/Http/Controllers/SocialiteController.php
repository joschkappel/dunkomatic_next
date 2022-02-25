<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{

        /**
     * redirect oauth request to the provider
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function redirect_to_oauth(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * callback from the oauth provider
     *
     * @param string $provider
     * @return void
     */
    public function callback_from_oauth(string $provider)
    {
        $user = Socialite::driver($provider)->user();
        Log::info('user authenticagted by oauth provider',['provider'=>$provider]);
        Log::debug('user data received', ['oauth-user'=>$user]);
        return true;
        // $user->token
    }

}
