                    <form class="form-horizontal" action="{{ route('schedule_event.shift', ['schedule'=>$schedule]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row ">
                                <label for="gamedayRange" class="col-sm-4 col-form-label">{{ trans_choice('schedule.game_day_short',2)}}</label>
                                <div class="col-sm-6">
                                <input id="gamedayRange" type="text" name="gamedayRange" value="">
                                </div>
                              </div>
                              <div class="form-group row ">
                                <label for="unitRange" class="col-sm-4 col-form-label">@lang('schedule.event.range')</label>
                                <div class="col-sm-6">
                                    <input id="unitRange" type="text" name="unitRange" value="">
                                </div>
                              </div>

                            <div class="form-group row">
                                <label for="radios3" class="col-sm-4 col-form-label">@lang('schedule.event.unit')</label>
                                <div class="col-sm-6">
                                    <label for="radio10" class="col-form-label radio-inline">
                                        {{ Form::radio('unit', 'DAY', true, ['id' => 'radio10']) }} {{ trans_choice('schedule.event.unit.day',2)}}
                                    </label>
                                    <label for="radio11" class="col-form-label radio-inline">
                                        {{ Form::radio('unit', 'WEEK', false, ['id' => 'radio11']) }} {{ trans_choice('schedule.event.unit.week',2 )}}
                                    </label>
                                    <label for="radio12" class="col-form-label radio-inline">
                                        {{ Form::radio('unit', 'MONTH', false, ['id' => 'radio12']) }} {{ trans_choice('schedule.event.unit.month',2)}}
                                    </label>
                                    <label for="radio13" class="col-form-label radio-inline">
                                        {{ Form::radio('unit', 'YEAR', false, ['id' => 'radio13']) }} {{ trans_choice('schedule.event.unit.year',2)}}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="radios" class="col-sm-4 col-form-label">@lang('schedule.event.direction')</label>
                                <div class="col-sm-6">
                                    <label for="radio1" class="col-form-label radio-inline">
                                        {{ Form::radio('direction', '+', true, ['id' => 'radio1']) }} @lang('schedule.event.forward')
                                    </label>
                                    <label for="radio2" class="col-form-label radio-inline">
                                        {{ Form::radio('direction', '-', false, ['id' => 'radio2']) }} @lang('schedule.event.backward')
                                    </label>
                                </div>
                            </div>
                            <div class="card-footer">
                                <label id="actiontxt" class="col-sm-8 col-form-label text-danger"></label>
                                <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                    <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>


@push('js')
<script>
    var directionlabel = '{{  __('schedule.event.forward') }}';
    var unitlabel = '{{  trans_choice('schedule.event.unit.day',1) }}';
    var unitcountlabel = '1';
    var gamedayrangelabel = '1 - {{$eventcount}}';

    function setActionLabel(){
        var labeltext = "{{ __('schedule.event.move') }}";
        labeltext = labeltext.replace('#unit#', unitlabel)
        labeltext = labeltext.replace('#unitcount#', unitcountlabel)
        labeltext = labeltext.replace('#direction#', directionlabel)
        labeltext = labeltext.replace('#gamedayrange#', gamedayrangelabel)

        $("#actiontxt").text(labeltext);

    }
    setActionLabel();

    $(document).on('change', 'input:radio[id^="radio"]', function (event) {
        if (event.target.value == 'WEEK'){
            if (unitcountlabel == '1'){
                unitlabel = " {{  trans_choice('schedule.event.unit.week',1) }}";
            } else {
                unitlabel = " {{  trans_choice('schedule.event.unit.week',2) }}";
            }
        } else if (event.target.value == 'YEAR'){
            if (unitcountlabel == '1'){
                unitlabel = " {{  trans_choice('schedule.event.unit.year',1 ) }}";
            } else {
                unitlabel = " {{  trans_choice('schedule.event.unit.year',2 ) }}";
            }
        } else if (event.target.value == 'MONTH'){
            if (unitcountlabel == '1'){
                unitlabel = " {{  trans_choice('schedule.event.unit.month',1 ) }}";
            } else {
                unitlabel = " {{  trans_choice('schedule.event.unit.month',2 ) }}";
            }
        } else if (event.target.value == 'DAY'){
            if (unitcountlabel == '1'){
                unitlabel = " {{  trans_choice('schedule.event.unit.day',1 ) }}";
            } else {
                unitlabel = " {{  trans_choice('schedule.event.unit.day',2 ) }}";
            }
        } else if (event.target.value == '+'){
            directionlabel = " {{  __('schedule.event.forward') }}";
        } else if (event.target.value == '-'){
            directionlabel = " {{  __('schedule.event.backward') }}";
        }


        $("#unitRange").data("ionRangeSlider").update({
            postfix: unitlabel
        });

        setActionLabel();
    });

    $('#unitRange').ionRangeSlider({
      skin: "big",
      min     : 1,
      max     : 12,
      from    : 1,
      grid    : true,
      step    : 1,
      prettify: true,
      postfix: " {{  trans_choice('schedule.event.unit.day',2) }}",
      onFinish: function (data) {
            unitcountlabel = data.from;
            setActionLabel();
        }
    })

    $('#gamedayRange').ionRangeSlider({
      skin: "big",
      min     : 1,
      max     : {{ $eventcount }},
      from    : 1,
      to      :  {{ $eventcount }},
      type    : 'double',
      drag_interval: true,
      grid    : true,
      step    : 1,
      prettify: true,
      prefix: "{{ trans_choice('schedule.game_day_short',1) }} ",
      onFinish: function (data) {
            if (data.from == data.to){
                gamedayrangelabel = data.from;
            } else {
                gamedayrangelabel = data.from + ' - '+data.to;
            }
            setActionLabel();
        }
    })
</script>
@endpush
