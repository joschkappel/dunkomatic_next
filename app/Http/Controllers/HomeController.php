<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use Carbon\CarbonImmutable;

class HomeController extends Controller
{
    public function approval()
    {
        return view('auth/approval');
    }

    public function home()
    {
        $today = Carbon::today()->toDateString();
        $user = Auth::user();

        $msglist = array();
        $vf = null;
        $mi = array();

        foreach ($user->unreadNotifications as $m) {
            $valid_from = CarbonImmutable::parse($m->created_at)->locale(app()->getLocale())->isoFormat('L');
            if ($vf != $valid_from) {
                $msglist[] = $mi;
                $mi = array();
                $mi['valid_from'] = $m->created_at;
                $vf = $valid_from;
            }

            $mi['items'][] = $m;

            if ($m->created_at->diffInDays() > 8) {
                $m->markAsRead();
            }
        }

        $msglist[] = $mi;
        array_shift($msglist);
        Log::info('preparing unread message list.', ['count' => count($msglist)]);

        return view('home', ['msglist' => $msglist]);
    }
}
