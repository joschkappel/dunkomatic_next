                    <form class="form-horizontal" action="{{ route('schedule_event.add', ['schedule'=>$schedule]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row ">
                                <label for="gamedayAddRange" class="col-sm-4 col-form-label">{{ trans_choice('schedule.game_day_short',2)}}</label>
                                <div class="col-sm-6">
                                <input id="gamedayAddRange" type="text" name="gamedayAddRange" value="">
                                </div>
                              </div>

                            <div class="card-footer">
                                <label id="actiontxtAdd" class="col-sm-8 col-form-label text-danger"></label>
                                <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                    <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>


@push('js')
<script>
    var gamedayAddRangelabel = '1 - {{$eventmax}}';

    function setActionLabelAdd(){
        var labeltext = "{{ __('schedule.event.add') }}";
        labeltext = labeltext.replace('#gamedayAddRange#', gamedayAddRangelabel)

        $("#actiontxtAdd").text(labeltext);

    }
    setActionLabelAdd();

    $('#gamedayAddRange').ionRangeSlider({
      skin: "big",
      min     : 1,
      max     : {{ $eventmax }},
      from    : 1,
      to      :  {{ $eventmax }},
      type    : 'double',
      drag_interval: true,
      grid    : true,
      step    : 1,
      prettify: true,
      prefix: "{{ trans_choice('schedule.game_day_short',1) }} ",
      onFinish: function (data) {
            if (data.from == data.to){
                gamedayAddRangelabel = data.from;
            } else {
                gamedayAddRangelabel = data.from + ' - '+data.to;
            }
            setActionLabelAdd();
        }
    })
</script>
@endpush
