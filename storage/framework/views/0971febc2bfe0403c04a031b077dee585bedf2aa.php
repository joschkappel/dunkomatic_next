<div class="modal fade right" id="modalInjectTeam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading"><?php echo app('translator')->get('league.action.inject', ['league'=>$league->shortname]); ?></p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" action="<?php echo e(route('league.team.inject', ['league'=>$league->id]), false); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="card-body">
                          <div class="form-group row">
                              <label for="selChar" class="col-sm-4 col-form-label"><?php echo e(__('league.char'), false); ?></label>
                              <div class="col-sm-6">
                                <select class='js-freechar-single js-states form-control select2' id='selChar' name='league_no'></select>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="selFreeTeam" class="col-sm-4 col-form-label"><?php echo e(__('club.team'), false); ?></label>
                              <div class="col-sm-6">
                                <select class='js-team-single js-states form-control select2' id='selFreeTeam' name='team_id'></select>
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

      $(".js-freechar-single").select2({
          placeholder: "<?php echo app('translator')->get('league.sb_freechar'); ?>...",
          theme: 'bootstrap4',
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "<?php echo e(route('league.sb_freechar', $league->id), false); ?>",
                  type: "get",
                  delay: 250,
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: false
                }
      });
      $(".js-team-single").select2({
          placeholder: "<?php echo e(__('club.action.select'), false); ?>...",
          theme: 'bootstrap4',
          allowClear: false,
          minimumResultsForSearch: 5,
          ajax: {
                  url: "<?php echo e(route('team.free.sb', $league->id), false); ?>",
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
<?php /**PATH /var/www/dunkonxt/resources/views/league/includes/inject_team.blade.php ENDPATH**/ ?>