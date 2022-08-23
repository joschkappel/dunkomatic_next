<?php

namespace App\Exports;

use App\Models\Region;

use App\Exports\Sheets\Title;
use App\Exports\Sheets\GamesSheet;
use App\Exports\Sheets\ClubsSheet;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RegionLeagueGamesReport implements WithMultipleSheets
{

    use Exportable;

    public Region $region;

    public function __construct(Region $region)
    {
        $this->region =$region;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {

        $sheets = [];

        $sheets[] = new Title('Runden-Spielpläne', $this->region, null, null);
        foreach($this->region->leagues as $l){
            $sheets[] = new GamesSheet($this->region, $l);
            $sheets[] = new ClubsSheet($this->region, $l);
        }

        return $sheets;
    }

}
