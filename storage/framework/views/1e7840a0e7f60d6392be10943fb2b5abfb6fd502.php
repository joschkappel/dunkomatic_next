<nav class="main-header navbar
    <?php echo e(config('menu.classes_topnav_nav', 'navbar-expand'), false); ?>

    <?php echo e(config('menu.classes_topnav', 'navbar-white navbar-light'), false); ?>">

    
    <ul class="navbar-nav">
        
        <?php echo $__env->make('layouts.partials.navbar.menu-item-left-sidebar-toggler', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <?php echo $__env->renderEach('layouts.partials.navbar.menu-item', app(\App\Menu::class)->menu('navbar-left'), 'item'); ?>

        
        <?php echo $__env->yieldContent('content_top_nav_left'); ?>
    </ul>

    
    <ul class="navbar-nav ml-auto">
        
        <?php echo $__env->yieldContent('content_top_nav_right'); ?>

        
        
        <?php echo $__env->renderEach('layouts.partials.navbar.menu-item', app(\App\Menu::class)->menu('navbar-right'), 'item'); ?>

        
        <?php if(Auth::user()): ?>
            <?php if(config('menu.usermenu_enabled')): ?>
                <?php echo $__env->make('layouts.partials.navbar.menu-item-dropdown-user-menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php else: ?>
                <?php echo $__env->make('layouts.partials.navbar.menu-item-logout-link', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
        <?php endif; ?>

        
        <?php if(config('menu.right_sidebar')): ?>
            <?php echo $__env->make('layouts.partials.navbar.menu-item-right-sidebar-toggler', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </ul>

</nav>
<?php /**PATH /var/www/dunkonxt/resources/views/layouts/partials/navbar/navbar.blade.php ENDPATH**/ ?>