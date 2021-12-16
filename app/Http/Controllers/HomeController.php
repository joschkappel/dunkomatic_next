<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use App\Models\User;

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

        foreach ($user->regions() as $region){
            if ($user->isAn('regionadmin', 'superadmin')) {
                // check new users waiting for approval
                $users_to_approve = $region->users()->whereNull('approved_at')->count();
                if ($users_to_approve > 0) {
                    $msg = [];
                    $msg['msg'] =  trans_choice('message.reminder.approvals', $users_to_approve, ['approvals' => $users_to_approve, 'region'=>$region->code ]);
                    $msg['action_msg'] =  __('message.reminder.approvals.action');
                    $msg['action'] = route('admin.user.index.new', ['language' => app()->getLocale(), 'region' => $region ]);
                    $msg['action_color'] = 'danger';
                    $reminders[] = $msg;
                }
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
                        $msg['msg'] =  __('message.reminder.deadline.assignment', ['deadline' => $region->close_assignment_at->diffForHumans(['parts' => 1])]);
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

            if ($user->isAn('clubadmin')) {
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
        foreach ($user->clubs() as $club) {
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
            if ($league->filecount > 0) {
                $msg = [];
                $msg['msg'] =  __('message.reminder.download.leagues', ['league' => $league->shortname, 'count' => $league->filecount]);
                $msg['action_msg'] =  __('message.reminder.download.action');
                $msg['action'] = route('league_archive.get', ['league' => $league]);
                $msg['action_color'] = 'info';
                $reminders[] = $msg;
            }
        }

        return view('home', ['msglist' => $msglist, 'reminders' => $reminders, 'infos' => $infos]);
    }
}
