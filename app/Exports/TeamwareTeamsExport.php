<?php

namespace App\Exports;

use App\Models\League;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class TeamwareTeamsExport implements FromView, WithCustomCsvSettings
{
    protected League $league;

    public function __construct(League $league)
    {
        $this->league = $league;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $teams = $this->league->teams()->with('club')->get()->sortBy('club.shortname');

        return view('reports.teamware_team', ['teams' => $teams]);
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => "\t",
            'enclosure' => '',
        ];
    }
}
