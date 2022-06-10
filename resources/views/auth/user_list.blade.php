@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('auth.title.list', ['region' => $region->name ]) }}">
    <th>Id</th>
    <th>@lang('auth.full_name')</th>
    <th>@lang('auth.email')</th>
    <th>@lang('auth.user.roles')</th>
    <th>@lang('auth.user.clubs')</th>
    <th>@lang('auth.user.leagues')</th>
    <th>@lang('auth.user.regions')</th>
    <th>@lang('auth.lastlogin_at')</th>
    <th>{{ __('Created at') }}</th>
    <th>{{ __('Email verfified at') }}</th>
    <th>{{ __('Approved at') }}</th>
    <th>{{ __('Rejected at') }}</th>
    <th>{{ __('Action') }}</th>
</x-card-list>
<!-- all modals here -->
<x-confirm-deletion modalId="modalDeleteUser" modalTitle="{{ __('auth.title.delete')}}" modalConfirm="{{ __('auth.confirm.delete')}}"
    deleteType="{{ __('auth.user') }}" />
<x-confirm-deletion modalId="modalBlockUser" modalTitle="{{ __('auth.title.block') }}"  modalConfirm="{{ __('auth.confirm.block') }}"
    modalMethod="POST" deleteType="{{ __('auth.user') }}" />
<!-- all modals above -->
@endsection

@section('js')
<script>
    $(function() {
        $('#goBack').click(function(e){
            history.back();
        });
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            stateSave: true,
            pageLength: {{ config('dunkomatic.table_page_length', 50)}},
            language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
            order: [
                [1, 'asc']
            ],
            ajax: '{{ route('admin.user.dt', ['language'=>app()->getLocale(),'region'=>$region]) }}',
            columns: [{
                    data: 'id',
                    name: 'id',
                    visible: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'roles',
                    name: 'roles'
                },
                {
                    data: 'clubs',
                    name: 'clubs'
                },
                {
                    data: 'leagues',
                    name: 'leagues'
                },
                {
                    data: 'regions',
                    name: 'regions'
                },
                {
                    data: {
                        _: 'lastlogin_at.filter',
                        display: 'lastlogin_at.display',
                        sort: 'lastlogin_at.ts'
                    },
                    name: 'lastlogin_at.ts'
                },
                {
                    data: {
                        _: 'created_at.filter',
                        display: 'created_at.display',
                        sort: 'created_at.ts'
                    },
                    name: 'created_at.ts'
                },
                {
                    data: {
                        _: 'email_verified_at.filter',
                        display: 'email_verified_at.display',
                        sort: 'email_verified_at.ts'
                    },
                    name: 'email_verified_at.ts'
                },
                {
                    data: {
                        _: 'approved_at.filter',
                        display: 'approved_at.display',
                        sort: 'approved_at.ts'
                    },
                    name: 'approved_at.ts'
                },
                {
                    data: {
                        _: 'rejected_at.filter',
                        display: 'rejected_at.display',
                        sort: 'rejected_at.ts'
                    },
                    name: 'rejected_at.ts'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $(document).on('click', '#deleteUser', function() {
            $('#modalDeleteUser_Instance').html($(this).data('user-name'));
            var url = "{{ route('admin.user.destroy', ['user' => ':userid:']) }}"
            url = url.replace(':userid:', $(this).data('user-id'));
            $('#modalDeleteUser_Form').attr('action', url);
            $('#modalDeleteUser').modal('show');
        });
        $(document).on('click', '#blockUser', function() {
            $('#modalBlockUser_Instance').html($(this).data('user-name'));
            var url = "{{ route('admin.user.block', ['user' => ':userid:']) }}"
            url = url.replace(':userid:', $(this).data('user-id'));
            $('#modalBlockUser_Form').attr('action', url);
            $('#modalBlockUser').modal('show');
        });

    });
</script>
@endsection
