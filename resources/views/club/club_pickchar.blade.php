@extends('layouts.page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-dark" id="teamsCard">
                <x-card-header title="{{__('club.title.pickchars')}}" icon="fas fa-vote-yea fa-lg"  count="{{$team_open_cnt.' / '.$team_total_cnt}}">
                </x-card-header>
                <div class="card-body">
                    <table width="100%" class="table table-hover table-bordered table-sm" id="teamtable">
                        <thead class="thead-light">
                            <tr>
                                <th hidden>league_id</th>
                                <th hidden>team_id</th>
                                <th>{{trans_choice('league.league',1 )}}</th>
                                <th>{{trans_choice('team.team',1 )}}</th>
                                <th>1-A</th>
                                <th>2-B</th>
                                <th>3-C</th>
                                <th>4-D</th>
                                <th>5-E</th>
                                <th>6-F</th>
                                <th>7-G</th>
                                <th>8-H</th>
                                <th>9-I</th>
                                <th>10-K</th>
                                <th>11-L</th>
                                <th>12-M</th>
                                <th>13-N</th>
                                <th>14-O</th>
                                <th>15-P</th>
                                <th>16-Q</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="container">
                        <div class="text-sm" id="notification"></div>
                    </div>
                </div>
                <!-- /.card-footer -->
            </div>
        </div>

        <!-- /.card -->
    </div>
    <div class="row">
        @include('team/includes/teamleague_chart')
    </div>
</div>
@stop


@section('js')

<script>

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
                    ajax: '{{ route('club.league_char.dt', ['language'=>app()->getLocale(),'club'=>$club]) }}',
                    //order: [[ 2, 'asc' ],[ 0, 'asc' ]],
                    dom: 'rti',
                    columns: [
                        { data: 'league.id', name: 'league_id', visible: false},
                        { data: 'id', name: 'team_id', visible: false},
                        { data: 'league.shortname', name: 'league_shortname'},
                        { data: 'team_no', name: 'team_no'},
                        { data: 'char_A', name: 'char_A'},
                        { data: 'char_B', name: 'char_B'},
                        { data: 'char_C', name: 'char_C'},
                        { data: 'char_D', name: 'char_D'},
                        { data: 'char_E', name: 'char_E'},
                        { data: 'char_F', name: 'char_F'},
                        { data: 'char_G', name: 'char_G'},
                        { data: 'char_H', name: 'char_H'},
                        { data: 'char_I', name: 'char_I'},
                        { data: 'char_K', name: 'char_K'},
                        { data: 'char_L', name: 'char_L'},
                        { data: 'char_M', name: 'char_M'},
                        { data: 'char_N', name: 'char_N'},
                        { data: 'char_O', name: 'char_O'},
                        { data: 'char_P', name: 'char_P'},
                        { data: 'char_Q', name: 'char_Q'},
                        ]
    });
    moment.locale('{{ app()->getLocale() }}');
    window.Echo.channel('user-leagues')
            .listen('.LeagueCharPickEvent', (data) => {
                teamtable.data().each( function (d) {
                    if (d.league.id == data.league.id){
                        console.log('yes i got this league '+d.league.shortname+', refreshing...');
                        teamtable.ajax.reload();
                        var utime = moment(data.updated_at).format('LTS');
                        $("#notification").append('<div class="alert alert-'+data.ccode+'">'+utime+'   '+data.action+'</div>');
                    }
                });
    });

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          datasets: [{
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            label: 'series1',

          }]
        },
        options: {
            scales: {
                yAxes: [{
                          ticks: {
                              beginAtZero:true,
                              precision: 0
                          },
                          scaleLabel: {
                            display: true,
                            labelString: '@lang('team.game.perday.games')'
                          }
                      }],
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: 'week'
                    },
                    distribution: 'linear',
                    ticks: {
                      maxTicksLimit: 30
                    }
                }]
            }
        }
    });

    function refreshChart(){
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      var selData = {};
      selData['club_id'] = {!! json_encode($club->id) !!};
      selData['_token'] = "{{ csrf_token() }}";
/*       teamtable.rows().every( function (  rowIdx, tableLoop, rowLoop ) {
            var d = this.data();
            selData['selSize:'+ d.league.id  +':'+ d.id ] = d.league_no;
      });
      console.log(selData); */
      var data = JSON.stringify(selData);
      $.ajax({
          type: 'POST',
          url: '{{ route('team.list-chart', app()->getLocale()) }}',
          data: data,
          dataType: 'json',
          contentType: "application/json",
          success: function(response) {
            var chartdata = response.map(function(elem) {
              return {
                t: elem.gamedate,
                y: elem.homegames
              };
            });
            myChart.data.datasets.forEach((dataset) => {
              dataset.data = chartdata;
            });
            myChart.update();
          },
      });
    }

    $('#teamtable tbody').on( 'click', 'td', function () {
        var val = teamtable.cell( this ).data();
        var row = teamtable.cell( this ).index().row;
        var league_no = teamtable.cell( this ).index().columnVisible - 1;
        var team_id = teamtable.row( this ).data().id;
        var league_id = teamtable.row( this ).data().league.id;
        // alert( teamtable.cell( this ).data() );
        if (val.includes('fa-frown')){
            var msg =  "{{  __('club.pickchar.taken.other') }}";
            msg = msg.replace('xleague_nox', league_no);
            alert(msg);
        } else if (val.includes('fa-dot-circle')){
            var msg =  "{{ __('club.pickchar.taken.own') }}";
            msg = msg.replace('xleague_nox', league_no);
            alert(msg);
            var url = "{{ route('league.team.releasechar', ['league'=>':league:'])}}";
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
                    teamtable.ajax.reload();
                },
                cache: false
            });
        } else if (val.includes('fa-times-circle')){
            var msg =  "{{ __('club.pickchar.not.avail') }}";
            msg = msg.replace('xleague_nox', league_no);
            alert(msg);
        } else if ((league_no > 0) && (league_no < 17)) {
            var msg = "{{__('club.pickchar.book')}}";
            msg = msg.replace('xleague_nox', league_no);
            alert( msg );
            var url = "{{ route('league.team.pickchar', ['league'=>':league:'])}}";
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
                    teamtable.ajax.reload();
                },
                cache: false
            });
        }
    } );
    $('#teamtable').on('xhr.dt', function ( e, settings, json, xhr ) {
        refreshChart();
    });
  });


</script>
@stop
