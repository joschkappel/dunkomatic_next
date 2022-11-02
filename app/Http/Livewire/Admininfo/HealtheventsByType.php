<?php

namespace App\Http\Livewire\Admininfo;

use App\Models\Club;
use App\Models\Game;
use App\Models\League;
use App\Models\Member;
use App\Models\Region;
use App\Models\Team;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HealtheventsByType extends Component
{
    public $firstRun = true;

    public $labels = [
        Club::class => 'Club',
        League::class => 'League',
        Team::class => 'Team',
        Member::class => 'Member',
        Region::class => 'Region',
        Game::class => 'Game',
    ];

    public function render()
    {
        $multicolumnChartModel = LivewireCharts::multiColumnChartModel()
            ->setTitle('Healthevents by Type')
            ->setAnimated($this->firstRun)
            ->stacked()
            ->withGrid()
            ->withLegend()
            ->withDataLabels();

        $healthevents = DB::table('health_check_result_history_items')
            ->where('status', 'failed')
            ->selectRaw('date(created_at) as health_date, check_name, count(check_name) as cnt')
            ->groupBy('health_date', 'check_name')
            ->orderBy('health_date')
            ->get();
        foreach ($healthevents->groupBy('health_date', 'check_name') as $he) {
            $multicolumnChartModel->addSeriesColumn($he->first()->check_name, $he->first()->health_date, $he->first()->cnt);
        }

        return view('livewire.admininfo.healthevents-by-type')
            ->with([
                'multiColumnChartModel' => $multicolumnChartModel,
            ]);
    }
}
