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
                                <th>@lang('schedule.events')</th>
                                <th>{{ __('Created at') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
            <!-- /.card-body -->
            <!-- all modals here -->
            @include('schedule/includes/schedule_delete')
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
                        data: 'events',
                        name: 'events'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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
            $('#schedule_id').val($(this).data('schedule-id'));
            $('#events').html($(this).data('events'));
            $('#schedule_name').html($(this).data('schedule-name'));
            var url = "{{ route('schedule.destroy', ['schedule' => ':scheduleid:']) }}";
            url = url.replace(':scheduleid:', $(this).data('schedule-id'));
            $('#confirmDeleteSchedule').attr('action', url);
            $('#modalDeleteSchedule').modal('show');
        });
    </script>
@stop
