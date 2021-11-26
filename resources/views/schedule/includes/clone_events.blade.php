<x-modal modalId="modalCloneEvents" modalTitle="{{ __('schedule.title.event.clone', ['schedule'=>$schedule->name]) }}">
        <div class="form-group row ">
            <label class="col-sm-4 col-form-label" for='selSchedule'>{{trans_choice('schedule.schedule',1)}}</label>
            <div class="col-sm-6">
            <select class='js-schedule-single js-states form-control select2' id='selSchedule' name='clone_from_schedule'>
            </select>
            </div>
        </div>
</x-modal>
<!--Modal: modalRelatedContent-->
@push('js')

<script>
    $(function() {
      $(".js-schedule-single").select2({
          placeholder: "@lang('schedule.action.select')...",
          width: '100%',
          multiple: false,
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('schedule.sb.size',['schedule'=>$schedule])}}",
                  type: "get",
                  delay: 250,
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
      });
    });
</script>
@endpush
