@extends('layouts.page')

@section('plugins.Pace', true)
@section('plugins.Chartjs', true)
@section('plugins.Datatables', true)

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8">

        <!-- card TEAMS -->
        <div class="card card-info">
          <div class="card-header">
            <h4 class="card-title"><i class="fas fa-users fa-lg"></i> {{trans_choice('team.team',2 )}}  <span class="badge badge-pill badge-dark">{{ count($club['teams']) }}</span></h4>
            <div class="card-tools">
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table width="100%" class="table table-striped table-bordered table-sm" id="table">
               <thead class="thead-light">
                  <tr>
                     <th hidden>league_id</th>
                     <th hidden>team_id</th>
                     <th>{{trans_choice('league.league',1 )}}</th>
                     <th>{{trans_choice('team.team',1 )}}</th>
                     <th>A</th>
                     <th>B</th>
                     <th>C</th>
                     <th>D</th>
                     <th>E</th>
                     <th>F</th>
                     <th>G</th>
                     <th>H</th>
                     <th>I</th>
                     <th>K</th>
                     <th>L</th>
                     <th>M</th>
                     <th>N</th>
                     <th>O</th>
                     <th>P</th>
                     <th>Q</th>
                  </tr>
               </thead>
               <tbody>
               @foreach ($club['teams'] as $t )
                @isset($t['league'])
                 <tr>
                   <td hidden>{{ $t->league['id'] }}</td>
                   <td hidden>{{ $t->id }}</td>
                   <td>{{ $t->league['shortname'] }}</td>
                   <td>{{$t->team_no}}</td>
                   <td>@if ($t->league_no == 1) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('A')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                   <td>@if ($t->league_no == 2) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('B')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                   <td>@if ($t->league_no == 3) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('C')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                   <td>@if ($t->league_no == 4) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('D')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                  <td>@if ($t['league']['schedule']['size'] <= 5) <i class="far fa-times-circle" style="color:gray"></i>  @elseif ($t->league_no == 5) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('E')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                  <td>@if ($t['league']['schedule']['size'] <= 6) <i class="far fa-times-circle" style="color:gray"></i> @elseif ($t->league_no == 6) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('F')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                  <td>@if ($t['league']['schedule']['size'] <= 7) <i class="far fa-times-circle" style="color:gray"></i> @elseif ($t->league_no == 7) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('G')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                  <td>@if ($t['league']['schedule']['size'] <= 8) <i class="far fa-times-circle" style="color:gray"></i> @elseif ($t->league_no == 8) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('H')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                  <td>@if ($t['league']['schedule']['size'] <= 9) <i class="far fa-times-circle" style="color:gray"></i> @elseif ($t->league_no == 9) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('I')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                  <td>@if ($t['league']['schedule']['size'] <= 10) <i class="far fa-times-circle" style="color:gray"></i> @elseif ($t->league_no == 10) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('K')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                  <td>@if ($t['league']['schedule']['size'] <= 11) <i class="far fa-times-circle" style="color:gray"></i> @elseif ($t->league_no == 11) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('L')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                  <td>@if ($t['league']['schedule']['size'] <= 12) <i class="far fa-times-circle" style="color:gray"></i> @elseif ($t->league_no == 12) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('M')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                  <td>@if ($t['league']['schedule']['size'] <= 13) <i class="far fa-times-circle" style="color:gray"></i> @elseif ($t->league_no == 13) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('N')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                  <td>@if ($t['league']['schedule']['size'] <= 14) <i class="far fa-times-circle" style="color:gray"></i> @elseif ($t->league_no == 14) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('O')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                  <td>@if ($t['league']['schedule']['size'] <= 15) <i class="far fa-times-circle" style="color:gray"></i> @elseif ($t->league_no == 15) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('P')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                  <td>@if ($t['league']['schedule']['size'] <= 16) <i class="far fa-times-circle" style="color:gray"></i> @elseif ($t->league_no == 16) <i class="far fa-dot-circle fa-lg" style="color:green"></i> @elseif ($t['league']['teams']->pluck('league_char')->contains('Q')) <i class="far fa-frown" style="color:red"></i> @endif</td>
                @endisset
              @endforeach
            </tbody>
         </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->

    </div>
    @include('team/includes/teamleague_chart')

    <!-- ./deck -->
    <!-- all modals here -->
    <!-- all modals above -->
</div>
</div>
@stop


@section('js')

<script>
  $(document).ajaxStart(function() { Pace.restart(); });

  $(function() {
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

    $('td').click(function(){
      var col = $(this).index();
      var row = $(this).parent().index();
      var val = $(this).html();
      var league = $(this).parent().find("td:eq(0)").text();
      var team = $(this).parent().find("td:eq(1)").text();
      console.log('Row: ' + row + ', Column: ' + col + ',L:'+league+',T:'+team);
      if (val.includes('fa-frown')){
        alert('This Char is already taken !!!!!');
      } else if (val.includes('fa-dot-circle')){
        alert('This Char is already YOURS !!!!!');
      } else if (val.includes('fa-times-circle')){
        alert('This is not part of this League  !!!!!');
      } else {
        var url = "{{ route('league.team.pickchar', ['league'=>':league:'])}}";
        url = url.replace(':league:', league);
        var league_no = col - 3;
        $.ajax( {
                url: url,
                dataType: "json",
                data: {
                  _token: "{{ csrf_token() }}",
                  league_no: league_no,
                  team_id: team,
                  league_id: league
                },
                type: "post",
                delay: 250,
                success: function (response) {
                  location.reload();
                  console.log('reload');
                },
                cache: false
              });
      }

    });

    refreshChart();

    function refreshChart(){
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      var selData = {};
      selData['club_id'] = {!! json_encode($club->id) !!};
      selData['_token'] = "{{ csrf_token() }}";
      @foreach ($club['teams'] as $t )
        @isset($t['league'])
        selData['selSize:'+ {!!$t['league']['id']!!}+':'+{!!$t['id']!!}] = {!!$t['league_no']!!};
        @endisset
      @endforeach
      console.log(selData);
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
  });

</script>
@stop
