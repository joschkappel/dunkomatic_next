<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Enums\Role;
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
   * @param  \App\Models\League  $league
   * @return \Illuminate\Http\Response
   */
    public function change_state(Request $request, League $league)
    {

        $data = $request->validate([
            'action' => ['required', new EnumValue(LeagueStateChange::class, false)],
        ]);

        switch ($data['action']) {
            case LeagueStateChange::CloseAssignment():
                $this->close_assignment($league);
                break;
            case LeagueStateChange::CloseScheduling():
                $this->close_scheduling($league);
                break;
        }

        $league->refresh();

        return true;
    }
}
