@extends('adminlte::page')

@section('plugins.Select2', true)


@section('css')
  <link href="{{ URL::asset('vendor/chart.js/Chart.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('vendor/bootstrap-slider/css/bootstrap-slider.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('vendor/pace-progress/themes/blue/pace-theme-center-radar.css') }}" rel="stylesheet" />
@endsection


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
                          @isset ($team['league']['schedule']->size)
                            <div class="form-group row ">
                              <label for="league{{$team['league']->id}}" class="col-sm-8 col-form-label">{{ $team['league']->shortname }}</label>
                              <div class="col-sm-4">
                                <select class="js-single-size form-control" name="selSize:{{$team['league']->id}}:{{$team->id}}" id='selSize'>
                                    @for ( $i=1; $i <= $team['league']['schedule']->size; $i++ )
                                        <option @if ($team->league_char == $i) selected @endif value="{{ $i }}">{{ $i }}</option>
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
                          <div class="form-group row ">
                            <label for="radios" class="col-sm-2 ">@lang('team.game.perday')</label>

                              <div class="col-sm-10">
                                  <label for="radio1" class="col-form-label radio-inline">
                                      {{ Form::radio('optmode', 'min', true, ['id' => 'radio1']) }} @lang('team.game.perday.min')
                                  </label>
                                  <label for="radio2" class="col-form-label radio-inline">
                                      {{ Form::radio('optmode', 'max', false, ['id' => 'radio2']) }} @lang('team.game.perday.max')
                                  </label>
                                  <label for="radio3" class="col-form-label radio-inline">
                                      {{ Form::radio('optmode', 'day', false, ['id' => 'radio3']) }} @lang('team.game.perday.num')
                                  </label>
                              </div>
                                <div class="col-sm-10 slider-gray">
                                    <span class="text-info ml-2 mt-1 gperday"></span>
                                    <input  type="text" name="gperday" id="gperday" value="" class="slider form-control" data-slider-value="1" data-slider-min="1" data-slider-max="{{ count($teams) }}" data-slider-step="1">
                                  </div>


                          </div>
                          <div class="form-group row ">
                            <div class="col-sm-10 slider-gray">
                                <label for="optRange">@lang('team.game.combination')</label>
                                <span class="text-info ml-2 mt-1 optRangeSpan"></span>
                                <input  type="text" id="optRange" value="0" class="slider form-control"  data-slider-min="0" data-slider-max="0" data-slider-step="1">
                              </div>
                          </div>
                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                            <button  type="submit" class="btn btn-info " id="proposebtn" name="proposebtn">@lang('team.action.showon-chart')</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        @include('team/includes/teamleague_chart')
    </div>
</div>
@stop

@section('footer')
jochenk
@stop


@section('js')
<script src="{{ URL::asset('vendor/moment/moment.min.js')}}"></script>
<script src="{{ URL::asset('vendor/chart.js/Chart.js')}}"></script>
<script src="{{ URL::asset('vendor/bootstrap-slider/bootstrap-slider.js')}}"></script>

  <script data-pace-options='maxProgressPerFrame: 2'  src="{{ URL::asset('vendor/pace-progress/pace.js') }}"></script>
<script >
    $(document).ajaxStart(function() { Pace.restart(); });

    $(function() {
        //Initialize Select2 Elements
        $('.js-single-size').select2({

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


        $("#teamLeaguePlanForm").submit(function(e) {
            e.preventDefault();
            var data = $(this).serialize();
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
                    url: '{{ route('team.propose', app()->getLocale() )}}',
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
                url: '{{ route('team.store-plan', app()->getLocale())}}',
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
@stop
