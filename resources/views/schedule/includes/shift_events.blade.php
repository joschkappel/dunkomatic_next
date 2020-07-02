<div class="modal fade right" id="modalShiftEvents" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading">Shift events for {{$schedule->name}}</p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" action="{{ route('schedule_event.shift') }}" method="POST">
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
                                <label for="radios" class="col-sm-2 col-form-label">Direction</label>
                                <div class="col-sm-10">
                                    <label for="radio1" class="col-form-label radio-inline">
                                        {{ Form::radio('direction', '+', true, ['id' => 'radio1']) }} forwards
                                    </label>
                                    <label for="radio2" class="col-form-label radio-inline">
                                        {{ Form::radio('direction', '-', false, ['id' => 'radio2']) }} backwards
                                    </label>

                                </div>
                            </div>
                            <div class="form-group row ">
                                <div class="col-sm-10">
                                    <label for="customRange2">Range</label>
                                    <input name='unitRange' type="range" class="custom-range" min="1" max="12" id="unitRange">
                                    <span class="font-weight-bold text-primary ml-2 mt-1 valueSpan"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="radios3" class="col-sm-2 col-form-label">Unit</label>
                                <div class="col-sm-10">
                                    <label for="radio10" class="col-form-label radio-inline">
                                        {{ Form::radio('unit', 'DAY', true, ['id' => 'radio10']) }} Day
                                    </label>
                                    <label for="radio11" class="col-form-label radio-inline">
                                        {{ Form::radio('unit', 'WEEK', false, ['id' => 'radio11']) }} Week
                                    </label>
                                    <label for="radio12" class="col-form-label radio-inline">
                                        {{ Form::radio('unit', 'MONTH', false, ['id' => 'radio12']) }} Month
                                    </label>
                                    <label for="radio13" class="col-form-label radio-inline">
                                        {{ Form::radio('unit', 'YEAR', false, ['id' => 'radio13']) }} Year
                                    </label>
                                </div>

                            </div>
                            <div class="card-footer">
                                <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                </div>
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
    const $valueSpan = $('.valueSpan');
    const $value = $('#unitRange');
    $valueSpan.html($value.val());
    $value.on('input change', () => {
        $valueSpan.html($value.val());
    });
</script>
@endpush
