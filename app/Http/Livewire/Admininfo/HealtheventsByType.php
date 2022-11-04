<?php

namespace App\Http\Livewire\Admininfo;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HealtheventsByType extends Component
{
    public $firstRun = true;

    public $byDate = null;

    protected $listeners = [
        'onColumnClickDate' => 'handleByDate',
        'onSliceClickClear' => 'clearFilter',
    ];

    public function handleByDate($bar)
    {
        $this->byDate = $bar['title'];
    }

    public function clearFilter()
    {
        $this->byDate = null;
    }

    public function render()
    {
        $health_by_date = DB::table('health_check_result_history_items')
            ->where('status', 'failed')
            ->get();

        $pieChartModel = $health_by_date->groupBy('check_name')
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
                ->setColors(['#006600', '#993399', '#CC0000', '#0033CC'])
            );

        $health_by_date = DB::table('health_check_result_history_items')
            ->where('status', 'failed')
            ->selectRaw('date(created_at) as health_date, check_name, count(*) as cnt')
            ->groupByRaw('date(created_at), check_name')
            ->orderBy('health_date')
            ->get();
        $htypes = $health_by_date->pluck('check_name')->unique();
        $hdates = $health_by_date->pluck('health_date')->unique();

        $multiColumnChartModel = $hdates
            ->reduce(function ($multiColumnChartModel, $data) use ($health_by_date, $htypes) {
                foreach ($htypes as $ht) {
                    $multiColumnChartModel
                        ->addSeriesColumn($ht, $data, $health_by_date->where('check_name', $ht)->where('health_date', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Healthevents by Type and Date')
                ->withOnColumnClickEventName('onColumnClickDate')
                ->setAnimated($this->firstRun)
                ->stacked()
                ->withGrid()
                ->withLegend()
                ->withDataLabels()
            );

        if ($this->byDate == null) {
            $health_by_hod = DB::table('health_check_result_history_items')
                ->where('status', 'failed')
                ->selectRaw('hour(created_at) as health_hour, check_name, count(*) as cnt')
                ->groupByRaw('hour(created_at), check_name')
                ->orderBy('health_hour')
                ->get();
            $htypes = $health_by_hod->pluck('check_name')->unique();
        } else {
            $health_by_hod = DB::table('health_check_result_history_items')
                ->where('status', 'failed')
                ->whereRaw('date(created_at) = ?', [$this->byDate])
                ->selectRaw('hour(created_at) as health_hour, check_name, count(*) as cnt')
                ->groupByRaw('hour(created_at), check_name')
                ->orderBy('health_hour')
                ->get();
            $htypes = $health_by_hod->pluck('check_name')->unique();
        }

        $multiColumnChartModel2 = collect([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24])
            ->reduce(function ($multiColumnChartModel2, $data) use ($health_by_hod, $htypes) {
                foreach ($htypes as $ht) {
                    $multiColumnChartModel2
                        ->addSeriesColumn($ht, $data, $health_by_hod->where('check_name', $ht)->where('health_hour', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel2;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Healthevents by Type and Hour of Day')
                ->setAnimated($this->firstRun)
                ->withOnColumnClickEventName('onSliceClickClear')
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
