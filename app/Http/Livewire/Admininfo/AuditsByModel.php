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

class AuditsByModel extends Component
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
        $multilineChartModel = LivewireCharts::multiLineChartModel()
        ->setTitle('Auditevents by Model')
        ->setAnimated($this->firstRun)
        ->multiLine()
        ->withLegend();

        $audits = DB::table('audits')
            ->selectRaw('date(created_at) as audit_date, auditable_type, count(auditable_type) as cnt')
            ->groupBy('audit_date', 'auditable_type')
            ->orderBy('audit_date')
            ->get();
        foreach ($audits->groupBy('audit_date', 'auditable_type') as $a) {
            $multilineChartModel->addSeriesPoint($this->labels[$a->first()->auditable_type], $a->first()->audit_date, $a->first()->cnt);
        }

        return view('livewire.admininfo.audits-by-model')
            ->with([
                'multiLineChartModel' => $multilineChartModel,
            ]);
    }
}
