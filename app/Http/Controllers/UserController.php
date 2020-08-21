<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
  public function index($language)
      {
          $users = User::whereNull('approved_at')->get();

          return view('auth/users', ['users' => compact($users)]);
      }

  public function approve($language, User $user)
      {
          Log::info('approve request for user '.$user->email);
          //$user = User::findOrFail($user_id);
          $user->update(['approved_at' => now()]);

          return redirect()->route('admin.users.index', app()->getLocale())->withMessage('User approved successfully');
      }
}
