@extends('layouts.page')

@section('plugins.Pace', true)
@section('plugins.Chartjs', true)

@section('content')
<x-card-form cardTitle="{{ __('club.title.gamehome.chart') }}" :omitSubmit="true" colWidth="12">
    <canvas id="myChart" height="120" width="200"></canvas>
</x-card-form>
@endsection


@section('js')
<script >
    $(document).ajaxStart(function() { Pace.restart(); });

    $(function() {
      $('#frmClose').click(function(e){
        history.back();
      });

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
@endsection
