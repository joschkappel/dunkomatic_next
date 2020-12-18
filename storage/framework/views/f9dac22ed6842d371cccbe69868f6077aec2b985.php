<?php $__env->startSection('plugins.ICheck', true); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-info">
                  <div class="card-header">
                      <h3 class="card-title"><?php echo app('translator')->get('league.title.edit', ['league'=>$league->shortname ]); ?></h3>
                  </div>
                  <!-- /.card-header -->
                  <form class="form-horizontal" action="<?php echo e(route('league.update',['language'=>app()->getLocale(), 'league' => $league]), false); ?>" method="post">
                        <div class="card-body">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo app('translator')->get('Please fix the following errors'); ?>
                            </div>
                            <?php endif; ?>
                            <div class="form-group row">
                                <label for="shortname" class="col-sm-4 col-form-label"><?php echo app('translator')->get('league.shortname'); ?></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control <?php $__errorArgs = ['shortname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="shortname" name="shortname" placeholder="<?php echo app('translator')->get('league.shortname'); ?>" value="<?php echo e($league->shortname, false); ?>">
                                    <?php $__errorArgs = ['shortname'];
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
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label"><?php echo app('translator')->get('league.name'); ?></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" placeholder="<?php echo app('translator')->get('league.shortname'); ?>" value="<?php echo e($league->name, false); ?>">
                                    <?php $__errorArgs = ['name'];
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
                            <div class="form-group row">
                                <label for="selSchedule" class="col-sm-4 col-form-label"><?php echo e(trans_choice('league.schedule',1), false); ?></label>
                                <div class="col-sm-6">
                                  <select class='js-example-placeholder-single js-states form-control select2' id='selSchedule' name='schedule_id'>
                                  <?php if( $league->schedule_id ): ?>
                                     <option value="<?php echo e($league->schedule_id, false); ?>" selected="selected"><?php echo e($league->schedule['name'], false); ?></option>
                                  <?php endif; ?>
                                  </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="selAgeType" class="col-sm-4 col-form-label"><?php echo app('translator')->get('league.agetype'); ?></label>
                                <div class="col-sm-6">
                                  <select class='js-placeholder-single js-states form-control select2 <?php $__errorArgs = ['age_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' id='selAgeType' name='age_type'>
                                     <?php $__currentLoopData = $agetype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $at): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                       <option value="<?php echo e($at->value, false); ?>" <?php if( $league->age_type == $at->value ): ?> selected="selected" <?php endif; ?> ><?php echo e($at->description, false); ?></option>
                                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </select>
                                  <?php $__errorArgs = ['age_type'];
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
                            <div class="form-group row">
                                <label for="selGenderType" class="col-sm-4 col-form-label"><?php echo app('translator')->get('league.gendertype'); ?></label>
                                <div class="col-sm-6">
                                  <select class='js-placeholder-single js-states form-control select2 <?php $__errorArgs = ['gender_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' id='selGenderType' name='gender_type'>
                                     <?php $__currentLoopData = $gendertype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                       <option value="<?php echo e($gt->value, false); ?>" <?php if( $league->gender_type == $gt->value ): ?> selected="selected" <?php endif; ?>><?php echo e($gt->description, false); ?></option>
                                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </select>
                                  <?php $__errorArgs = ['gender_type'];
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
                            <div class="form-group  row">
                              <div class="icheck-info ">
                                <input type="checkbox" id="above_region" name="above_region"
                                <?php if($league->above_region): ?> checked <?php endif; ?>>
                                <label for="above_region" ><?php echo app('translator')->get('league.above-region'); ?> ?</label>
                              </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-primary"><?php echo e(__('Submit'), false); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<script>
    $(function() {

      $("#selAgeType").select2({
          theme: 'bootstrap4',
          multiple: false,
          allowClear: false,
          minimumResultsForSearch: 10
      });
      $("#selGenderType").select2({
          theme: 'bootstrap4',
          multiple: false,
          allowClear: false,
          minimumResultsForSearch: 10
      });


      $("#selSchedule").select2({
          placeholder: "Select a schedule...",
          theme: 'bootstrap4',
          multiple: false,
          allowClear: true,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "<?php echo e(route('schedule.sb.region', ['region' => $league->region_id]), false); ?>",
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

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/league/league_edit.blade.php ENDPATH**/ ?>