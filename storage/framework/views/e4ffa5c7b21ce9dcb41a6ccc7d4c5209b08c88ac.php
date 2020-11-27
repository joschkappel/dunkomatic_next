<li <?php if(isset($item['id'])): ?> id="<?php echo e($item['id'], false); ?>" <?php endif; ?> class="nav-item dropdown">

    
    <a class="nav-link dropdown-toggle" href=""
       data-toggle="dropdown" <?php echo $item['data-compiled'] ?? ''; ?>>

        
        <?php if(isset($item['icon'])): ?>
            <i class="<?php echo e($item['icon'], false); ?> <?php echo e(isset($item['icon_color']) ? 'text-' . $item['icon_color'] : '', false); ?>"></i>
        <?php endif; ?>

        
        <?php echo e($item['text'], false); ?>


        
        <?php if(isset($item['label'])): ?>
            <span class="badge badge-<?php echo e($item['label_color'] ?? 'primary', false); ?>">
                <?php echo e($item['label'], false); ?>

            </span>
        <?php endif; ?>

    </a>

    
    <ul class="dropdown-menu border-0 shadow">
        <?php echo $__env->renderEach('layouts.partials.navbar.dropdown-item', $item['submenu'], 'item'); ?>
    </ul>

</li>
<?php /**PATH /var/www/dunkonxt/resources/views/layouts/partials/navbar/menu-item-dropdown-menu.blade.php ENDPATH**/ ?>