@extends('layouts.page')

@section('content_header')
    <div class="container-fluid">
        <div class="row ">
            <div class="col-sm">
                <!-- small card CLUB -->
                <div class="small-box bg-primary ">
                    @if ($club->inactive)
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-danger">@lang('Inactive')</div>
                    </div>
                    @endif
                    <div class="inner">
                        <div class="row">
                            <input type="hidden" id="entitytype" value="App\Models\Club">
                            <div class="col-sm-6 pd-2">
                                <h3>{{ $club->shortname }}</h3>
                                <h5>{{ $club->club_no }} - {{ $club->name }}</h5>
                                <div class="text-xs text-nowrap">{{ $club->audits()->exists() ?
                                     __('audit.last', [ 'audit_created_at' => Carbon\Carbon::parse($club->audits()->latest()->first()->created_at)->locale(app()->getLocale())->isoFormat('LLL'),
                                                        'user_name' => $club->audits()->latest()->first()->user->name ?? config('app.name') ] ) :
                                     __('audit.unavailable')  }}
                                </div>
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
                    @if ($club->filecount > 0)
                    <a href="{{ route('club_archive.get', ['club' => $club]) }}" class="small-box-footer bg-secondary">
                        @lang('club.action.download') <i class="fas fa-file-download"></i>
                    </a>
                    @endif
                </div>
            </div>
            <div class="col-sm ">
                <div class="info-box">
                    @if ($leagues == 0)
                        <span class="info-box-icon bg-danger"><i class="fas fa-trophy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('club.entitled.no')</span>
                        </div>
                    @elseif ($leagues == $teams )
                        <span class="info-box-icon bg-success"><i class="fas fa-trophy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('club.entitled.all')</span>
                        </div>
                    @else
                        <span class="info-box-icon bg-warning"><i class="fas fa-trophy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('club.entitled.some', [ 'entitled' =>
                                $leagues, 'total' => $teams] )</span>
                        </div>
                    @endif
                </div>
                <div class="info-box">
                    @if ($registered_teams == 0)
                        <span class="info-box-icon bg-danger"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('team.registered.no')</span>
                        </div>
                    @elseif ($registered_teams == $teams )
                        <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('team.registered.all')</span>
                        </div>
                    @else
                        <span class="info-box-icon bg-warning"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('team.registered.some',
                                ['registered'=>$registered_teams, 'total'=>$teams])</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-sm ">
                <div class="info-box">
                    @if ($selected_teams == 0)
                        <span class="info-box-icon bg-danger"><i class="fas fa-battery-empty"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('team.selected.no')</span>
                        </div>
                    @elseif ($selected_teams == $teams )
                        <span class="info-box-icon bg-success"><i class="fas fa-battery-full"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('team.selected.all')</span>
                        </div>
                    @else
                        <span class="info-box-icon bg-warning"><i class="fas fa-battery-half"></i></span>
                        <div class="info-box-content">
                            <span
                                class="info-box-text text-lg">@lang('team.selected.some',['selected'=>$selected_teams,
                                'total'=>$teams])</span>
                        </div>
                    @endif
                </div>
                <div class="info-box">
                    @if ( ($games_home_notime == 0) and (count($games_home)>0))
                        <span class="info-box-icon bg-success"><i class="far fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('club.game_notime.no')</span>
                        </div>
                    @elseif ( ($games_home_notime == count($games_home) ) and (count($games_home)>0))
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
    <div class="container-fluid ">
        <div class="row">
            <div class="col-sm-6 pd-2">
                <!-- card MEMBERS -->
                <x-member-card :members="$members" :entity="$club" entity-class="App\Models\Club" />
                <!-- /.card -->
            </div>
            <div class="col-sm-6">
                <!-- card GYMS -->
                <div class="card card-outline card-dark collapsed-card" id="gymsCard">
                    <x-card-header title="{{trans_choice('gym.gym', 2)}}" icon="fas fa-building"  :count="count($gyms)">
                            @can('create-gyms')
                            <a href="{{ route('club.gym.create', ['language' => app()->getLocale(), 'club' => $club]) }}"
                                class="btn btn-success btn-sm text-md">
                                <i class="fas fa-plus-circle"></i> @lang('gym.action.create')
                            </a>
                            @endcan
                    </x-card-header>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                        @foreach ($gyms as $gym)
                           <li class="list-group-item ">
                                <span data-toggle="tooltip" title="{{__('gym.action.delete')}}">
                                    <button type="button" id="deleteGym" class="btn btn-outline-danger btn-sm"
                                        @cannot('create-gyms') disabled @else @if (($gym->games()->exists()) or ($club->teams->load('gym')->where('gym_id',$gym->id)->count() >0 )) disabled @endif @endcannot
                                        data-gym-id="{{ $gym->id }}"
                                        data-gym-name="{{ $gym->gym_no }} - {{ $gym->name }}"
                                        data-club-sname="{{ $club->shortname }}" data-toggle="modal"
                                        data-target="#modalDeleteGym"><i class="fa fa-trash"></i></button>
                                </span>
                                @can('update-gyms')
                                    <span data-toggle="tooltip" title="{{__('gym.action.edit')}}">
                                        <a href="{{ route('gym.edit', ['language' => app()->getLocale(), 'gym' => $gym]) }}"
                                            class=" px-2">
                                            {{ $gym->gym_no }} - {{ $gym->name }} <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </span>
                                @else
                                    {{ $gym->gym_no }} - {{ $gym->name }}
                                @endcan
                                <span data-toggle="tooltip" title="{{__('gym.tooltip.map')}}">
                                    <a href="{{ config('dunkomatic.maps_uri') }}{{ urlencode($gym->address) }}"
                                        class=" px-4" target="_blank">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </a>
                                </span>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    <!-- /.card-body -->
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->


            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <!-- card CLUB TEAM ASSIGNMENT -->
                <div class="card card-outline card-dark collapsed-card" id="teamsCard">
                    <x-card-header title="{{trans_choice('team.team', 2)}}" icon="fas fa-basketball-ball"  :count="$teams">
                            @can('create-teams')
                            <a href="{{ route('club.team.create', ['language' => app()->getLocale(), 'club' => $club]) }}"
                            class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> @lang('team.action.create')</a>
                            @endcan
                    </x-card-header>
                    <div class="card-body">
                        <table width="100%" class="table table-hover table-bordered table-sm" id="teamtable">
                            <thead class="thead-light">
                                <tr>
                                    <th>@lang('Action')</th>
                                    <th scope="col">{{ trans_choice('team.team', 1) }}</th>
                                    <th scope="col">@lang('league.state.registered')</th>
                                    <th scope="col">@lang('league.state.selected')</th>
                                    <th scope="col">{{ trans_choice('league.league', 1) }}</th>
                                    <th scope="col">{{ trans_choice('team.shirtcolor', 1) }}</th>
                                    <th scope="col">{{ trans_choice('team.training', 1) }}</th>
                                    <th scope="col">{{Str::limit( trans_choice('team.game.preferred', 1),18) }}</th>
                                    <th scope="col">{{Str::limit( trans_choice('team.gym.preferred', 1),18) }}</th>
                                    <th scope="col">{{ Str::limit(trans_choice('team.coach', 1),10) }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="card-tools">
                            @can('update-teams')
                            @if ( ($club->leagues->where('state', App\Enums\LeagueState::Registration())->count() > 0) and
                                  ($club->teams->whereNotNull('league_id')->count() > 0  ))
                            <a href="{{ route('team.plan-leagues', ['language' => app()->getLocale(), 'club' => $club]) }}"
                                class="btn btn-primary float-right">
                                <i class="fas fa-map"></i> @lang('team.action.plan.season')</a>
                            @endif
                            @if ($club->leagues->where('state', App\Enums\LeagueState::Selection())->count() > 0)
                            <a href="{{ route('club.team.pickchar', ['language' => app()->getLocale(), 'club' => $club]) }}"
                                    class="btn btn-outline-primary float-right mr-2">
                                    <i class="fas fa-edit"></i> @lang('team.action.pickchars')</a>
                            @endif
                            @endcan
                        </div>
                    </div>
                    <!-- /.card-footer -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <!-- card GAMES -->
                <div class="card card-outline card-dark collapsed-card" id="gamesCard">
                    <x-card-header title="{{__('game.home')}}" icon="fas fa-trophy"  :count="count($games_home)" />
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="list-group overflow-auto">
                            @foreach ($files as $f)
                                @php $fname=explode('/',$f); @endphp
                                <a href="{{ route('file.get', [ 'type' => 'App\Models\Club', 'club' => $club, 'file' => $fname[4]]) }}"
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
                        <a href="{{ route('cal.club', ['language' => app()->getLocale(), 'club' => $club]) }}"
                            class="btn btn-outline-primary float-right mr-2">
                            <i class="fas fa-calendar-alt"></i> {{ __('reports.ical.club.all')}}</a>
                        <a href="{{ route('cal.club.home', ['language' => app()->getLocale(), 'club' => $club]) }}"
                            class="btn btn-outline-primary float-right mr-2">
                            <i class="fas fa-calendar-alt"></i> {{ __('reports.ical.club.home')}}</a>
                        <a href="{{ route('cal.club.referee', ['language' => app()->getLocale(), 'club' => $club]) }}"
                            class="btn btn-outline-primary float-right mr-2">
                            <i class="fas fa-calendar-alt"></i> {{ __('reports.ical.club.referee')}}</a>
                        <a href="{{ route('club.game.chart', ['language' => app()->getLocale(), 'club' => $club]) }}"
                            class="btn btn-outline-primary float-right mr-2">
                            <i class="far fa-chart-bar"></i> @lang('club.action.chart-homegame')</a>
                        @endcan
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
    <!-- all modals here -->
    @include('club/includes/assign_league')
    <x-confirm-deletion modalId="modalDeleteClub" modalTitle="{{ __('club.title.delete') }}" modalConfirm="{{ __('club.confirm.delete') }}" deleteType="{{ trans_choice('club.club',1) }}" />
    <x-confirm-deletion modalId="modalDeleteMember" modalTitle="{{ __('role.title.delete') }}" modalConfirm="{{ __('role.confirm.delete') }}" deleteType="{{ trans_choice('role.member', 1) }}" />
    <x-confirm-deletion modalId="modalDeleteGym" modalTitle="{{ __('gym.title.delete') }}" modalConfirm="{{ __('gym.confirm.delete') }}" deleteType="{{ trans_choice('gym.gym',1) }}" />
    <x-confirm-deletion modalId="modalDeleteTeam" modalTitle="{{ __('team.title.delete') }}" modalConfirm="{{ __('team.confirm.delete') }}" deleteType="{{ trans_choice('team.team',1) }}" />
    @include('member/includes/membership_add')
    @include('member/includes/membership_modify')
    <!-- all modals above -->
@stop

@section('js')
    <script>


        $(document).on("click", 'button#unregisterTeam', function(e) {
            var url = "{{ route('league.unregister.team', ['league' => ':league:', 'team' => ':team:']) }}"
            url = url.replace(':team:', $(this).data("team-id") );
            url = url.replace(':league:', $(this).data("league-id") );

            $.ajax({
                type: "DELETE",
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: 'DELETE'
                },
                url: url,
                success: function(data) {
                    toastr.success('{{__('team.unregister.ok')}}', '{{__('team.action.register')}}');
                },
                error: function(data) {
                    console.log('Error:', data);
                    toastr.error('{{__('team.unregister.nook')}}', '{{__('team.action.register')}}');
                }
            });
        });
        $(document).on("click", "button#deleteTeam", function(e) {
                if ($(this).data('league-sname') == ""){
                    $('#modalDeleteTeam_Info').html('{{ trans_choice('league.league',1) .'   '. __('team.unassigned')  }}');
                } else {
                    $('#modalDeleteTeam_Info').html( '{{ trans_choice('league.league',1) .' ' }}' + $(this).data('league-sname'));
                }
                $('#modalDeleteTeam_Instance').html( $(this).data('club-sname') + $(this).data('team-no') );
                var url = "{{ route('team.destroy', ['team' => ':team:']) }}";
                url = url.replace(':team:', $(this).data('team-id'))
                $('#modalDeleteTeam_Form').attr('action', url);
                $('#modalDeleteTeam').modal('show');
        });

        function registerTeam( league_id, team_id){
            var url = "{{ route('league.register.team', ['league' => ':league:', 'team' => ':team:']) }}"
            url = url.replace(':team:', team_id);
            url = url.replace(':league:', league_id);

            $.ajax({
                type: "PUT",
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: 'PUT'
                },
                url: url,
                success: function(data) {
                    toastr.success('{{__('team.register.ok')}}', '{{__('team.action.register')}}');
                },
                error: function(data) {
                    console.log('Error:', data);
                    toastr.error('{{__('team.register.notok')}}', '{{__('team.action.register')}}');
                }
            });
        };

        $(function() {
            var teamtable = $('#teamtable').DataTable({
                    processing: true,
                    serverSide: false,
                    responsive: true,
                    //scrollY: "200px",
                    scrollCollapse: true,
                    paging: false,
                    autoWidth: false,
                    language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                    ajax: '{{ route('club.team.dt', ['language'=>app()->getLocale(),'club'=>$club]) }}',
                    //order: [[ 2, 'asc' ],[ 0, 'asc' ]],
                    dom: 'rti',
                    columns: [
                        { data: 'action', name: 'action'},
                        { data: 'team', name: 'team'},
                        { data: 'registered', name: 'registered'},
                        { data: 'selected', name: 'selected', width: '5%'},
                        { data: {
                            _: 'league.sort',
                            display: 'league.display',
                            sort: 'league.sort'
                         }, name: 'league'},
                        {data: 'shirt_color', name: 'shirt_color'},
                        {data: 'training', name: 'training'},
                        {data: 'gameday', name: 'gameday'},
                        {data: 'gym', name: 'gym'},
                        {data: 'coach', name: 'coach'},
                        ]
            });
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
            $("button#deleteMember").click(function() {
                $('#modalDeleteMember_Instance').html($(this).data('member-name'));
                var url =
                    "{{ route('membership.club.destroy', ['club' => $club, 'member' => ':member:']) }}";
                url = url.replace(':member:', $(this).data('member-id'));
                $('#modalDeleteMember_Form').attr('action', url);
                $('#modalDeleteMember').modal('show');
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
                $('#modalDeleteClub_Info').html('{{ __('club.info.delete',['club'=>$club->shortname,'noteam'=>$teams,'nomember'=>count($members),'nogym'=>count($gyms)]) }}');
                var url = "{{ route('club.destroy', ['club' => $club]) }}";
                $('#modalDeleteClub_Form').attr('action', url);
                $('#modalDeleteClub').modal('show');
            });
                toastr.options.onHidden = function () {
                                window.location.reload();
                            };
        });
    </script>
@stop
