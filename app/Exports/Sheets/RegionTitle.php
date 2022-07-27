<?php

namespace App\Exports\Sheets;

use App\Models\Region;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RegionTitle implements FromView, WithTitle, ShouldAutoSize
{
    protected Region $region;
    protected string $rptname;

    public function __construct(Region $region, string $rptname)
    {
        $this->region = $region;
        $this->rptname = $rptname;
    }

    public function view(): View
    {
        return view('reports.region_title', ['region' => $this->region, 'rptname' => $this->rptname]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
       return 'Titel';
    }

}
