@extends('layouts.page')

@section('plugins.Datatables', true)

@section('content')
<x-card-list cardTitle="{{ __('game.title.list', ['region'=>$region->name ]) }}">
    <th>id</th>
    <th>@lang('game.game_date')</th>
    <th>@lang('game.game_time')</th>
    <th>{{ trans_choice('league.league',1)}}</th>
    <th>@lang('game.game_no')</th>
    <th>@lang('game.team_home')</th>
    <th>@lang('game.team_guest')</th>
    <th>@lang('game.gym_no')</th>
    <th>{{ __('game.referee') }} 1</th>
    <th>{{ __('game.referee') }} 2</th>
</x-card-list>
@endsection

@section('js')

<script>
         $(function() {
              $('#goBack').click(function(e){
                  history.back();
              });
              $('#table').DataTable({
                 processing: false,
                 serverSide: false,
                 responsive: true,
                 @if (app()->getLocale() == 'de')
                 language: { "url": "{{URL::asset('vendor/datatables-plugins/i18n/German.json')}}" },
                 @else
                 language: { "url": "{{URL::asset('vendor/datatables-plugins/i18n/English.json')}}" },
                 @endif
                 ajax: '{{ route('game.datatable', ['region' => $region, 'language'=> app()->getLocale()]) }}',
                 columns:  [
                    { data: 'id', name: 'id', visible: false },
                    { data: {
                            _: 'game_date.filter',
                            export: 'game_date.filter',
                            display: 'game_date.display',
                            sort: 'game_date.ts'
                        },
                        name: 'game_date.ts'
                    },
                    { data: 'game_time', name: 'game_time'},
                    { data: 'game_league', name: 'game_league'},
                    { data: { _: 'game_no.display', sort: 'game_no.sort' }, name: 'game_no.sort' },
                    { data: 'team_home', name: 'team_home' },
                    { data: 'team_guest', name: 'team_guest' },
                    { data: {
                            _: 'gym_no.default',
                            export: 'gym_no.default',
                            display: 'gym_no.display'
                        },
                        name: 'gym_no.default'
                    },
                    { data: 'referee_1', name: 'referee_1'},
                    { data: 'referee_2', name: 'referee_2'}
                ]

              });
        });
</script>
@endsection
