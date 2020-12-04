<?php $__env->startSection('plugins.Datatables', true); ?>

<?php $__env->startSection('content'); ?>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-secondary">
                    <h3 class="card-title"><?php echo app('translator')->get('message.title.list'); ?></h3>
                  </div>
                  <!-- /.card-header -->
          <div class="card-body">
            <?php echo csrf_field(); ?>

         <table width="100%" class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th><?php echo app('translator')->get('message.action.send'); ?></th>
                  <th><?php echo app('translator')->get('message.title'); ?></th>
                  <th><?php echo app('translator')->get('message.body'); ?></th>
                  <th><?php echo app('translator')->get('message.send_at'); ?></th>
                  <th><?php echo app('translator')->get('message.sent_at'); ?></th>
                  <th><?php echo e(__('Updated at'), false); ?></th>
                  <th><?php echo e(__('Action'), false); ?></th>
               </tr>
            </thead>
         </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="<?php echo e(route('message.create', app()->getLocale() ), false); ?>" class="text-center btn btn-success mb-3"><i class="fas fa-plus-circle"></i> <?php echo app('translator')->get('message.action.create'); ?></a>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        <!-- all modals here -->
        <?php echo $__env->make('message/includes/message_delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- all modals above -->


      </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script>
         $(function() {
               $('#table').DataTable({
                 processing: true,
                 serverSide: true,
                 responsive: true,
                 <?php if(app()->getLocale() == 'de'): ?>
                 language: { "url": "<?php echo e(URL::asset('vendor/datatables-plugins/i18n/German.json'), false); ?>" },
                 <?php else: ?>
                 language: { "url": "<?php echo e(URL::asset('vendor/datatables-plugins/i18n/English.json'), false); ?>" },
                 <?php endif; ?>
                 order: [[1,'asc']],
                 ajax: '<?php echo e(route('message.user.dt', ['language'=>app()->getLocale(), 'user'=> Auth::user()->id]), false); ?>',
                 columns: [
                          { data: 'id', name: 'id', visible: false },
                          { data: 'action_send', name: 'action_send', orderable: false, searchable: false},
                          { data: 'title', name: 'title' },
                          { data: 'body', name: 'body' },
                          { data: 'send_at', name: 'send_at' },
                          { data: 'sent_at', name: 'sent_at'},
                          { data: 'updated_at', name: 'updated_at'},
                          { data: 'action', name: 'action', orderable: false, searchable: false},
                       ]
              });
            });

          $(document).on('click', '#deleteMessage', function () {
              $('#msg_id').val($(this).data('msg-id'));
              $('#msg_title').html($(this).data('msg-title'));
              var url = "<?php echo e(route('message.destroy', [ 'message'=>':messageid:']), false); ?>"
              url = url.replace(':messageid:',$(this).data('msg-id') );
              $('#confirmDeleteMessage').attr('action', url);
              $('#modalDeleteMessage').modal('show');
           });

           $(document).on('click', '#sendMessage', function () {
               var url = "<?php echo e(route('message.send', [ 'language'=>app()->getLocale(), 'message'=>':messageid:']), false); ?>"
               url = url.replace(':messageid:',$(this).data('msg-id') );
               $.ajax( {
                       url: url,
                       type: "post",
                       dataType: 'json',
                       data: {
                         _token: "<?php echo e(csrf_token(), false); ?>",
                         _method: 'POST'
                       },
                       delay: 250,
                       success: function (response) {
                         location.reload();
                         console.log('reload');
                       },
                       cache: false
                     });
            });


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dunkonxt/resources/views/message/message_list.blade.php ENDPATH**/ ?>