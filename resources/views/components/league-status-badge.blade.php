{{-- @if ($currentState->is(App\Enums\LeagueState::Setup()))
    <span class="badge badge-danger"><i class="fas fa-pencil-ruler fa-lg"></i></span>
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
@endif --}}
@if ($currentState->is(App\Enums\LeagueState::Setup()))
    <span><i class="fas fa-cog fa-lg text-danger"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Registration()))
    <span><i class="fas fa-file-signature fa-lg text-info"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Selection()))
    <span><i class="fas  fa-list-ol fa-lg text-info"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Freeze()))
    <span><i class="fas fa-pause-circle fa-lg text-orange"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Scheduling()))
    <span><i class="fas fa-calendar-alt fa-lg text-info"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Referees()))
    <span><i class="fas fa-stopwatch fa-lg text-orange"></i></span>
@elseif ($currentState->is(App\Enums\LeagueState::Live()))
    <span><i class="fas fa-fire fa-lg text-success"></i></span>
@endif
