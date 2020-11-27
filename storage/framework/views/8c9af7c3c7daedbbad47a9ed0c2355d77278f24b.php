<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Users List to Approve</div>

                    <div class="card-body">

                        <?php if(session('message')): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo e(session('message'), false); ?>

                            </div>
                        <?php endif; ?>

                        <table width="100%" class="table table-striped table-border">
                            <tr>
                                <th>User name</th>
                                <th>Email</th>
                                <th>Registered at</th>
                                <th></th>
                            </tr>
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($auser->name, false); ?></td>
                                    <td><?php echo e($auser->email, false); ?></td>
                                    <td><?php echo e($auser->created_at, false); ?></td>
                                    <td><a href="<?php echo e(route('admin.user.edit', ['language' => app()->getLocale(), 'user' => $auser->id ]), false); ?>"
                                           class="btn btn-primary btn-sm">Approve</a></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/auth/users.blade.php ENDPATH**/ ?>