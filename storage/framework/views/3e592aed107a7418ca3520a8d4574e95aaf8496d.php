<?php $__env->startSection('plugins.Summernote', true); ?>
<?php $__env->startSection('plugins.Moment', true); ?>
<?php $__env->startSection('plugins.TempusDominus', true); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('message.title.edit', ['region' => session('cur_region')->name ]); ?></h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="<?php echo e(route('message.update',['message'=>$message['message'],'region'=>session('cur_region')->id]), false); ?>" method="post">
                    <div class="card-body">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <?php if($errors->any()): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo app('translator')->get('Please fix the following errors'); ?>
                        </div>
                        <?php endif; ?>
                        <input type="hidden" class="form-control" id="author" name="author" value="<?php echo e(Auth::user()->id, false); ?>">
                        <input type="hidden" class="form-control" id="region_id" name="dest.region_id" value="<?php echo e(Auth::user()->region, false); ?>">
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 col-form-label"><?php echo app('translator')->get('message.title'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="title" name="title" placeholder="<?php echo app('translator')->get('message.title'); ?>" value="<?php echo e((old('title')!='') ? old('title') : $message['message']->title, false); ?>">
                                <?php $__errorArgs = ['title'];
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
                            <label for="greeting" class="col-sm-4 col-form-label"><?php echo app('translator')->get('message.greeting'); ?></label>
                            <div class="col-sm-6">
                              <textarea class="form-control <?php $__errorArgs = ['greeting'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="greeting" id="greeting"></textarea>
                              <?php $__errorArgs = ['greeting'];
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
                            <label for="body" class="col-sm-4 col-form-label"><?php echo app('translator')->get('message.body'); ?></label>
                            <div class="col-sm-6">
                              <textarea class="form-control <?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="body" id="summernote"></textarea>
                              <?php $__errorArgs = ['body'];
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
                            <label for="salutation" class="col-sm-4 col-form-label"><?php echo app('translator')->get('message.salutation'); ?></label>
                            <div class="col-sm-6">
                              <textarea class="form-control <?php $__errorArgs = ['salutation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="salutation" id="salutation" ></textarea>
                              <?php $__errorArgs = ['salutation'];
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
                            <label for="send_at" class="col-sm-4 col-form-label"><?php echo app('translator')->get('message.send_at'); ?></label>
                            <div class="col-sm-6">
                                <div class="input-group date" id="send_at" data-target-input="nearest">
                                    <input type="text" name='send_at' id='send_at' class="form-control datetimepicker-input <?php $__errorArgs = ['send_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-target="#send_at" />
                                    <div class="input-group-append" data-target="#send_at" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <?php $__errorArgs = ['send_at'];
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
                        <div class="form-group row">
                            <label for="selDestTo" class="col-sm-4 col-form-label"><?php echo app('translator')->get('message.dest_to'); ?></label>
                            <div class="col-sm-6">
                              <select class='js-sel-to js-states form-control select2 <?php $__errorArgs = ["dest_to"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' id='selDestTo' name="dest_to[]">
                                 <?php $__currentLoopData = $scopetype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   <option value="<?php echo e($st->value, false); ?>"><?php echo e($st->description, false); ?></option>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                              <?php $__errorArgs = ["dest_to"];
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
                            <label for="selDestCc" class="col-sm-4 col-form-label"><?php echo app('translator')->get('message.dest_cc'); ?></label>
                            <div class="col-sm-6">
                              <select class='js-sel-cc js-states form-control select2 <?php $__errorArgs = ["dest_cc"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' id='selDestCc' name="dest_cc[]">
                                 <?php $__currentLoopData = $scopetype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   <option value="<?php echo e($st->value, false); ?>"><?php echo e($st->description, false); ?></option>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                              <?php $__errorArgs = ["dest_cc"];
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
        $('#summernote').summernote({
          lang: <?php if(app()->getLocale() == 'de'): ?> 'de-DE' <?php else: ?> 'en-US'  <?php endif; ?>,
          placeholder: 'Edit your message...',
          tabsize: 2,
          height: 100,
          toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear', 'italic']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['view', ['fullscreen', 'help']],
          ],
        });

        var content = <?php echo (old('body')!='') ? old('body') : json_encode($message['message']->body); ?>

        $('#summernote').summernote('code',content);

        $("#greeting").val('<?php echo e((old('greeting')!='') ? old('greeting') : $message['message']->greeting, false); ?>');
        $("#salutation").val('<?php echo e((old('salutation')!='') ? old('salutation') : $message['message']->salutation, false); ?>');
        $("#selDestTo").select2({
            theme: 'bootstrap4',
            multiple: true,
            allowClear: false,
        });
        $("#selDestTo").val(<?php echo e(json_encode(Arr::flatten($message['dest_to'])), false); ?> ).change();
        $("#selDestCc").select2({
            theme: 'bootstrap4',
            multiple: true,
            allowClear: false,
        });
        $("#selDestCc").val(<?php echo e(json_encode(Arr::flatten($message['dest_cc'])), false); ?> ).change();


        moment.locale('<?php echo e(app()->getLocale(), false); ?>');

        var send_at = '<?php echo e((old('send_at')!='') ? old('send_at') : $message['message']->send_at, false); ?>';
        var m_send_at = moment(send_at);

        $('#send_at').datetimepicker({
            format: 'L',
            locale: '<?php echo e(app()->getLocale(), false); ?>',
            defaultDate: m_send_at.format('L')
        });

      });

 </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/message/message_edit.blade.php ENDPATH**/ ?>