<?php $__env->startSection('plugins.Pace', true); ?>
<?php $__env->startSection('plugins.Chartjs', true); ?>
<?php $__env->startSection('plugins.Datatables', true); ?>

<?php $__env->startSection('content'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token(), false); ?>">
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8">

        <!-- card TEAMS -->
        <div class="card card-info">
          <div class="card-header">
            <h4 class="card-title"><i class="fas fa-users fa-lg"></i> <?php echo e(trans_choice('team.team',2 ), false); ?>  <span class="badge badge-pill badge-dark"><?php echo e(count($club['teams']), false); ?></span></h4>
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
                     <th><?php echo e(trans_choice('league.league',1 ), false); ?></th>
                     <th><?php echo e(trans_choice('team.team',1 ), false); ?></th>
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
               <?php $__currentLoopData = $club['teams']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(isset($t['league'])): ?>
                 <tr>
                   <td hidden><?php echo e($t->league['id'], false); ?></td>
                   <td hidden><?php echo e($t->id, false); ?></td>
                   <td><?php echo e($t->league['shortname'], false); ?></td>
                   <td><?php echo e($t->team_no, false); ?></td>
                   <td><?php if($t->league_no == 1): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('A')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                   <td><?php if($t->league_no == 2): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('B')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                   <td><?php if($t->league_no == 3): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('C')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                   <td><?php if($t->league_no == 4): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('D')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                  <td><?php if($t['league']['schedule']['size'] <= 5): ?> <i class="far fa-times-circle" style="color:gray"></i>  <?php elseif($t->league_no == 5): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('E')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                  <td><?php if($t['league']['schedule']['size'] <= 6): ?> <i class="far fa-times-circle" style="color:gray"></i> <?php elseif($t->league_no == 6): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('F')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                  <td><?php if($t['league']['schedule']['size'] <= 7): ?> <i class="far fa-times-circle" style="color:gray"></i> <?php elseif($t->league_no == 7): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('G')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                  <td><?php if($t['league']['schedule']['size'] <= 8): ?> <i class="far fa-times-circle" style="color:gray"></i> <?php elseif($t->league_no == 8): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('H')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                  <td><?php if($t['league']['schedule']['size'] <= 9): ?> <i class="far fa-times-circle" style="color:gray"></i> <?php elseif($t->league_no == 9): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('I')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                  <td><?php if($t['league']['schedule']['size'] <= 10): ?> <i class="far fa-times-circle" style="color:gray"></i> <?php elseif($t->league_no == 10): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('K')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                  <td><?php if($t['league']['schedule']['size'] <= 11): ?> <i class="far fa-times-circle" style="color:gray"></i> <?php elseif($t->league_no == 11): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('L')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                  <td><?php if($t['league']['schedule']['size'] <= 12): ?> <i class="far fa-times-circle" style="color:gray"></i> <?php elseif($t->league_no == 12): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('M')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                  <td><?php if($t['league']['schedule']['size'] <= 13): ?> <i class="far fa-times-circle" style="color:gray"></i> <?php elseif($t->league_no == 13): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('N')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                  <td><?php if($t['league']['schedule']['size'] <= 14): ?> <i class="far fa-times-circle" style="color:gray"></i> <?php elseif($t->league_no == 14): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('O')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                  <td><?php if($t['league']['schedule']['size'] <= 15): ?> <i class="far fa-times-circle" style="color:gray"></i> <?php elseif($t->league_no == 15): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('P')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                  <td><?php if($t['league']['schedule']['size'] <= 16): ?> <i class="far fa-times-circle" style="color:gray"></i> <?php elseif($t->league_no == 16): ?> <i class="far fa-dot-circle fa-lg" style="color:green"></i> <?php elseif($t['league']['teams']->pluck('league_char')->contains('Q')): ?> <i class="far fa-frown" style="color:red"></i> <?php endif; ?></td>
                <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    <?php echo $__env->make('team/includes/teamleague_chart', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- ./deck -->
    <!-- all modals here -->
    <!-- all modals above -->
</div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>

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
        var url = "<?php echo e(route('league.team.pickchar', ['league'=>':league:']), false); ?>";
        url = url.replace(':league:', league);
        var league_no = col - 3;
        $.ajax( {
                url: url,
                dataType: "json",
                data: {
                  _token: "<?php echo e(csrf_token(), false); ?>",
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
      selData['club_id'] = <?php echo json_encode($club->id); ?>;
      selData['_token'] = "<?php echo e(csrf_token(), false); ?>";
      <?php $__currentLoopData = $club['teams']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(isset($t['league'])): ?>
        selData['selSize:'+ <?php echo $t['league']['id']; ?>+':'+<?php echo $t['id']; ?>] = <?php echo $t['league_no']; ?>;
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      console.log(selData);
      var data = JSON.stringify(selData);
      $.ajax({
          type: 'POST',
          url: '<?php echo e(route('team.list-chart', app()->getLocale()), false); ?>',
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/club/club_pickchar.blade.php ENDPATH**/ ?>