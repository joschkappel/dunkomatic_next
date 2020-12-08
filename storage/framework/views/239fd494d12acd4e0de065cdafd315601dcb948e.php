<?php $__env->startSection('plugins.Select2', true); ?>

<?php $__env->startSection('app_css'); ?>
    <?php echo $__env->yieldPushContent('css'); ?>
    <?php echo $__env->yieldContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('classes_body', 'register-page'); ?>

<?php ( $login_url = View::getSection('login_url') ?? config('dunkomatic.login_url', 'login') ); ?>
<?php ( $register_url = View::getSection('register_url') ?? config('dunkomatic.register_url', 'register') ); ?>
<?php ( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'home') ); ?>

<?php ( $login_url = $login_url ? route($login_url, app()->getLocale()) : '' ); ?>
<?php ( $register_url = $register_url ? route($register_url, app()->getLocale()) : '' ); ?>
<?php ( $dashboard_url = $dashboard_url ? route($dashboard_url,app()->getLocale()) : '' ); ?>

<?php $__env->startSection('body'); ?>
    <div class="register-box">
        <div class="register-logo">
            <a href="<?php echo e($dashboard_url, false); ?>"><?php echo config('menu.logo'); ?></a>
        </div>
        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg"><?php echo e(__('auth.register_message'), false); ?></p>
                <form action="<?php echo e($register_url, false); ?>" method="post">
                    <?php echo e(csrf_field(), false); ?>


                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control <?php echo e($errors->has('name') ? 'is-invalid' : '', false); ?>" value="<?php echo e(old('name'), false); ?>"
                               placeholder="<?php echo e(__('auth.full_name'), false); ?>" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>

                        <?php if($errors->has('name')): ?>
                            <div class="invalid-feedback">
                                <strong><?php echo e($errors->first('name'), false); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control <?php echo e($errors->has('email') ? 'is-invalid' : '', false); ?>" value="<?php echo e(old('email'), false); ?>"
                               placeholder="<?php echo e(__('auth.email'), false); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <?php if($errors->has('email')): ?>
                            <div class="invalid-feedback">
                                <strong><?php echo e($errors->first('email'), false); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control <?php echo e($errors->has('password') ? 'is-invalid' : '', false); ?>"
                               placeholder="<?php echo e(__('auth.password'), false); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <?php if($errors->has('password')): ?>
                            <div class="invalid-feedback">
                                <strong><?php echo e($errors->first('password'), false); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control <?php echo e($errors->has('password_confirmation') ? 'is-invalid' : '', false); ?>"
                               placeholder="<?php echo e(__('auth.retype_password'), false); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <?php if($errors->has('password_confirmation')): ?>
                            <div class="invalid-feedback">
                                <strong><?php echo e($errors->first('password_confirmation'), false); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group mb-3">
                        <select class='sel-region js-states form-control select2' id='selRegion' name='region_id'>
                      </select>
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button" data-select2-open="region_id">
                          <span class="fas fa-globe-europe"></span>
                        </button>
                      </span>
                    </div>
                    <div class="input-group mb-3">
                        <input type="input" name="reason_join" class="form-control <?php echo e($errors->has('reason_join') ? 'is-invalid' : '', false); ?>" value="<?php echo e(old('reason_join'), false); ?>"
                               placeholder="<?php echo e(__('auth.reason_join'), false); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="far fa-question-circle"></span>
                            </div>
                        </div>
                        <?php if($errors->has('reason_join')): ?>
                            <div class="invalid-feedback">
                                <strong><?php echo e($errors->first('reason_join'), false); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                        <?php echo e(__('auth.register'), false); ?>

                    </button>
                </form>
                <p class="mt-2 mb-1">
                    <a href="<?php echo e($login_url, false); ?>">
                        <?php echo e(__('auth.i_already_have_a_membership'), false); ?>

                    </a>
                </p>
            </div><!-- /.card-body -->
        </div><!-- /.card -->
    </div><!-- /.register-box -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('app_js'); ?>

    <?php echo $__env->yieldPushContent('js'); ?>
    <script>
      $(document).ready(function(){

          $("#selRegion").select2({
              multiple: false,
              theme: 'bootstrap4',
              allowClear: false,
              minimumResultsForSearch: 10,
              placeholder: "<?php echo e(__('club.region'), false); ?>",
              ajax: {
                      url: "<?php echo e(route('region.admin.sb'), false); ?>",
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
    <?php echo $__env->yieldContent('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/auth/register.blade.php ENDPATH**/ ?>