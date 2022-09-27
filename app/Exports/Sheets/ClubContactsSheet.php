<?php

namespace App\Exports\Sheets;

use App\Models\Region;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class ClubContactsSheet implements FromView, WithTitle, WithColumnWidths, ShouldAutoSize
{
    public $gdate;

    public Region $region;

    public function __construct(Region $region)
    {
        $this->gdate = null;
        $this->region = $region;

        Log::info('[EXCEL EXPORT] creating REGION CLUB CONTACTS sheet.', ['region-id' => $this->region->id]);
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
        return __('Vereinsadressen ').' '.$this->region->code;
    }

    public function view(): View
    {
        $clubs = $this->region->clubs()->with('memberships.member', 'gyms')->active()->get()->sortBy('shortname');

        return view('reports.club_contacts_sheet', ['clubs' => $clubs]);
    }
}
