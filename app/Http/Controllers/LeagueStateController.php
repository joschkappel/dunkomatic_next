<?php

namespace App\Http\Controllers;

use App\Enums\LeagueState;
use App\Enums\LeagueStateChange;
use App\Models\League;
use App\Models\Region;
use App\Traits\GameManager;
use App\Traits\LeagueFSM;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeagueStateController extends Controller
{
    use LeagueFSM, GameManager;

    /**
     * change state by seeting state dates
     *
     * @param  Request  $request
     * @param  \App\Models\League  $league
     * @return bool
     */
    public function change_state(Request $request, League $league)
    {
        $data = $request->validate([
            'action' => ['required', new EnumValue(LeagueStateChange::class, false)],
        ]);
        Log::info('new league state form data validated OK.', ['league-id' => $league->id]);

        switch ($data['action']) {
            case LeagueStateChange::StartLeague():
                $this->start_league($league);
                break;
            case LeagueStateChange::OpenSelection():
                $this->open_char_selection($league);
                break;
            case LeagueStateChange::FreezeLeague():
                $this->freeze_league($league);
                break;
            case LeagueStateChange::OpenScheduling():
                $this->open_game_scheduling($league);
                break;
            case LeagueStateChange::OpenReferees():
                $this->open_ref_assignment($league);
                break;
            case LeagueStateChange::GoLiveLeague():
                $this->golive_league($league);
                break;
            case LeagueStateChange::ReStartLeague():
                $this->restart_league($league);
                break;

            case LeagueStateChange::ReOpenReferees():
                $this->reopen_ref_assignment(($league));
                break;
            case LeagueStateChange::ReOpenScheduling():
                $this->reopen_game_scheduling($league);
                break;
            case LeagueStateChange::ReFreezeLeague():
                $this->refreeze_league($league);
                break;
            case LeagueStateChange::ReOpenSelection():
                $this->reopen_char_selection($league);
                break;
            case LeagueStateChange::ReOpenRegistration():
                $this->reopen_team_registration($league);
                break;
            case LeagueStateChange::CloseLeague():
                $this->close_league($league);
                break;
        }

        $league->refresh();

        return true;
    }

    /**
     * change state by setting state dates
     *
     * @param  Request  $request
     * @param  \App\Models\Region  $region
     * @return bool
     */
    public function change_state_region(Request $request, Region $region)
    {
        $data = $request->validate([
            'from_state' => ['required', new EnumValue(LeagueState::class, false)],
            'action' => ['required', new EnumValue(LeagueStateChange::class, false)],
        ]);
        Log::info('new league state form data validated OK.', ['region-id' => $region->id]);

        // get all leagues that are in from_state
        $leagues = $region->leagues()->where('state', $data['from_state'])->get();
        foreach ($leagues as $l) {
            // for each league call state change
            $this->change_state($request, $l);
        }

        return true;
    }

    /**
     * remove games with missing teams
     *
     * @param  Request  $request
     * @param  \App\Models\Region  $region
     * @return bool
     */
    public function destroy_noshow_games(Request $request, Region $region)
    {
        $data = $request->validate([
            'from_state' => ['required', new EnumValue(LeagueState::class, false)],
        ]);
        Log::info('purge game form data validated OK.', ['region-id' => $region->id]);

        // get all leagues that are in from_state
        $leagues = $region->leagues()->where('state', $data['from_state'])->get();
        foreach ($leagues as $l) {
            // for each league emove games with no home ro guest team
            $this->delete_noshow_games($l);
        }

        return true;
    }
}
