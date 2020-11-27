<?php $__env->startSection('app_css_pre'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css'), false); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('app_css'); ?>
    <?php echo $__env->yieldPushContent('css'); ?>
    <?php echo $__env->yieldContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('classes_body', 'login-page'); ?>

<?php ( $login_url = View::getSection('login_url') ?? config('dunkomatic.login_url', 'login') ); ?>
<?php ( $register_url = View::getSection('register_url') ?? config('dunkomatic.register_url', 'register') ); ?>
<?php ( $password_reset_url = View::getSection('password_reset_url') ?? config('dunkomatic.password_reset_url', 'password/reset') ); ?>
<?php ( $dashboard_url = View::getSection('dashboard_url') ?? config('dunkomatic.dashboard_url', 'home') ); ?>

<?php ( $login_url = $login_url ? route($login_url, app()->getLocale() ) : '' ); ?>
<?php ( $register_url = $register_url ? route($register_url, app()->getLocale()) : '' ); ?>
<?php ( $password_reset_url = $password_reset_url ? route($password_reset_url, [app()->getLocale(),'']) : '' ); ?>
<?php ( $dashboard_url = $dashboard_url ? route($dashboard_url, app()->getLocale() ) : '' ); ?>

<?php $__env->startSection('body'); ?>
    <div class="login-box">
        <div class="login-logo">
            <a href="<?php echo e($dashboard_url, false); ?>"><?php echo config('menu.logo'); ?></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg"><?php echo e(__('auth.login_message'), false); ?></p>
                <form action="<?php echo e($login_url, false); ?>" method="post">
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
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control <?php echo e($errors->has('password') ? 'is-invalid' : '', false); ?>" placeholder="<?php echo e(__('auth.password'), false); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <?php if($errors->has('password')): ?>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('password'), false); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember" id="remember">
                                <label for="remember"><?php echo e(__('auth.remember_me'), false); ?></label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button dusk="login" type="submit" class="btn btn-primary btn-block btn-flat">
                                <?php echo e(__('auth.sign_in'), false); ?>

                            </button>
                        </div>
                    </div>
                </form>
                <?php if($password_reset_url): ?>
                    <p class="mt-2 mb-1">
                        <a href="<?php echo e($password_reset_url, false); ?>">
                            <?php echo e(__('auth.i_forgot_my_password'), false); ?>

                        </a>
                    </p>
                <?php endif; ?>

                <?php if($register_url): ?>
                    <p class="mb-0">
                        <a href="<?php echo e($register_url, false); ?>">
                            <?php echo e(__('auth.register_a_new_membership'), false); ?>

                        </a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('app_js'); ?>
    <?php echo $__env->yieldPushContent('js'); ?>
    <?php echo $__env->yieldContent('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/auth/login.blade.php ENDPATH**/ ?>