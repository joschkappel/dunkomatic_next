@extends('layouts.page')

@section('plugins.Pace', true)
@section('plugins.Select2', true)

@section('content_header')
    <div class="container-fluid">
        <div class="row ">
            <div class="col-sm">
                <!-- small card CLUB -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <div class="row">
                            <input type="hidden" id="entitytype" value="App\Models\Club">
                            <div class="col-sm-6 pd-2">
                                <h3>{{ $club->shortname }}</h3>
                                <h5>{{ $club->name }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-basketball-ball"></i>
                    </div>
                    @can('update-clubs')
                    <a href="{{ route('club.edit', ['language' => app()->getLocale(), 'club' => $club]) }}"
                        class="small-box-footer" dusk="btn-edit">
                        @lang('club.action.edit') <i class="fas fa-arrow-circle-right"></i>
                    </a>
                    @endcan
                    @can('create-clubs')
                    @if (count($games_home) == 0)
                        <a id="deleteClub" href="#" data-toggle="modal" data-target="#modalDeleteClub"
                            class="small-box-footer" dusk="btn-delete">
                            @lang('club.action.delete') <i class="fa fa-trash"></i>
                        </a>
                    @endif
                    @endcan
                </div>
            </div>
            <div class="col-sm ">
                <div class="info-box">
                    @if (count($leagues) == 0)
                        <span class="info-box-icon bg-danger"><i class="fas fa-trophy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('club.entitled.no')</span>
                        </div>
                    @elseif (count($leagues) == count($teams) )
                        <span class="info-box-icon bg-success"><i class="fas fa-trophy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('club.entitled.all')</span>
                        </div>
                    @else
                        <span class="info-box-icon bg-warning"><i class="fas fa-trophy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('club.entitled.some', [ 'entitled' =>
                                count($leagues), 'total' => count($teams)] )</span>
                        </div>
                    @endif
                </div>
                <div class="info-box">
                    @if (count($registered_teams) == 0)
                        <span class="info-box-icon bg-danger"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('team.registered.no')</span>
                        </div>
                    @elseif (count($registered_teams) == count($teams) )
                        <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('team.registered.all')</span>
                        </div>
                    @else
                        <span class="info-box-icon bg-warning"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('team.registered.some',
                                ['registered'=>count($registered_teams), 'total'=>count($teams)])</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-sm ">
                <div class="info-box">
                    @if (count($selected_teams) == 0)
                        <span class="info-box-icon bg-danger"><i class="fas fa-battery-empty"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('team.selected.no')</span>
                        </div>
                    @elseif (count($selected_teams) == count($teams) )
                        <span class="info-box-icon bg-success"><i class="fas fa-battery-full"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('team.selected.all')</span>
                        </div>
                    @else
                        <span class="info-box-icon bg-warning"><i class="fas fa-battery-half"></i></span>
                        <div class="info-box-content">
                            <span
                                class="info-box-text text-lg">@lang('team.selected.some',['selected'=>count($selected_teams),
                                'total'=>count($teams)])</span>
                        </div>
                    @endif
                </div>
                <div class="info-box">
                    @if ($games_home_notime == 0)
                        <span class="info-box-icon bg-success"><i class="far fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('club.game_notime.no')</span>
                        </div>
                    @elseif ($games_home_notime == count($games_home) )
                        <span class="info-box-icon bg-danger"><i class="far fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('club.game_notime.all')</span>
                        </div>
                    @else
                        <span class="info-box-icon bg-warning"><i class="far fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('club.game_notime.some', ['notime'=>$games_home_notime,
                                'total'=>count($games_home)])</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 pd-2">
                <!-- card MEMBERS -->
                <x-member-card :members="$members" :entity="$club" entity-class="App\Models\Club" />
                <!-- /.card -->
                <!-- card CLUB TEAM ASSIGNMENT -->
                <div class="card card-outline card-dark collapsed-card" id="teamsCard">
                    <div class="card-header">
                        <h4 class="card-title pt-2"><i class="fas fa-basketball-ball fa-lg"></i> {{ trans_choice('team.team', 2) }}
                            <span class="badge badge-pill badge-info">{{ count($teams) }}</span>
                        </h4>
                        <div class="card-tools">
                            @can('create-teams')
                            <a href="{{ route('club.team.create', ['language' => app()->getLocale(), 'club' => $club]) }}"
                            class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> @lang('team.action.create')</a>
                            @endcan
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body ">
                        <div class="row  justify-content-center">
                            <div class="col-md-8 ">
                                <div class="table-responsive-md">
                                    <table class="table table-hover table-sm " id="table2">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>@lang('Action')</th>
                                                <th scope="col">{{ trans_choice('team.team', 1) }}</th>
                                                <th scope="col">@lang('league.state.registered')</th>
                                                <th scope="col">@lang('league.state.selected')</th>
                                                <th scope="col">{{ trans_choice('league.league', 1) }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leagues as $l)
                                                @if (! $registered_teams->contains($l->id))
                                                <tr>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td class="text-danger">{{ $l->shortname }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                            @foreach ($teams as $team)
                                                <tr>
                                                    <td>
                                                    @if ( ! ( ($registered_teams->contains($team->league_id)) and ($team->league->state->in([ App\Enums\LeagueState::Selection(), App\Enums\LeagueState::Scheduling(), App\Enums\LeagueState::Freeze(), App\Enums\LeagueState::Live() ])) ) )
                                                        <button id="deleteTeam" data-team-id="{{ $team->id }}" data-league-sname="@if( isset($team->league->shortname) ){{ $team->league->shortname }}@else{{ __('team.unassigned')}} @endif"
                                                            data-team-no="{{ $team->team_no }}" data-club-sname="{{ $club->shortname }}" type="button"
                                                            class="btn btn-outline-danger btn-sm "  @cannot('create-teams') disabled @endcannot> <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                    </td>
                                                    <td>
                                                        @can('update-teams')
                                                        <a href="{{ route('team.edit', ['language' => app()->getLocale(), 'team' => $team->id]) }}">{{ $club->shortname }}{{ $team->team_no }}
                                                            <i class="fas fa-arrow-circle-right"></i>
                                                        </a>
                                                        @else
                                                        {{ $club->shortname }}{{ $team->team_no }}
                                                        @endcannot
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($registered_teams->contains($team->league_id))<i
                                                                class="far fa-check-circle text-success"></i> @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($selected_teams->contains($team->league_id))
                                                            <i class="far fa-check-circle text-success"></i>
                                                        @endif
                                                    </td>
                                                    @if($registered_teams->contains($team->league_id))
                                                        @if ($team->league->state->in([App\Enums\LeagueState::Registration(),App\Enums\LeagueState::Selection(),App\Enums\LeagueState::Assignment()]) )
                                                            <td><button id="deassignLeague" data-league-id="{{ $team->league['id'] }}"
                                                        data-team-id="{{ $team->id }}" data-club-id="{{ $club->id }}"
                                                        type="button" class="btn btn-outline-dark btn-sm "> <i
                                                            class="fas fa-unlink pr-2"></i> {{ $team->league['shortname'] }} <span
                                                                    class="badge badge-pill badge-dark pl-2">
                                                                    {{ $team->league_no  }}</span></button>
                                                            </td>
                                                        @else
                                                          <td><button type="button" class="btn btn-outline-dark btn-sm"
                                                                disabled>{{ $team->league['shortname'] }}
                                                                @isset( $team->league_no )<span class="badge badge-pill badge-dark pl-2">
                                                                    {{ $team->league_no  }}</span>
                                                                @endisset </button></td>
                                                        @endif
                                                    @else
                                                        <td>
                                                        @can('update-teams')
                                                        <button type="button" id="assignLeague" class="btn btn-outline-info btn-sm"
                                                            data-team-id="{{ $team->id }}" data-club-id="{{ $club->id }}"
                                                            data-toggle="modal" data-target="#modalAssignLeague"><i
                                                        class="fas fa-link pr-2"></i>@lang('league.action.register')</button>
                                                        @endcan
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="card-tools">
                            @can('update-teams')
                            @if ($club->leagues->where('state', App\Enums\LeagueState::Selection())->count() > 0)
                            <a href="{{ route('team.plan-leagues', ['language' => app()->getLocale(), 'club' => $club]) }}"
                                class="btn btn-primary float-right">
                                <i class="fas fa-map"></i> @lang('team.action.plan.season')</a>

                            <a href="{{ route('club.team.pickchar', ['language' => app()->getLocale(), 'club' => $club]) }}"
                                    class="btn btn-outline-primary float-right mr-2">
                                    <i class="fas fa-edit"></i> @lang('team.action.pickchars')</a>
                            @endif
                            @endcan
                        </div>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card CLUB TEAM ASSIGNMENT -->
            </div>
            <div class="col-sm-6">
                <!-- card GYMS -->
                <div class="card card-outline card-dark collapsed-card" id="gymsCard">
                    <div class="card-header">
                        <h4 class="card-title mt-2"><i class="fas fa-building fa-lg"></i> {{ trans_choice('gym.gym', 2) }} <span
                                class="badge badge-pill badge-info">{{ count($gyms) }}</span></h4>
                        <div class="card-tools">
                            @can('create-gyms')
                            <a href="{{ route('club.gym.create', ['language' => app()->getLocale(), 'club' => $club]) }}"
                                class="btn btn-success">
                                <i class="fas fa-plus-circle"></i> @lang('gym.action.create')
                            </a>
                            @endcan
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                        @foreach ($gyms as $gym)
                           <li class="list-group-item ">
                                <button type="button" id="deleteGym" class="btn btn-outline-danger btn-sm"
                                    @cannot('create-gyms') disabled @else @if ($gym->games()->exists()) disabled @endif @endcannot
                                    data-gym-id="{{ $gym->id }}"
                                    data-gym-name="{{ $gym->gym_no }} - {{ $gym->name }}"
                                    data-club-sname="{{ $club->shortname }}" data-toggle="modal"
                                    data-target="#modalDeleteGym"><i class="fa fa-trash"></i></button>
                                @can('update-gyms')
                                <a href="{{ route('gym.edit', ['language' => app()->getLocale(), 'gym' => $gym]) }}"
                                    class=" px-2">
                                    {{ $gym->gym_no }} - {{ $gym->name }} <i
                                        class="fas fa-arrow-circle-right"></i></a>
                                @else
                                    {{ $gym->gym_no }} - {{ $gym->name }}
                                @endcan
                                <a href="{{ config('dunkomatic.maps_uri') }}{{ urlencode($gym->address) }}"
                                    class=" px-4" target="_blank">
                                    <i class="fas fa-map-marked-alt"></i>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    <!-- /.card-body -->
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
                <!-- card GAMES -->
                <div class="card card-outline card-dark collapsed-card" id="gamesCard">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fas fa-trophy fa-lg"></i> @lang('game.home') <span
                                class="badge badge-pill badge-info">{{ count($games_home) }}</span></h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="list-group overflow-auto">
                            @foreach ($files as $f)
                                @php $fname=explode('/',$f); @endphp
                                <a href="{{ route('file.get', ['season' => $fname[1], 'region' => $fname[2], 'type' => $fname[3], 'file' => $fname[4]]) }}"
                                    class="list-group-item list-group-item-action list-group-item-info">
                                    {{ basename($f) }}</a>
                            @endforeach
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @can('update-games')
                        <a href="{{ route('club.list.homegame', ['language' => app()->getLocale(), 'club' => $club]) }}"
                            class="btn btn-primary float-right mr-2">
                            <i class="far fa-edit"></i> @lang('club.action.edit-homegame')</a>
                        @endcan
                        @can('view-games')
                        <a href="{{ route('club.game.chart', ['language' => app()->getLocale(), 'club' => $club]) }}"
                            class="btn btn-outline-primary float-right mr-2">
                            <i class="far fa-chart-bar"></i> @lang('club.action.chart-homegame')</a>
                        @endcan
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
                <!-- all modals here -->
                @include('club/includes/assign_league')
                <x-confirm-deletion modalId="modalDeleteClub" modalTitle="{{ __('club.title.delete') }}" modalConfirm="{{ __('club.confirm.delete') }}" deleteType="{{ trans_choice('club.club',1) }}" />
                <x-confirm-deletion modalId="modalDeleteMember" modalTitle="{{ __('role.title.delete') }}" modalConfirm="{{ __('role.confirm.delete') }}" deleteType="{{ __('role.member') }}" />
                <x-confirm-deletion modalId="modalDeleteGym" modalTitle="{{ __('gym.title.delete') }}" modalConfirm="{{ __('gym.confirm.delete') }}" deleteType="{{ trans_choice('gym.gym',1) }}" />
                <x-confirm-deletion modalId="modalDeleteTeam" modalTitle="{{ __('team.title.delete') }}" modalConfirm="{{ __('team.confirm.delete') }}" deleteType="{{ trans_choice('team.team',1) }}" />
                @include('member/includes/membership_add')
                @include('member/includes/membership_modify')
                <!-- all modals above -->
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function() {
            $("button#addMembership").click(function() {
                var url =
                    "{{ route('membership.club.add', ['club' => ':clubid:', 'member' => ':memberid:']) }}";
                url = url.replace(':memberid:', $(this).data('member-id'));
                url = url.replace(':clubid:', $(this).data('club-id'));
                $('#modalAddMembership_Form').attr('action', url);
                $('#modalAddMembership').modal('show');
            });
            $("button#modMembership").click(function() {
                var url = "{{ route('membership.update', ['membership' => ':membershipid:']) }}";
                url = url.replace(':membershipid:', $(this).data('membership-id'));
                var url2 = "{{ route('membership.destroy', ['membership' => ':membershipid:']) }}";
                url2 = url2.replace(':membershipid:', $(this).data('membership-id'));
                $('#hidDelUrl').val(url2);
                $('#modmemfunction').val($(this).data('function'));
                $('#modmememail').val($(this).data('email'));
                $('#modmemrole').val($(this).data('role'));
                $('#modalMembershipMod_Form').attr('action', url);
                $('#modalMembershipMod').modal('show');
            });
            $("button#assignLeague").click(function() {
                $('#team_id').val($(this).data('team-id'));
                $('#club_id').val($(this).data('club-id'));
                var url = "{{ route('team.assign-league') }}";
                $('#modalAssignLeague_Form').attr('action', url);
                $('#modalAssignLeague').modal('show');
            });
            $("button#deassignLeague").click(function() {
                var team_id = $(this).data('team-id');
                var club_id = $(this).data('club-id');
                var league_id = $(this).data('league-id');
                Pace.restart();
                Pace.track(function() {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('team.deassign-league') }}",
                        dataType: 'json',
                        data: {
                            club_id: club_id,
                            team_id: team_id,
                            league_id: league_id,
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            location.reload()
                        },
                    });
                });
            });
            $("button#deleteMember").click(function() {
                $('#modalDeleteMember_Instance').html($(this).data('member-name'));
                var url =
                    "{{ route('membership.club.destroy', ['club' => $club, 'member' => ':member:']) }}";
                url = url.replace(':member:', $(this).data('member-id'));
                $('#modalDeleteMember_Form').attr('action', url);
                $('#modalDeleteMember').modal('show');
            });
            $("button#deleteTeam").click(function() {
                if ($(this).data('league-sname') == ""){
                    $('#modalDeleteTeam_Info').html('{{ trans_choice('league.league',1) .' '. __('team.unassigned')  }}');
                } else {
                    $('#modalDeleteTeam_Info').html( '{{ trans_choice('league.league',1) .' ' }}' + $(this).data('league-sname'));
                }
                $('#modalDeleteTeam_Instance').html( $(this).data('club-sname') + $(this).data('team-no') );
                var url = "{{ route('team.destroy', ['team' => ':team:']) }}";
                url = url.replace(':team:', $(this).data('team-id'))
                $('#modalDeleteTeam_Form').attr('action', url);
                $('#modalDeleteTeam').modal('show');
            });
            $("button#deleteGym").click(function() {
                $('#modalDeleteGym_Instance').html($(this).data('gym-name'));
                var url = "{{ route('gym.destroy', ['gym' => ':gymid:']) }}";
                url = url.replace(':gymid:', $(this).data('gym-id'));
                $('#modalDeleteGym_Form').attr('action', url);
                $('#modalDeleteGym').modal('show');
            });
            $("#deleteClub").click(function() {
                $('#modalDeleteClub_Instance').html('{{ $club->name }}');
                $('#modalDeleteClub_Info').html('{{ __('club.info.delete',['club'=>$club->shortname,'noteam'=>count($teams),'nomember'=>count($members),'nogym'=>count($gyms)]) }}');
                var url = "{{ route('club.destroy', ['club' => $club]) }}";
                $('#modalDeleteClub_Form').attr('action', url);
                $('#modalDeleteClub').modal('show');
            });
        });
    </script>
@stop
