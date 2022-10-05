@extends('layouts.page')

@section('content_header')
    <div class="container-fluid">
        <div class="row ">
            <div class="col-sm ">
                <!-- small card LEAGUE -->
                <div class="small-box bg-primary">
                    @isset($league->schedule)
                        @if ($league->schedule->custom_events)
                        <div class="ribbon-wrapper ribbon-xl">
                            <div class="ribbon bg-danger">@lang('Custom')</div>
                        </div>
                        @else
                        <div class="ribbon-wrapper ribbon-xl">
                            <div class="ribbon bg-light">{{ $league->schedule->name }}</div>
                        </div>
                        @endif
                        @endisset
                        @empty($league->schedule)
                        <div class="ribbon-wrapper ribbon-xl">
                            <div class="ribbon bg-danger">{{ __('Undefined') }}</div>
                        </div>
                        @endempty
                    <div class="inner">
                        <div class="row">
                            <input type="hidden" id="entitytype" value="App\Models\League">
                            <div class="col-sm-6 pd-2">
                                <h3>{{ $league->shortname }}</h3>
                                <h5>{{ $league->name }} </h5>
                                <div class="text-xs text-nowrap">{{ $league->audits()->exists() ?
                                    __('audit.last', [ 'audit_created_at' => Carbon\Carbon::parse($league->audits()->latest()->first()->created_at)->locale(app()->getLocale())->isoFormat('LLL'),
                                                       'user_name' => $league->audits()->latest()->first()->user->name ?? config('app.name')] ) :
                                    __('audit.unavailable')  }}
                               </div>
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    @can('update-leagues')
                    @can('access',$league->region)
                    <a href="{{ route('league.edit', ['language' => app()->getLocale(), 'league' => $league]) }}"
                        class="small-box-footer">
                        @lang('league.action.edit') <i class="fas fa-arrow-circle-right"></i>
                    </a>
                    @endcan
                    @endcan
                    @can('create-leagues')
                    @can('access',$league->region)
                    @if (count($games) == 0)
                        <a id="deleteLeague" href="#" data-toggle="modal" data-target="#modalDeleteLeague"
                            class="small-box-footer" dusk="btn-delete">
                            @lang('league.action.delete') <i class="fa fa-trash"></i>
                        </a>
                    @endif
                    @endcan
                    @endcan
                    <a href="{{ route('league.briefing', ['language'=>app()->getLocale(), 'league'=>$league]) }}"  class="small-box-footer">{{__('league.action.contacts')}}
                        <i class="fas fa-address-book"></i></a>
                    <a href="#" data-toggle="modal"  class="small-box-footer" data-target="#modalDownloadZone">{{__('reports.action.downloads')}}
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-sm ">
                <div class="info-box">
                    <x-league-status :league="$league" mode="infobox-icon"/>
                    <div class="info-box-content">
                        <span class="info-box-text text-lg">{{__('league.state')}}</span>
                        <span class="info-box-number">{{ $league->state->description }}</span>
                        <x-league-status :league="$league" />
                    </div>
                </div>
                <div class="info-box">
                    @if ($league->state_count['assigned'] == 0)
                        <span class="info-box-icon bg-danger"><i class="fas fa-basketball-ball"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">@lang('club.entitled.no')</span>
                        @elseif ($league->state_count['assigned'] == $league->state_count['size'] )
                            <span class="info-box-icon bg-success"><i class="fas fa-basketball-ball"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-lg">@lang('club.entitled.all')</span>
                            @else
                                <span class="info-box-icon bg-warning"><i class="fas fa-basketball-ball"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-md">@lang('club.entitled.some', [ 'entitled' =>
                                        $league->state_count['assigned'], 'total' => $league->state_count['size']] )</span>
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
                    </div>
                @elseif ($league->state_count['registered'] == $league->state_count['size'] )
                    <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-lg">@lang('team.registered.all')</span>
                    </div>
                @else
                    <span class="info-box-icon bg-warning"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-md">@lang('team.registered.some',
                            ['registered'=>$league->state_count['registered'],
                            'total'=>$league->state_count['assigned']])</span>
                    </div>
                @endif
            </div>
            <div class="info-box">
                @if (count($games) == 0)
                    <span class="info-box-icon bg-danger"><i class="fas fa-running"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-lg">@lang('game.created.no')</span>
                    </div>
                @else
                    <span class="info-box-icon bg-success"><i class="fas fa-running"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-lg">@lang('game.created.some', ['total'=> count($games)])</span>
                    </div>
                @endif
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
                    <x-card-header title="{{trans_choice('team.team', 2)}}" icon="fas fa-basketball-ball"  :count="$league->state_count['size']" />
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table width="100%" class="table table-hover table-bordered table-sm" id="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">@lang('league.state.assigned')</th>
                                    <th scope="col">@lang('league.state.registered')</th>
                                    <th scope="col">@lang('league.state.selected')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <!-- card MEMBERS -->
                <x-member-card :members="$members" :entity="$league" entity-class="App\Models\League" />
                <!-- /.card -->
                <!-- card GAMES -->
                <div class="card card-outline card-dark collapsed-card" id="gamesCard">
                    <x-card-header title="{{trans_choice('game.game', 2)}}" icon="fas fa-trophy"  :count="count($games)" />
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="list-group overflow-auto">
                            @foreach ($files as $f)
                            @if (pathinfo($f)['extension']=='html')
                                @php $fname=explode('/',$f); @endphp
                                <a target="_blank" rel="noopener noreferrer" href="{{ route('file.get', [ 'type' => 'App\Models\League', 'league' => $league->id, 'file' => $fname[4]]) }}"
                                    class="list-group-item list-group-item-action list-group-item-info">
                                    {{ basename($f) }}</a>
                            @endif
                            @endforeach
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="card-tools">
                            @can('view-games')
                            <a href="{{ route('league.game.index', ['language' => app()->getLocale(), 'league' => $league]) }}"
                                class="btn btn-primary float-right">
                                <i class="far fa-edit"></i> @lang('league.action.game.list')</a>
                            <a href="{{ route('cal.league', ['language' => app()->getLocale(), 'league' => $league]) }}"
                                class="btn btn-outline-primary float-right mr-2">
                                <i class="fas fa-calendar-alt"></i> {{ __('reports.ical.league')}}</a>
                            @endcan
                        </div>
                    </div>
                    {{-- <img class="card-img-bottom"
                        src="{{ asset('img/' . config('dunkomatic.grafics.league', 'oops.jpg')) }}" class="card-img"
                        alt="..."> --}}

                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
                <!-- all modals here -->
                @include('league/includes/assign_club')
                <x-confirm-deletion modalId="modalDeleteLeague" modalTitle="{{ __('league.title.delete') }}" modalConfirm="{{ __('league.confirm.delete') }}" deleteType="{{ trans_choice('league.league',1) }}" />
                <x-confirm-deletion modalId="modalDeleteMember" modalTitle="{{ __('role.title.delete') }}" modalConfirm="{{ __('role.confirm.delete') }}" deleteType="{{ trans_choice('role.member', 1) }}" />
                @include('league/includes/inject_team')
                @include('reports/includes/download_zone')
                <!-- all modals above -->
            </div>

        </div>
    </div>
