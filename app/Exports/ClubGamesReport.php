<?php

namespace App\Exports;

use App\Enums\ReportScope;
use App\Exports\Sheets\ClubGames;
use App\Exports\Sheets\ClubLeagueGames;
use App\Exports\Sheets\ClubsSheet;
use App\Exports\Sheets\Title;
use App\Models\Club;
use App\Models\Game;
use App\Models\League;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClubGamesReport implements WithMultipleSheets
{
    use Exportable;

    protected Club $club;

    protected ReportScope $scope;

    protected League $league;

    public function __construct(int $club_id, ReportScope $scope, int $league_id = null)
    {
        $this->club = Club::find($club_id);
        if ($league_id == null) {
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
        $sheets = [];

        if ($this->scope == ReportScope::ms_all()) {
            $sheets[] = new Title('Spielpläne', null, $this->club, null);
            $sheets[] = new ClubGames($this->club, ReportScope::ss_club_all());
            $sheets[] = new ClubGames($this->club, ReportScope::ss_club_home());
            $sheets[] = new ClubGames($this->club, ReportScope::ss_club_referee());

            $leagues = Game::where('club_id_home', $this->club->id)->get()->pluck('league_id')->unique();
            $leagues = League::whereIn('id', $leagues)->get();
            foreach ($leagues as $l) {
                $sheets[] = new ClubLeagueGames($this->club, $l);
                $sheets[] = new ClubsSheet($l->region, $l);
            }
        } elseif (($this->scope == ReportScope::ss_club_all()) or ($this->scope == ReportScope::ss_club_home()) or ($this->scope == ReportScope::ss_club_referee())) {
            $sheets[] = new ClubGames($this->club, new ReportScope($this->scope->value));
        } elseif ($this->scope == ReportScope::ss_club_league()) {
            $sheets[] = new ClubLeagueGames($this->club, $this->league);
            $sheets[] = new ClubsSheet($this->league->region, $this->league);
        }

        return $sheets;
    }
}
