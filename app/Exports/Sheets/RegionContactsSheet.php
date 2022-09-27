<?php

namespace App\Exports\Sheets;

use App\Models\Region;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class RegionContactsSheet implements FromView, WithTitle, ShouldAutoSize, WithColumnWidths
{
    public $gdate;

    public Region $region;

    public function __construct(Region $region)
    {
        $this->gdate = null;
        $this->region = $region;

        Log::info('[EXCEL EXPORT] creating REGION CONTACTS sheet.', ['region-id' => $this->region->id]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 30,
            'C' => 30,
            'D' => 40,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('Bezirksleitung ').' '.$this->region->code;
    }

    public function view(): View
    {
        $region = $this->region->with('memberships.member')->first();
        foreach ($region->childRegions as $cr) {
            $cr->load('memberships.member');
        }

        return view('reports.region_contacts_sheet', ['region' => $region]);
    }
}
