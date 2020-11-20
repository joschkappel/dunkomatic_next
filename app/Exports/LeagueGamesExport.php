<?php

namespace App\Exports;

use App\Models\Game;
use App\Models\League;
use App\Enums\ReportScope;

use App\Exports\Sheets\LeagueGames;

use App\Exports\Sheets\Title;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LeagueGamesExport implements WithMultipleSheets
{

    use Exportable;

    protected $scope;
    protected $league;

    public function __construct($league_id, ReportScope $scope)
    {
        $this->league = League::find($league_id);
        $this->scope = $scope;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {

      if ( $this->scope == ReportScope::ms_all()){
        $sheets[] = new LeagueGames($this->league, ReportScope::ms_all());
      }

        return $sheets;
    }

}
