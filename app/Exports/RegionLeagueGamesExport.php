<?php

namespace App\Exports;

use App\Models\Region;

use App\Exports\Sheets\LeagueGames;
use App\Exports\Sheets\RegionTitle;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RegionLeagueGamesExport implements WithMultipleSheets
{

    use Exportable;

    protected Region $region;

    public function __construct(int $region_id)
    {
        $this->region = Region::find($region_id);
    }

    /**
     * @return array
     */
    public function sheets(): array
    {

        $sheets[] = new RegionTitle($this->region, 'Runden-Spielpläne');
        foreach($this->region->leagues as $l){
            $sheets[] = new LeagueGames($l);
        }

        return $sheets;
    }

}
