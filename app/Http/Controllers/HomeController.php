<?php

namespace App\Http\Controllers;

use App\Enums\Report;
use App\Enums\ReportFileType;
use App\Mail\Feedback;
use App\Traits\ReportVersioning;
use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class HomeController extends Controller
{
    use ReportVersioning;

    /**
     * display view with list of users to approve
     *
     * @return \Illuminate\View\View
     */
    public function approval()
    {
        return view('auth/approval');
    }

    /**
     * diplay home pahe for all users
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        $user = Auth::user();

        /*         $msglist = array();
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
                array_shift($msglist); */
        $msglist = $user->unreadNotifications;
        Log::info('preparing unread message list.', ['count' => count($msglist)]);

        $reminders = [];
        $withdrawals = [];
        $links = collect();

        //bouncer db access is massive !
        $user_is_region_super = $user->isAn('regionadmin', 'superadmin');
        $user_is_club_region_super = $user->isAn('clubadmin', 'regionadmin', 'superadmin');
        $user_can_view_regions = $user->can('view-regions');

        foreach ($this->get_outdated_downloads($user) as $rpt) {
            $msg = [];
            $msg['msg'] = __('message.reminder.download', ['report' => Report::coerce($rpt->report_id)->description]);
            $msg['action_msg'] = __('message.reminder.download.action');
            $msg['action_color'] = 'info';
            $msg['action'] = Report::coerce($rpt->report_id)->getReportDownloadLink($rpt->model_id, ReportFileType::None());
            $reminders[] = $msg;
        }

        foreach ($user->regions() as $region) {
            if (($user_is_region_super) and $user->can('access', $region)) {
                $links[] = ['text' => $region->code, 'url' => route('region.dashboard', ['region' => $region, 'language' => app()->getLocale()])];
                // check new users waiting for approval
                $users_to_approve = $region->users()->whereNull('approved_at')->whereNull('rejected_at')->count();
                if ($users_to_approve > 0) {
                    $msg = [];
                    $msg['msg'] = trans_choice('message.reminder.approvals', $users_to_approve, ['approvals' => $users_to_approve, 'region' => $region->code]);
                    $msg['action_msg'] = __('message.reminder.approvals.action');
                    $msg['action'] = route('admin.user.index.new', ['language' => app()->getLocale(), 'region' => $region]);
                    $msg['action_color'] = 'danger';
                    $reminders[] = $msg;
                }
                // check region deadlines
                // check close referees deadline
                if (($region->close_referees_at != null) and ($region->close_referees_at > now())) {
                    $msg = [];
                    $msg['action'] = '';

                    if ($region->close_referees_at <= now()->addWeeks(1)) {
                        $msg['msg'] = __('message.reminder.deadline.referees', ['deadline' => $region->close_referees_at->diffForHumans(['parts' => 3, 'join' => true]), 'region' => $region->code]);
                        $msg['action_msg'] = __('message.reminder.deadline.action');
                        $msg['action'] = route('game.index', ['language' => app()->getLocale(), 'region' => $region]);
                        $msg['action_color'] = 'danger';
                        $reminders[] = $msg;
                    }
                }

                foreach ($region->load('clubs')->clubs as $club) {
                    $links[] = ['text' => $club->shortname, 'url' => route('club.dashboard', ['club' => $club,  'language' => app()->getLocale()])];
                }
                foreach ($region->load('leagues')->leagues as $league) {
                    $links[] = ['text' => $league->shortname, 'url' => route('league.dashboard', ['league' => $league,  'language' => app()->getLocale()])];
                }
            } else {
                $links[] = ['text' => $region->code, 'url' => route('region.briefing', ['region' => $region,  'language' => app()->getLocale()])];
            }

            if ($user_is_club_region_super) {
                // check close registration deadline
                if (($region->close_registration_at != null) and ($region->close_registration_at > now())) {
                    $msg = [];
                    $msg['action'] = '';

                    if ($region->close_registration_at <= now()->addWeeks(1)) {
                        $msg['msg'] = __('message.reminder.deadline.registration', ['deadline' => $region->close_registration_at->setHour(20)->diffForHumans(['parts' => 3, 'join' => true]), 'region' => $region->code]);
                        $msg['action_color'] = 'info';
                        $reminders[] = $msg;
                    }
                }
                // check open selection deadline
                if (($region->open_selection_at != null) and ($region->open_selection_at > now())) {
                    $msg = [];
                    $msg['action'] = '';

                    if ($region->open_selection_at <= now()->addWeeks(1)) {
                        $msg['msg'] = __('message.reminder.deadline.start.selection', ['deadline' => $region->open_selection_at->setHour(8)->diffForHumans(['parts' => 3, 'join' => true]), 'region' => $region->code]);
                        $msg['action_color'] = 'info';
                        $reminders[] = $msg;
                    }
                }
                // check close selection deadline
                if (($region->close_selection_at != null) and ($region->close_selection_at > now())) {
                    $msg = [];
                    $msg['action'] = '';

                    if ($region->close_selection_at <= now()->addWeeks(1)) {
                        $msg['msg'] = __('message.reminder.deadline.close.selection', ['deadline' => $region->close_selection_at->setHour(20)->diffForHumans(['parts' => 3, 'join' => true]), 'region' => $region->code]);
                        $msg['action_color'] = 'info';
                        $reminders[] = $msg;
                    }
                }
                // check open scheduling deadline
                if (($region->open_scheduling_at != null) and ($region->open_scheduling_at > now())) {
                    $msg = [];
                    $msg['action'] = '';

                    if ($region->open_scheduling_at <= now()->addWeeks(1)) {
                        $msg['msg'] = __('message.reminder.deadline.start.scheduling', ['deadline' => $region->open_scheduling_at->setHour(8)->diffForHumans(['parts' => 3, 'join' => true]), 'region' => $region->code]);
                        $msg['action_color'] = 'info';
                        $reminders[] = $msg;
                    }
                }
                // check close scheduling deadline
                if (($region->close_scheduling_at != null) and ($region->close_scheduling_at > now())) {
                    $msg = [];
                    $msg['action'] = '';

                    if ($region->close_scheduling_at <= now()->addWeeks(1)) {
                        $msg['msg'] = __('message.reminder.deadline.close.scheduling', ['deadline' => $region->close_scheduling_at->setHour(20)->diffForHumans(['parts' => 3, 'join' => true]), 'region' => $region->code]);
                        $msg['action_color'] = 'info';
                        $reminders[] = $msg;
                    }
                }
            }

        }

        if (! $user_is_region_super) {
            foreach ($user->clubs() as $club) {
                $links[] = ['text' => $club->shortname, 'url' => route('club.dashboard', ['club' => $club,  'language' => app()->getLocale()])];
            }
            foreach ($user->leagues() as $league) {
                $links[] = ['text' => $league->shortname, 'url' => route('league.dashboard', ['league' => $league,  'language' => app()->getLocale()])];
            }
        }
        // get withdrawals
        $teams = Team::whereNotNull('withdrawn_at')->orderBy('withdrawn_at','desc')->get();
        foreach ($teams as $t){
            $msg['msg'] = $t->state['withdrawn'].': Team '.$t->name.' zurückgezogen.';
            $msg['msg_color'] = 'info';
            $withdrawals[] = $msg;
        }
        return view('home', ['msglist' => $msglist, 'reminders' => $reminders, 'withdrawals' => $withdrawals, 'links' => $links->slice(0, 20)]);
    }

    /**
     * send feedback to webadmin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send_feedback(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:60',
            'body' => 'required|string|max:1000',
        ]);
        Log::info('feedback form data validated OK.', ['input' => $data]);

        $executed = RateLimiter::attempt(
            'send-message:'.Auth::user()->id,
            $perHour = 4,
            function () use ($data) {
                Mail::to(config('app.contact'))->send(new Feedback(Auth::user(), $data['title'], $data['body']));                // Send message...
            }
        );

        if (! $executed) {
            Log::warning('Too many mails sent!');
        }

        return redirect('home');
    }
}
