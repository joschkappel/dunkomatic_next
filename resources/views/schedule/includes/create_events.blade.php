<x-modal modalId="modalCreateEvents" modalTitle="{{ __('schedule.title.event.create', ['schedule'=>$schedule->name]) }}">
        <div class="form-group row ">
            <label for="startdate" class="col-sm-2 col-form-label">Start Date</label>
            <div class="col-sm-10">
                <div class="input-group date" id="startdate" data-target-input="nearest">
                    <input type="text" name='startdate' id='startdate' class="form-control datetimepicker-input" data-target="#startdate" />
                    <div class="input-group-append" data-target="#startdate" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
</x-modal>
<!--Modal: modalRelatedContent-->
@push('js')

<script>
    $(function() {
        let date = new Date();
        let startDate = date.setDate(date.getDate() + 30);
        let endDate = date.setDate(date.getDate() + 365);

        $('#startdate').datetimepicker({
            format: 'L',
            locale: '{{ app()->getLocale()}}',
            useCurrent: true,
            minDate: startDate,
            maxDate: endDate,
        });
    });
</script>
@endpush
