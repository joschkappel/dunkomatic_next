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
            ->whereNotNull('id')
            ->get();

        $pieChartModel = $audits->groupBy('auditable_type')
            ->reduce(function ($pieChartModel, $data) {
                $type = $data->first()->auditable_type;
                $value = $data->count();

                return $pieChartModel->addSlice($type, $value, '#f66665');
            }, LivewireCharts::pieChartModel()
                ->setTitle('Audits by Model')
                ->setAnimated($this->firstRun)
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(true)
                ->setColors(['#b01a1b', '#d41b2c', '#ec3c3b', '#f66665'])
            );

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
                'pieChartModel' => $pieChartModel,
                'multiColumnChartModel' => $multiColumnChartModel,
            ]);
    }
}
