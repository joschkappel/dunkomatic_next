<?php

namespace App\Http\Livewire\Admininfo;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Enums\Report;
use Carbon\CarbonPeriod;

class DownloadsByReport extends Component
{
    public $firstRun = true;

    public function render()
    {
        $downloads = DB::table('report_downloads')
            ->whereNotNull('id')
            ->get();

        $pieChartModel = $downloads->groupBy('report_id')
        ->reduce(function ($pieChartModel, $data) {
            $type = Report::coerce($data->first()->report_id)->description;
            $value = $data->count();

            return $pieChartModel->addSlice($type, $value, '#f66665');
        }, LivewireCharts::pieChartModel()
            ->setTitle('Downloads by Report')
            ->setAnimated($this->firstRun)
            ->legendPositionBottom()
            ->legendHorizontallyAlignedCenter()
            ->setDataLabelsEnabled(true)
            ->setColors(['#006600', '#993399', '#CC0000', '#0033CC', '#b01a1b', '#d41b2c', '#ec3c3b', '#f66665'])
        );

        $downloads = DB::table('report_downloads')
            ->selectRaw('date(created_at) as dload_date, report_id, count(*) as cnt')
            ->groupByRaw('date(created_at), report_id')
            ->orderBy('dload_date')
            ->get();
        $atypes = $downloads->pluck('report_id')->unique();
        $adates = $downloads->pluck('dload_date')->unique();

        // get all dates for range in adates
        $period = CarbonPeriod::create($adates->min(), $adates->max());
        // Iterate over the period
        $alldates = collect();
        foreach ($period as $date) {
            $alldates->push( $date->format('Y-m-d'));
        };

        $multiColumnChartModel = $alldates
            ->reduce(function ($multiColumnChartModel, $data) use ($downloads, $atypes) {
                foreach ($atypes as $at) {
                    $multiColumnChartModel
                        ->addSeriesColumn(Report::coerce($at)->description, $data, $downloads->where('report_id', $at)->where('dload_date', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Downloads by Report and Date')
                ->setAnimated($this->firstRun)
                ->stacked()
                ->withGrid()
                ->withLegend()
                ->withDataLabels()
            );

        return view('livewire.admininfo.downloads-by-report')
            ->with([
                'pieChartModel' => $pieChartModel,
                'multiColumnChartModel' => $multiColumnChartModel,
            ]);
    }
}
