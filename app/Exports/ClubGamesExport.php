<?php

namespace App\Exports;

use App\Models\Club;
use App\Models\Game;
use App\Models\League;
use App\Enums\ReportScope;

use App\Exports\Sheets\ClubGames;
use App\Exports\Sheets\ClubLeagueGames;

use App\Exports\Sheets\ClubTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Log;

class ClubGamesExport implements WithMultipleSheets
{
    use Exportable;

    protected Club $club;
    protected ReportScope $scope;
    protected League $league;

    public function __construct(int $club_id, ReportScope $scope,  int $league_id = NULL)
    {
        $this->club = Club::find($club_id);
        if ($league_id == NULL) {
            $this->league = new League();
        } else {
            $this->league = League::find($league_id);
        }
        $this->scope = $scope;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = array();

        if ($this->scope == ReportScope::ms_all()) {
            $sheets[] = new ClubTitle($this->club, 'Spielpläne');
            $sheets[] = new ClubGames($this->club, ReportScope::ss_club_all());
            $sheets[] = new ClubGames($this->club, ReportScope::ss_club_home());
            $sheets[] = new ClubGames($this->club, ReportScope::ss_club_referee());

            $leagues = Game::where('club_id_home', $this->club->id)->get()->pluck('league_id')->unique();
            foreach ($leagues as $l) {
                $sheets[] = new ClubLeagueGames($this->club, League::find($l));
            }
        } elseif (($this->scope == ReportScope::ss_club_all()) or ($this->scope == ReportScope::ss_club_home()) or ($this->scope == ReportScope::ss_club_referee())) {
            $sheets[] = new ClubGames($this->club, new ReportScope($this->scope->value));
        } elseif ($this->scope == ReportScope::ss_club_league()) {
            $sheets[] = new ClubLeagueGames($this->club, $this->league);
        }

        return $sheets;
    }
}
