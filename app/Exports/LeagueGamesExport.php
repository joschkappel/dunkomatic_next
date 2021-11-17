<?php

namespace App\Exports;

use App\Models\League;

use App\Exports\Sheets\LeagueGames;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LeagueGamesExport implements WithMultipleSheets
{

    use Exportable;

    protected $scope;
    protected $league;

    public function __construct($league_id)
    {
        $this->league = League::find($league_id);
    }

    /**
     * @return array
     */
    public function sheets(): array
    {

        $sheets[] = new LeagueGames($this->league);

        return $sheets;
    }

}
