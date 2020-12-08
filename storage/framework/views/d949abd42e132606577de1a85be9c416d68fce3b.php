<?php $__env->startSection('plugins.Datatables', true); ?>
<?php $__env->startSection('plugins.DatatableButtons', true); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-12">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php echo app('translator')->get('league.title.game', ['league'=>$league->shortname]); ?></h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table width="100%" class="table table-hover table-bordered table-sm" id="table">
                        <thead class="thead-light">
                            <tr>
                                <th>id</th>
                                <th>
                                    <?php echo app('translator')->get('game.game_no'); ?></th>
                                <th>
                                    <?php echo app('translator')->get('game.game_date'); ?></th>
                                <th>
                                    <?php echo app('translator')->get('game.gym_no'); ?></th>
                                <th>gym_id</th>
                                <th>
                                    <?php echo app('translator')->get('game.game_time'); ?></th>
                                <th>
                                    <?php echo app('translator')->get('game.team_home'); ?></th>
                                <th>
                                    <?php echo app('translator')->get('game.team_guest'); ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- all modals here -->
    <!-- all modals above -->
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script src="<?php echo e(URL::asset('vendor/moment/moment-with-locales.min.js'), false); ?>"></script>

<script>
    $('#table').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        <?php if(app()->getLocale() == 'de'): ?>
        language: { "url": "<?php echo e(URL::asset('vendor/datatables-plugins/i18n/German.json'), false); ?>" },
        <?php else: ?>
        language: { "url": "<?php echo e(URL::asset('vendor/datatables-plugins/i18n/English.json'), false); ?>" },
        <?php endif; ?>
        ordering: true,
        stateSave: true,
        dom: 'Bflrtip',
        buttons: [
          { extend: 'excelHtml5',
            text: '<?php echo e(__('game.excel.export'), false); ?>',
            exportOptions: { orthogonal: 'export' },
            title: '<?php echo e($league->shortname, false); ?>_<?php echo e(trans_choice('game.homegame',2), false); ?>',
            sheetName: '<?php echo e(trans_choice('game.homegame',2), false); ?>',
          },
          'print'
        ],
        order: [[ 1,'asc']],
        <?php if(app()->getLocale() == 'de'): ?>
        language: { "url": "<?php echo e(URL::asset('vendor/datatables-plugins/i18n/German.json'), false); ?>" },
        <?php endif; ?>
        <?php if(app()->getLocale() == 'en'): ?>
        language: { "url": "<?php echo e(URL::asset('vendor/datatables-plugins/i18n/English.json'), false); ?>" },
        <?php endif; ?>
        ajax: '<?php echo e(route('league.game.dt',['language' => app()->getLocale(), 'league'=>$league]), false); ?>',
        columns: [
                 { data: 'id', name: 'id', visible: false },
                 { data: {
                     _: 'game_no.display',
                     sort: 'game_no.sort'
                   }, name: 'game_no.sort'},
                 { data: {
                    _: 'game_date.filter',
                    export: 'game_date.filter',
                    display: 'game_date.display',
                    sort: 'game_date.ts'
                  }, name: 'game_date.ts' },
                 { data: {
                    _: 'gym_no.default',
                    export: 'gym_no.default',
                    display: 'gym_no.display'
                  }, name: 'gym_no.default' },
                 { data: 'gym_id', name: 'gym_id', visible: false },
                 { data: 'game_time', name: 'game_time' },
                 { data: 'team_home', name: 'team_home'},
                 { data: 'team_guest', name: 'team_guest'},
              ]
    });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/game/league_game_list.blade.php ENDPATH**/ ?>