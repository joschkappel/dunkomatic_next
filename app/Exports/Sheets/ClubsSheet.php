<?php

namespace App\Exports\Sheets;

use App\Models\Game;
use App\Models\Region;
use App\Models\Team;
use App\Models\Club;
use App\Models\Gym;
use App\Models\League;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ClubsSheet implements FromView, WithTitle, ShouldAutoSize
{

    public $gdate;
    public Region $region;
    public ?League $league;

    public function __construct(Region $region, League $league=null)
    {
        $this->gdate = null;
        $this->region = $region;
        $this->league = $league;

        if ($this->league == null){
            Log::info('[EXCEL EXPORT] creating REGION CLUBS sheet.', ['region-id'=>$this->region->id]);
        } else {
            Log::info('[EXCEL EXPORT] creating REGION LEAGUE CLUBS sheet.', ['region-id'=>$this->region->id, 'league-id'=>$this->league->id]);
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        if ($this->league == null){
            return __('Gegner und Hallen').' '.$this->region->code;
        } else {
            return __('Gegner und Hallen').' '.$this->league->shortname;
        }
    }

    public function view(): View
    {
        if ($this->league == null){
            $games =  Game::where('region',$this->region->code)
                        ->with('league')
                        ->orderBy('game_date','asc')
                        ->orderBy('game_time','asc')
                        ->orderBy('game_no','asc')
                        ->get();
        } else {
            $games =  Game::where('league_id',$this->league->id)
                ->with('league')
                ->orderBy('game_date','asc')
                ->orderBy('game_time','asc')
                ->orderBy('game_no','asc')
                ->get();
        }

        $guests = $games->pluck('club_id_guest')->unique();

        $clubs = Club::whereIn('id', $guests)
                ->orderBy('shortname')
                ->get();
        $g = 0;
        $t = 0;

        foreach ($clubs as $c){
          $c['teams'] = Team::whereIn('id', $games->where('club_id_home',$c->id)->pluck('team_id_home')->unique())->orderBy('team_no')->get();
          $t += $c['teams']->count();
          $c['gyms'] = Gym::whereIn('id', $games->where('club_id_home',$c->id)->pluck('gym_id')->unique())->orderBy('gym_no')->get();
          $g += $c['gyms']->count();
        }


        return view('reports.clubs_sheet', ['clubs'=>$clubs]);
    }




}