<?php $__env->startSection('plugins.Pace', true); ?>

<?php $__env->startSection('plugins.Select2', true); ?>
<?php $__env->startSection('plugins.Chartjs', true); ?>


<?php $__env->startSection('css'); ?>
  <link href="<?php echo e(URL::asset('vendor/bootstrap-slider/css/bootstrap-slider.css'), false); ?>" rel="stylesheet">

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><?php echo app('translator')->get('team.title.plan', ['club'=> $club->shortname ]); ?></div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" id="teamLeaguePlanForm">

                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="club_id" value="<?php echo e($club->id, false); ?>">
                        <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php if(isset($team['league']->size)): ?>
                            <div class="form-group row ">
                              <label for="league<?php echo e($team['league']->id, false); ?>" class="col-sm-8 col-form-label"><?php echo e($team['league']->shortname, false); ?></label>
                              <div class="col-sm-4">
                                <select class="js-single-size form-control" name="selSize:<?php echo e($team['league']->id, false); ?>:<?php echo e($team->id, false); ?>" id='selSize'>
                                    <?php for( $i=1; $i <= $team['league']->size; $i++ ): ?>
                                        <option <?php if($team->league_char == $i): ?> selected <?php endif; ?> value="<?php echo e($i, false); ?>"><?php echo e($i, false); ?></option>
                                    <?php endfor; ?>
                                </select>
                                </div>
                            </div>
                          <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                            <button type="submit" name="refreshbtn" id="refreshbtn" class="btn btn-info"><?php echo app('translator')->get('team.action.refresh-chart'); ?></button>
                            <button  name="savebtn" id="savebtn" class="btn btn-success"><?php echo app('translator')->get('team.action.save-assignment'); ?></button>
                        </div>
                    </form>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" id="teamLeagueOptForm">
                          <div class="form-group row ">
                            <label for="radios" class="col-sm-2 "><?php echo app('translator')->get('team.game.perday'); ?></label>

                              <div class="col-sm-10">
                                  <label for="radio1" class="col-form-label radio-inline">
                                      <?php echo e(Form::radio('optmode', 'min', true, ['id' => 'radio1']), false); ?> <?php echo app('translator')->get('team.game.perday.min'); ?>
                                  </label>
                                  <label for="radio2" class="col-form-label radio-inline">
                                      <?php echo e(Form::radio('optmode', 'max', false, ['id' => 'radio2']), false); ?> <?php echo app('translator')->get('team.game.perday.max'); ?>
                                  </label>
                                  <label for="radio3" class="col-form-label radio-inline">
                                      <?php echo e(Form::radio('optmode', 'day', false, ['id' => 'radio3']), false); ?> <?php echo app('translator')->get('team.game.perday.num'); ?>
                                  </label>
                              </div>
                                <div class="col-sm-10 slider-gray">
                                    <span class="text-info ml-2 mt-1 gperday"></span>
                                    <input  type="text" name="gperday" id="gperday" value="" class="slider form-control" data-slider-value="1" data-slider-min="1" data-slider-max="<?php echo e(count($teams), false); ?>" data-slider-step="1">
                                  </div>


                          </div>
                          <div class="form-group row ">
                            <div class="col-sm-10 slider-gray">
                                <label for="optRange"><?php echo app('translator')->get('team.game.combination'); ?></label>
                                <span class="text-info ml-2 mt-1 optRangeSpan"></span>
                                <input  type="text" id="optRange" value="0" class="slider form-control"  data-slider-min="0" data-slider-max="0" data-slider-step="1">
                              </div>
                          </div>
                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                            <button  type="submit" class="btn btn-info " id="proposebtn" name="proposebtn"><?php echo app('translator')->get('team.action.showon-chart'); ?></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <?php echo $__env->make('team/includes/teamleague_chart', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script src="<?php echo e(URL::asset('vendor/bootstrap-slider/bootstrap-slider.js'), false); ?>"></script>

<script >
    $(document).ajaxStart(function() { Pace.restart(); });

    $(function() {
        //Initialize Select2 Elements
        $('.js-single-size').select2({
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false
        });
        var mySlider = $("#optRange").bootstrapSlider();
        var mySlider2 = $("#gperday").bootstrapSlider();

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


        $("#teamLeaguePlanForm").submit(function(e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '<?php echo e(route('team.list-chart', app()->getLocale()), false); ?>',
                data: data,
                dataType: 'json',
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
        });

        var leagues = [];
        var c_min = [];
        var c_max = [];
        var c_day = [];
        var c_sel = [];

        function setElements( ) {
          if ($("input[name=optmode]:checked").val() == 'min'){
            if (c_min.length > 0){
              c_sel = c_min;
            } else {
              return false;
            }
          } else if ($("input[name=optmode]:checked").val() == 'max'){
            if (c_max.length > 0){
              c_sel = c_max;
            } else {
              return false;
            }
          } else {
            gpd = $("input[name=gperday]").val();
            if ((typeof c_day[gpd] !== 'undefined') &&(c_day[gpd].length > 0)){
              c_sel = c_day[gpd];
            } else {
              return false;
            }
          }
          mySlider.bootstrapSlider('setAttribute','max', c_sel.length);
          mySlider.bootstrapSlider('setAttribute','min', 1);
          mySlider.bootstrapSlider('setValue',1);
          $(".optRangeSpan").html("1/"+c_sel.length);
          //set option 0 values:
          leagues.forEach( function (item,idx){
            var selName = "selSize:"+item;
            var selVal = c_sel[0][idx];
            elem = $("select[name^='"+selName+"']");
            //document.getElementsByName(selName);
            $(elem).val(selVal).trigger("change");
          });
          $("#refreshbtn").click();
          return true;
        };


        $("#teamLeagueOptForm").submit(function(e) {
            e.preventDefault();
            var data = $("#teamLeaguePlanForm").serialize();
            data = data + '&'+ $("#teamLeagueOptForm").serialize();

            Pace.restart();
            Pace.track(function () {
              var check = setElements();
              if (!check){
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(route('team.propose', app()->getLocale() ), false); ?>',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                      leagues = response['leagues'];
                      c_min = response['c_min'];
                      c_max = response['c_max'];
                      c_day = response['c_day'];
                      check = setElements();
                    },
                });
              }
            });
        });

        $("#savebtn").click(function (e){
          e.preventDefault();
          var data = $("#teamLeaguePlanForm").serialize();
          Pace.restart();
          Pace.track(function () {
            $.ajax({
                type: 'POST',
                url: '<?php echo e(route('team.store-plan'), false); ?>',
                data: data,
                dataType: 'json',
                success: function(response) {
                },
            });
          });
        });

        $("#optRange").on('input change', () => {
            var selCombi = $("#optRange").val();
            $(".optRangeSpan").html(selCombi+"/"+c_sel.length);
            var selIdx = selCombi -1 ;
            leagues.forEach( function (item,idx){
              var selName = "selSize:"+item;
              var selVal = c_sel[selIdx][idx];
              elem = $("select[name^='"+selName+"']");
              $(elem).val(selVal).trigger("change");
            });

            $("#refreshbtn").click();
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/team/teamleague_dashboard.blade.php ENDPATH**/ ?>