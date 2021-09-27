@if ($currentState->is(App\Enums\LeagueState::Assignment()))
    <span class="badge badge-info"><i class="fas fa-battery-empty fa-lg"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Registration()))
    <span class="badge badge-info"><i class="fas fa-battery-quarter fa-lg"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Selection()))
    <span class="badge badge-info"><i class="fas fa-battery-half fa-lg"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Freeze()))
    <span class="badge badge-warning"><i class="fas fa-battery-half fa-lg"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Scheduling()))
    <span class="badge badge-info"><i class="fas fa-battery-three-quarters fa-lg"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Referees()))
    <span class="badge badge-warning"><i class="fas fa-battery-full fa-lg"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Live()))
    <span class="badge badge-success"><i class="fas fa-battery-full fa-lg"></i></span>
@endif
