<?php

namespace App\Exports;

use App\Exports\Sheets\ClubsSheet;
use App\Exports\Sheets\LeagueGamesSheet;
use App\Exports\Sheets\Title;
use App\Models\League;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LeagueGamesReport implements WithMultipleSheets
{
    use Exportable;

    protected League $league;

    public function __construct(int $league_id)
    {
        $this->league = League::find($league_id);
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new Title('Rundenspielplan', null, null, $this->league);
        $sheets[] = new LeagueGamesSheet($this->league);
        $sheets[] = new ClubsSheet($this->league->region, $this->league);

        return $sheets;
    }
}
