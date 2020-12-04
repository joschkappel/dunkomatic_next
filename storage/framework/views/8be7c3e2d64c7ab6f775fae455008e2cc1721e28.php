
<div class="card-body" id="pivottable" >
<?php if(isset($scheme)): ?>
                    <table width="100%" class="table table-hover table-striped table-bordered table-sm" id="table">
                        <thead class="thead-light">
                          <tr>
                            <?php $__currentLoopData = $scheme[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <th class="text-center"><h6><span class="badge badge-pill badge-danger"><?php if($key == 'game_day'): ?> <?php echo app('translator')->get('schedule.game_day'); ?> <?php else: ?> <?php echo e($key, false); ?> <?php endif; ?></span></h6></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $__currentLoopData = $scheme; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                          <?php $__currentLoopData = $gd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td class="text-center"><h6><?php if($values === ' '): ?> <?php elseif($key === 'game_day'): ?><span class="badge badge-pill badge-dark"><?php echo e($values, false); ?></span><?php else: ?>- <span class="badge badge-pill badge-info"><?php echo e($values, false); ?></span><?php endif; ?></h6></td>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
                    </table>
<?php endif; ?>
<?php if(empty($scheme)): ?>

<?php endif; ?>
</div>
<?php /**PATH /var/www/dunkonxt/resources/views/league/includes/league_scheme_pivot.blade.php ENDPATH**/ ?>