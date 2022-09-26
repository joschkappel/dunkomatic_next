<?php

namespace App\Exports\Sheets;

use App\Models\Region;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class LeagueContactsSheet implements FromView, WithTitle, ShouldAutoSize, WithColumnWidths
{
    public $gdate;

    public Region $region;

    public function __construct(Region $region)
    {
        $this->gdate = null;
        $this->region = $region;

        Log::info('[EXCEL EXPORT] creating REGION LEAGUE CONTACTS sheet.', ['region-id' => $this->region->id]);
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
        return __('Rundenadressen ').' '.$this->region->code;
    }

    public function view(): View
    {
        $leagues = $this->region->leagues()->with(['memberships.member', 'teams.club', 'teams.members', 'teams.memberships'])->get()->sortBy('shortname');

        return view('reports.league_contacts_sheet', ['leagues' => $leagues]);
    }
}
