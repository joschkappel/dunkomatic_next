                    <form class="form-horizontal" action="{{ route('schedule_event.remove', ['schedule'=>$schedule]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row ">
                                <label for="gamedayRemoveRange" class="col-sm-4 col-form-label">{{ trans_choice('schedule.game_day_short',2)}}</label>
                                <div class="col-sm-6">
                                <input id="gamedayRemoveRange" type="text" name="gamedayRemoveRange" value="">
                                </div>
                              </div>

                            <div class="card-footer">
                                <label id="actiontxtRemove" class="col-sm-8 col-form-label text-danger"></label>
                                <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                    <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>


@push('js')
<script>
    var gamedayRemoveRangelabel = '1 - {{$eventmax}}';

    function setActionLabelRemove(){
        var labeltext = "{{ __('schedule.event.remove') }}";
        labeltext = labeltext.replace('#gamedayRemoveRange#', gamedayRemoveRangelabel)

        $("#actiontxtRemove").text(labeltext);

    }
    setActionLabelRemove();

    $('#gamedayRemoveRange').ionRangeSlider({
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
                gamedayRemoveRangelabel = data.from;
            } else {
                gamedayRemoveRangelabel = data.from + ' - '+data.to;
            }
            setActionLabelRemove();
        }
    })
</script>
@endpush
