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

    protected $listeners = [
        'onSliceClick' => 'handleOnSliceClick',
    ];

    public function handleOnSliceClick($slice)
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
            ->withOnSliceClickEvent('onSliceClick')
            ->setTitle('Users by Provider');
        //->setColors(['#ffd700', '#ff0000', '#ffa500']);

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
                ->stacked()
                ->withGrid()
                ->withLegend()
                ->withDataLabels()
            );

        return view('livewire.admininfo.users-by-providers')
            ->with([
                'pieChartModel' => $pieChartModel,
                'multiColumnChartModel' => $multiColumnChartModel,
            ]);
    }
}
