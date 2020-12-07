<?php $__env->startSection('plugins.Datatables', true); ?>
<?php $__env->startSection('plugins.Select2', true); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <?php echo app('translator')->get('schedule.title.compare', ['region' => session('cur_region')->name ]); ?>
                    </div>
                </div>
                <div class="card-body">
                    <!-- For defining autocomplete -->
                    <label class="col-sm-2 col-form-label" for='selSize'><?php echo e(trans_choice('schedule.schedule',2), false); ?></label>
                    <div class="col-sm-10">
                        <select class='js-example-placeholder-single js-states form-control select2' id='selSize'>
                        </select>
                    </div>

                    <?php echo $__env->make('schedule/includes/scheduleevent_pivot', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(function() {

        $(".js-example-placeholder-single").select2({
            placeholder: "<?php echo app('translator')->get('schedule.action.select'); ?>...",
            theme: 'bootstrap4',
            multiple: true,
            allowClear: false,
            minimumResultsForSearch: -1,
            ajax: {
                url: "<?php echo e(route('schedule.sb.region', ['region' => session('cur_region')->id]), false); ?>",
                type: "get",
                delay: 250,
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#selSize').on('select2:select select2:unselect', function(e) {
            var data = e.params.data;
            var values = $('#selSize').select2('data');
            var selVals = values.map(function(elem) {
                return {
                    id: elem.id,
                    text: elem.text
                };
            });
            if (selVals.length == 0) {
                selVals[0] = {};
            }
            console.log(selVals);
            var url = '<?php echo e(route("schedule_event.list-piv"), false); ?>';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    selVals
                },
                dataType: 'json',
                success: function(data) {
                    $('.collapse').collapse('show');
                    $('#pivottable').html(data);
                },
            });
        });

    });
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/schedule/schedules_list.blade.php ENDPATH**/ ?>