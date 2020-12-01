<li <?php if(isset($item['id'])): ?> id="<?php echo e($item['id'], false); ?>" <?php endif; ?> class="nav-item">

    <a class="nav-link <?php echo e($item['class'], false); ?>" href="<?php echo e($item['href'], false); ?>"
       <?php if(isset($item['target'])): ?> target="<?php echo e($item['target'], false); ?>" <?php endif; ?>
       <?php echo $item['data-compiled'] ?? ''; ?>>

        
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

</li><?php /**PATH /var/www/dunkonxt/resources/views/layouts/partials/navbar/menu-item-link.blade.php ENDPATH**/ ?>