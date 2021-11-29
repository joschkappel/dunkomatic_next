@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('schedule.title.list', ['region'=>$region->name ]) }}" cardNewAction="{{ route('schedule.create', ['language'=>app()->getLocale(),'region'=>$region]) }}" cardNewTitle="{{ __('schedule.action.create') }}" cardNewAbility="create-schedules">
    <th>Id</th>
    <th>Name</th>
    <th>Eventcolor</th>
    <th>@lang('schedule.color')</th>
    <th>@lang('schedule.size')</th>
    <th>@lang('schedule.iterations')</th>
    <th>@lang('schedule.events')</th>
    <th>{{ __('schedule.leagues') }}</th>
    <th>{{ __('Action') }}</th>
</x-card-list>

<x-confirm-deletion modalId="modalDeleteSchedule" modalTitle="{{ __('schedule.title.delete') }}" modalConfirm="{{ __('schedule.confirm.delete') }}" deleteType="{{ trans_choice('schedule.schedule',1) }}" />

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
                language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                order: [
                    [1, 'asc']
                ],
                ajax: '{{ route('schedule.list', ['region' => $region]) }}',
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
                        data: 'eventcolor',
                        name: 'eventcolor',
                        visible: false
                    },
                    {
                        data: 'color',
                        name: 'color',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'league_size.description',
                        name: 'description'
                    },
                    {
                        data: 'iterations',
                        name: 'iterations'
                    },
                    {
                        data: 'events',
                        name: 'events'
                    },
                    {
                        data: 'used_by_leagues',
                        name: 'used_by_leagues'
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

        $(document).on('click', '#deleteSchedule', function() {
            $('#modalDeleteSchedule_Info').html($(this).data('events') + ' ' + '{{ __('schedule.events') }}');
            $('#modalDeleteSchedule_Instance').html($(this).data('schedule-name'));
            var url = "{{ route('schedule.destroy', ['schedule' => ':scheduleid:']) }}";
            url = url.replace(':scheduleid:', $(this).data('schedule-id'));
            $('#modalDeleteSchedule_Form').attr('action', url);
            $('#modalDeleteSchedule').modal('show');
        });
    </script>
@endsection
