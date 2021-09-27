<div class="progress" style="height: 20px;">
    @if ($currentState->is( App\Enums\LeagueState::Assignment  ) )
        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['assigned'].'/'.$league_kpis['size'] }}</div>
    @elseif ($currentState->is( App\Enums\LeagueState::Registration  ) )
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['assigned'].'/'.$league_kpis['size'] }}</div>
        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['registered'].'/'.$league_kpis['assigned'] }}</div>
    @elseif ($currentState->is( App\Enums\LeagueState::Selection  ) )
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['assigned'].'/'.$league_kpis['size'] }}</div>
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['registered'].'/'.$league_kpis['assigned'] }}</div>
        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['charspicked'].'/'.$league_kpis['registered'] }}</div>
    @elseif ($currentState->is( App\Enums\LeagueState::Freeze  ) )
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['assigned'].'/'.$league_kpis['size'] }}</div>
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['registered'].'/'.$league_kpis['assigned'] }}</div>
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['charspicked'].'/'.$league_kpis['registered'] }}</div>
        <div class="progress-bar bg-warning" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
    @elseif ($currentState->is( App\Enums\LeagueState::Scheduling  ) )
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['assigned'].'/'.$league_kpis['size'] }}</div>
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['registered'].'/'.$league_kpis['assigned'] }}</div>
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['charspicked'].'/'.$league_kpis['registered'] }}</div>
        <div class="progress-bar bg-warning" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['scheduled'].'/'.$league_kpis['generated'] }}</div>
    @elseif ($currentState->is( App\Enums\LeagueState::Referees  ) )
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['assigned'].'/'.$league_kpis['size'] }}</div>
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['registered'].'/'.$league_kpis['assigned'] }}</div>
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['charspicked'].'/'.$league_kpis['registered'] }}</div>
        <div class="progress-bar bg-warning" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
        <div class="progress-bar bg-primary" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['scheduled'].'/'.$league_kpis['generated'] }}</div>
        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['referees'].'/'.$league_kpis['generated'] }}</div>
    @elseif ($currentState->is( App\Enums\LeagueState::Live  ) )
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['assigned'].'/'.$league_kpis['size'] }}</div>
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['registered'].'/'.$league_kpis['assigned'] }}</div>
        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['charspicked'].'/'.$league_kpis['registered'] }}</div>
        <div class="progress-bar bg-warning" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
        <div class="progress-bar bg-primary" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['scheduled'].'/'.$league_kpis['generated'] }}</div>
        <div class="progress-bar bg-primary" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">{{ $league_kpis['referees'].'/'.$league_kpis['generated'] }}</div>
        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">LIVE</div>
    @else
        <div class="progress-bar bg-info" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    @endif

</div>
