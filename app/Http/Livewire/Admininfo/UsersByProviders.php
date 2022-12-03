<?php

namespace App\Http\Livewire\Admininfo;

use App\Models\User;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\CarbonPeriod;
use Carbon\Carbon;

class UsersByProviders extends Component
{
    public $colors = [
        'google' => '#006600',
        'facebook' => '#993399',
        'twitter' => '#CC0000',
        'None' => '#0033CC',
    ];

    public $labels = [
        '0' => 'failed',
        '1' => 'ok',
    ];

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

        $users = User::whereNull('provider')->pluck('id');

        $logins = DB::table('authentication_log')
                ->whereNotNull('login_at')
                ->whereRaw('date(login_at) = ?', [$this->show_date])
                ->whereIn('authenticatable_id', $users)
                ->selectRaw('hour(login_at) as login_hour, login_successful, count(*) as cnt')
                ->groupByRaw('hour(login_at), login_successful')
                ->orderBy('login_hour')
                ->get();
        $ltypes = $logins->pluck('login_successful')->unique();

        $multiColumnChartModel = collect([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24])
            ->reduce(function ($multiColumnChartModel, $data) use ($logins, $ltypes) {
                foreach ($ltypes as $lt) {
                    $multiColumnChartModel
                        ->addSeriesColumn($this->labels[$lt], $data, $logins->where('login_successful', $lt)->where('login_hour', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Logins by Result and Hour of Day')
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

        $users = User::whereNull('provider')->pluck('id');

        $logins = DB::table('authentication_log')
                ->whereNotNull('login_at')
                ->whereRaw('month(login_at) = ?', [$this->show_month])
                ->whereIn('authenticatable_id', $users)
                ->selectRaw('date(login_at) as login_date, login_successful, count(*) as cnt')
                ->groupByRaw('date(login_at), login_successful')
                ->orderBy('login_date')
                ->get();
        $ltypes = $logins->pluck('login_successful')->unique();
        $adates = $logins->pluck('login_date')->unique();
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
            ->reduce(function ($multiColumnChartModel, $data) use ($logins, $ltypes) {
                foreach ($ltypes as $lt) {
                    $multiColumnChartModel
                        ->addSeriesColumn($this->labels[$lt], $data, $logins->where('login_successful', $lt)->where('login_date', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Logins by Result and Day')
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
        $users = User::whereNull('provider')->pluck('id');

        $logins = DB::table('authentication_log')
                ->whereNotNull('login_at')
                ->whereIn('authenticatable_id', $users)
                ->selectRaw('month(login_at) as login_month, login_successful, count(*) as cnt')
                ->groupByRaw('month(login_at), login_successful')
                ->orderBy('login_month')
                ->get();
        $ltypes = $logins->pluck('login_successful')->unique();

        $multiColumnChartModel = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])
            ->reduce(function ($multiColumnChartModel, $data) use ($logins, $ltypes) {
                foreach ($ltypes as $lt) {
                    $multiColumnChartModel
                        ->addSeriesColumn($this->labels[$lt], $data, $logins->where('login_successful', $lt)->where('login_month', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Logins by Result and Month')
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
        $users = User::whereNotNull('id')->get();
        $pieChartModel = (new PieChartModel())
            ->setAnimated($this->firstRun)
            // ->setType('donut')
            ->legendPositionBottom()
            ->withDataLabels()
            ->setTitle('#Users by Provider')
            ->setColors(['#006600', '#993399', '#CC0000','#0033CC']);

        foreach ($users->countBy('provider') as $p => $cnt) {
            if (($p) == '') {
                $p = 'None';
            }
            $pieChartModel->addSlice($p, $cnt, $this->colors[$p]);
        }
        $this->pieChartModel = $pieChartModel;
    }

    public function mount()
    {
        $this->dataForYear();
    }

    public function render()
    {

        $this->getPieData();

        return view('livewire.admininfo.users-by-providers')
            ->with([
                'pieChartModel' => $this->pieChartModel,
                'multiColumnChartModel' => $this->multiColumnChartModel,
            ]);
    }
}
