@extends('layouts.page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">@lang('team.title.plan', ['club'=> $club->shortname ])</div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" id="teamLeaguePlanForm">
                        @csrf
                        <input type="hidden" name="club_id" value="{{ $club->id }}">
                        @foreach ($teams as $team)
                          @isset ($team['league']->size)
                            <div class="form-group row">
                              <label for="selSize{{$team['league']->id}}" class="col-sm-8 col-form-label">{{ $team['league']->shortname }}</label>
                              <div class="col-sm-4">
                                <select class="js-single-size form-control" name="selSize:{{$team['league']->id}}:{{$team->id}}" id='selSize{{$team['league']->id}}'>
                                    @for ( $i=1; $i <= $team['league']->size; $i++ )
                                        <option @if ($team->preferred_league_no == $i) selected @endif value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                              </div>
                            </div>
                          @endisset
                        @endforeach
                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                            <button type="submit" name="refreshbtn" id="refreshbtn" class="btn btn-info">@lang('team.action.refresh-chart')</button>
                            <button  name="savebtn" id="savebtn" class="btn btn-success">@lang('team.action.save-assignment')</button>
                        </div>
                    </form>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" id="teamLeagueOptForm">
                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                            <button  type="submit" class="btn btn-info " id="proposebtn" name="proposebtn">@lang('team.action.showon-chart')</button>
                        </div>
                          <div class="form-group row ">
                            <div class="col-sm-10 slider-gray">
                                <span class="text-info ml-2 mt-1 dayRange"></span>
                                <input  type="text" name="dayRange" id="dayRange" value="" class="slider form-control">
                            </div>
                          </div>
                          <div class="form-group row ">
                            <div class="col-sm-10 slider-gray">
                                <span class="text-info ml-2 mt-1 optRangeSpan"></span>
                                <input  type="text" id="optRange" name="optRange" value="" class="slider form-control">
                              </div>
                          </div>

                    </form>

                </div>
            </div>
        </div>
        @include('team/includes/teamleague_chart')
    </div>
</div>
@stop

@section('js')
<script >
    $(function() {

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-full-width",
            "preventDuplicates": true,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": 0,
            "onclick": null,
            "onCloseClick": null,
            "extendedTimeOut": 0,
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "tapToDismiss": false
            };

        var leagues = [];
        var c_day = [];
        var c_sel = [];
        var selIdx = 0;
        var day_vals = [];
        var opt_vals = [];

        var daySlider = $("#dayRange").ionRangeSlider({
            skin: "big",
            grid: true,
            grid_snap: true,
            grid_num: 0,
            step: 1,
            prettify: true,
            postfix: " {{__('game.homegame.day')}}",
            onFinish: function (data){
                initOptionRangeForDay( day_vals[data.from] );
            }
        });

        var optionSlider = $("#optRange").ionRangeSlider({
            skin: "big",
            grid: true,
            grid_snap: true,
            step: 1,
            prettify: true,
            prefix: "Option ",
            onFinish: function (data){
                applyOptionSettings( opt_vals[data.from] );
            }
        });

        function initDayRange( ) {
            var idx;
            day_vals = [];
            for (idx in c_day){
                if ( c_day[idx].length > 0){
                day_vals.push(idx);
                }
            };

            $("#dayRange").data("ionRangeSlider").update({ values: day_vals, from: 0 });

            initOptionRangeForDay(day_vals[0]);
        };

        function initOptionRangeForDay( day_idx ) {
            if (Object.keys(c_day).length > 0){
                c_sel = c_day[ day_idx ];
            } else {
                return false;
            }

            var o_idx;
            opt_vals = [];
            if ( c_sel === undefined){
            c_sel = [];
            opt_vals = [0];
            } else {
            c_sel.forEach( function (item, o_idx){
                if (item[0].length > 0){
                    opt_vals.push(o_idx);
                }
            });
            }
            $("#optRange").data("ionRangeSlider").update({ values: opt_vals, from: opt_vals[0]});

            applyOptionSettings(opt_vals[0]);
        };

        function applyOptionSettings( opt_idx) {
            var lidx;
            leagues.forEach( function (item,lidx ){
                var selName = "selSize:"+item;
                var selVal = c_sel[opt_idx][0][lidx];
                elem = $("select[name^='"+selName+"']");
                //document.getElementsByName(selName);
                $(elem).val(selVal).trigger("change");
          });

          $("#refreshbtn").click();
        }

        $("#teamLeagueOptForm").submit(function(e) {
            e.preventDefault();
            @if(count($teams)<=6)
            toastr.info("{{__('game.calc.wait.sec')}}","{{__('game.calc.options')}}");
            @else
            toastr.info("{{__('game.calc.wait.min')}}","{{__('game.calc.options')}}");
            @endif
            var data = $("#teamLeaguePlanForm").serialize();
            data = data + '&'+ $("#teamLeagueOptForm").serialize();

            $.ajax({
                type: 'POST',
                url: '{{ route('team.propose', app()->getLocale() )}}',
                data: data,
                dataType: 'json',
                success: function(response) {
                    leagues = response['leagues'];
                    c_day = response['c_day'];
                    initDayRange();
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


        $("#refreshbtn").click(function (e){
            e.preventDefault();
            var data =$("#teamLeaguePlanForm").serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('team.list-chart', app()->getLocale()) }}',
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

        $("#savebtn").click(function (e){
          e.preventDefault();
          var data = $("#teamLeaguePlanForm").serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route('team.store-plan') }}',
                data: data,
                dataType: 'json',
            });
        });

    });
</script>
@stop
