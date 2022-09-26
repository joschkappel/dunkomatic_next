<?php

namespace App\Exports;

use App\Exports\Sheets\ClubsSheet;
use App\Exports\Sheets\GamesSheet;
use App\Exports\Sheets\Title;
use App\Models\Region;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RegionGamesReport implements WithMultipleSheets
{
    use Exportable;

    public Region $region;

    public function __construct(Region $region)
    {
        $this->region = $region;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new Title('Gesamtspielplan', $this->region, null, null);
        $sheets[] = new GamesSheet($this->region);
        $sheets[] = new ClubsSheet($this->region);

        return $sheets;
    }
}
