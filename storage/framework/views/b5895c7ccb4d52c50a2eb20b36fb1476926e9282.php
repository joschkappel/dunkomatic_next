<table width="100%" class="table table-hover table-striped table-sm" id="pivottable">
    <tbody>
        <?php if(isset($plan)): ?>

        <?php $__currentLoopData = $plan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="d-flex">
            <?php $__currentLoopData = $gd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <td class="text-center col-2">
                <?php if($values === ' '): ?><span></span>
                <?php elseif($key === 'Game Date'): ?><?php echo e($values, false); ?>

                <?php else: ?><span class="badge badge-pill badge-info"><?php echo e($values, false); ?></span>
                <?php endif; ?>
            </td>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php endif; ?>
        <?php if(empty($plan)): ?>
        <tr>
            <td>
                <div>
                    empty
                </div>
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php /**PATH /var/www/dunkonxt/resources/views/team/teamleague_pivot.blade.php ENDPATH**/ ?>