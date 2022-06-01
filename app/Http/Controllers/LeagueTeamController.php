<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\Club;
use App\Models\Team;
use App\Enums\Role;
use App\Enums\LeagueState;
use App\Models\Game;

use App\Notifications\ClubDeAssigned;
use App\Events\LeagueTeamCharUpdated;

use App\Traits\GameManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LeagueTeamController extends Controller
{
    use GameManager;

    /**
     * Attach club to league
     *
     * @param Request $request
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function assign_clubs(Request $request, League $league)
    {

        $data = $request->validate([
            'assignedClubs' => ['nullable', 'array', 'min:0', 'max:' . $league->size],
            'assignedClubs.*' => 'nullable|exists:clubs,id',
            'club_id' => 'nullable|exists:clubs,id',
        ]);
        Log::info('club assignment form data validated OK.');

        $upperArr = config('dunkomatic.league_team_chars');

        // assign new list
        if (isset($data['assignedClubs'])) {
            Log::notice('replace assigned clubs with new list.', ['league-id' => $league->id, 'clubs' => count($data['assignedClubs'])]);
            // delete old entries
            $league->clubs()->detach();

            foreach ($data['assignedClubs'] as $i => $c) {
                $league_no = $i + 1;
                $league_char = $upperArr[$league_no];
                Log::debug('league_char: ' . $league_char);

                $league->clubs()->attach(
                    $c,
                    [
                        'league_no' => $league_no,
                        'league_char' => $league_char
                    ]
                );
            }
        } elseif (isset($data['club_id'])) {
            $league_no = $league->clubs->max('pivot.league_no') + 1;
            $league_char = $upperArr[$league_no];
            $league->clubs()->attach(
                Club::find($data['club_id']),
                [
                    'league_no' => $league_no,
                    'league_char' => $league_char
                ]
            );
            Log::notice('assign new club to league.', ['league-id' => $league->id, 'club-id' => $data['club_id']]);
        }

        if (count($data) == 0) { // remove all assigned clubs
            Log::notice('de-assign ALL clubs.', ['league-id' => $league->id]);
            // delete all assignments
            $league->clubs()->detach();
        }

        return redirect()->back();
        //route('league.dashboard', ['language' => app()->getLocale(), 'league' => $league]);
    }

    /**
     * Detach club from league
     *
     * @param Request $request
     * @param  \App\Models\League  $league
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function deassign_club(Request $request, League $league, Club $club)
    {
        $upperArr = config('dunkomatic.league_team_chars');
        // special treatment as values might be duplicate
        $occurences = $league->clubs->pluck('id')->intersect([$club->id])->count();
        if ($occurences > 1) {
            Log::info('club has multiple assignments', ['league-id' => $league->id, 'club-id' => $club->id, 'assignments' => $occurences]);
            $assigned_clubs = $league->clubs->pluck('id')->diff([$club->id]);
            for ($i = 1; $i < $occurences; $i++) {
                $assigned_clubs[] = $club->id;
            }
            $league->clubs()->detach();
            foreach ($assigned_clubs as $i => $ac) {
                $c = $upperArr[$i + 1];
                $league->clubs()->attach([$ac => ['league_no' => $i + 1, 'league_char' => $c]]);
            }
        } else {
            $league->clubs()->detach($club);
            Log::info('club deassigned from league', ['league-id' => $league->id, 'club-id' => $club->id]);
        }


        // deassign teams as well
        $team = Team::where('club_id', $club->id)->where('league_id', $league->id)->first();
        if (isset($team)) {
            Log::info('de-register team from league', ['league-id' => $league->id, 'club-id' => $club->id, 'team-id' => $team->id]);
            $team->update(['league_id' => null, 'league_no' => null, 'league_char' => null]);

            // if league games are generated, delete these games as well
            $this->blank_team_games($league, $team);

            $member = $club->members()->wherePivot('role_id', Role::ClubLead)->first();

/*             if (isset($member)) {
                $member->notify(new ClubDeAssigned($league, $club, $team, Auth::user()->name, $member->name));
                Log::info('[NOTIFICATION] club deassigned.', ['league-id' => $league->id, 'club-id' => $club->id, 'team-id' => $team->id, 'member-id' => $member->id]);

                $user = $member->user;
                if (isset($user)) {
                    $user->notify(new ClubDeAssigned($league, $club, $team, Auth::user()->name, $user->name));
                    Log::info('[NOTIFICATION] club deassigned.', ['league-id' => $league->id, 'club-id' => $club->id, 'team-id' => $team->id, 'user-id' => $user->id]);
                }
            } */
        }

        return Response::json(['success' => 'all good'], 200);
    }

    /**
     * register team fora league
     *
     * @param  \App\Models\League  $league
     * @param \App\Models\Team $team
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function league_register_team(Request $request, League $league, Team $team)
    {
        if ($request->has('team_id')){
            // get data
            $data = $request->validate([
                'team_id' => 'required|exists:teams,id',
            ]);
            $team = Team::findOrFail($data['team_id']);
        }


        $team->league()->associate($league);
        $team->save();
        Log::notice('team registered for league.', ['team-id' => $team->id, 'league-id' => $league->id]);

        if ( $request->has('team_id')) {
            return redirect()->back();
        } else {
            return Response::json(['success' => 'all good'], 200);
        }
    }

    /**
     * remove team registration  from league
     *
     * @param  \App\Models\League  $league
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function league_unregister_team(League $league, Team $team)
    {
        $team->update(['league_id' => null, 'league_no' => null, 'league_char' => null]);
        Log::notice('team un-registered and league no cleared.', ['team-id' => $team->id, 'league-id' => $league->id, 'league-team-no' => $team->league_no]);

        // $league->clubs()->wherePivot('club_id', '=', $team->club->id)->detach();
        // Log::info('club deassigned from league.', ['club-id' => $team->club->id, 'league-id' => $league->id]);

        if ($league->state > LeagueState::Freeze()) {
            Game::whereIn('id', $team->games_guest->pluck('id'))->delete();
            Game::whereIn('id', $team->games_home->pluck('id'))->delete();
            Log::info('games deleted for team.', ['team-id' => $team->id, 'league-id' => $league->id]);
        }

        return Response::json(['success' => 'all good'], 200);
    }

    /**
     * Attach team to league
     *
     * @param Request $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function team_register_league(Request $request, Team $team)
    {
        // get data
        $data = $request->validate([
            'league_id' => 'required|exists:leagues,id',
        ]);
        Log::info('team registration form data validated OK.');

        $league = League::findOrFail($data['league_id']);

        $team->league()->associate($league);
        $team->save();
        Log::notice('team registered for league.', ['team-id' => $team->id, 'league-id' => $league->id]);

        return back();
    }

    /**
     * Add a team to league
     *
     * @param Request $request
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function inject(Request $request, League $league)
    {
        $data = $request->validate([
            'league_no' => 'required|integer|between:1,16',
            'team_id' => 'required|exists:teams,id'
        ]);
        Log::info('team inject form data validated OK.');

        $league_no = $data['league_no'];
        $size = $league->size;
        $chars = config('dunkomatic.league_team_chars');
        $upperArr = array_slice($chars, 0, $size, true);
        $league_char = $upperArr[$league_no];
        // update team
        $team = Team::findOrFail($data['team_id']);
        $team->update(['league_id' => $league->id, 'league_no' => $league_no, 'league_char' => $league_char]);
        Log::notice('team added to league.', ['league-id' => $league->id, 'team-id' => $team->id]);

        $used_char = $league->clubs->pluck('pivot.league_char')->toArray();
        $free_char = array_diff($upperArr, $used_char);

        // special treatment as values might be duplicate
        $occurences_club = $league->clubs->pluck('id')->intersect([$team->club->id])->count();
        $occurences_team = $league->teams->pluck('club_id')->intersect([$team->club->id])->count();

        if ($league->clubs->count() < $size) {
            if ($occurences_club < $occurences_team) {
                $clubleague_char = array_shift($free_char);
                $clubleague_no = array_search($clubleague_char, $chars, false);
                $league->clubs()->attach($team->club->id, ['league_no' => $clubleague_no, 'league_char' => $clubleague_char]);
                Log::info('club assigned to league.', ['league-id' => $league->id, 'club-id' => $team->club->id]);
            }
        }

        $this->inject_team_games($league, $team, $league_no);

        return redirect()->back();
    }

    /**
     * withdraw a team from a league
     *
     * @param Request $request
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function withdraw(Request $request, League $league)
    {
        $data = $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);
        Log::info('team withdrawal form data validated OK.');

        $team = Team::findOrFail($data['team_id']);

        // Team: league_prev, league_id, league_char, league_no,
        $team->update(['league_prev' => $league->shortname, 'league_id' => null, 'league_char' => null, 'league_no' => null]);
        Log::info('team deregistered from league', ['team-id' => $team->id, 'league-id' => $league->id]);

        // Game: blank all games with gameteam home+guest
        $this->blank_team_games($league, $team);

        // League:club delete
        $upperArr = config('dunkomatic.league_team_chars');
        // special treatment as values might be duplicate
        $occurences = $league->clubs->pluck('id')->intersect([$team->club->id])->count();
        if ($occurences > 1) {
            Log::info('teams club has multiple assignments', ['league-id' => $league->id, 'club-id' => $team->club->id, 'assignments' => $occurences]);
            $assigned_clubs = $league->clubs->pluck('id')->diff([$team->club->id]);
            for ($i = 1; $i < $occurences; $i++) {
                $assigned_clubs[] = $team->club->id;
            }
            $league->clubs()->detach();
            foreach ($assigned_clubs as $i => $ac) {
                $c = $upperArr[$i + 1];
                $league->clubs()->attach([$ac => ['league_no' => $i + 1, 'league_char' => $c]]);
            }
        } else {
            $league->clubs()->detach($team->club);
            Log::info('club deassigned from league', ['league-id' => $league->id, 'club-id' => $team->club->id]);
        }

        return redirect()->back();
    }

    /**
     * Pick a league number for a team
     *
     * @param Request $request
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\JsonResponse
     */
    public function pick_char(Request $request, League $league)
    {
        // get data
        $data = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'league_no' => 'required|integer|between:1,16',
        ]);
        Log::info('team league no form data validated OK.');

        $udata = array();
        $udata['league_id'] = $league->id;
        $udata['league_no'] = $data['league_no'];
        $upperArr = config('dunkomatic.league_team_chars');
        $udata['league_char'] = $upperArr[$data['league_no']];

        $team = Team::findOrFail($data['team_id']);
        $team->update($udata);
        Log::notice('team league no set.', ['team-id' => $team->id, 'league-id' => $league->id, 'league-team-no' => $data['league_no']]);

        $action = __('notifications.event.char.picked', [
            'league' => $league->shortname,
            'club' => $team->club->shortname,
            'league_no' => $udata['league_no'] . '/' . $udata['league_char']
        ]);

        // broadcast event to all other users on this view
        broadcast(new LeagueTeamCharUpdated($league, $action, 'danger'))->toOthers();

        return Response::json(['success' => 'all good'], 200);
    }

    /**
     * un-Pick a league number for a team
     *
     * @param Request $request
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function release_char(Request $request, League $league)
    {
        // get data
        $data = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'league_no' => 'required|integer|between:1,16',
        ]);
        Log::info('team league no form data validated OK.');

        $udata = array();
        $udata['league_no'] = null;
        $udata['league_char'] = null;

        $team = Team::where('id', $data['team_id'])->where('league_id', $league->id)->where('league_no', $data['league_no'])->first();
        if ($team != null) {
            $action = __('notifications.event.char.released', [
                'league' => $league->shortname,
                'club' => $team->club->shortname,
                'league_no' => $team->league_no . '/' . $team->league_char
            ]);

            $team->update($udata);
            Log::notice('team league no released.', ['team-id' => $team->id, 'league-id' => $league->id, 'league-team-no' => $data['league_no']]);

            broadcast(new LeagueTeamCharUpdated($league, $action, 'success'))->toOthers();

            return Response::json(['success' => 'all good'], 200);
        } else {
            Log::error('release league char: team not found.', ['team-id' =>  $data['team_id'], 'league-id' => $league->id, 'league-team-no' => $data['league_no']]);
            return Response::json(['error' => 'team not found'], 404);
        }
    }
}
