<?php $__env->startSection('plugins.ICheck',true); ?>
<?php $__env->startSection('plugins.Select2', true); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('role.title.edit', ['member'=> $member->firstname.' '.$member->lastname] ); ?></h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form id="editMembership" class="form-horizontal" action="<?php echo e(route('membership.club.update',['club' => $club, 'member' => $member]), false); ?>" method="POST">
                        <?php echo method_field('PUT'); ?>
                        <?php echo csrf_field(); ?>
                        <?php if($errors->any()): ?>
                        <div class="alert alert-danger" role="alert">
                           <?php echo app('translator')->get('Please fix the following errors'); ?>
                        </div>
                        <?php endif; ?>
                        <?php if(session('member_mod')): ?>
                            <div class="alert alert-success">
                              Member updated: <?php echo e(session('member_mod')->name, false); ?>

                            </div>
                        <?php endif; ?>
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label" for='selRole'><?php echo e(trans_choice('role.role',1), false); ?></label>
                          <div class="col-sm-6">
                            <select class='js-sel-role js-states form-control select2 <?php $__errorArgs = ['selRole'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' name="selRole[]" id='selRole'>
                             <?php $__currentLoopData = $membership; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mship): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                               <option value="<?php echo e($mship->role_id, false); ?>" ><?php echo e(App\Enums\Role::getDescription($mship->role_id), false); ?></option>
                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['selRole'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">PLs select at least one Role</div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="function" class="col-sm-4 col-form-label"><?php echo app('translator')->get('role.function'); ?></label>
                          <div class="col-sm-6">
                              <input type="text" class="form-control <?php $__errorArgs = ['function'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="function" name="function" placeholder="<?php echo app('translator')->get('role.function'); ?>" value="<?php echo e(old('function'), false); ?>">
                              <?php $__errorArgs = ['function'];
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
                          <label for="function" class="col-sm-4 col-form-label"><?php echo app('translator')->get('role.member.action.create'); ?></label>
                        </div>
                        <div class="form-group row">
                          <div class="col-sm-10">
                          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="button" id="btnUpdateMember" class="btn btn-secondary" form="#" data-target="#updateMember" data-toggle="collapse">Modify Member</button>
                            <?php if($members->count() > 0): ?>
                            <div class="btn-group" role="group">
                              <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Select from Club
                              </button>
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <a class="dropdown-item" href="#" onclick="show_member(<?php echo e($m->id, false); ?>,'<?php echo e($m->name, false); ?>','<?php echo e($m->street, false); ?>','<?php echo e($m->zipcode, false); ?>','<?php echo e($m->city, false); ?>','<?php echo e($m->email1, false); ?>','<?php echo e($m->phone1, false); ?>'); return false"><?php echo e($m->name, false); ?></a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </div>
                            </div>
                            <?php endif; ?>
                            <button type="button" id="btnSelectMember" class="btn btn-secondary">Select from Region</button>
                            <?php if(!session('member_mod')): ?>
                            <button type="button" id="btnClear" class="btn btn-secondary">Clear</button>
                          <?php endif; ?>
                          </div>
                          </div>
                        </div>
                        <?php echo $__env->make('member.includes.member_show', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <button type="submit" class="btn btn-info"><?php echo e(__('Submit'), false); ?></button>
                      </form>
                    </div>
                </div>
            </div>
            <?php echo $__env->make('member.includes.member_edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('member.includes.member_select', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    function show_member(id, name, street, zipcode, city, email1, mobile) {
      $('#mname').val(name);
      $('#mstreet').val(street);
      $('#mzipcode').val(zipcode);
      $('#mcity').val(city);
      $('#memail1').val(email1);
      $('#mmobile').val(mobile);
      $('#member_id').val(id);
    }

    $(function() {
      <?php if($errors->err_member->any()): ?>
        $("#updateMember").collapse("toggle");
      <?php endif; ?>

      <?php if(session('member_mod')): ?>
       show_member( <?php echo e(session('member_mod')->id, false); ?>,
                    '<?php echo e(session('member_mod')->name, false); ?>',
                    '<?php echo e(session('member_mod')->street, false); ?>',
                    '<?php echo e(session('member_mod')->zipcode, false); ?>',
                    '<?php echo e(session('member_mod')->city, false); ?>',
                    '<?php echo e(session('member_mod')->email1, false); ?>',
                    '<?php echo e(session('member_mod')->mobile, false); ?>');
      <?php else: ?>
        show_member( <?php echo e($member->id, false); ?>,
                     '<?php echo e($member->name, false); ?>',
                     '<?php echo e($member->street, false); ?>',
                     '<?php echo e($member->zipcode, false); ?>',
                     '<?php echo e($member->city, false); ?>',
                     '<?php echo e($member->email1, false); ?>',
                     '<?php echo e($member->mobile, false); ?>');
      <?php endif; ?>

      $("button#btnSelectMember").click( function(){
         $('#modalSelectMember').modal('show');
      });
      $("button#btnClear").click( function(){
        show_member( <?php echo e($member->id, false); ?>,
                     '<?php echo e($member->name, false); ?>',
                     '<?php echo e($member->street, false); ?>',
                     '<?php echo e($member->zipcode, false); ?>',
                     '<?php echo e($member->city, false); ?>',
                     '<?php echo e($member->email1, false); ?>',
                     '<?php echo e($member->mobile, false); ?>');
         $("#updateMember").collapse("hide");
      });

      $(".js-sel-role").select2({
          placeholder: "<?php echo app('translator')->get('role.action.select'); ?>...",
          theme: 'bootstrap4',
          multiple: true,
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "<?php echo e(route('role.index'), false); ?>",
                  type: "POST",
                  dataType: "json",
                  data: {
                       "_token": "<?php echo e(csrf_token(), false); ?>",
                       "scope": 'ALL'
                   },
                  delay: 250,
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
      });
      $("#selRole").val(<?php echo e(collect($membership->pluck('role_id')), false); ?> ).change();

      $(".js-sel-member").select2({
          placeholder: "<?php echo app('translator')->get('role.member.action.select'); ?>...",
          theme: 'bootstrap4',
          multiple: false,
          allowClear: true,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "<?php echo e(route('member.sb.region', ['region' => $club->region()->first()->id]), false); ?>",
                  type: "get",
                  delay: 250,
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
      });


      $('#selMember').on('select2:select', function(e) {
                var values = $('#selMember').select2('data');
                var selVals = values.map(function(elem) {
                  return {
                    id: elem.id,
                    text: elem.text
                  };
                });

                console.log(selVals);
                var url = "<?php echo e(route('member.show', ['language'=>app()->getLocale(), 'member'=>':member:']), false); ?>";
                url = url.replace(':member:', selVals[0].id);
                $.ajax({
                  type: 'GET',
                  url: url,
                  success: function (data) {
                    show_member(data.id,
                                data.firstname+' '+data.lastname,
                                data.street,
                                data.zipcode,
                                data.city,
                                data.email1,
                                data.mobile);
                  },
                });
            });

      $('#selMember').on('select2:unselect select2:clear', function(e) {
          show_member('','','','','','','','');
      });


    });

</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/member/membership_club_edit.blade.php ENDPATH**/ ?>