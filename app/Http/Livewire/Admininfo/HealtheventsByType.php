<?php

namespace App\Http\Livewire\Admininfo;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HealtheventsByType extends Component
{
    public $firstRun = true;

    public function render()
    {
        $healthevents = DB::table('health_check_result_history_items')
            ->where('status', 'failed')
            ->get();

        $pieChartModel = $healthevents->groupBy('check_name')
            ->reduce(function ($pieChartModel, $data) {
                $type = $data->first()->check_name;
                $value = $data->count();

                return $pieChartModel->addSlice($type, $value, '#f66665');
            }, LivewireCharts::pieChartModel()
                ->setTitle('Healthevents by Type')
                ->setAnimated($this->firstRun)
                ->legendPositionBottom()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled(true)
                ->setColors(['#b01a1b', '#d41b2c', '#ec3c3b', '#f66665'])
            );

        $healthevents = DB::table('health_check_result_history_items')
            ->where('status', 'failed')
            ->selectRaw('date(created_at) as health_date, check_name, count(*) as cnt')
            ->groupByRaw('date(created_at), check_name')
            ->orderBy('health_date')
            ->get();
        $htypes = $healthevents->pluck('check_name')->unique();
        $hdates = $healthevents->pluck('health_date')->unique();

        $multiColumnChartModel = $hdates
            ->reduce(function ($multiColumnChartModel, $data) use ($healthevents, $htypes) {
                foreach ($htypes as $ht) {
                    $multiColumnChartModel
                        ->addSeriesColumn($ht, $data, $healthevents->where('check_name', $ht)->where('health_date', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Healthevents by Type and Date')
                ->setAnimated($this->firstRun)
                ->stacked()
                ->withGrid()
                ->withLegend()
                ->withDataLabels()
            );

        $healthevents2 = DB::table('health_check_result_history_items')
            ->where('status', 'failed')
            ->selectRaw('hour(created_at) as health_hour, check_name, count(*) as cnt')
            ->groupByRaw('hour(created_at), check_name')
            ->orderBy('health_hour')
            ->get();
        $htypes = $healthevents2->pluck('check_name')->unique();

        $multiColumnChartModel2 = collect([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24])
            ->reduce(function ($multiColumnChartModel2, $data) use ($healthevents2, $htypes) {
                foreach ($htypes as $ht) {
                    $multiColumnChartModel2
                        ->addSeriesColumn($ht, $data, $healthevents2->where('check_name', $ht)->where('health_hour', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel2;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Healthevents by Type and Hour of Day')
                ->setAnimated($this->firstRun)
                ->stacked()
                ->withGrid()
                ->withLegend()
                ->withDataLabels()
            );

        return view('livewire.admininfo.healthevents-by-type')
            ->with([
                'pieChartModel' => $pieChartModel,
                'multiColumnChartModel' => $multiColumnChartModel,
                'multiColumnChartModel2' => $multiColumnChartModel2,
            ]);
    }
}
