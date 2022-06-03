@if ($currentState->is(App\Enums\LeagueState::Setup()))
    <span class="info-box-icon bg-danger"><i class="fas fa-cog"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Registration()))
    <span class="info-box-icon bg-info"><i class="fas fa-file-signature"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Selection()))
    <span class="info-box-icon bg-info"><i class="fas fa-list-ol"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Freeze()))
    <span class="info-box-icon bg-warning"><i class="fas fa-pause-circle"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Scheduling()))
    <span class="info-box-icon bg-info"><i class="fas fa-calendar-alt"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Referees()))
    <span class="info-box-icon bg-warning"><i class="fas fa-stopwatch"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Live()))
    <span class="info-box-icon bg-success"><i class="fas fa-fire"></i></span>
@endif
