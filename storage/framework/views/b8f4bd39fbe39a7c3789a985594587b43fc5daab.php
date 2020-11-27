<?php $__env->startSection('app_css'); ?>
    <?php echo $__env->yieldContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('classes_body', 'login-page'); ?>

<?php ( $password_email_url = View::getSection('password_email_url') ?? config('dunkomatic.password_email_url', 'password/email') ); ?>
<?php ( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'home') ); ?>

<?php ( $password_email_url = $password_email_url ? route($password_email_url, app()->getLocale() ) : '' ); ?>
<?php ( $dashboard_url = $dashboard_url ? route($dashboard_url, app()->getLocale() ) : '' ); ?>

<?php $__env->startSection('body'); ?>
    <div class="login-box">
        <div class="login-logo">
            <a href="<?php echo e($dashboard_url, false); ?>"><?php echo config('menu.logo'); ?></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg"><?php echo e(__('auth.password_reset_message'), false); ?></p>
                <?php if(session('status')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('status'), false); ?>

                    </div>
                <?php endif; ?>
                <form action="<?php echo e($password_email_url, false); ?>" method="post">
                    <?php echo e(csrf_field(), false); ?>

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control <?php echo e($errors->has('email') ? 'is-invalid' : '', false); ?>" value="<?php echo e(old('email'), false); ?>" placeholder="<?php echo e(__('auth.email'), false); ?>" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <?php if($errors->has('email')): ?>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('email'), false); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                        <?php echo e(__('auth.send_password_reset_link'), false); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('app_js'); ?>
    <?php echo $__env->yieldPushContent('js'); ?>
    <?php echo $__env->yieldContent('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/auth/passwords/email.blade.php ENDPATH**/ ?>