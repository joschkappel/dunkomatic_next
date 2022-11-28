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

    public $show_date;
    public $show_month;
    protected $multiColumnChartModel;
    protected $pieChartModel;

    protected $listeners = [
        'onColumnClickDate' => 'dataForDate',
        'onColumnClickMonth' => 'dataForMonth',
        'onColumnClickYear' => 'dataForYear',
    ];

    public function dataForDate($date)
    {
        $this->show_date = $date['title'];
        $data_by_date = DB::table('report_downloads')
        ->whereRaw('date(created_at) = ?', [$this->show_date])
        ->selectRaw('hour(created_at) as download_hour, report_id, count(*) as cnt')
        ->groupByRaw('hour(created_at), report_id')
        ->orderBy('download_hour')
        ->get();
        $rtypes = $data_by_date->pluck('report_id')->unique();

        $multiColumnChartModel = collect([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24])
            ->reduce(function ($multiColumnChartModel, $data) use ($data_by_date, $rtypes) {
                foreach ($rtypes as $rt) {
                    $multiColumnChartModel
                        ->addSeriesColumn( Report::coerce($rt)->description, $data, $data_by_date->where('report_id', $rt)->where('download_hour', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Report Downloads by Report and Hour of Day')
                ->setAnimated($this->firstRun)
                ->withOnColumnClickEventName('onColumnClickYear')
                ->stacked()
                ->withGrid()
                ->withLegend()
                ->withDataLabels()
        );
        $this->multiColumnChartModel = $multiColumnChartModel;

    }

    public function dataForMonth($month)
    {
        $this->show_month = $month['title'];
        $data_by_month = DB::table('report_downloads')
            ->whereRaw('month(created_at) = ?', [$this->show_month])
            ->selectRaw('date(created_at) as download_date, report_id, count(*) as cnt')
            ->groupByRaw('date(created_at), report_id')
            ->orderBy('download_date')
            ->get();
        $rtypes = $data_by_month->pluck('report_id')->unique();
        $adates = $data_by_month->pluck('download_date')->unique();

        // get all dates for range in adates
        $period = CarbonPeriod::create($adates->min(), $adates->max());
        // Iterate over the period
        $alldates = collect();
        foreach ($period as $date) {
            $alldates->push( $date->format('Y-m-d'));
        };

        $multiColumnChartModel = $alldates
            ->reduce(function ($multiColumnChartModel, $data) use ($data_by_month, $rtypes) {
                foreach ($rtypes as $rt) {
                    $multiColumnChartModel
                        ->addSeriesColumn(Report::coerce($rt)->description, $data, $data_by_month->where('report_id', $rt)->where('download_date', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Report Downloads by Report and Day')
                ->setAnimated($this->firstRun)
                ->withOnColumnClickEventName('onColumnClickDate')
                ->stacked()
                ->withGrid()
                ->withLegend()
                ->withDataLabels()
        );
        $this->multiColumnChartModel = $multiColumnChartModel;
    }

    public function dataForYear()
    {
        $data_by_year = DB::table('report_downloads')
        ->selectRaw('month(created_at) as download_month, report_id, count(*) as cnt')
        ->groupByRaw('month(created_at), report_id')
        ->orderBy('download_month')
        ->get();
        $rtypes = $data_by_year->pluck('report_id')->unique();

        $multiColumnChartModel = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])
            ->reduce(function ($multiColumnChartModel, $data) use ($data_by_year, $rtypes) {
                foreach ($rtypes as $rt) {
                    $multiColumnChartModel
                        ->addSeriesColumn(Report::coerce($rt)->description, $data, $data_by_year->where('report_id', $rt)->where('download_month', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Report Downloads by Report and Month')
                ->setAnimated($this->firstRun)
                ->withOnColumnClickEventName('onColumnClickMonth')
                ->stacked()
                ->withGrid()
                ->withLegend()
                ->withDataLabels()
        );
        $this->multiColumnChartModel = $multiColumnChartModel;
    }

    public function getPieData()
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
        $this->pieChartModel = $pieChartModel;
    }
    public function mount()
    {
        $this->dataForYear();
    }
    public function render()
    {
        $this->getPieData();
        return view('livewire.admininfo.downloads-by-report')
            ->with([
                'pieChartModel' => $this->pieChartModel,
                'multiColumnChartModel' => $this->multiColumnChartModel,
            ]);
    }
}
