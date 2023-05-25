@extends('layouts.page')

@section('content')
    <x-card-list cardTitle="{{ __('message.title.list', ['user' => $user->name, 'region' => $region->name]) }}"
        cardNewAction="{{ route('message.create', ['language' => app()->getLocale(), 'user' => $user, 'region' => $region]) }}"
        cardNewTitle="{{ __('message.action.create') }}" cardNewAbility="update-members">
        <th>Id</th>
        <th>@lang('message.action.send')</th>
        <th>@lang('message.title')</th>
        <th>@lang('message.body')</th>
        <th>@lang('message.attachment')</th>
        <th>@lang('message.send_at')</th>
        <th>@lang('message.sent_at')</th>
        <th>{{ __('message.delete_at') }}</th>
        <th>{{ __('Action') }}</th>
    </x-card-list>

    <x-confirm-deletion modalId="modalDeleteMessage" modalTitle="{{ __('message.title.delete') }}"
        modalConfirm="{{ __('message.confirm.delete') }}" deleteType="{{ trans_choice('message.message', 1) }}" />
    @include('message.includes.message_show')
@endsection

@section('js')
    <script>
        $(function() {
            $('#goBack').click(function(e) {
                history.back();
            });
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                language: {
                    "url": "{{ URL::asset('lang/vendor/datatables.net/' . app()->getLocale() . '.json') }}"
                },
                order: [
                    [1, 'asc']
                ],
                ajax: '{{ route('message.user.dt', ['language' => app()->getLocale(), 'user' => $user, 'region' => $region]) }}',
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false
                    },
                    {
                        data: 'action_send',
                        name: 'action_send',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'body',
                        name: 'body'
                    },
                    {
                        data: 'message_attachments_count',
                        name: 'message_attachments_count'
                    },
                    {
                        data: 'send_at',
                        name: 'send_at'
                    },
                    {
                        data: 'sent_at',
                        name: 'sent_at'
                    },
                    {
                        data: 'delete_at',
                        name: 'delete_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

        $(document).on('click', '#deleteMessage', function() {
            $('#modalDeleteMessage_Instance').html($(this).data('msg-title'));
            var url = "{{ route('message.destroy', ['message' => ':messageid:']) }}"
            url = url.replace(':messageid:', $(this).data('msg-id'));
            $('#modalDeleteMessage_Form').attr('action', url);
            $('#modalDeleteMessage').modal('show');
        });

        $(document).on('click', '#sendMessage', function() {
            var url = "{{ route('message.send', ['language' => app()->getLocale(), 'message' => ':messageid:']) }}"
            url = url.replace(':messageid:', $(this).data('msg-id'));
            $.ajax({
                url: url,
                type: "post",
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: 'POST'
                },
                delay: 250,
                success: function(response) {
                    location.reload();
                    console.log('reload');
                },
                cache: false
            });
        });

        $(document).on('click', '#copyMessage', function() {
            var url = "{{ route('message.copy', ['language' => app()->getLocale(), 'message' => ':messageid:']) }}"
            url = url.replace(':messageid:', $(this).data('msg-id'));
            $.ajax({
                url: url,
                type: "post",
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: 'POST'
                },
                delay: 250,
                success: function(response) {
                    location.reload();
                    console.log('reload');
                },
                cache: false
            });
        });
        $(document).on('click', '#showMessage', function(data) {
            // console.log($(this).data('msg-id'));
            $('#modalShowMessageTitle').html($(this).data('msg-subject'));
            $('#msgSalutation').html($(this).data('msg-salutation'));
            $('#msgBody').html($(this).data('msg-body'));
            $('#msgGreeting').html($(this).data('msg-greeting'));
            if ($(this).data('msg-attachment') == '') {
                $('#btnGetAttachment').hide();
            } else {
                $('#btnGetAttachment').show();
                var url = "{{ route('message.attachment', ['message' => ':messageid:']) }}"
                url = url.replace(':messageid:', $(this).data('msg-id'));
                document.getElementById('btnGetAttachment').href = url;
                // $('#btnGetAttachment').html($(this).data('msg-attachment'));
            }
            $('#btnMarkUnread').hide();
            $('#modalShowMessage').data('msg-id', $(this).data('msg-id'));
            $('#modalShowMessage').modal('show');
        });
    </script>
@endsection
