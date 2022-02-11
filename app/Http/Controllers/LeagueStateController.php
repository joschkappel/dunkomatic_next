<?php

namespace App\Http\Controllers;

use App\Models\League;
use Illuminate\Support\Facades\Log;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\LeagueStateChange;

use App\Traits\LeagueFSM;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            case LeagueStateChange::CloseAssignment():
                $this->close_assignment($league);
                break;
            case LeagueStateChange::OpenAssignment():
                $this->open_assignment($league);
                break;
            case LeagueStateChange::CloseScheduling():
                $this->close_scheduling($league);
                break;
            case LeagueStateChange::OpenScheduling():
                $this->open_scheduling($league);
                break;
            case LeagueStateChange::CloseRegistration():
                $this->close_registration($league);
                break;
            case LeagueStateChange::OpenRegistration():
                $this->open_registration($league);
                break;
            case LeagueStateChange::CloseSelection():
                $this->close_selection($league);
                break;
            case LeagueStateChange::OpenSelection():
                $this->open_selection($league);
                break;
            case LeagueStateChange::CloseFreeze():
                $this->close_freeze($league);
                break;
            case LeagueStateChange::OpenFreeze():
                $this->open_freeze($league);
                break;
            case LeagueStateChange::CloseReferees():
                $this->close_referees($league);
                break;
            case LeagueStateChange::OpenReferees():
                $this->open_referees($league);
                break;
        }

        $league->refresh();

        return true;
    }
}
