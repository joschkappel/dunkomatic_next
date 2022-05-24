@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('role.address.title.list', ['region'=> $region->code, 'role'=> App\Enums\Role::coerce(intval($role))->description ]) }}">
    <th>{{ trans_choice('role.member',1) }}</th>
    <th>@lang('role.email1')</th>
    <th>@lang('role.phone')</th>
</x-card-list>
@endsection

@section('js')
<script>
         $(function() {
              $('#goBack').click(function(e){
                  history.back();
              });
              $('#table').DataTable({
                  colReorder: true,
                 processing: true,
                 serverSide: false,
                 responsive: true,
                 language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                 ordering: true,
                stateSave: true,
                dom: 'Bflrtip',
                buttons: [
                    { extend: 'excelHtml5',
                        exportOptions: { orthogonal: 'export', columns: ':visible' },
                        title: '{{ __('role.address.title.list', ['region'=> $region->code, 'role'=> App\Enums\Role::coerce(intval($role))->description ]) }}',
                    },
                    { extend: 'pdfHtml5',
                        title: '{{ __('role.address.title.list', ['region'=> $region->code, 'role'=> App\Enums\Role::coerce(intval($role))->description ]) }}',
                        exportOptions: { orthogonal: 'export', columns: ':visible' },
                    },
                    { extend: 'print',
                        title: '{{ __('role.address.title.list', ['region'=> $region->code, 'role'=> App\Enums\Role::coerce(intval($role))->description ]) }}',
                        exportOptions: { orthogonal: 'export', columns: ':visible' },
                    },
                    { extend: 'copy',
                        exportOptions: { columns: ':visible' },
                    },
                    { extend: 'csv',
                        exportOptions: { orthogonal: 'export', columns: ':visible' },
                    },
                    { extend: 'colvis',
                    },
                ],
                 ajax: '{{ route('address.index_byrole.dt', ['region' => $region, 'language'=> app()->getLocale(), 'role'=> $role ]) }}',
                 columns:  [
                    { data: 'name', name: 'name'},
                    { data: 'email', name: 'email'},
                    { data: 'phone', name: 'phone'},
                ]

              });
        });
</script>
@endsection
