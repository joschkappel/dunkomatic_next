@extends('layouts.page')

@section('content_header')
    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
@stop

@section('plugins.Datatables', true)
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card card-secondary">
                <h4 class="card-header">@lang('schedule.title.list', ['region'=>session('cur_region')->name ])</h4>
                <!-- /.card-header -->

                <div class="card-tools p-2">
                    <a href="{{ route('schedule.create', app()->getLocale()) }}"
                        class="text-center btn btn-success btn-sm mb-3"><i
                            class="fas fa-plus-circle pr-2"></i>@lang('schedule.action.create')</a>
                </div>
                <div class="card-body">
                    @csrf

                    <table width="100%" class="table table-hover table-bordered table-sm" id="table">
                        <thead class="thead-light">
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Eventcolor</th>
                                <th>@lang('schedule.color')</th>
                                <th>@lang('schedule.size')</th>
                                <th>@lang('schedule.iterations')</th>
                                <th>@lang('schedule.events')</th>
                                <th>{{ __('schedule.leagues') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
            <!-- /.card-body -->
            <!-- all modals here -->
            <x-confirm-deletion modalId="modalDeleteSchedule" modalTitle="{{ __('schedule.title.delete') }}" modalConfirm="{{ __('schedule.confirm.delete') }}" deleteType="{{ trans_choice('schedule.schedule',1) }}" />
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
                    language: { "url": "{{ URL::asset('vendor/datatables-plugins/i18n/German.json') }}" },
                @else
                    language: { "url": "{{ URL::asset('vendor/datatables-plugins/i18n/English.json') }}" },
                @endif
                order: [
                    [1, 'asc']
                ],
                ajax: '{{ route('schedule.list', ['region' => session('cur_region')->id]) }}',
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
@stop
