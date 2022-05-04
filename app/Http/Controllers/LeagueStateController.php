<?php

namespace App\Http\Controllers;

use App\Models\League;
use Illuminate\Support\Facades\Log;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\LeagueStateChange;

use App\Traits\LeagueFSM;

use Illuminate\Http\Request;

class LeagueStateController extends Controller
{

    use LeagueFSM;

  /**
   * change state by seeting state dates
   *
   * @param Request $request
   * @param  \App\Models\League  $league
   * @return boolean
   *
   */
    public function change_state(Request $request, League $league)
    {

        $data = $request->validate([
            'action' => ['required', new EnumValue(LeagueStateChange::class, false)],
        ]);
        Log::info('new league state form data validated OK.', ['league-id'=>$league->id]);

        switch ($data['action']) {
            case LeagueStateChange::OpenRegistration():
                $this->open_team_registration($league);
                break;
            case LeagueStateChange::ReOpenAssignment():
                $this->reopen_club_assignment($league);
                break;
            case LeagueStateChange::OpenReferees():
                $this->open_ref_assignment($league);
                break;
            case LeagueStateChange::ReOpenScheduling():
                $this->reopen_game_scheduling($league);
                break;
            case LeagueStateChange::OpenSelection():
                $this->open_char_selection($league);
                break;
            case LeagueStateChange::ReOpenRegistration():
                $this->reopen_team_registration($league);
                break;
            case LeagueStateChange::FreezeLeague():
                $this->freeze_league($league);
                break;
            case LeagueStateChange::ReOpenSelection():
                $this->reopen_char_selection($league);
                break;
            case LeagueStateChange::OpenScheduling():
                $this->open_game_scheduling($league);
                break;
            case LeagueStateChange::ReFreezeLeague():
                $this->refreeze_league($league);
                break;
            case LeagueStateChange::GoLiveLeague():
                $this->golive_league($league);
                break;
            case LeagueStateChange::CloseLeague():
                $this->close_league($league);
                break;
            case LeagueStateChange::StartLeague():
                $this->start_league($league);
                break;
            case LeagueStateChange::ReStartLeague():
                $this->restart_league($league);
                break;
        }

        $league->refresh();

        return true;
    }
}
