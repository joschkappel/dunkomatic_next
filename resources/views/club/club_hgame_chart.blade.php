@extends('layouts.page')

@section('plugins.Pace', true)
@section('plugins.Chartjs', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card rounded">
                <div class="card-body py-2 px-2">
                    <div style="width: 100%" style="height: 25%">
                        <canvas id="myChart" height="120" width="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('footer')
jochenk
@stop


@section('js')
<script >
    $(document).ajaxStart(function() { Pace.restart(); });

    $(function() {
       var ctx = document.getElementById('myChart').getContext('2d');
       var myChart = new Chart(ctx, {
            type: 'scatter',
            data: {
              datasets: [{
                data: [],
              },
              {
                data: [],
              },
              {
                data: [],
              },
              {
                data: [],
              },
              {
                data: [],
              },
              {
                data: [],
              }]
            },
            options: {
                plugins: {
                  colorschemes: {
                    scheme: 'brewer.SetOne9'
                  }
                },
                scales: {
                    yAxes: [{
                              ticks: {
                                  beginAtZero:false,
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

       function load_hgame_chart(chart) {
            $.ajax({
                type: 'GET',
                url: '{{ route('club.game.chart_home', ['club' => $club->id]) }}',
                success: function(response) {
                  var i=0;
                  for ( var hg in response){
                    myChart.data.datasets[i].label = response[hg]['label'];
                    myChart.data.datasets[i].data = response[hg]['data'];
                    console.log(response[hg]['label']);
                    i++;
                  };

                  myChart.update();
                },
            });
        };

      load_hgame_chart( myChart);

    });
</script>
@stop
