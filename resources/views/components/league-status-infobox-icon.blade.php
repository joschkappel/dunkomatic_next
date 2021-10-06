@if ($currentState->is(App\Enums\LeagueState::Setup()))
    <span class="info-box-icon bg-danger"><i class="fas fa-pencil-ruler"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Assignment()))
    <span class="info-box-icon bg-info"><i class="fas fa-battery-empty"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Registration()))
    <span class="info-box-icon bg-info"><i class="fas fa-battery-quarter"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Selection()))
    <span class="info-box-icon bg-info"><i class="fas fa-battery-half"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Freeze()))
    <span class="info-box-icon bg-warning"><i class="fas fa-battery-half"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Scheduling()))
    <span class="info-box-icon bg-info"><i class="fas fa-battery-three-quarters"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Referees()))
    <span class="info-box-icon bg-warning"><i class="fas fa-battery-full"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Live()))
    <span class="info-box-icon bg-success"><i class="fas fa-battery-full"></i></span>
@endif
