<?php $__env->startSection('plugins.Pace', true); ?>
<?php $__env->startSection('plugins.Chartjs', true); ?>

<?php $__env->startSection('content'); ?>
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
<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>
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
                                labelString: '<?php echo app('translator')->get('team.game.perday.games'); ?>'
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
                url: '<?php echo e(route('club.game.chart_home', ['club' => $club->id]), false); ?>',
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/club/club_hgame_chart.blade.php ENDPATH**/ ?>