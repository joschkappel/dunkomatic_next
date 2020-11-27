<div class="modal fade right" id="modalAssignLeague" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading"><?php echo app('translator')->get('team.title.assign.league'); ?></p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" action="<?php echo e(route('team.assign-league'), false); ?>" method="POST">
                        <?php echo method_field('PUT'); ?>
                        <?php echo csrf_field(); ?>
                        <div class="card-body">
                            <input type="hidden" name="team_id" id="team_id" value=""  />
                            <input type="hidden" name="club_id" id="club_id" value=""  />
                            <div class="form-group row">
                                <label for="selLeague" class="col-sm-4 col-form-label"><?php echo e(trans_choice('league.league',1), false); ?></label>
                                <div class="col-sm-6">
                                  <select class='js-league-single js-states form-control select2' id='selLeague' name='league_id'></select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="selChar" class="col-sm-4 col-form-label"><?php echo e(trans_choice('league.char',1), false); ?></label>
                                <div class="col-sm-6">
                                  <select class='js-freechar-single js-states form-control select2' id='selChar' name='league_no'></select>
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


      $(".js-league-single").select2({
          placeholder: "<?php echo app('translator')->get('league.action.select'); ?>...",
          theme: 'bootstrap4',
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "<?php echo e(route('league.sb.club',['club' => $club]), false); ?>",
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


      $(".js-freechar-single").select2({
          placeholder: "<?php echo e(__('league.sb_freechar'), false); ?>...",
          theme: 'bootstrap4',
          allowClear: true,
      });

      $("#selLeague").on("select2:select", function (e) {
        var data = e.params.data;
        var url = "<?php echo e(route('league.sb_freechar', ':leagueid:'), false); ?>"
        url = url.replace(':leagueid:', data['id']);
        $('#selChar').val(null).trigger('change');
        $(".js-freechar-single").select2({
            placeholder: "<?php echo e(__('league.sb_freechar'), false); ?>...",
            theme: 'bootstrap4',
            allowClear: true,
            ajax: {
                    url: url,
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
      });

    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/dunkonxt/resources/views/club/includes/assign_league.blade.php ENDPATH**/ ?>