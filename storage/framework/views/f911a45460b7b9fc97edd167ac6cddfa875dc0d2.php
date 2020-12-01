<div class="col-md-4">
  <div class="card card-primary collapse" id="updateMember">
    <div class="card-header">
      <?php if(isset($club)): ?>
        <h3 class="card-title"><?php echo app('translator')->get('role.title.edit', ['unittype'=> trans_choice('club.club',1), 'unitname' => $club->shortname ]); ?></h3>
      <?php else: ?>
        <h3 class="card-title"><?php echo app('translator')->get('role.title.edit', ['unittype'=> trans_choice('league.league',1), 'unitname' => $league->shortname ]); ?></h3>
      <?php endif; ?>
    </div>
    <!-- /.card-header -->
      <div class="card-body">
          <form id="editMember" class="form-horizontal" action="<?php echo e(route('member.update',['member'=>$member]), false); ?>" method="POST">
              <?php echo csrf_field(); ?>
              <?php echo method_field('PUT'); ?>
              <?php if($errors->err_member->any()): ?>
              <div class="alert alert-danger" role="alert">
                 <?php echo app('translator')->get('Please fix the following errors'); ?>
              </div>
              <?php endif; ?>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control <?php $__errorArgs = ['firstname','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="firstname" name="firstname" placeholder="<?php echo app('translator')->get('role.firstname'); ?>" value="<?php echo e(old('firstname') ? old('firstname') : $member->firstname, false); ?>"></input>
                      <?php $__errorArgs = ['firstname','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control <?php $__errorArgs = ['lastname','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="lastname" name="lastname" placeholder="<?php echo app('translator')->get('role.lastname'); ?>" value="<?php echo e(old('lastname') ? old('lastname') : $member->lastname, false); ?>"></input>
                      <?php $__errorArgs = ['lastname','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control <?php $__errorArgs = ['street','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="street" name="street" placeholder="<?php echo app('translator')->get('role.street'); ?>" value="<?php echo e(old('street') ? old('street') : $member->street, false); ?>"></input>
                      <?php $__errorArgs = ['street','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control <?php $__errorArgs = ['zipcode','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="zipcode" name="zipcode" placeholder="<?php echo app('translator')->get('role.zipcode'); ?>" value="<?php echo e(old('zipcode') ? old('zipcode') : $member->zipcode, false); ?>"></input>
                      <?php $__errorArgs = ['zipcode','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control <?php $__errorArgs = ['city','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="city" name="city" placeholder="<?php echo app('translator')->get('role.city'); ?>" value="<?php echo e(old('city') ? old('city') : $member->city, false); ?>"></input>
                      <?php $__errorArgs = ['city','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control <?php $__errorArgs = ['mobile','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="mobile" name="mobile" placeholder="<?php echo app('translator')->get('role.mobile'); ?>" value="<?php echo e(old('mobile') ? old('mobile') : $member->mobile, false); ?>"></input>
                      <?php $__errorArgs = ['mobile','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control <?php $__errorArgs = ['phone1','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="phone1" name="phone1" placeholder="<?php echo app('translator')->get('role.phone1'); ?>" value="<?php echo e(old('phone1') ? old('phone1') : $member->phone1, false); ?>"></input>
                      <?php $__errorArgs = ['phone1','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control <?php $__errorArgs = ['phone2','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="phone2" name="phone2" placeholder="<?php echo app('translator')->get('role.phone2'); ?>" value="<?php echo e(old('phone2') ? old('phone2') : $member->phone2, false); ?>"></input>
                      <?php $__errorArgs = ['phone2','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control <?php $__errorArgs = ['email1','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="email1" name="email1" placeholder="<?php echo app('translator')->get('role.email1'); ?>" value="<?php echo e(old('email1') ? old('email1') : $member->email1, false); ?>"></input>
                      <?php $__errorArgs = ['email1','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control <?php $__errorArgs = ['email2','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="email2" name="email2" placeholder="<?php echo app('translator')->get('role.email2'); ?>" value="<?php echo e(old('email2') ? old('email2') : $member->email2, false); ?>"></input>
                      <?php $__errorArgs = ['email2','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control <?php $__errorArgs = ['fax1','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="fax1" name="fax1" placeholder="<?php echo app('translator')->get('role.fax1'); ?>" value="<?php echo e(old('fax1') ? old('fax1') : $member->fax1, false); ?>"></input>
                      <?php $__errorArgs = ['fax1','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control <?php $__errorArgs = ['fax2','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="fax2" name="fax2" placeholder="<?php echo app('translator')->get('role.fax2'); ?>" value="<?php echo e(old('fax2') ? old('fax2') : $member->fax2, false); ?>"></input>
                      <?php $__errorArgs = ['fax2','err_member'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message, false); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
              </div>
              <!--/.Content-->
              <button type="submit" class="btn btn-info"><?php echo e(__('Submit'), false); ?></button>
          </form>
      </div>
  </div>
  <!--Modal: modalRelatedContent-->
  </div>
<?php /**PATH /var/www/dunkonxt/resources/views/member/includes/member_edit.blade.php ENDPATH**/ ?>