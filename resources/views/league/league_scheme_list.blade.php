@extends('layouts.page')

@section('content_header')
<div class="row d-flex justify-content-between">
    <div class="col-4">
        <div>
            <label for='selSize'>@lang('schedule.action.size.select')</label>
            <select class='js-example-placeholder-single js-size1 js-states form-control select2'
                id='selSize'>
            </select>
        </div>
    </div>
    <div class="col-4">
        <div>
            <label for='selSize2'>@lang('schedule.action.size.compare')</label>
            <select class='js-example-placeholder-single js-size2 js-states form-control select2'
                id='selSize2'>
            </select>
        </div>
    </div>
</div>
@stop

@section('content')
<x-card-list cardTitle="{{ __('schedule.title.scheme.games',['size'=>'?']) }}" >
    <th>@lang('game.game_day')</th>
    <th>@lang('game.team_home'): 1</th>
    <th>2</th>
    <th>3</th>
    <th>4</th>
    <th>5</th>
    <th>6</th>
    <th>7</th>
    <th>8</th>
    <th>9</th>
    <th>10</th>
    <th>11</th>
    <th>12</th>
    <th>13</th>
    <th>14</th>
    <th>15</th>
    <th>16</th>
    <x-slot:addButtons>
        <button type="button" class="btn btn-outline-secondary mr-2" id="getHelp">{{ __('Help')}}</button>
    </x-slot:addButtons>
</x-card-list>

@include('league.includes.league_scheme_list_help')

<x-card-list tableId="tblSchemeMatch"  cardTitle="{{ __('schedule.title.scheme.matches', ['size'=>'?']) }}">
    <th>Ziffern</th>
    <th>1</th>
    <th>2</th>
    <th>3</th>
    <th>4</th>
    <th>5</th>
    <th>6</th>
    <th>7</th>
    <th>8</th>
    <th>9</th>
    <th>10</th>
    <th>11</th>
    <th>12</th>
    <th>13</th>
    <th>14</th>
    <th>15</th>
    <th>16</th>
    <x-slot:addButtons>
        <button type="button" class="btn btn-outline-secondary mr-2" id="getHelp">{{ __('Help')}}</button>
    </x-slot:addButtons>
</x-card-list>

<x-card-list tableId="tblSchemeCompare"  cardTitle="{{ __('schedule.title.scheme.xmatches', ['size1'=>'?', 'size2'=>'?']) }}">
    <th>Ziffern</th>
    <th>1</th>
    <th>2</th>
    <th>3</th>
    <th>4</th>
    <th>5</th>
    <th>6</th>
    <th>7</th>
    <th>8</th>
    <th>9</th>
    <th>10</th>
    <th>11</th>
    <th>12</th>
    <th>13</th>
    <th>14</th>
    <th>15</th>
    <th>16</th>
    <x-slot:addButtons>
        <button type="button" class="btn btn-outline-secondary mr-2" id="getHelp">{{ __('Help')}}</button>
    </x-slot:addButtons>
</x-card-list>
@stop