@stop

@section('js')
    <script>
        function registerTeam( team_id){
            console.log('team is:'+team_id);
            var url = "{{ route('league.register.team', ['league' => $league, 'team' => ':team:']) }}"
            url = url.replace(':team:', team_id);

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
        $(document).on("click", 'button#unregisterTeam', function(e) {
            var team_id = $(this).data("team-id");
            var url = "{{ route('league.unregister.team', ['league' => $league, 'team' => ':team:']) }}"
            url = url.replace(':team:', team_id);

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
                    toastr.error('{{__('team.unregister.notok')}}', '{{__('team.action.register')}}');
                }
            });
        });
        $(document).on("click", 'button#assignClub', function(e) {
            $('#modalAssignClub_region_id').val($(this).data('region-id'));
            //$('#modalAssignClub_region').html($(this).data('region-code'));
            var url = "{{ route('league.assign-clubs', ['league'=>$league->id]) }}";
            $('#modalAssignClub_Form').attr('action', url);
            $('#modalAssignClub').modal('show');
        });
        $(document).on("click", 'button#deassignClub', function(e) {
            var club_id = $(this).data("id");
            var url = "{{ route('league.deassign-club', ['league' => $league, 'club' => ':club:']) }}"
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
                    toastr.success('{{__('club.deassign.ok')}}', '{{__('club.action.register')}}');
                },
                error: function(data) {
                    toastr.error('{{__('club.deassign.notok')}}', '{{__('club.action.register')}}');
                    console.log('Error:', data);
                }
            });
        });
        $(document).on("click", "button#injectTeam", function(e) {
            var url = "{{ route('league.team.inject', ['league'=>$league->id]) }}";
            $('#modalInjectTeam_Form').attr('action', url);

            var urlsb1 = "{{ route('league.sb_freechar', ['league'=>$league]) }}";
            $('#modalInjectTeam').attr( "data-urlsb1",  urlsb1);

            var urlsb2 = "{{ route('team.free.sb', ['league'=>$league]) }}";
            $('#modalInjectTeam').attr( "data-urlsb2",  urlsb2);

            $('#modalInjectTeam').modal('show');
        });
        $(document).on("click", "button#releaseChar", function(e) {
            var team_id = $(this).data("team-id");
            var league_no = $(this).data("league-no");
            var url = "{{ route('league.team.releasechar', ['league' => $league]) }}"

            $.ajax( {
                url: url,
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    league_no: league_no,
                    team_id: team_id,
                },
                type: "post",
                delay: 250,
                success: function (response) {
                    var msg = '{{__('club.pickchar.taken.own')}}';
                    msg = msg.replace('xleague_nox', league_no);
                    toastr.success(msg, '{{__('team.action.pickchars')}}');
                    console.log('reloading ...');
                },
                error: function (xhr){
                    toastr.error(xhr.responseText, '{{__('team.action.pickchars')}}');
                },
                cache: false
            });
        })
        $(document).on("click", "button#pickChar", function(e) {
            var team_id = $(this).data("team-id");
            var league_no = $(this).data("league-no");
            var url = "{{ route('league.team.pickchar', ['league' => $league]) }}"

            $.ajax( {
                url: url,
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    league_no: league_no,
                    team_id: team_id,
                },
                type: "post",
                delay: 250,
                success: function (response) {
                    var msg = '{{__('club.pickchar.book')}}';
                    msg = msg.replace('xleague_nox', league_no);
                    toastr.success(msg, '{{__('team.action.pickchars')}}');
                    console.log('reloading ...');
                },
                error: function (xhr){
                    toastr.error(xhr.responseText, '{{__('team.action.pickchars')}}');
                },
                cache: false
            });
        });

        $(function() {

            var teamtable = $('#table').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                //scrollY: "200px",
                scrollCollapse: true,
                paging: false,
                language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                ajax: '{{ route('league.team.dt', ['language'=>app()->getLocale(),'league'=>$league]) }}',
                order: [[ 2, 'asc' ],[ 0, 'asc' ]],
                dom: 'rti',
                columns: [
                    { data: {
                        _: 'club_shortname.sort',
                        display: 'club_shortname.display',
                        sort: 'club_shortname.sort'
                        }, name: 'shortname'},
                    { data: 'team_name', name: 'team_name'},
                    { data: {
                        _: 'team_league_no.sort',
                        display: 'team_league_no.display',
                        sort: 'team_league_no.sort'
                        }, name: 'team_league_no'}
                    ]
            });
            $("button#deleteMember").click(function() {
                $('#modalDeleteMember_Instance').html($(this).data('member-name'));
                var url =
                    "{{ route('membership.league.destroy', ['league' => $league, 'member' => ':member:']) }}";
                url = url.replace(':member:', $(this).data('member-id'));
                $('#modalDeleteMember_Form').attr('action', url);
                $('#modalDeleteMember').modal('show');
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
                        toastr.success('{{__('game.deleted')}}', '{{__('game.action.delete')}}');
                    },
                    error: function(data) {
                        toastr.error(data.responseText, '{{__('game.action.delete')}}');
                        console.log('Error:', data);
                    }
                });
            });

            $("#deleteLeague").click(function() {
                $('#modalDeleteLeague_Info').html('{{ __('league.info.delete',['league'=>$league->shortname,'noteam'=>$league->state_count['registered'],'nomember'=>count($members)])  }}');
                $('#modalDeleteLeague_Instance').html( '{{ $league->name }}' );

                var url = "{{ route('league.destroy', ['league' => $league]) }}";
                $('#modalDeleteLeague_Form').attr('action', url);
                $('#modalDeleteLeague').modal('show');
            });

            toastr.options.onHidden = function () {
                            window.location.reload();
            };
        });
    </script>
@stop
