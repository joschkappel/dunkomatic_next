<?php ( $logout_url = View::getSection('logout_url') ?? config('dunkomatic.logout_url', 'logout') ); ?>
<?php ( $profile_url = View::getSection('profile_url') ?? config('dunkomatic.profile_url', 'profile') ); ?>

<?php ( $profile_url = $profile_url ? route($profile_url, ['language'=>app()->getLocale(),'user'=>Auth::user()]) : '' ); ?>
<?php ( $logout_url = $logout_url ? route($logout_url, app()->getLocale()) : '' ); ?>


<li class="nav-item dropdown user-menu">

    
    <a href="#" class="nav-link dropdown-toggle " data-toggle="dropdown">
        <span <?php if(config('menu.usermenu_image')): ?> class="d-none d-md-inline" <?php endif; ?>>
            <?php if(!Auth::user()->member()->first()->is_complete): ?>   <i class="fas fa-exclamation-triangle text-danger"></i><?php endif; ?> <?php echo e(Auth::user()->name, false); ?>

        </span>
    </a>

    
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

        
        <?php if(!View::hasSection('usermenu_header') && config('menu.usermenu_header')): ?>
            <li class="user-header <?php echo e(config('menu.usermenu_header_class', 'bg-primary'), false); ?> h-auto">
                <p class="mt-0 ">
                      <?php if(!Auth::user()->member()->first()->is_complete): ?>   <i class="fas fa-exclamation-triangle text-white"></i><?php endif; ?> <?php echo e(Auth::user()->name, false); ?>

                </p>
            </li>
        <?php else: ?>
            <?php echo $__env->yieldContent('usermenu_header'); ?>
        <?php endif; ?>

        

        <?php echo $__env->renderEach('layouts.partials.navbar.dropdown-item', app(\App\Menu::class)->menu("navbar-user"), 'item'); ?>

        
        <?php if (! empty(trim($__env->yieldContent('usermenu_body')))): ?>
            <li class="user-body">
                <?php echo $__env->yieldContent('usermenu_body'); ?>
            </li>
        <?php endif; ?>

        
        <li class="user-footer">
            <?php if($profile_url): ?>
                <a href="<?php echo e($profile_url, false); ?>" class="btn btn-default btn-flat">
                    <i class="fa fa-fw fa-user"></i>
                    <?php echo e(__('auth.profile'), false); ?>

                </a>
            <?php endif; ?>
            <a class="btn btn-default btn-flat float-right <?php if(!$profile_url): ?> btn-block <?php endif; ?>"
               href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-fw fa-power-off"></i>
                <?php echo e(__('auth.log_out'), false); ?>

            </a>
            <form id="logout-form" action="<?php echo e($logout_url, false); ?>" method="POST" style="display: none;">
                <?php if(config('dunkomatic.logout_method')): ?>
                    <?php echo e(method_field(config('dunkomatic.logout_method')), false); ?>

                <?php endif; ?>
                <?php echo e(csrf_field(), false); ?>

            </form>
        </li>

    </ul>

</li>
<?php /**PATH /var/www/dunkonxt/resources/views/layouts/partials/navbar/menu-item-dropdown-user-menu.blade.php ENDPATH**/ ?>