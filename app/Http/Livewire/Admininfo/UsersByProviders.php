<?php

namespace App\Http\Livewire\Admininfo;

use App\Models\User;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

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

    public $provider = null;

    public $byDate = null;

    protected $listeners = [
        'onSliceClickProvider' => 'handleByProvider',
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

    public function handleByProvider($slice)
    {
        if ($slice['title'] == 'None') {
            $this->provider = '';
        } else {
            $this->provider = $slice['title'];
        }
    }

    public function render()
    {
        $users = User::whereNotNull('id')->get();
        $pieChartModel = (new PieChartModel())
            ->setAnimated($this->firstRun)
            // ->setType('donut')
            ->legendPositionBottom()
            ->withDataLabels()
            ->withOnSliceClickEvent('onSliceClickProvider')
            ->setTitle('#Users by Provider');
        //->setColors(['#006600', '#993399', '#CC0000','#0033CC']);

        foreach ($users->countBy('provider') as $p => $cnt) {
            if (($p) == '') {
                $p = 'None';
            }
            $pieChartModel->addSlice($p, $cnt, $this->colors[$p]);
        }

        if ($this->provider == '') {
            $users = User::whereNull('provider')->pluck('id');
        } else {
            $users = User::where('provider', $this->provider)->pluck('id');
        }

        $logins = DB::table('authentication_log')
                ->whereNotNull('login_at')
                ->whereIn('authenticatable_id', $users)
                ->selectRaw('date(login_at) as login_date, login_successful, count(*) as cnt')
                ->groupByRaw('date(login_at), login_successful')
                ->orderBy('login_date')
                ->get();
        $ltypes = $logins->pluck('login_successful')->unique();
        $ldates = $logins->pluck('login_date')->unique();

        $multiColumnChartModel = $ldates
            ->reduce(function ($multiColumnChartModel, $data) use ($logins, $ltypes) {
                foreach ($ltypes as $lt) {
                    $multiColumnChartModel
                        ->addSeriesColumn($this->labels[$lt], $data, $logins->where('login_successful', $lt)->where('login_date', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Logins by Result '.($this->provider != '' ? '(provider: '.$this->provider.')' : '(no provider)'))
                ->setAnimated($this->firstRun)
                ->withOnColumnClickEventName('onColumnClickDate')
                ->stacked()
                ->withGrid()
                ->withLegend()
                ->withDataLabels()
            );

        if ($this->byDate == null) {
            $logins_by_hod = DB::table('authentication_log')
                ->whereNotNull('login_at')
                ->whereIn('authenticatable_id', $users)
                ->selectRaw('hour(login_at) as login_hour, login_successful, count(*) as cnt')
                ->groupByRaw('hour(login_at), login_successful')
                ->orderBy('login_hour')
                ->get();
            $ltypes = $logins_by_hod->pluck('login_successful')->unique();
        } else {
            $logins_by_hod = DB::table('authentication_log')
                ->whereNotNull('login_at')
                ->whereIn('authenticatable_id', $users)
                ->whereRaw('date(login_at) = ?', [$this->byDate])
                ->selectRaw('hour(login_at) as login_hour, login_successful, count(*) as cnt')
                ->groupByRaw('hour(login_at), login_successful')
                ->orderBy('login_hour')
                ->get();
            $ltypes = $logins_by_hod->pluck('login_successful')->unique();
        }

        $multiColumnChartModel2 = collect([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24])
            ->reduce(function ($multiColumnChartModel2, $data) use ($logins_by_hod, $ltypes) {
                foreach ($ltypes as $lt) {
                    $multiColumnChartModel2
                        ->addSeriesColumn($lt, $data, $logins_by_hod->where('login_successful', $lt)->where('login_hour', $data)->first()->cnt ?? 0);
                }

                return $multiColumnChartModel2;
            }, LivewireCharts::multiColumnChartModel()
                ->setTitle('Logins by Hour of Day')
                ->setAnimated($this->firstRun)
                ->withOnColumnClickEventName('onSliceClickClear')
                ->stacked()
                ->withGrid()
                ->withLegend()
                ->withDataLabels()
            );

        return view('livewire.admininfo.users-by-providers')
            ->with([
                'pieChartModel' => $pieChartModel,
                'multiColumnChartModel' => $multiColumnChartModel,
                'multiColumnChartModel2' => $multiColumnChartModel2,
            ]);
    }
}
