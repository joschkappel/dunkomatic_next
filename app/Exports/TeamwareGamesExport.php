<?php

namespace App\Exports;

use App\Models\League;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class TeamwareGamesExport implements FromView, WithCustomCsvSettings
{
    protected League $league;

    public function __construct(League $league)
    {
        $this->league = $league;
    }

    /**
    * @return View
    */
    public function view(): View
    {

        $games = $this->league->games()->with('club_home')->get()->sortBy('game_no');
        $scheme = $this->league->league_size->schemes->pluck('game_day','game_no');

        // prepare for iterations
        $iterations = $this->league->schedule->iterations ?? 1;
        $total_gday = $scheme->values()->unique()->count();

        $iteration_scheme = $scheme;
        for ($i=1; $i < $iterations ; $i++) {
            $iteration_scheme = $iteration_scheme->concat( $scheme->map(function ($item, $key) use ($i, $total_gday) {
                return $item + ($i * $total_gday);
            }) );
        };

        return view('reports.teamware_game', ['games'=>$games, 'schemes'=>$iteration_scheme] );
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => "\t",
            'enclosure' => ""
        ];
    }
}
