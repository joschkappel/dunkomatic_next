<?php

namespace App\Exports;

use App\Exports\Sheets\ClubContactsSheet;
use App\Exports\Sheets\LeagueContactsSheet;
use App\Exports\Sheets\RegionContactsSheet;
use App\Exports\Sheets\Title;
use App\Models\Region;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RegionContactsReport implements WithMultipleSheets, ShouldAutoSize
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

        $sheets[] = new Title('Adressbuch', $this->region, null, null);
        $sheets[] = new RegionContactsSheet($this->region);
        $sheets[] = new LeagueContactsSheet($this->region);

        foreach ($this->region->childRegions as $cr) {
            $sheets[] = new ClubContactsSheet($cr);
            $sheets[] = new LeagueContactsSheet($cr);
        }

        return $sheets;
    }
}
