<div class="modal fade right" id="modalCloneEvents" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading">Clone events for {{$schedule->name}}</p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" action="{{ route('schedule_event.clone') }}" method="POST">
                        @csrf
                        <div class="card-body">

                            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                            <input type="hidden" name="schedule_size" value="{{ $schedule->size }}">
                            <div class="form-group row ">
                              <label class="col-sm-2 col-form-label" for='selSchedule'>pls select schedules</label>
                              <div class="col-sm-10">
                                <select class='js-schedule-single js-states form-control select2' id='selSchedule' name='clone_from_schedule'>
                                </select>
                              </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-info">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>
<!--Modal: modalRelatedContent-->
@push('js')

<script>
    $(function() {
      $(".js-schedule-single").select2({
          placeholder: "Select schedule...",
          multiple: false,
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('schedule.list_size_sel',['size' => $schedule->size])}}",
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
