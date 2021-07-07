@extends('layouts.page')
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.Toastr', true)

@section('content_header')
    <div class="container-fluid">
        <div class="row ">
            <div class="col-sm ">
                <div class="small-box bg-gray">
                    <div class="inner">
                        <div class="row">
                            <input type="hidden" id="entitytype" value="App\Models\League">
                            <div class="col-sm-6 pd-2">
                                <h3>{{ $league->shortname }}</h3>
                                <h5>{{ $league->name }} </h5>
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <a href="{{ route('league.edit', ['language' => app()->getLocale(), 'league' => $league]) }}"
                        class="small-box-footer">
                        @lang('league.action.edit') <i class="fas fa-arrow-circle-right"></i>
                    </a>
                    @if (count($games) == 0)
                        <a id="deleteLeague" href="#" data-toggle="modal" data-target="#modalDeleteLeague"
                            class="small-box-footer" dusk="btn-delete">
                            @lang('league.action.delete') <i class="fa fa-trash"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-sm ">
                <div class="info-box">
                    @if ($league->isInState(App\Enums\LeagueState::Assignment()))
                        <span class="info-box-icon bg-info"><i class="fas fa-battery-empty"></i></span>
                    @elseif ($league->isInState(App\Enums\LeagueState::Registration()))
                        <span class="info-box-icon bg-info"><i class="fas fa-battery-quarter"></i></span>
                    @elseif ($league->isInState(App\Enums\LeagueState::Selection()))
                        <span class="info-box-icon bg-info"><i class="fas fa-battery-half"></i></span>
                    @elseif ($league->isInState(App\Enums\LeagueState::Scheduling()))
                        <span class="info-box-icon bg-info"><i class="fas fa-battery-three-quarters"></i></span>
                    @elseif ($league->isInState(App\Enums\LeagueState::Freeze()))
                        <span class="info-box-icon bg-warning"><i class="fas fa-battery-half"></i></span>
                    @elseif ($league->isInState(App\Enums\LeagueState::Live()))
                        <span class="info-box-icon bg-success"><i class="fas fa-battery-full"></i></span>
                    @endif
                    <div class="info-box-content">
                        <span class="info-box-text text-lg">Status</span>
                        <span class="info-box-number">{{ $league->state->description }}</span>
                    </div>
                </div>
                <div class="info-box">
                    @if (count($assigned_clubs) == 0)
                        <span class="info-box-icon bg-danger"><i class="fas fa-basketball-ball"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('club.entitled.no')</span>
                        @elseif (count($assigned_clubs) == $league->size )
                            <span class="info-box-icon bg-success"><i class="fas fa-basketball-ball"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-lg">@lang('club.entitled.all')</span>
                            @else
                                <span class="info-box-icon bg-warning"><i class="fas fa-basketball-ball"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-lg">@lang('club.entitled.some', [ 'entitled' =>
                                        count($assigned_clubs), 'total' => $league->size] )</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm ">
            <div class="info-box">
                @if ($league->state_count['registered'] == 0)
                    <span class="info-box-icon bg-danger"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-lg">@lang('team.registered.no')</span>
                    @elseif ($league->state_count['registered'] == $league->size )
                        <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('team.registered.all')</span>
                        @else
                            <span class="info-box-icon bg-warning"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-lg">@lang('team.registered.some',
                                    ['registered'=>$league->state_count['registered'],
                                    'total'=>$league->state_count['size']])</span>
                @endif
            </div>
        </div>
        <div class="info-box">
            @if (count($games) == 0)
                <span class="info-box-icon bg-danger"><i class="fas fa-running"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-lg">@lang('game.created.no')</span>
                @else
                    <span class="info-box-icon bg-success"><i class="fas fa-running"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-lg">@lang('game.created.some', ['total'=> count($games)])</span>
            @endif
        </div>
    </div>
    </div>

    </div>
    </div><!-- /.container-fluid -->
@stop


