<div class="modal fade right" id="modalAssignClub" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading"><?php echo app('translator')->get('club.action.assign', ['league'=>$league->shortname]); ?></p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" action="<?php echo e(route('league.assign-club', ['league'=>$league->id]), false); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="card-body">
                            <input type="hidden" name="item_id" id="itemid" value=""  />
                            <div class="form-group row">
                                <label for="selClub" class="col-sm-4 col-form-label"><?php echo e(trans_choice('club.club',1), false); ?></label>
                                <div class="col-sm-6">
                                  <select class='js-club-single js-states form-control select2' id='selClub' name='club_id'></select>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-info"><?php echo e(__('Submit'), false); ?></button>
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
<?php $__env->startPush('js'); ?>

<script>
    $(function() {

      $(".js-club-single").select2({
          placeholder: "<?php echo e(__('club.action.select'), false); ?>...",
          theme: 'bootstrap4',
          allowClear: false,
          minimumResultsForSearch: 5,
          ajax: {
                  url: "<?php echo e(route('club.sb.region'), false); ?>",
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
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/dunkonxt/resources/views/league/includes/assign_club.blade.php ENDPATH**/ ?>