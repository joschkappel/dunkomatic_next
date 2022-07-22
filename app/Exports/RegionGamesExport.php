<?php

namespace App\Exports;

use App\Models\Region;
use App\Exports\Sheets\RegionGames;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RegionGamesExport implements WithMultipleSheets
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

        $sheets[] = new RegionGames($this->region);

        return $sheets;
    }

}
