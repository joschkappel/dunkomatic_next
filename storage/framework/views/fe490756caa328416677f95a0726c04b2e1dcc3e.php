<?php $__env->startSection('plugins.ICheck',true); ?>
<?php $__env->startSection('plugins.Select2',true); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">

                <!-- general form elements -->
                <div class="card card-info">
                  <div class="card-header">
                      <h3 class="card-title"><?php echo app('translator')->get('league.title.new', ['region'=>Auth::user()->region ]); ?></h3>
                  </div>
                  <!-- /.card-header -->
                    <form class="form-horizontal" action="<?php echo e(route('league.store', app()->getLocale()), false); ?>" method="post">
                        <div class="card-body">
                            <?php echo csrf_field(); ?>
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo app('translator')->get('Please fix the following errors'); ?>
                            </div>
                            <?php endif; ?>
                            <div class="form-group row">
                                <label for="region" class="col-sm-4 col-form-label"><?php echo app('translator')->get('club.region'); ?></label>
                                <div class="col-sm-6">
                                    <input type="text" readonly class="form-control <?php $__errorArgs = ['region'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="region" name="region" placeholder="<?php echo app('translator')->get('club.region'); ?>" value="<?php echo e(Auth::user()->region, false); ?>">
                                    <?php $__errorArgs = ['region'];
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
                                <label for="shortname" class="col-sm-4 col-form-label"><?php echo app('translator')->get('league.shortname'); ?></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control <?php $__errorArgs = ['shortname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="shortname" name="shortname" placeholder="<?php echo app('translator')->get('league.shortname'); ?>" value="<?php echo e(old('shortname'), false); ?>">
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
unset($__errorArgs, $__bag); ?>" id="name" name="name" placeholder="<?php echo app('translator')->get('league.name'); ?>" value="<?php echo e(old('name'), false); ?>">
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
                                <label for="selSchedule" class="col-sm-4 col-form-label"><?php echo e(trans_choice('schedule.schedule',1), false); ?></label>
                                <div class="col-sm-6">
                                  <select class='js-sel-schedule js-states form-control select2 <?php $__errorArgs = ['schedule_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' id='selSchedule' name='schedule_id'></select>
                                  <?php $__errorArgs = ['schedule_id'];
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
                                       <option value="<?php echo e($at->value, false); ?>" ><?php echo e($at->description, false); ?></option>
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
                                       <option value="<?php echo e($gt->value, false); ?>" ><?php echo e($gt->description, false); ?></option>
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
                            <div class="form-group  clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" id="above_region" name="above_region" >
                                <label for="above_region" ><?php echo app('translator')->get('league.above-region'); ?></label>
                              </div>
                            </div>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" id="active" name="active" checked>
                                <label for="active"><?php echo e(__('Active'), false); ?> ?</label>
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
          minimumResultsForSearch: 10,
      });
      $("#selGenderType").select2({
          theme: 'bootstrap4',
          multiple: false,
          allowClear: false,
          minimumResultsForSearch: 10,
      });


      $(".js-sel-schedule").select2({
          placeholder: "<?php echo app('translator')->get('schedule.action.select'); ?>...",
          theme: 'bootstrap4',
          multiple: false,
          allowClear: true,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "<?php echo e(route('schedule.sb.region'), false); ?>",
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

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/league/league_new.blade.php ENDPATH**/ ?>