@extends('layouts.page')

@section('content')
<div class="card card-outline card-primary">
<x-card-header title="{{__('club.title.homegame.chart')}}"  :showtools="false" />
<div class="card-body">
    <div class="text-info">
        @lang('club.chart.gamesbyday.hint')
    </div>
        <canvas id="gameChart" height="60" width="200"></canvas>
</div>
<div class="card-footer" id="chartCard">
    <a role="button" class="btn btn-info" href="{{ route('club.list.homegame',['language'=>app()->getLocale(), 'club'=>$club])}}">@lang('Open Listview')</a>
</div>
</div>
<x-card-list cardTitle="{{ __('club.title.gamehome.edit', ['club'=>$club->shortname]) }}" :showtools="false">
    <xslot-extra-header-slot>
    <div class="text-info">
        @lang('club.action.gamesbyday.hint')
    </div>
    </xslot-extra-header-slot>
    <th>{{ __('Spielbeginn')}}</th>
    @foreach($club->gyms->sortBy('gym_no') as $g)
    <th>{{ $g->name }}</th>
    @endforeach
    <th>{{ __('game.guest')}}</th>
</x-card-list>
<x-card-list cardTitle="{{ __('club.title.gamehome.edit', ['club'=>$club->shortname]) }}" tableId="gametable2" :showtools="false">
    <xslot-extra-header-slot>
    <div class="text-info">
        @lang('club.action.gamesbyday.hint')
    </div>
    </xslot-extra-header-slot>
    <th>{{ __('Spielbeginn')}}</th>
    @foreach($club->gyms->sortBy('gym_no') as $g)
    <th>{{ $g->name }}</th>
    @endforeach
    <th>{{ __('game.guest')}}</th>
</x-card-list>
<!-- all modals here -->
@include('game/includes/edit_gamedate_home')
<!-- all modals above -->
@endsection

@section('js')

<script>
$(function() {
    $('#goBack').click(function(e){
      history.back();
    });
    var ctx = document.getElementById('gameChart').getContext('2d');
    var gameChart = new Chart(ctx, {
        type: 'bar',
        data: { datasets: [{ data: [], }] },
            options: {
              responsive: true,
              title: {
                display: true,
                text: '{{ __('club.chart.gamesbyday') }}'
              },
            scales: {
                yAxes: [{
                            stacked: true,
                            scaleLabel: {
                            display: true,
                            labelString: '@lang('Anzahl Spiele')'
                            },
                            ticks: {
                               precision: 0
                            }
                        }],
                xAxes: [{
                    stacked: true,
                }]
            },
            onClick:function(e){
                if (typeof gameChart.getElementAtEvent(e)[0] !== 'undefined'){
                    var activePoint = gameChart.getElementAtEvent(e)[0];
                    var data = activePoint._chart.data;
                    var datasetIndex = activePoint._datasetIndex;
                    var label = data.datasets[datasetIndex].label;
                    var value = data.datasets[datasetIndex].data[activePoint._index];
                    var xValue = activePoint._xScale.ticks[activePoint._index];
                    console.log(label, value, xValue );
                    getGamesForDate(xValue);
                } else {
                    return true;
                }
            }
        }
    });
    function load_game_chart(chart) {
        $.ajax({
            type: 'GET',
            url: '{{ route('club.games.byday.chart', ['language'=>app()->getLocale(),'club' => $club->id]) }}',
            success: function(response) {
                chart.data.labels = response['labels'];
                chart.data.datasets = response['datasets'];

                chart.update();
            },
        });
    };

    load_game_chart( gameChart);
    var colwidth = (100 / ({{ count($club->gyms)}} + 1))+'%';

    var myGameTable = $('#table').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        bSort: false,
        bFilter: false,
        paging: false,
        language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
        columns: [
            { data: 'game_time', name: 'game_time', width: '10px' },
            @foreach($club->gyms->sortBy('gym_no') as $g)
            { data: 'gym_{{$g->gym_no}}', name: 'gym_{{$g->gym_no}}', width: colwidth },
            @endforeach
            { data: 'guest', name: 'guest', width: colwidth  }
        ],
        createdRow: function( row, data, dataIndex){
                if ( ! moment().hour(data['game_time'].split(":")[0]).isBetween(moment().hour(9).minute(0), moment().hour(20).minute(50))) {
                    $(row).addClass('table-info');
                };
        }
    });
    var myGameTable2 = $('#gametable2').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        bSort: false,
        bFilter: false,
        paging: false,
        language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
        columns: [
            { data: 'game_time', name: 'game_time', width: '10px' },
            @foreach($club->gyms->sortBy('gym_no') as $g)
            { data: 'gym_{{$g->gym_no}}', name: 'gym_{{$g->gym_no}}', width: colwidth },
            @endforeach
            { data: 'guest', name: 'guest', width: colwidth  }
        ],
        createdRow: function( row, data, dataIndex){
                if ( ! moment().hour(data['game_time'].split(":")[0]).isBetween(moment().hour(9).minute(0), moment().hour(20).minute(50))) {
                    $(row).addClass('table-info');
                };
        }
    });

    function getGamesForDate(game_date) {
        var url = "{{ route('club.game.bydate.dt', ['language'=>app()->getLocale(),'club'=>$club->id, 'game_date'=> ':date:']) }}";
        moment.locale('{{app()->getLocale()}}');
        var gdate = moment(game_date,'L');
        url1 = url.replace(':date:', gdate.format('X')  );
        myGameTable.ajax.url( url1 ).load();
        var gdate2 = moment(game_date,'L').add(1,'days');
        url2 = url.replace(':date:', gdate2.format('X')  );
        myGameTable2.ajax.url( url2 ).load();

        $('#titletable').html("{{__('club.title.gamehome.edit') }} {{__('for') }} "+ moment(gdate).format('ddd L'));
        $('#titlegametable2').html("{{__('club.title.gamehome.edit') }} {{__('for')}} "+ moment(gdate2).format('ddd L'));

    };
    $('body').on('click', '#gameEditLink', function() {
        moment.locale('{{app()->getLocale()}}');
        var gdate = moment($(this).data('game-date')).format('L');
        var gtime = moment($(this).data('game-time'),'HH:mm:ss').format('LT');
        $("#game_time").val(gtime);
        $("#game_date").val(gdate);
        $("#gym_id").val($(this).data('gym-id'));
        $("#gym_no").val($(this).data('gym-no'));
        $("#game_id").val($(this).data('id'));
        $("#league").val($(this).data('league'));
        $("#modalTitle").html( $(this).data('league') + ' - '+ gdate + '  {{ __('game.action.editdate') }}' );
        var url = "{{route('game.update_home',['game'=>':game:'])}}";
        url = url.replace(':game:', $(this).data('id'));
        $('#formGamedate').attr('action', url);
        $("#modalEditGamedate").modal('show');
      });

});

</script>
@endsection
