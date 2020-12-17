<?php $__env->startSection('plugins.Moment', true); ?>
<?php $__env->startSection('plugins.TempusDominus', true); ?>
<?php $__env->startSection('plugins.Select2', true); ?>

<div class="modal fade right" id="modalEditGamedate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading"><?php echo app('translator')->get('game.action.editdate'); ?></p>  <p class="heading" id="modtitle"></p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">
                  <form class="form-horizontal" id="formGamedate" action="" method="POST">
                      <div class="card-body">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo app('translator')->get('Please fix the following errors'); ?>
                            </div>
                            <?php endif; ?>
                            <input type="hidden"  name="gym_id" id="gym_id"/>
                            <input type="hidden"  name="gym_no" id="gym_no"/>
                            <input type="hidden"  name="game_id" id="game_id"/>
                            <input type="hidden"  name="league" id="league"/>
                            <div class="form-group row ">
                              <label for='gdate' class="col-sm-4 col-form-label"><?php echo app('translator')->get('game.game_date'); ?></label>
                              <div class="col-sm-5">
                                <div class="input-group date" id="gdate" data-target-input="nearest">
                                  <input type="text" class="form-control datetimepicker-input  <?php $__errorArgs = ['game_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> /> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-target="#gdate" name="game_date" id="game_date"/>
                                  <div class="input-group-append" data-target="#gdate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                  <?php $__errorArgs = ['game_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                  <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                              </div>
                            </div>
                            <div class="form-group row ">
                              <label for='gtime' class="col-sm-4 col-form-label"><?php echo app('translator')->get('game.game_time'); ?></label>
                              <div class="col-sm-5">
                                <div class="input-group date" id="gtime" data-target-input="nearest">
                                  <input type="text"  class="form-control datetimepicker-input" data-target="#gtime" name="game_time" id="game_time"/>
                                  <div class="input-group-append" data-target="#gtime" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                  </div>
                                </div>
                                <?php $__errorArgs = ['game_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                              </div>
                            </div>
                            <div class="form-group row ">
                              <label for='selGym' class="col-sm-4 col-form-label"><?php echo e(trans_choice('gym.gym',1), false); ?></label>
                                  <div class="col-sm-6">
                                    <select class='js-gym-single js-states form-control select2 <?php $__errorArgs = ['gym_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> /> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' id='selGym' name="gym_id">
                                       <option id="selOption" value="" selected="selected"></option>
                                    </select>
                                    <?php $__errorArgs = ['gym_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-info"><?php echo e(__('Submit'), false); ?></button>
                            </div>
                        </div>

                </div>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>
<!--Modal: modalRelatedContent-->
<?php $__env->startPush('js'); ?>

<script>
    $(document).ready(function() {
      $('#gtime').datetimepicker({
          format: 'LT',
          locale: '<?php echo e(app()->getLocale(), false); ?>',
          stepping: 15,
          useCurrent: false,
          disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 22, 23, 24],
          enabledHours: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
      });

      $('#gdate').datetimepicker({
          format: 'L',
          locale: '<?php echo e(app()->getLocale(), false); ?>',
          useCurrent: false
      });


      $("#modalEditGamedate").on('show.bs.modal',  function (e) {
        var urlgyms = "<?php echo e(route('gym.sb.club',['club' => $club->id]), false); ?>";
        var gymSelect = $('#selGym');
        var urlgym = "<?php echo e(route('club.gym.show',['club' => $club->id, 'gym_no' => ':gymno:']), false); ?>";
        urlgym = urlgym.replace(':gymno:', $("#gym_no").val());

        $("#selGym").select2({
            placeholder: "<?php echo e(__('gym.action.select'), false); ?>...",
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
            dropdownParent: $('#modalEditGamedate'),
            ajax: {
                    url: urlgyms,
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

        $.ajax({
            type: 'GET',
            url: urlgym
        }).then(function (data) {
            // create the option and append to Select2
            var option = new Option(data[0].text, data[0].id, true, true);
            gymSelect.append(option).trigger('change');

            // manually trigger the `select2:select` event
            gymSelect.trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            });
        });
      });


      });







</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/dunkonxt/resources/views/game/includes/edit_gamedate.blade.php ENDPATH**/ ?>