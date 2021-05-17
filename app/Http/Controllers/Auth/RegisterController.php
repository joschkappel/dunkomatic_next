<?php

namespace App\Http\Controllers\Auth;

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
      $radmin = Region::find($data['region_id'])->regionadmin->first()->user()->first();

      if (isset($data['invited_by']) and (Crypt::decryptString( $data['invited_by'] ) == $data['email'])){

          $user->update(['approved_at'=>now()]);
          $member = Member::where('email1', $data['email'])->orWhere('email2',$data['email'])->first();
          $member->user()->save($user);
          $clubs = $member->clubs->unique()->pluck('id');
          foreach ($clubs as $c) {
              $member->clubs()->attach([$c], array('role_id' => Role::User));    
          }
          $leagues = $member->leagues->unique()->pluck('id');
          foreach ($leagues as $l) {
              $member->leagues()->attach([$l], array('role_id' => Role::User));    
          }

      } else {
        if ( $radmin !== null ) {
            Log::debug(print_r($radmin,true));
            $radmin->notify(new NewUser($user));
        } else {
            Log::error('is null');
        }
      }

      return $user;
    }

    /**
     * Show the application registration form for invited users.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationFormInvited($language, Member $member, User $inviting_user, $invited_by)
    {
        return view('auth.register_invited',['language'=>$language, 'member'=>$member, 'user'=>$inviting_user, 'invited_by'=>$invited_by]);
    }    
}
