<?php $__env->startSection('plugins.Slider',true); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header bg-secondary">
                    <h3 class="card-title"><?php echo app('translator')->get('region.title.edit', ['region' => session('cur_region')->name ]); ?></h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="<?php echo e(route('region.update',['region'=>$region]), false); ?>" method="post">
                    <div class="card-body">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <?php if($errors->any()): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo app('translator')->get('Please fix the following errors'); ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <label for="code" class="col-sm-6 col-form-label"><?php echo app('translator')->get('region.code'); ?></label>
                            <div class="col-sm-4">
                                <input type="text"  readonly class="form-control" id="code" name="code" value="<?php echo e((old('code')!='') ? old('code') : $region->code, false); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-6 col-form-label"><?php echo app('translator')->get('region.name'); ?></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo e((old('name')!='') ? old('name') : $region->name, false); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selNolead" class="col-sm-6 col-form-label"><?php echo app('translator')->get('region.job.noleads'); ?></label>
                            <div class="col-sm-4">
                              <select class='js-sel-noleads js-states form-control select2' id='selNolead' name="job_noleads">
                                <?php $__currentLoopData = $frequencytype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($ft->value == $region->job_noleads): ?> selected <?php endif; ?> value="<?php echo e($ft->value, false); ?>"><?php echo e($ft->description, false); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selEmailCheck" class="col-sm-6 col-form-label"><?php echo app('translator')->get('region.job.emails'); ?></label>
                            <div class="col-sm-4">
                              <select class='js-sel-emailcheck js-states form-control select2' id='selEmailCheck' name="job_email_valid">
                                <?php $__currentLoopData = $frequencytype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($ft->value == $region->job_email_valid): ?> selected <?php endif; ?> value="<?php echo e($ft->value, false); ?>"><?php echo e($ft->description, false); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selNotime" class="col-sm-6 col-form-label"><?php echo app('translator')->get('region.job.notime'); ?></label>
                            <div class="col-sm-4">
                              <select class='js-sel-notime js-states form-control select2' id='selNotime' name="job_game_notime">
                                <?php $__currentLoopData = $frequencytype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($ft->value == $region->job_game_notime): ?> selected <?php endif; ?> value="<?php echo e($ft->value, false); ?>"><?php echo e($ft->description, false); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selOverlaps" class="col-sm-6 col-form-label"><?php echo app('translator')->get('region.job.overlaps'); ?></label>
                            <div class="col-sm-4">
                              <select class='js-sel-overlaps js-states form-control select2' id='selOverlaps' name="job_game_overlaps">
                                <?php $__currentLoopData = $frequencytype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($ft->value == $region->job_game_overlaps): ?> selected <?php endif; ?> value="<?php echo e($ft->value, false); ?>"><?php echo e($ft->description, false); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="game_slot" class="col-sm-6 col-form-label"><?php echo app('translator')->get('region.game_slot'); ?></label>
                            <div class="col-sm-4">
                              <input id="game_slot" name="game_slot" type="text" class="form-control" ></input>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selLeagueReport" class="col-sm-6 col-form-label"><?php echo app('translator')->get('region.job.league_reports'); ?></label>
                            <div class="col-sm-4">
                              <select class='js-sel-league-reports js-states form-control select2' id='selLeagueReport' name="job_league_reports">
                                <?php $__currentLoopData = $frequencytype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($ft->value == $region->job_league_reports): ?> selected <?php endif; ?> value="<?php echo e($ft->value, false); ?>"><?php echo e($ft->description, false); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                           <label for="selLeagueReportFmt" class="col-sm-6 col-form-label"></label>
                            <div class="col-sm-4">
                              <select class='js-sel-league-reports-fmt js-states form-control select2' id='selLeagueReportFmt' name="fmt_league_reports[]">
                                <?php $__currentLoopData = $filetype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ft->value, false); ?>"><?php echo e($ft->description, false); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selClubReport" class="col-sm-6 col-form-label"><?php echo app('translator')->get('region.job.club_reports'); ?></label>
                            <div class="col-sm-4">
                              <select class='js-sel-league-reports js-states form-control select2' id='selClubReport' name="job_club_reports">
                                <?php $__currentLoopData = $frequencytype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($ft->value == $region->job_club_reports): ?> selected <?php endif; ?> value="<?php echo e($ft->value, false); ?>"><?php echo e($ft->description, false); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                           <label for="selClubReportFmt" class="col-sm-6 col-form-label"></label>
                            <div class="col-sm-4">
                              <select class='js-sel-club-reports-fmt js-states form-control select2' id='selClubReportFmt' name="fmt_club_reports[]">
                                <?php $__currentLoopData = $filetype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ft->value, false); ?>"><?php echo e($ft->description, false); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>
                        </div>
                    <div class="card-footer">
                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                            <button type="submit" class="btn btn-primary"><?php echo e(__('Submit'), false); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>

  <script>
      $(function() {
        var gameslotSlider = $("#game_slot").bootstrapSlider({
          ticks: [60,75,90,105,120,135,150],
          ticks_labels: ['60','75', '90','105', '120', '135','150'],
          lock_to_ticks: true
        });

        gameslotSlider.bootstrapSlider('setValue', <?php echo e((old('game_slot')!='') ? old('game_slot') : $region->game_slot, false); ?>);

        $("#selNolead").select2({
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
        });
        $("#selNotime").select2({
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
        });
        $("#selEmailCheck").select2({
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
        });
        $("#selOverlaps").select2({
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
        });
        $("#selLeagueReport").select2({
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
        });
        $("#selLeagueReportFmt").select2({
            theme: 'bootstrap4',
            multiple: true,
            maximumSelectionLength: 2,
            language: '<?php echo e(\Str::lower( app()->getLocale()), false); ?>',
            allowClear: false,
        });
        $("#selLeagueReportFmt").val(<?php echo e(collect($region->fmt_league_reports->getFlags())->pluck('value'), false); ?> ).change();

        $("#selClubReport").select2({
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
        });
        $("#selClubReportFmt").select2({
            theme: 'bootstrap4',
            multiple: true,
            maximumSelectionLength: 2,
            language: '<?php echo e(\Str::lower( app()->getLocale()), false); ?>',
            allowClear: false,
        });
        $("#selClubReportFmt").val(<?php echo e(collect($region->fmt_club_reports->getFlags())->pluck('value'), false); ?> ).change();
      });

 </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/region/region_edit.blade.php ENDPATH**/ ?>