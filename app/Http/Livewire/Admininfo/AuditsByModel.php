<?php

namespace App\Http\Livewire\Admininfo;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\CarbonPeriod;
use Carbon\Carbon;

class AuditsByModel extends Component
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

        $audits = DB::table('audits')
            ->whereRaw('date(created_at) = ?', [$this->show_date])
            ->selectRaw('hour(created_at) as audit_hour, auditable_type, count(*) as cnt')
            ->groupByRaw('hour(created_at), auditable_type')
            ->orderBy('audit_hour')
            ->get();
        $atypes = $audits->pluck('auditable_type')->unique();

        $multiColumnChartModel = collect([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24])
            ->reduce(function ($multiColumnChartModel, $data) use ($audits, $atypes) {
                foreach ($atypes as $at) {
                    $multiColumnChartModel
                        ->addSeriesColumn($at, $data, $audits->where('auditable_type', $at)->where('audit_hour', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Auditevents by Model and Hour of Day')
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

        $audits = DB::table('audits')
            ->whereRaw('month(created_at) = ?', [$this->show_month])
            ->selectRaw('date(created_at) as audit_date, auditable_type, count(*) as cnt')
            ->groupByRaw('date(created_at), auditable_type')
            ->orderBy('audit_date')
            ->get();
        $atypes = $audits->pluck('auditable_type')->unique();
        $adates = $audits->pluck('audit_date')->unique();
        $mindate = $adates->min() ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $maxdate = $adates->max() ?? Carbon::now()->format('Y-m-d');

        // get all dates for range in adates
        $period = CarbonPeriod::create($mindate, $maxdate);
        // Iterate over the period
        $alldates = collect();
        foreach ($period as $date) {
            $alldates->push( $date->format('Y-m-d'));
        };

        $multiColumnChartModel = $alldates
            ->reduce(function ($multiColumnChartModel, $data) use ($audits, $atypes) {
                foreach ($atypes as $at) {
                    $multiColumnChartModel
                        ->addSeriesColumn($at, $data, $audits->where('auditable_type', $at)->where('audit_date', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Auditevents by Model and Day')
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
        $audits = DB::table('audits')
            ->selectRaw('month(created_at) as audit_month, auditable_type, count(*) as cnt')
            ->groupByRaw('month(created_at), auditable_type')
            ->orderBy('audit_month')
            ->get();
        $atypes = $audits->pluck('auditable_type')->unique();

        $multiColumnChartModel = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])
            ->reduce(function ($multiColumnChartModel, $data) use ($audits, $atypes) {
                foreach ($atypes as $at) {
                    $multiColumnChartModel
                        ->addSeriesColumn($at, $data, $audits->where('auditable_type', $at)->where('audit_month', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Auditevents by Model and Month')
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

        return view('livewire.admininfo.audits-by-model')
            ->with([
                'pieChartModel' => $this->pieChartModel,
                'multiColumnChartModel' => $this->multiColumnChartModel,
            ]);
    }
}
