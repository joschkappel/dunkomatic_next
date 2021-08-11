@extends('layouts.page')

@section('plugins.Datatables', true)

@section('content')
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-secondary">
                    <h3 class="card-title">@lang('message.title.list')</h3>
                  </div>
                  <!-- /.card-header -->
          <div class="card-body">
            @csrf

         <table width="100%" class="table table-hover table-bordered table-sm" id="table">
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
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="{{ route('message.create', app()->getLocale() ) }}" class="text-center btn btn-success mb-3"><i class="fas fa-plus-circle"></i> @lang('message.action.create')</a>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        <!-- all modals here -->
        <x-confirm-deletion modalId="modalDeleteMessage" modalTitle="{{ __('message.title.delete') }}" modalConfirm="{{ __('message.confirm.delete') }}" deleteType="{{ trans_choice('message.message',1) }}" />
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
                 responsive: true,
                 @if (app()->getLocale() == 'de')
                 language: { "url": "{{URL::asset('vendor/datatables-plugins/i18n/German.json')}}" },
                 @else
                 language: { "url": "{{URL::asset('vendor/datatables-plugins/i18n/English.json')}}" },
                 @endif
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
              $('#modalDeleteMessage_Instance').html($(this).data('msg-title'));
              var url = "{{ route('message.destroy', [ 'message'=>':messageid:'])}}"
              url = url.replace(':messageid:',$(this).data('msg-id') );
              $('#modalDeleteMessage_Form').attr('action', url);
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
