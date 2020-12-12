<?php $__env->startSection('plugins.Moment', true); ?>
<?php $__env->startSection('plugins.TempusDominus', true); ?>
<?php $__env->startSection('plugins.Select2', true); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->get('team.title.modify', ['team'=> $team->club['shortname'].' '.$team->team_no ]); ?> </h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="<?php echo e(route('team.update',['team' => $team]), false); ?>" method="POST">
                    <div class="card-body">
                        <input type="hidden" name="_method" value="PUT">
                        <?php echo csrf_field(); ?>
                        <?php if($errors->any()): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo app('translator')->get('Please fix the following errors'); ?>
                        </div>
                        <?php endif; ?>

                        <div class="form-group row ">
                            <label for='selTeamNo' class="col-sm-4 col-form-label"><?php echo app('translator')->get('team.no'); ?></label>
                            <div class="col-sm-6">
                                <select class='js-teamno-placeholder-single js-states form-control select2 <?php $__errorArgs = ['team_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> /> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' id='selTeamNo' name="team_no">
                                <?php for($i=1; $i<=9; $i++): ?>
                                  <option <?php if($i == $team->team_no): ?> selected <?php endif; ?> value="<?php echo e($i, false); ?>"><?php echo e($i, false); ?></option>
                                <?php endfor; ?>
                                </select>
                                <?php $__errorArgs = ['team_no'];
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
                        <div class="form-group row ">
                            <label for='selLeague' class="col-sm-4 col-form-label"><?php echo e(trans_choice('league.league',1), false); ?></label>
                            <div class="col-sm-6">
                                <select class='js-teamno-placeholder-single js-states form-control select2 <?php $__errorArgs = ['league_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> /> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' id='selLeague' name="league_id">
                                  <?php if($team->league_id): ?>
                                    <option  selected value="<?php echo e($team->league_id, false); ?>"><?php echo e($team->league['shortname'], false); ?></option>
                                  <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['league_id'];
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
                        <div class="form-group row ">
                            <label for="league_prev" class="col-sm-4 col-form-label"><?php echo app('translator')->get('team.league.previous'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['league_prev'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="league_prev" name="league_prev" placeholder="<?php echo app('translator')->get('team.league.previous'); ?>" value="<?php echo e($team->league_prev, false); ?>">
                                <?php $__errorArgs = ['league_prev'];
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
                        <div class="form-group row ">
                            <label for="shirt_color" class="col-sm-4 col-form-label"><?php echo app('translator')->get('team.shirtcolor'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['shirt_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="shirt_color" name="shirt_color" placeholder="<?php echo app('translator')->get('team.shirtcolor'); ?>" value="<?php echo e($team->shirt_color, false); ?>">
                                <?php $__errorArgs = ['shirt_color'];
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
                        
                        
                        <div class="form-group row ">
                            <label for='selTday' class="col-sm-4 col-form-label"><?php echo app('translator')->get('team.training'); ?></label>
                            <div class="col-sm-3">
                                <select class='js-tday-placeholder-single js-states form-control select2 <?php $__errorArgs = [' training_day'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' id='selTday' name="training_day">
                                  <option value="1" <?php if( $team->training_day == '1' ): ?> selected <?php endif; ?>><?php echo e(Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::MONDAY)->locale(Config::get('app.locale'))->dayName, false); ?>

                                  <option value="2" <?php if( $team->training_day == '2' ): ?> selected <?php endif; ?>><?php echo e(Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::TUESDAY)->locale(Config::get('app.locale'))->dayName, false); ?>

                                  <option value="3" <?php if( $team->training_day == '3' ): ?> selected <?php endif; ?>><?php echo e(Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::WEDNESDAY)->locale(Config::get('app.locale'))->dayName, false); ?>

                                  <option value="4" <?php if( $team->training_day == '4' ): ?> selected <?php endif; ?>><?php echo e(Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::THURSDAY)->locale(Config::get('app.locale'))->dayName, false); ?>

                                  <option value="5" <?php if( $team->training_day == '5' ): ?> selected <?php endif; ?>><?php echo e(Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::FRIDAY)->locale(Config::get('app.locale'))->dayName, false); ?>

                                  </option>
                                </select>
                                <?php $__errorArgs = ['training_day'];
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
                            <div class="col-sm-3">
                              <div class="input-group date" id="ttime" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#ttime" name="training_time" value="<?php echo e($team->training_time, false); ?>"/>
                                <div class="input-group-append" data-target="#ttime" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for='selGday' class="col-sm-4 col-form-label"><?php echo app('translator')->get('team.game.preferred'); ?></label>
                            <div class="col-sm-3">
                                <select class='js-gday-placeholder-single js-states form-control select2 <?php $__errorArgs = [' preferred_game_day'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>' id='selGday' name="preferred_game_day">
                                  
                                  <option value="6" <?php if( $team->preferred_game_day == '6' ): ?> selected <?php endif; ?>><?php echo e(Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::SATURDAY)->locale(Config::get('app.locale'))->dayName, false); ?>

                                  <option value="7" <?php if( $team->preferred_game_day == '7' ): ?> selected <?php endif; ?>><?php echo e(Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::SUNDAY)->locale(Config::get('app.locale'))->dayName, false); ?>

                                </select>
                                <?php $__errorArgs = ['preferred_game_day'];
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
                            <div class="col-sm-3">
                              <div class="input-group date" id="gtime" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#gtime" name="preferred_game_time"/>
                                <div class="input-group-append" data-target="#gtime" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for="coach_name" class="col-sm-4 col-form-label"><?php echo app('translator')->get('team.coach'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['coach_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="coach_name" name="coach_name" placeholder="<?php echo app('translator')->get('team.coach'); ?>" value="<?php echo e($team->coach_name, false); ?>">
                                <?php $__errorArgs = ['coach_name'];
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
                        <div class="form-group row ">
                            <label for="coach_email" class="col-sm-4 col-form-label"><?php echo app('translator')->get('team.email'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['coach_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="coach_email" name="coach_email" placeholder="<?php echo app('translator')->get('team.email'); ?>" value="<?php echo e(old('coach_email', $team->coach_email), false); ?>">
                                <?php $__errorArgs = ['coach_email'];
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
                        <div class="form-group row ">
                            <label for="coach_phone1" class="col-sm-4 col-form-label"><?php echo app('translator')->get('team.phone1'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['coach_phone1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="coach_phone1" name="coach_phone1" placeholder="<?php echo app('translator')->get('team.phone1'); ?>" value="<?php echo e($team->coach_phone1, false); ?>">
                                <?php $__errorArgs = ['coach_phone1'];
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
                        <div class="form-group row ">
                            <label for="coach_phone2" class="col-sm-4 col-form-label"><?php echo app('translator')->get('team.phone2'); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control <?php $__errorArgs = ['coach_phone2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="coach_phone2" name="coach_phone2" placeholder="<?php echo app('translator')->get('team.phone2'); ?>" value="<?php echo e($team->coach_phone2, false); ?>">
                                <?php $__errorArgs = ['coach_phone2'];
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
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-info"><?php echo e(__('Submit'), false); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(function() {

        $('#ttime').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
            userCurrent: false,
            disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 23, 24],
            enabledHours: [13, 14, 15, 16, 17, 18, 19, 20, 21, 22],
        });

        var ttime = moment("<?php echo e($team->training_time, false); ?>", 'HH:mm');
        $('input[name=training_time]').val(ttime.format('HH:mm'));

        $('#gtime').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
            userCurrent: false,
            disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 22, 23, 24],
            enabledHours: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
        });
        var gtime = moment("<?php echo e($team->preferred_game_time, false); ?>",'HH:mm');
        $('input[name=preferred_game_time]').val(gtime.format('HH:mm') );

        $("#selTday").select2({
            placeholder: "Select training day...",
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
            minimumResultsForSearch: 20
        });
        $("#selGday").select2({
            placeholder: "Select preferred game day...",
            theme: 'bootstrap4',
            multiple: false,
            allowClear: true,
            minimumResultsForSearch: 20
        });
        $("#selTeamNo").select2({
            placeholder: "Select team number...",
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
            minimumResultsForSearch: 20
        });
        $("#selLeague").select2({
            placeholder: "<?php echo app('translator')->get('league.action.select'); ?>...",
            theme: 'bootstrap4',
            multiple: false,
            allowClear: true,
            minimumResultsForSearch: 20,
            ajax: {
                    url: "<?php echo e(route('league.sb.club',['club' => $team->club_id]), false); ?>",
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
    });
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/team/team_edit.blade.php ENDPATH**/ ?>