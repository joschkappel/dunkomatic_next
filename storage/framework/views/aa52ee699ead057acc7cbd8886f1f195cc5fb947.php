<?php $menuItemHelper = app('\App\Helpers\MenuItemHelper'); ?>

<?php if($menuItemHelper->isSubmenu($item)): ?>

    
    <?php echo $__env->make('layouts.partials.navbar.dropdown-item-submenu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php elseif($menuItemHelper->isLink($item)): ?>

    
    <?php echo $__env->make('layouts.partials.navbar.dropdown-item-link', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php endif; ?>
<?php /**PATH /var/www/dunkonxt/resources/views/layouts/partials/navbar/dropdown-item.blade.php ENDPATH**/ ?>