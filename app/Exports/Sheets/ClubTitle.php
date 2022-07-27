<?php

namespace App\Exports\Sheets;

use App\Models\Club;
use App\Models\Region;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClubTitle implements FromView, WithTitle, ShouldAutoSize
{
    protected Club $club;
    protected Region $hq;
    protected string $rptname;

    public function __construct(Club $club, string $rptname)
    {
        $this->club = $club;
        $this->rptname = $rptname;
        $this->hq = $club->region->parentRegion;
    }

    public function view(): View
    {
        return view('reports.club_title', ['club' => $this->club, 'hqregion' => $this->hq, 'rptname' => $this->rptname]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
       return 'Titel';
    }

}
