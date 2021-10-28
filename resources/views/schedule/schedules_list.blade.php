@extends('layouts.page')

{{-- @section('plugins.Datatables', true)
@section('plugins.DatatableButtons', true)
 --}}
@section('content')
<x-card-list cardTitle="{{ __('schedule.title.list', ['region'=>$region->name ]) }}">
            <th>{{ trans_choice('schedule.event.unit.year', 1) }}</th>
            <th>@lang('game.game_date')</th>
            <th></th>
            @foreach ($schedules as $s )
                @if ($s->region->is_base_level)
                    <th class="bg-orange">{{$s->name}}</th>
                @else
                    <th class="bg-warning">{{$s->name}}</th>
                @endif
            @endforeach
</x-card-list>
@endsection

@section('css')
td.highlight {
    background-color: whitesmoke !important;
}
@endsection

@section('js')

<script>
         $(function() {
              $('#goBack').click(function(e){
                  history.back();
              });

              $('#table').DataTable({
                 processing: true,
                 serverSide: false,
                 responsive: true,
                 language: { "url": "{{URL::asset('vendor/datatables.net/i18n/'.app()->getLocale().'.json')}}" },
                 ordering: false,
                 paging: false,
                stateSave: true,
                ajax: "{{ route('schedule.compare.dt', ['language'=>$language,'region' => $region] ) }}",
                dom: 'Brtip',
                buttons: [
                    { extend: 'excelHtml5',
                        exportOptions: { orthogonal: 'export' },
                        title: '{{$region->code}}_{{ __('schedule.allregion') }}',
                        sheetName: '{{ __('schedule.allregion')}}',
                    },
                    { extend: 'pdfHtml5',
                        orientation: 'landscape',
                        title: '{{$region->code}}_{{ __('schedule.allregion') }}',
                        exportOptions: { orthogonal: 'export', columns: ':visible' },
                    },
                    { extend: 'print',
                        title: '{{$region->code}}_{{ __('schedule.allregion') }}',
                        exportOptions: { orthogonal: 'export', columns: ':visible' },
                    },
                    'copy',

                    { extend: 'colvis',
                        columns: 'th:nth-child(n+4)'
                    },

                ],
                 columns:  [
                    { data: 'year',  name: 'year' },
                    // { data: 'game_date',  name: 'game_date' },
                    { data: 'sat_game',  name: 'sat_game' },
                    { data: 'sun_game',  name: 'sun_game' },
                    @foreach ($schedules as $s )
                    { data: 's_{{ $s->id }}',  name: 's_{{$s->id}}' },
                    @endforeach

                ],
/*                 rowGroup: {
                   dataSrc: 'year'
                } */
/*                 rowCallback: function( row, data, index ) {
                    if (data.game_date.substring(0, 2) == "Sa") {
                        $('td', row).css('background-color', '#FFCC99');
                    }
                }, */
              });
        });
</script>
@endsection
