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

        $reminders  = [];
        $infos  = [];

        if (! $user->member->first()->is_complete ) {
            $msg = [];
            $msg['msg'] =  __('auth.complete.profile');
            $msg['action'] = route(@config('dunkomatic.profile_url'), ['language'=>app()->getLocale(),'user'=>$user]);
            $msg['actiontext'] = __('auth.action.complete.profile');
            $reminders[] = $msg;
        }


        if ($user->isA('regionadmin','superadmin')){
            // check new users waiting for approval
            $users_to_approve = $user->region->users->whereNull('approved_at')->count();
            if ($users_to_approve > 0){
                $msg = [];
                $msg['msg'] =  trans_choice('message.reminder.approvals', $users_to_approve, ['approvals'=>$users_to_approve]);
                $msg['action'] = route('admin.user.index.new', ['language'=>app()->getLocale(),'region'=>$user->region]);
                $msg['actiontext'] = __('auth.title.approve');
                $reminders[] = $msg;
            }

            // check close assignment deadline
            if ( ( $user->region->close_assignment_at != null ) and ( $user->region->close_assignment_at > now() ) ) {
                $msg = [];
                $msg['action'] = '';

                if ($user->region->close_assignment_at <= now()->addWeeks(1) ){
                    $msg['msg'] =  __('message.reminder.deadline.assignment', ['deadline'=> $user->region->close_assignment_at->diffForHumans(['parts'=>3,'join'=>true])   ]);
                    $msg['action'] = route('league.index_mgmt', ['language'=>app()->getLocale(),'region'=>$user->region]);
                    $msg['actiontext'] = trans_choice('league.league',2).' '.__('league.menu.manage');
                    $reminders[] = $msg;
                } else {
                    $msg['msg'] =  __('message.reminder.deadline.assignment', ['deadline'=> $user->region->close_assignment_at->diffForHumans(['parts'=>1])   ]);
                    $infos[] = $msg;
                }
            }
            // check close referees deadline
            if ( ( $user->region->close_referees_at != null ) and  ( $user->region->close_referees_at > now() ) ) {
                $msg = [];
                $msg['action'] = '';

                if ($user->region->close_referees_at <= now()->addWeeks(1) ){
                    $msg['msg'] =  __('message.reminder.deadline.referees', ['deadline'=> $user->region->close_referees_at->diffForHumans(['parts'=>3,'join'=>true]) ]);
                    $msg['action'] = route('game.index', ['language'=>app()->getLocale(),'region'=>$user->region]);
                    $msg['actiontext'] = __('game.action.assign-referees');
                    $reminders[] = $msg;
                } else {
                    $msg['msg'] =  __('message.reminder.deadline.referees', ['deadline'=> $user->region->close_referees_at->diffForHumans(['parts'=>1]) ]);
                    $infos[] = $msg;
                }
            }
        }

        if ($user->isA('clubadmin','superadmin')){
            // check close registration deadline
            if ( ( $user->region->close_registration_at != null ) and ($user->region->close_registration_at > now() ) ) {
                $msg = [];
                $msg['action'] = '';

                if ($user->region->close_registration_at <= now()->addWeeks(1) ){
                    $msg['msg'] =  __('message.reminder.deadline.registration', ['deadline'=> $user->region->close_registration_at->diffForHumans(['parts'=>3,'join'=>true])  ]);
                    $reminders[] = $msg;
                } else {
                    $msg['msg'] =  __('message.reminder.deadline.registration', ['deadline'=> $user->region->close_registration_at->diffForHumans(['parts'=>1])  ]);
                    $infos[] = $msg;
                }
            }
            // check close selection deadline
            if ( ( $user->region->close_selection_at != null ) and ($user->region->close_selection_at > now() ) ) {
                $msg = [];
                $msg['action'] = '';

                if ($user->region->close_selection_at <= now()->addWeeks(1) ){
                    $msg['msg'] =  __('message.reminder.deadline.selection', ['deadline'=> $user->region->close_selection_at->diffForHumans(['parts'=>3,'join'=>true]) ]);
                    $reminders[] = $msg;
                } else {
                    $msg['msg'] =  __('message.reminder.deadline.selection', ['deadline'=> $user->region->close_selection_at->diffForHumans(['parts'=>1]) ]);
                    $infos[] = $msg;
                }
            }
            // check close scheduling deadline
            if ( ( $user->region->close_scheduling_at != null ) and ($user->region->close_scheduling_at > now() ) ) {
                $msg = [];
                $msg['action'] = '';

                if ($user->region->close_scheduling_at <= now()->addWeeks(1) ){
                    $msg['msg'] =  __('message.reminder.deadline.scheduling', ['deadline'=> $user->region->close_scheduling_at->diffForHumans(['parts'=>3,'join'=>true])  ]);
                    $reminders[] = $msg;
                } else {
                    $msg['msg'] =  __('message.reminder.deadline.scheduling', ['deadline'=> $user->region->close_scheduling_at->diffForHumans(['parts'=>1])  ]);
                    $infos[] = $msg;
                }
            }
        }


        return view('home', ['msglist' => $msglist, 'reminders'=>$reminders, 'infos'=>$infos]);
    }
}
