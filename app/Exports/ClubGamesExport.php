<?php

namespace App\Exports;

use App\Models\Club;
use App\Models\Game;
use App\Models\League;

use App\Exports\Sheets\ClubGames;
use App\Exports\Sheets\ClubLeagueGames;
use App\Exports\Sheets\Title;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClubGamesExport implements WithMultipleSheets
{
    use Exportable;

    protected $club;

    public function __construct($club_id = "")
    {
        $this->club = Club::find($club_id);
    }

    /**
     * @return array
     */
    public function sheets(): array
    {

        $sheets[] = new Title($this->club, 'Spielpläne');
        $sheets[] = new ClubGames($this->club, 'ALL');
        $sheets[] = new ClubGames($this->club, 'HOME');

        $leagues = Game::where('club_id_home',$this->club->id)->with('league')->get()->pluck('league.id')->unique();
        foreach ($leagues as $l){
          $sheets[] = new ClubLeagueGames($this->club, League::find($l));
        }

        return $sheets;
    }

}
