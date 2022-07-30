<?php

namespace App\Exports\Sheets;
use App\Models\Game;
use App\Models\Region;
use App\Models\League;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use Illuminate\Support\Facades\Log;

class GamesSheet implements FromView, WithTitle, ShouldAutoSize
{

    protected ?Date $gdate = null;
    public Region $region;
    public ?League $league;

    public function __construct(Region $region, League $league=null)
    {
        $this->gdate = null;
        $this->region = $region;
        $this->league = $league;

        if ($league == null){
            Log::info('[EXCEL EXPORT] creating REGION GAMES sheet.', ['region-id'=>$this->region->id]);
        } else {
            Log::info('[EXCEL EXPORT] creating REGION LEAGUE GAMES sheet.', ['region-id'=>$this->region->id, 'league-id'=>$this->league->id]);
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        if ($this->league == null){
            return __('reports.games.all').' '.$this->region->code;
        } else {
            return __('reports.games.league').' '.$this->league->shortname;
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


        return view('reports.games_sheet', ['games'=>$games, 'gdate'=>$this->gdate, 'gtime'=>null]);
    }



}
