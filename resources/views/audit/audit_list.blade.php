@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('audit.title.list', ['region' => $region->name ]) }}" >
    <th>Id</th>
    <th>{{ __('Created at')}}</th>
    <th>{{__('auth.user')}}</th>
    <th>{{__('Action')}}</th>
    <th>{{__('audit.type')}}</th>
    <th>{{__('audit.tags')}}</th>
    <th>{{__('audit.mod_values')}}</th>
</x-card-list>
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
                ajax: '{{ route('audits.dt', ['language' => app()->getLocale(), 'region' => $region ]) }}',
                columns: [
                    { data: 'id',name: 'id', visible: true},
                    { data: 'created_at',name: 'created_at', visible: true},
                    { data: 'user.name',name: 'username', visible: true},
                    { data: 'event',name: 'event', visible: true},
                    { data: 'auditable_type',name: 'type', visible: true},
                    { data: 'tags',name: 'tags', visible: true},
                    { data: 'mod_values',name: 'mod_values', visible: true},

                ]
            });
        });
    </script>
@endsection
