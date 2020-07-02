<div class="modal fade right" id="modalCreateEvents" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading">Create events for {{$schedule->name}}</p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" action="{{ route('schedule_event.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                Please fix the following errors
                            </div>
                            @endif
                            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                            <input type="hidden" name="schedule_size" value="{{ $schedule->size }}">
                            <div class="form-group row ">
                                <label for="title" class="col-sm-2 col-form-label">Start Date</label>
                                <div class="col-sm-10">
                                    <div class="input-group date" id="startdate" data-target-input="nearest">
                                        <input type="text" name='startdate' class="form-control " data-target="#startdate" />
                                        <div class="input-group-append" data-target="#startdate" data-toggle="startdate">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
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
        let thisyear = new Date().getFullYear();
        let oneYearFromNow = new Date().getFullYear() + 1;
        console.log(thisyear);
        console.log(oneYearFromNow);
        var today = new Date();

        $('input[name="startdate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            opens: 'center',
            drops: 'auto',
            minYear: thisyear,
            maxYear: oneYearFromNow,
            startDate: today,
        });
    });
</script>
@endpush
