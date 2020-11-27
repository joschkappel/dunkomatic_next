<li class="nav-item">
    <a class="nav-link" data-widget="pushmenu" href="#"
        <?php if(config('menu.sidebar_collapse_remember')): ?>
            data-enable-remember="true"
        <?php endif; ?>
        <?php if(!config('menu.sidebar_collapse_remember_no_transition')): ?>
            data-no-transition-after-reload="false"
        <?php endif; ?>
        <?php if(config('menu.sidebar_collapse_auto_size')): ?>
            data-auto-collapse-size="<?php echo e(config('menu.sidebar_collapse_auto_size'), false); ?>"
        <?php endif; ?>>
        <i class="fas fa-bars"></i>
        <span class="sr-only"><?php echo e(__('auth.toggle_navigation'), false); ?></span>
    </a>
</li>
<?php /**PATH /var/www/dunkonxt/resources/views/layouts/partials/navbar/menu-item-left-sidebar-toggler.blade.php ENDPATH**/ ?>