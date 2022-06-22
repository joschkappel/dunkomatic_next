@extends('layouts.page')
@section('css')
<style>
th, td { white-space: nowrap; }
</style>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">@lang('league.title.management')</h3>
                    <div class="card-tools">
                        @if (Auth::user()->isAn('regionadmin','superadmin'))
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger">all to previous state</button>
                            <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                @foreach ($states as $s)
                                @if ($s->is( App\Enums\LeagueState::Selection))
                                    <button id="btnStateChange" class="dropdown-item btn btn-info"><i class="fas  fa-list-ol pr-2"></i> nach <i class="fas fa-file-signature"></i></button>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger">all to next state</button>
                            <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                @foreach ($states as $s)
                                @if ($s->is( App\Enums\LeagueState::Selection))
                                    <button id="btnStateChange" class="dropdown-item btn btn-info" data-from-state="{{App\Enums\LeagueState::Selection}}" data-action="{{App\Enums\LeagueStateChange::FreezeLeague}}"><i class="fas  fa-list-ol pr-2"></i> nach <i class="fas fa-pause-circle"></i></button>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                        <span>
                            @can('create-leagues')
                            <a href="{{ route('league.create', ['language'=>app()->getLocale(), 'region'=>$region]) }}" class="btn btn-success"><i
                                class="fas fa-plus-circle pr-2"></i>@lang('league.action.create')</a>
                            @endcan
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <table width="100%" class="table table-hover table-bordered table-sm" id="tblAssignClubs">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                @if ($region->is_base_level)
                                    <th>{{ trans_choice('region.region',1) }}</th>
                                @endif
                                <th>@lang('league.shortname')</th>
                                <th>{{ Str::limit(__('league.state'),2,'.')}}</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                                <th>6</th>
                                <th>7</th>
                                <th>8</th>
                                <th>9</th>
                                <th>10</th>
                                <th>11</th>
                                <th>12</th>
                                <th>13</th>
                                <th>14</th>
                                <th>15</th>
                                <th>16</th>
                                <th>@lang('league.next.state')</th>
                                <th>@lang('league.prev.state')</th>
                                <th>@lang('league.agetype')</th>
                                <th>@lang('league.gendertype')</th>
                                <th>@lang('league.size')</th>
                                <th>{{__('Total Games')}}</th>
                                <th>{{__('Games No Time')}}</th>
                                <th>{{__('Games No Teams')}}</th>

                            </tr>
                        </thead>
                    </table>


                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                </div>
                <!-- /.card-footer -->
            </div>
            @include('league/includes/assign_club')
            @include('league/includes/register_team')
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function() {

            $('#tblAssignClubs').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                retrieve: true,
                stateSave: true,
                pageLength: {{ config('dunkomatic.table_page_length', 50)}},
                language: { url: "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}",
                            },
                language: { processing: '<p>{{__('Loading')}}</p><div class="dot-rolling px-2"></div>'},
                order: [
                    [1, 'asc']
                ],
                ajax: '{{ route('league.list_mgmt', ['language'=> app()->getLocale(),'region' => $region]) }}',
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false
                    },
                    @if ($region->is_base_level)
                        { data: 'alien_region', name: 'alien_region'},
                    @endif
                    { data:  {
                        _: 'shortname.sort',
                        filter: 'shortname.sort',
                        display: 'shortname.display',
                        sort: 'shortname.sort'
                    }, name: 'shortname.sort' },
                    { data: 'state', name: 'state', width: '1%'},
                    { data: 't1', name: 't1', width: '1%'},
                    { data: 't2', name: 't2', width: '1%'},
                    { data: 't3', name: 't3', width: '1%'},
                    { data: 't4', name: 't4', width: '1%'},
                    { data: 't5', name: 't5', width: '1%'},
                    { data: 't6', name: 't6', width: '1%'},
                    { data: 't7', name: 't7', width: '1%'},
                    { data: 't8', name: 't8', width: '1%'},
                    { data: 't9', name: 't9', width: '1%'},
                    { data: 't10', name: 't10', width: '1%'},
                    { data: 't11', name: 't11', width: '1%'},
                    { data: 't12', name: 't12', width: '1%'},
                    { data: 't13', name: 't13', width: '1%'},
                    { data: 't14', name: 't14', width: '1%'},
                    { data: 't15', name: 't15', width: '1%'},
                    { data: 't16', name: 't16', width: '1%'},
                    { data: 'nextaction', name: 'nextaction'},
                    { data: 'rollbackaction', name: 'rollbackaction'},
                    { data: {
                     _: 'age_type.display',
                     filter: 'age_type.display',
                     display: 'age_type.display',
                     sort: 'age_type.sort'
                     }, name: 'age_type.display'
                  },
                   { data: {
                     _: 'gender_type.display',
                     filter: 'gender_type.display',
                     display: 'gender_type.display',
                     sort: 'gender_type.sort'
                     }, name: 'gender_type.display'
                  },
                  { data: {
                     _: 'size.sort',
                     filter: 'size.sort',
                     display: 'size.display',
                     sort: 'size.sort'
                     }, name: 'size.sort', width: '2%'},
                  { data: 'games_count', name: 'games_count', width: '2%'},
                  { data: 'games_notime_count', name: 'games_notime_count', width: '2%'},
                  { data: 'games_noshow_count', name: 'games_noshow_count', width: '2%'},
                ],
                columnDefs: [ {
                    targets: '_all',
                    createdCell: function (td, cellData, rowData, row, col) {
                        if ( cellData == 'X' ) {
                            $(td).css('background-color', '#e6e9ec');
                            $(td).css('color', '#e6e9ec');
                        }
                    }
                } ],

            });

            toastr.options.onHidden = function () {
                            window.location.reload();
            };

            $(document).on('click', 'button#changeState', function() {
                var leagueid = $(this).data("league");
                var action = $(this).data('action');
                var url = "{{ route('league.state.change', ['league' => ':leagueid:']) }}";
                url = url.replace(':leagueid:', leagueid);

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        action: action
                    },
                    url: url,
                    success: function(data) {
                        location.reload()
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });

            $(document).on("click", 'button#deassignClub', function(e) {
                var league_id = $(this).data("league-id");
                var club_id = $(this).data("club-id");
                var url = "{{ route('league.deassign-club', ['league' => ':league:', 'club' => ':club:']) }}"
                url = url.replace(':club:', club_id);
                url = url.replace(':league:', league_id);

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

            $(document).on("click", 'button#assignClub', function(e) {
                $('#modalAssignClub_region_id').val($(this).data('region-id'));
                var league_id = $(this).data("league-id");
                var url = "{{ route('league.assign-clubs', ['league'=>':league:']) }}";
                url = url.replace(':league:', league_id);

                $('#modalAssignClub_Form').attr('action', url);
                $('#modalAssignClub').modal('show');
            });

            $(document).on("click", "button#registerTeam", function(e) {
                var league_id = $(this).data("league-id");
                var club_id = $(this).data("club-id");
                var url = "{{ route('league.register.team', ['league'=>':league:']) }}";
                url = url.replace(':league:', league_id);

                var urlsb2 = "{{ route('club.team.free.sb', ['club'=>':club:']) }}";
                urlsb2 = urlsb2.replace(':club:', club_id);

                $('#modalRegisterTeam').attr( "data-urlsb2",  urlsb2);
                $('#modalRegisterTeam_Form').attr('action', url);
                $('#modalRegisterTeam').modal('show');
            });

            $(document).on("click", 'button#unregisterTeam', function(e) {
                var team_id = $(this).data("team-id");
                var league_id = $(this).data("league-id");
                var url = "{{ route('league.unregister.team', ['league' => ':league:', 'team' => ':team:']) }}"
                url = url.replace(':team:', team_id);
                url = url.replace(':league:', league_id);

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

            $(document).on("click", "button#pickChar", function(e) {
                var team_id = $(this).data("team-id");
                var league_no = $(this).data("league-no");
                var league_id = $(this).data("league-id");
                var url = "{{ route('league.team.pickchar', ['league' => ':league:']) }}"
                url = url.replace(':league:', league_id);

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
            })

            $(document).on("click", "button#releaseChar", function(e) {
                var team_id = $(this).data("team-id");
                var league_no = $(this).data("league-no");
                var league_id = $(this).data("league-id");
                var url = "{{ route('league.team.releasechar', ['league' => ':league:']) }}";
                url = url.replace(':league:', league_id);

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

            $(document).on("click", "button#btnStateChange", function (e) {
                var from_state = $(this).data("from-state");
                var action = $(this).data("action");
                var url = "{{ route('region.league.state.change', ['region' => $region]) }}";

                $.ajax( {
                    url: url,
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        action: action,
                        from_state: from_state,
                    },
                    type: "post",
                    delay: 250,
                    success: function (response) {
                        toastr.success(msg, '{{__('team.action.pickchars')}}');
                    },
                    error: function (xhr){
                        toastr.error(xhr.responseText, '{{__('team.action.pickchars')}}');
                    },
                    cache: false
                });
            })
        });
    </script>
@stop
