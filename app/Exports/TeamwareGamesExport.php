<?php

namespace App\Exports;

use App\Models\League;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class TeamwareGamesExport implements FromView, WithCustomCsvSettings
{
    protected $league;

    public function __construct(League $league)
    {
        $this->league = $league;
    }

    /**
    * @return Illuminate\Contracts\View\View
    */
    public function view(): View
    {

        $games = $this->league->games->sortBy('game_no');
        $scheme = $this->league->league_size->schemes->pluck('game_day','game_no');

        return view('reports.teamware_game', ['games'=>$games, 'schemes'=>$scheme] );
    }
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => "\t",
            'enclosure' => ""
        ];
    }
}