@extends('layouts.page')

@section('plugins.Datatables', true)

@section('content')
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">@lang('message.title.list')</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
            <a href="{{ route('message.create', app()->getLocale() ) }}" class="text-center btn btn-success btn-sm mb-3">@lang('message.action.create')</a>
          </div>
          <div class="card-body">
            @csrf

         <table class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>@lang('message.action.send')</th>
                  <th>@lang('message.title')</th>
                  <th>@lang('message.body')</th>
                  <th>@lang('message.send_at')</th>
                  <th>@lang('message.sent_at')</th>
                  <th>{{__('Updated at')}}</th>
                  <th>{{__('Action')}}</th>
               </tr>
            </thead>
         </table>
          </div>

        </div>
        <!-- /.card-body -->
        <!-- all modals here -->
        @include('message/includes/message_delete')
        <!-- all modals above -->
      </div>
    </div>
@stop

@section('js')

<script>
         $(function() {
               $('#table').DataTable({
                 processing: true,
                 serverSide: true,
                 order: [[1,'asc']],
                 ajax: '{{ route('message.user.dt', ['language'=>app()->getLocale(), 'user'=> Auth::user()->id]) }}',
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
              var url = "{{ route('message.destroy', [ 'message'=>':messageid:'])}}"
              url = url.replace(':messageid:',$(this).data('msg-id') );
              $('#confirmDeleteMessage').attr('action', url);
              $('#modalDeleteMessage').modal('show');
           });

           $(document).on('click', '#sendMessage', function () {
               var url = "{{ route('message.send', [ 'language'=>app()->getLocale(), 'message'=>':messageid:'])}}"
               url = url.replace(':messageid:',$(this).data('msg-id') );
               $.ajax( {
                       url: url,
                       type: "post",
                       dataType: 'json',
                       data: {
                         _token: "{{ csrf_token() }}",
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
@stop
