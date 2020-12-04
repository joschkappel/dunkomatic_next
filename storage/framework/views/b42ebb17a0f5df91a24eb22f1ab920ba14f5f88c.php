<?php $__env->startSection('plugins.Datatables', true); ?>
<?php $__env->startSection('plugins.Select2', true); ?>

<?php $__env->startSection('content_header'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><?php echo app('translator')->get('schedule.title.scheme.list'); ?></h3>
                    <!-- For defining autocomplete -->
                    <div>
                        <label for='selSize'><?php echo app('translator')->get('schedule.action.size.select'); ?></label>
                        <select class='js-example-placeholder-single js-states form-control select2' id='selSize'>
                        </select>
                    </div>
                </div>
              </div>
                  <?php echo $__env->make('league/includes/league_scheme_pivot', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            </div>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(function() {


      $(".js-example-placeholder-single").select2({
          placeholder: "<?php echo app('translator')->get('schedule.action.size.select'); ?>...",
          theme: 'bootstrap4',
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "<?php echo e(route('size.index'), false); ?>",
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

      $('#selSize').on('select2:select', function(e) {
                var data = e.params.data;
                var url = '<?php echo e(route("scheme.list_piv", ['size'=>":size:"]), false); ?>';
                url = url.replace(':size:', data.id);

                $.ajax({
                  type: 'GET',
                  url: url,
                  success: function (data) {
                    $('.collapse').collapse('show');
                    $('#pivottable').html( data );
                  },


                });
      });
    });

</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/league/league_scheme_list.blade.php ENDPATH**/ ?>