@section('js')
    <script>
        $(function() {
            $(document).on('click', 'button#getHelp', function() {
                $('#modalLeagueSchemeListHelp_{{app()->getLocale()}}').modal('show');
            });
            $('#goBack').click(function(e){
                history.back();
            });

            $(".js-example-placeholder-single").select2({
                placeholder: "@lang('schedule.action.size.select')...",
                width: '100%',
                allowClear: false,
                minimumResultsForSearch: -1,
                ajax: {
                    url: "{{ route('size.index') }}",
                    type: "get",
                    delay: 250,
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });

            var myTable = $('#table').DataTable({
                responsive: true,
                paging: false,
                bSort: false,
                bFilter: false,
                language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                columns: [
                    { data: 'game_day', name: 'game_day' },
                    { data: '1', name: '1' },
                    { data: '2', name: '2' },
                    { data: '3', name: '3' },
                    { data: '4', name: '4' },
                    { data: '5', name: '5' },
                    { data: '6', name: '6' },
                    { data: '7', name: '7' },
                    { data: '8', name: '8' },
                    { data: '9', name: '9' },
                    { data: '10', name: '10' },
                    { data: '11', name: '11' },
                    { data: '12', name: '12' },
                    { data: '13', name: '13' },
                    { data: '14', name: '14' },
                    { data: '15', name: '15' },
                    { data: '16', name: '16' }
                ]
            });
            var myMatchTable = $('#tblSchemeMatch').DataTable({
                responsive: true,
                paging: false,
                bSort: false,
                bFilter: false,
                language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                columns: [
                    { data: 'league_no', name: 'league_no' },
                    { data: '1', name: '1' },
                    { data: '2', name: '2' },
                    { data: '3', name: '3' },
                    { data: '4', name: '4' },
                    { data: '5', name: '5' },
                    { data: '6', name: '6' },
                    { data: '7', name: '7' },
                    { data: '8', name: '8' },
                    { data: '9', name: '9' },
                    { data: '10', name: '10' },
                    { data: '11', name: '11' },
                    { data: '12', name: '12' },
                    { data: '13', name: '13' },
                    { data: '14', name: '14' },
                    { data: '15', name: '15' },
                    { data: '16', name: '16' }
                ]
            });
            var myCompareTable = $('#tblSchemeCompare').DataTable({
                responsive: true,
                paging: false,
                bSort: false,
                bFilter: false,
                language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                columns: [
                    { data: 'league_no', name: 'league_no' },
                    { data: '1', name: '1' },
                    { data: '2', name: '2' },
                    { data: '3', name: '3' },
                    { data: '4', name: '4' },
                    { data: '5', name: '5' },
                    { data: '6', name: '6' },
                    { data: '7', name: '7' },
                    { data: '8', name: '8' },
                    { data: '9', name: '9' },
                    { data: '10', name: '10' },
                    { data: '11', name: '11' },
                    { data: '12', name: '12' },
                    { data: '13', name: '13' },
                    { data: '14', name: '14' },
                    { data: '15', name: '15' },
                    { data: '16', name: '16' }
                ]
            });

            $(".js-size1").on('select2:select', function(e) {
                var data = e.params.data;
                var url = '{{ route('scheme.list_piv', ['size' => ':size:']) }}';
                url = url.replace(':size:', data.id);
                myTable.ajax.url( url ).load();
                var title1 = '{{__('schedule.title.scheme.games',['size'=>':size:'])}}';
                var title2 = '{{__('schedule.title.scheme.matches',['size'=>':size:'])}}';
                title1 = title1.replace(':size:', '"'+data.text+'"');
                title2 = title2.replace(':size:', '"'+data.text+'"');
                $('#titletable').html(title1);
                $('#titletblSchemeMatch').html(title2);

                var url2 = '{{ route('scheme.list_match', ['size' => ':size:']) }}';
                url2 = url2.replace(':size:', data.id);
                myMatchTable.ajax.url( url2 ).load();

            });
            $(".js-size2").on('select2:select', function(e) {
                var data1 = $('.js-size1').select2('data');
                var data2 = e.params.data;
                var title3 = '{{__('schedule.title.scheme.xmatches',['size1'=>':size1:','size2'=>':size2:'])}}';
                title3 = title3.replace(':size1:', '"'+data1[0].text+'"');
                title3 = title3.replace(':size2:', '"'+data2.text+'"');
                $('#titletblSchemeCompare').html(title3);

                var url3 = '{{ route('scheme.list_compare', ['size1' => ':size1:', 'size2' => ':size2:']) }}';
                url3 = url3.replace(':size2:', data2.id);
                url3 = url3.replace(':size1:', data1[0].id);
                myCompareTable.ajax.url( url3 ).load();

            });
        });
    </script>


@stop