@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 pd-2">

                <!-- card CLUB TEAM ASSIGNMENT -->
                <div class="card card-outline card-dark " id="clubsCard">
                    <div class="card-header">
                        <h4 class="card-title"><i
                                class="fas fa-basketball-ball pr-2"></i>{{ $league->state_count['size'] }}
                            {{ trans_choice('team.team', 2) }}:
                            <span
                                class="badge badge-pill badge-info mr-2">{{ $league->state_count['assigned'] }}</span>@lang('league.state.assigned')
                            -
                            <span class="badge badge-pill badge-info mr-2">{{ $league->state_count['registered'] }}</span>
                            @lang('league.state.registered') -
                            <span
                                class="badge badge-pill badge-info mr-2">{{ $league->state_count['charspicked'] }}</span>@lang('league.state.selected')
                        </h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="col-md-6 m-3 ">
                                <h5 class="sub-header">@lang('league.action.close.assignment')</h5>
                                <div class="table-responsive-xs">
                                    <table class="table table-hover table-sm w-auto" id="table1">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">@lang('team.entitled')</th>
                                                <th scope="col">@lang('league.state.registered')</th>
                                                <th scope="col">@lang('league.state.selected')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 1; $i <= $league->size; $i++)
                                                <tr scope="row" >
                                                    @isset($assigned_clubs[$i])
                                                        <td scope="row" class="text-center"><button id="deassignClub"
                                                                data-id="{{ $assigned_clubs[$i]['club_id'] }}" type="button"
                                                                class="btn btn-success btn-sm" @if ($league->state > App\Enums\LeagueState::Assignment()) disabled @endif>
                                                                {{ $assigned_clubs[$i]['shortname'] }} </button>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($assigned_clubs[$i]['team_registered'])<i
                                                                    class="far fa-check-circle text-success"></i> @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($assigned_clubs[$i]['team_selected'])
                                                                <i class="far fa-check-circle text-success"></i>
                                                            @endif
                                                        </td>
                                                    @endisset
                                                    @empty($assigned_clubs[$i])
                                                        @if ($league->isNotInState(App\Enums\LeagueState::Live()))
                                                            <td><button type="button" id="assignClub"
                                                                    class="btn btn-outline-info btn-sm"
                                                                    data-itemid="{{ $i }}" data-toggle="modal"
                                                                    data-target="#modalAssignClub"><i class="fas fa-link"></i>
                                                                    @lang('league.action.assign')</button></td>
                                                        @else <td></td>
                                                        @endif
                                                        <td></td>
                                                        <td></td>
                                                    @endempty
                                                </tr>
                                            @endfor
                                            {{-- @endfor --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-3 m-3">
                                <h5 class="sub-header">@lang('league.action.close.registration')</h5>
                                <div class="table-responsive-xs">
                                    <table class="table table-hover table-sm w-auto" id="table2">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">{{ trans_choice('team.team', 1) }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 1; $i <= $league->size; $i++)
                                                <tr scope="row" class="h-10">
                                                    @isset($selected_teams[$i])
                                                        <td class="text-center">
                                                        <button type="button" class="btn btn-outline-dark btn-sm" id="withdrawTeam" @if ($league->state_count['registered'] == 0) disabled @endif>
                                                        <span class="badge badge-pill badge-dark">{{ $i }}</span>
                                                        {{ $selected_teams[$i]['shortname'] }} {{ $selected_teams[$i]['team_no'] }}</button>
                                                        </td>
                                                    @endisset
                                                    @empty($selected_teams[$i])
                                                        <td class="text-center">
                                                        <button  type="button" class="btn btn-outline-info btn-sm" id="injectTeam" @if ($league->state_count['registered'] == $league->size) disabled @endif>
                                                        <span class="badge badge-pill badge-info">{{ $i }}</span> ______
                                                        </button>
                                                        </td>
                                                    @endempty
                                                </tr>
                                            @endfor
                                            {{-- @endfor --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @if ($league->isInState(App\Enums\LeagueState::Assignment()))
                            <button type="button" class="btn btn-outline-primary" id="changeState"
                                data-action="{{ App\Enums\LeagueStateChange::CloseAssignment() }}"><i
                                    class="fas fa-lock"></i> Close Assignment
                            </button>
                        @endif
                        <button type="button" class="btn btn-outline-secondary" id="injectTeam" @if ($league->state_count['registered'] == $league->size) disabled @endif><i class="fa fa-trash"></i> @lang('game.action.team.add')
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="withdrawTeam" @if ($league->state_count['registered'] == 0) disabled @endif><i
                                class="fa fa-trash"></i> @lang('game.action.team.withdraw')
                        </button>

                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card CLUB TEAM ASSIGNMENT -->


            </div>

            <div class="col-sm-6">
                <!-- card MEMBERS -->
                <div class="card card-outline card-secondary collapsed-card">
                    <div class="card-header ">
                        <h4 class="card-title"><i class="fas fa-user-tie"></i> {{ trans_choice('role.member', 2) }} <span
                                class="badge badge-pill badge-info">{{ count($members) }}</span></h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach ($members as $member)
                                <li class="list-group-item ">
                                    <button type="button" id="deleteMember" class="btn btn-outline-danger btn-sm"
                                        data-member-id="{{ $member->id }}" data-member-name="{{ $member->name }}"
                                        data-league-sname="{{ $league->shortname }}" data-toggle="modal"
                                        data-target="#modalDeleteMember"><i class="fa fa-trash"></i></button>
                                    <a href="{{ route('member.edit', ['language' => app()->getLocale(), 'member' => $member]) }}"
                                        class=" px-2">{{ $member->name }} <i class="fas fa-arrow-circle-right"></i></a>
                                    @if (!$member->is_user)
                                        <a href="{{ route('member.invite', ['member' => $member]) }}" type="button"
                                            class="btn btn-outline-primary btn-sm"><i class="far fa-paper-plane"></i></a>
                                    @endif
                                    <button type="button" id="addMembership" class="btn btn-outline-primary btn-sm"
                                        data-member-id="{{ $member->id }}" data-league-id="{{ $league->id }}"
                                        data-toggle="modal" data-target="#modalLeagueMembershipAdd"><i
                                            class="fas fa-user-tag"></i></button>
                                    @foreach ($member['memberships'] as $membership)
                                        @if ($membership->membership_type == 'App\Models\League' and $membership->membership_id == $league->id)
                                            <button type="button" id="modMembership" class="btn btn-outline-primary btn-sm"
                                                data-membership-id="{{ $membership->id }}"
                                                data-function="{{ $membership->function }}"
                                                data-email="{{ $membership->email }}"
                                                data-role="{{ App\Enums\Role::getDescription($membership->role_id) }}"
                                                data-toggle="modal"
                                                data-target="#modalLeagueMembershipMod">{{ App\Enums\Role::getDescription($membership->role_id) }}</button>
                                        @else
                                            <span
                                                class="badge badge-secondary">{{ App\Enums\Role::getDescription($membership->role_id) }}</span>
                                        @endif
                                    @endforeach
                                </li>
                            @endforeach
                        </ul>


                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <a href="{{ route('membership.league.create', ['language' => app()->getLocale(), 'league' => $league]) }}"
                            class="btn btn-outline-secondary">
                            <i class="fas fa-plus-circle"></i> @lang('role.action.create')
                        </a>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
                <!-- card GAMES -->
                <div class="card card-outline card-secondary collapsed-card">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fas fa-trophy"></i> {{ trans_choice('game.game', 2) }} <span
                                class="badge badge-pill badge-info">{{ count($games) }}</span></h4>
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
                        <button type="button" class="btn btn-outline-secondary" id="deleteGames" @if ($league->isNotInState(App\Enums\LeagueState::Live())) disabled @endif><i class="fa fa-trash"></i> @lang('game.action.delete')
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="deleteNoshowGames" @if ($league->isNotInState(App\Enums\LeagueState::Scheduling())) disabled @endif><i class="fa fa-trash"></i> @lang('game.action.delete.noshow')
                        </button>
                        <a href="{{ route('league.game.index', ['language' => app()->getLocale(), 'league' => $league]) }}"
                            class="btn btn-primary">
                            <i class="far fa-edit"></i> @lang('league.action.game.list')</a>
                        <a href="{{ route('cal.league', ['language' => app()->getLocale(), 'league' => $league]) }}"
                            class="btn btn-secondary">
                            <i class="fas fa-calendar-alt"></i> iCAL</a>
                    </div>
                    {{-- <img class="card-img-bottom"
                        src="{{ asset('img/' . config('dunkomatic.grafics.league', 'oops.jpg')) }}" class="card-img"
                        alt="..."> --}}

                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
                <!-- all modals here -->
                @include('league/includes/assign_club')
                @include('league/includes/league_delete')
                @include('member/includes/member_delete')
                @include('league/includes/withdraw_team')
                @include('league/includes/inject_team')
                @include('member/includes/membership_add')
                @include('member/includes/membership_modify')
                <!-- all modals above -->
            </div>

        </div>
    @stop

    @section('js')
        <script>
            $(function() {

                toastr.options.closeButton = true;
                toastr.options.closeMethod = 'fadeOut';
                toastr.options.closeDuration = 300;
                toastr.options.closeEasing = 'swing';
                toastr.options.progressBar = true;

                $("button#deassignClub").click(function() {
                    var club_id = $(this).data("id");
                    var url =
                        "{{ route('league.deassign-club', ['league' => $league, 'club' => ':club:']) }}"
                    url = url.replace(':club:', club_id);

                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: {
                            id: club_id,
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        url: url,
                        success: function(data) {

                            toastr.options.onHidden = function() {
                                location.reload();
                                console.log('reload');
                            }

                            toastr.info('club deassigned', 'success');

                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });

                $("button#assignClub").click(function() {
                    var itemid = $(this).data("itemid");
                    $('#itemid').val($(this).data('itemid'));
                    $('#modalAssignClub').modal('show');
                });

                $("button#withdrawTeam").click(function() {
                    $('#modalWithdrawTeam').modal('show');
                });

                $("button#injectTeam").click(function() {
                    $('#modalInjectTeam').modal('show');
                });
                $("button#addMembership").click(function() {
                    var url =
                        "{{ route('membership.league.add', ['league' => ':leagueid:', 'member' => ':memberid:']) }}";
                    url = url.replace(':memberid:', $(this).data('member-id'));
                    url = url.replace(':leagueid:', $(this).data('league-id'));
                    $('#addClubMembership').attr('action', url);
                    $('#modalAddMembership').modal('show');
                });
                $("button#modMembership").click(function() {
                    var url = "{{ route('membership.update', ['membership' => ':membershipid:']) }}";
                    url = url.replace(':membershipid:', $(this).data('membership-id'));
                    var url2 = "{{ route('membership.destroy', ['membership' => ':membershipid:']) }}";
                    url2 = url2.replace(':membershipid:', $(this).data('membership-id'));
                    $('#modmemfunction').val($(this).data('function'));
                    $('#modmememail').val($(this).data('email'));
                    $('#modmemrole').val($(this).data('role'));
                    $('#frmModMembership').attr('action', url);
                    $('#hidDelUrl').val(url2);
                    $('#modalMembershipMod').modal('show');
                });

                $("button#deleteMember").click(function() {
                    $('#member_id').val($(this).data('member-id'));
                    $('#unit_type').html('{{ trans_choice('league.league', 1) }}');
                    $('#unit_shortname').html($(this).data('league-sname'));
                    $('#role_name').html($(this).data('role-name'));
                    $('#member_name').html($(this).data('member-name'));
                    var url =
                        "{{ route('membership.league.destroy', ['league' => $league, 'member' => ':member:']) }}";
                    url = url.replace(':member:', $(this).data('member-id'));
                    $('#confirmDeleteMember').attr('action', url);
                    $('#modalDeleteMember').modal('show');
                });

                $("button#changeState").click(function() {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {
                            _token: "{{ csrf_token() }}",
                            action: $(this).data('action')
                        },
                        url: "{{ route('league.state.change', ['league' => $league]) }}",
                        success: function(data) {
                            location.reload()
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });


                $("button#deleteGames").click(function() {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {
                            _method: "DELETE",
                            _token: "{{ csrf_token() }}"
                        },
                        url: "{{ route('league.game.destroy', ['league' => $league]) }}",
                        success: function(data) {
                            location.reload()
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });
                $("button#deleteNoshowGames").click(function() {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {
                            _method: "DELETE",
                            _token: "{{ csrf_token() }}"
                        },
                        url: "{{ route('league.game.destroy_noshow', ['league' => $league]) }}",
                        success: function(data) {
                            location.reload()
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });
                $("#deleteLeague").click(function() {
                    var url = "{{ route('league.destroy', ['league' => $league]) }}";
                    $('#confirmDeleteLeague').attr('action', url);
                    $('#modalDeleteLeague').modal('show');
                });
            });
        </script>
    @stop
