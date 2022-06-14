<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Carbon;


class HomeController extends Controller
{
    /**
     * display view with list of users to approve
     *
     * @return \Illuminate\View\View
     *
     */
    public function approval()
    {
        return view('auth/approval');
    }

    /**
     * diplay home pahe for all users
     *
     * @return \Illuminate\View\View
     *
     */
    public function home()
    {
        $today = Carbon::today()->toDateString();
        $user = Auth::user();

        $msglist = array();
        $vf = null;
        $mi = array();

        foreach ($user->unreadNotifications as $m) {
            $valid_from = Carbon::parse($m->created_at)->locale(app()->getLocale())->isoFormat('L');
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
        $links = collect();

        foreach ($user->regions() as $region){
            if ( ($user->isAn('regionadmin', 'superadmin')) and $user->can('access',$region) ) {
                $links[] = ['text'=>$region->code, 'url'=> route('region.dashboard',['region'=>$region, 'language'=>app()->getLocale()])];
                // check new users waiting for approval
                $users_to_approve = $region->users()->whereNull('approved_at')->whereNull('rejected_at')->count();
                if ($users_to_approve > 0) {
                    $msg = [];
                    $msg['msg'] =  trans_choice('message.reminder.approvals', $users_to_approve, ['approvals' => $users_to_approve, 'region'=>$region->code ]);
                    $msg['action_msg'] =  __('message.reminder.approvals.action');
                    $msg['action'] = route('admin.user.index.new', ['language' => app()->getLocale(), 'region' => $region ]);
                    $msg['action_color'] = 'danger';
                    $reminders[] = $msg;
                }
                // check region deadlines
                if ( $region->auto_state_change ){
                    // check close assignment deadline
                    if ( ($region->close_assignment_at != null) and ( $region->close_assignment_at > now())) {
                        $msg = [];
                        $msg['action'] = '';

                        if ( $region->close_assignment_at <= now()->addWeeks(1)) {
                            $msg['msg'] =  __('message.reminder.deadline.assignment', ['deadline' => $region->close_assignment_at->diffForHumans(['parts' => 3, 'join' => true]), 'region'=>$region->code ]);
                            $msg['action_msg'] =  __('message.reminder.deadline.action');
                            $msg['action'] = route('league.index_mgmt', ['language' => app()->getLocale(), 'region'=>$region]);
                            $msg['action_color'] = 'danger';
                            $reminders[] = $msg;
                        } else {
                            $msg['msg'] =  __('message.reminder.deadline.assignment', ['deadline' => $region->close_assignment_at->diffForHumans(['parts' => 1]), 'region'=>$region->code]);
                            $msg['msg_color'] = 'warning';
                            $infos[] = $msg;
                        }
                    }
                    // check close referees deadline
                    if ( ( $region->close_referees_at != null) and  ( $region->close_referees_at > now())) {
                        $msg = [];
                        $msg['action'] = '';

                        if ( $region->close_referees_at <= now()->addWeeks(1)) {
                            $msg['msg'] =  __('message.reminder.deadline.referees', ['deadline' => $region->close_referees_at->diffForHumans(['parts' => 3, 'join' => true]), 'region'=>$region->code]);
                            $msg['action_msg'] =  __('message.reminder.deadline.action');
                            $msg['action'] = route('game.index', ['language' => app()->getLocale(), 'region'=>$region]);
                            $msg['action_color'] = 'danger';
                            $reminders[] = $msg;
                        } else {
                            $msg['msg'] =  __('message.reminder.deadline.referees', ['deadline' => $region->close_referees_at->diffForHumans(['parts' => 1]), 'region'=>$region->code]);
                            $msg['msg_color'] = 'warning';
                            $infos[] = $msg;
                        }
                    }
                }

                foreach ($region->load('clubs')->clubs as $club) {
                    $links[] = ['text'=>$club->shortname, 'url'=> route('club.dashboard',['club'=>$club,  'language'=>app()->getLocale()])];
                }
                foreach ($region->load('leagues')->leagues as $league) {
                    $links[] = ['text'=>$league->shortname, 'url'=> route('league.dashboard',['league'=>$league,  'language'=>app()->getLocale()])];
                }
            } else {
                $links[] = ['text'=>$region->code, 'url'=> route('region.briefing',['region'=>$region,  'language'=>app()->getLocale()])];
            }

            if ( ($user->isAn('clubadmin')) and ( $region->auto_state_change )) {
                // check close registration deadline
                if ( ( $region->close_registration_at != null) and ( $region->close_registration_at > now())) {
                    $msg = [];
                    $msg['action'] = '';

                    if ( $region->close_registration_at <= now()->addWeeks(1)) {
                        $msg['msg'] =  __('message.reminder.deadline.registration', ['deadline' => $region->close_registration_at->diffForHumans(['parts' => 3, 'join' => true]), 'region'=>$region->code ]);
                        $msg['action_color'] = 'info';
                        $reminders[] = $msg;
                    } else {
                        $msg['msg'] =  __('message.reminder.deadline.registration', ['deadline' => $region->close_registration_at->diffForHumans(['parts' => 1]), 'region'=>$region->code ]);
                        $msg['msg_color'] = 'warning';
                        $infos[] = $msg;
                    }
                }
                // check close selection deadline
                if ( ( $region->close_selection_at != null) and ( $region->close_selection_at > now())) {
                    $msg = [];
                    $msg['action'] = '';

                    if ( $region->close_selection_at <= now()->addWeeks(1)) {
                        $msg['msg'] =  __('message.reminder.deadline.selection', ['deadline' => $region->close_selection_at->diffForHumans(['parts' => 3, 'join' => true]), 'region'=>$region->code]);
                        $msg['action_color'] = 'info';
                        $reminders[] = $msg;
                    } else {
                        $msg['msg'] =  __('message.reminder.deadline.selection', ['deadline' => $region->close_selection_at->diffForHumans(['parts' => 1]), 'region'=>$region->code]);
                        $msg['msg_color'] = 'warning';
                        $infos[] = $msg;
                    }
                }
                // check close scheduling deadline
                if ( ( $region->close_scheduling_at != null) and ( $region->close_scheduling_at > now())) {
                    $msg = [];
                    $msg['action'] = '';

                    if ( $region->close_scheduling_at <= now()->addWeeks(1)) {
                        $msg['msg'] =  __('message.reminder.deadline.scheduling', ['deadline' => $region->close_scheduling_at->diffForHumans(['parts' => 3, 'join' => true]), 'region'=>$region->code]);
                        $msg['action_color'] = 'info';
                        $reminders[] = $msg;
                    } else {
                        $msg['msg'] =  __('message.reminder.deadline.scheduling', ['deadline' => $region->close_scheduling_at->diffForHumans(['parts' => 1]), 'region'=>$region->code]);
                        $msg['msg_color'] = 'warning';
                        $infos[] = $msg;
                    }
                }
            }

            if ($user->can('view-regions')) {
                if ($region->league_filecount > 0) {
                    $msg = [];
                    $msg['msg'] =  __('message.reminder.download.region.leagues', ['region' => $region->code, 'count' => $region->league_filecount]);
                    $msg['action_msg'] =  __('message.reminder.download.action');
                    $msg['action_color'] = 'info';
                    $msg['action'] = route('region_league_archive.get', ['region'=>$region]);
                    $reminders[] = $msg;
                }
                if ($region->teamware_filecount > 0) {
                    $msg = [];
                    $msg['msg'] =  __('message.reminder.download.region.teamware', ['region' => $region->code, 'count' => $region->teamware_filecount]);
                    $msg['action_msg'] =  __('message.reminder.download.action');
                    $msg['action'] = route('region_teamware_archive.get', ['region'=>$region]);
                    $msg['action_color'] = 'info';
                    $reminders[] = $msg;
                }
            }
        }
        if ($user->isNotAn('regionadmin', 'superadmin')){
            foreach ($user->clubs() as $club) {
                if ($user->can('access', $club)) {
                    $links[] = ['text'=>$club->shortname, 'url'=> route('club.dashboard',['club'=>$club,  'language'=>app()->getLocale()])];
                } else {
                    $links[] = ['text'=>$club->shortname, 'url'=> route('club.briefing',['club'=>$club,  'language'=>app()->getLocale()])];
                }

                if ($club->filecount > 0) {
                    $msg = [];
                    $msg['msg'] =  __('message.reminder.download.clubs', ['club' => $club->shortname, 'count' => $club->filecount]);
                    $msg['action_msg'] =  __('message.reminder.download.action');
                    $msg['action'] = route('club_archive.get', ['club' => $club]);
                    $msg['action_color'] = 'info';
                    $reminders[] = $msg;
                }
            }
            foreach ($user->leagues() as $league) {
                if ($user->can('access',$league)) {
                    $links[] = ['text'=>$league->shortname, 'url'=> route('league.dashboard',['league'=>$league,  'language'=>app()->getLocale()])];
                } else {
                    $links[] = ['text'=>$league->shortname, 'url'=> route('league.briefing',['league'=>$league,  'language'=>app()->getLocale()])];
                }
                if ($league->filecount > 0) {
                    $msg = [];
                    $msg['msg'] =  __('message.reminder.download.leagues', ['league' => $league->shortname, 'count' => $league->filecount]);
                    $msg['action_msg'] =  __('message.reminder.download.action');
                    $msg['action'] = route('league_archive.get', ['league' => $league]);
                    $msg['action_color'] = 'info';
                    $reminders[] = $msg;
                }
            }
        }

        return view('home', ['msglist' => $msglist, 'reminders' => $reminders, 'infos' => $infos, 'links' => $links->slice(0,20)]);
    }
}
