<?php

namespace App\Http\Livewire\Admininfo;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AuditsByModel extends Component
{
    public $firstRun = true;

    public function render()
    {
        $audits = DB::table('audits')
            ->selectRaw('date(created_at) as audit_date, auditable_type, count(*) as cnt')
            ->groupByRaw('date(created_at), auditable_type')
            ->orderBy('audit_date')
            ->get();
        $atypes = $audits->pluck('auditable_type')->unique();
        $adates = $audits->pluck('audit_date')->unique();

        $multiColumnChartModel = $adates
            ->reduce(function ($multiColumnChartModel, $data) use ($audits, $atypes) {
                foreach ($atypes as $at) {
                    $multiColumnChartModel
                        ->addSeriesColumn($at, $data, $audits->where('auditable_type', $at)->where('audit_date', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Auditevents by Model and Date')
                ->setAnimated($this->firstRun)
                ->stacked()
                ->withGrid()
                ->withLegend()
                ->withDataLabels()
            );

        return view('livewire.admininfo.audits-by-model')
            ->with([
                'multiColumnChartModel' => $multiColumnChartModel,
            ]);
    }
}
