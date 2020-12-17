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
                    <h3 class="card-title"><?php echo app('translator')->get('club.title.gamehome.edit', ['club'=>$club->shortname]); ?></h3>
                </div>
                <!-- /.card-header -->
                    <div class="card-body">
                      <table width="100%" class="table table-hover table-bordered table-sm" id="table">
                         <thead class="thead-light">
                            <tr>
                               <th>id</th>
                               <th><?php echo app('translator')->get('game.game_no'); ?></th>
                               <th><?php echo app('translator')->get('game.game_date'); ?></th>
                               <th><?php echo app('translator')->get('game.gym_no'); ?></th>
                               <th>gym_id</th>
                               <th><?php echo app('translator')->get('game.game_time'); ?></th>
                               <th class="text-center"><?php echo app('translator')->get('game.overlap'); ?></th>
                               <th><?php echo e(trans_choice('league.league',1), false); ?></th>
                               <th><?php echo app('translator')->get('game.team_home'); ?></th>
                               <th><?php echo app('translator')->get('game.team_guest'); ?></th>
                            </tr>
                         </thead>
                      </table>
                    </div>
            </div>
        </div>
    </div>
    <!-- all modals here -->
    <?php echo $__env->make('game/includes/edit_gamedate', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- all modals above -->
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script src="<?php echo e(URL::asset('vendor/moment/moment-with-locales.min.js'), false); ?>"></script>

<script>
$(function() {
    $.fn.dataTable.ext.buttons.import = {
        text: '<?php echo e(__('game.excel.import'), false); ?>',
        action: function ( e, dt, node, config ) {
            window.open('<?php echo e(route('club.upload.homegame',['language'=>app()->getLocale(), 'club' => $club ]), false); ?>',"_self");
        }
    };
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
            title: '<?php echo e($club->shortname, false); ?>_<?php echo e(trans_choice('game.homegame',2), false); ?>',
            sheetName: '<?php echo e(trans_choice('game.homegame',2), false); ?>',
          },
          'print',
          'import'
        ],
        order: [[ 2,'asc'],[ 3,'asc'], [ 4,'asc']],
        <?php if(app()->getLocale() == 'de'): ?>
        language: { "url": "<?php echo e(URL::asset('vendor/datatables-plugins/i18n/German.json'), false); ?>" },
        <?php endif; ?>
        <?php if(app()->getLocale() == 'en'): ?>
        language: { "url": "<?php echo e(URL::asset('vendor/datatables-plugins/i18n/English.json'), false); ?>" },
        <?php endif; ?>
        ajax: '<?php echo e(route('club.game.list_home',['language' => app()->getLocale(), 'club'=>$club]), false); ?>',
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
                 { data: 'duplicate', name: 'duplicate' },
                 { data: 'league.shortname', name: 'league.shortname'  },
                 { data: 'team_home', name: 'team_home'},
                 { data: 'team_guest', name: 'team_guest'},
              ]
    });

  $('body').on('click', '#gameEditLink', function() {
        moment.locale('<?php echo e(app()->getLocale(), false); ?>');
        var gdate = moment($(this).data('game-date')).format('L');
        var gtime = moment($(this).data('game-time'),'HH:mm:ss').format('LT');
        $("#game_time").val(gtime);
        $("#game_date").val(gdate);
        $("#gym_id").val($(this).data('gym-id'));
        $("#gym_no").val($(this).data('gym-no'));
        $("#game_id").val($(this).data('id'));
        $("#league").val($(this).data('league'));
        $("#modtitle").html($(this).data('league')+' '+$(this).data('gym-no'));
        var url = "<?php echo e(route('game.update',['game'=>':game:']), false); ?>";
        url = url.replace(':game:', $(this).data('id'));
        $('#formGamedate').attr('action', url);
        $("#modalEditGamedate").modal('show');
      });

  <?php if($errors->any()): ?>
    $("#game_time").val("<?php echo e(old('game_time'), false); ?>");
    $("#game_date").val("<?php echo e(old('game_date'), false); ?>");
    $("#gym_id").val("<?php echo e(old('gym_id'), false); ?>");
    $("#gym_no").val("<?php echo e(old('gym_no'), false); ?>");
    $("#game_id").val("<?php echo e(old('game_id'), false); ?>");
    $("#league").val("<?php echo e(old('league'), false); ?>");
    $("#modtitle").html("<?php echo e(old('league'), false); ?>"+' '+"<?php echo e(old('gym_no'), false); ?>");
    var url = "<?php echo e(route('game.update',['game'=>':game:']), false); ?>";
    url = url.replace(':game:', "<?php echo e(old('game_id'), false); ?>");
    $('#formGamedate').attr('action', url);
    $("#modalEditGamedate").modal('show');
  <?php endif; ?>

});

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/game/gamehome_list.blade.php ENDPATH**/ ?>