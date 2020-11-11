<?php

namespace App\Exports;

use App\Models\Game;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LeagueGamesExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{

    private $gdate = null;
    private $league_id;

    public function __construct($league_id = "")
    {
        $this->league_id = $league_id;
        $this->gdate = null;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
       return Game::where('league_id',$this->league_id)->orderBy('game_date','asc')->orderBy('game_time','asc')->orderBy('game_no','asc');
    }

    /**
    * @var Game $game
    */
    public function map($game): array
    {
        if ( $this->gdate != $game->game_date){
          $this->gdate = $game->game_date;
        } else {
          $game->game_date = null;
        };
        return [
            ($game->game_date == null) ? '' : $game->game_date->locale( app()->getLocale())->isoFormat('ddd L'),
            Carbon::parse($game->game_time)->isoFormat('LT'),
            $game->game_no,
            $game->team_home,
            $game->team_guest,
            $game->gym_no,
            $game->referee_1
        ];
    }
    public function headings(): array
    {
        return [
          [
            'Rundenspielplan',
            'RUnde X',
            'Runde Y'
          ],
          [
            __('game.game_date'),
            __('game.game_time'),
            __('game.game_no'),
            __('game.team_home'),
            __('game.team_guest'),
            __('game.gym_no'),
            __('game.referee')
          ]
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            2    => ['font' => ['bold' => true]],

        ];
    }

}
