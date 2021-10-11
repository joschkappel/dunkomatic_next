<table style="font-family: Tahoma, Geneva, sans-serif;">
  <thead>
     <tr>
         <th>@lang('game.game_date')</th>
         <th>@lang('game.game_time')</th>
         <th>{{ trans_choice('league.league',1)}}</th>
         <th>@lang('game.game_no')</th>
         <th>@lang('game.team_home')</th>
         <th>@lang('game.team_guest')</th>
         <th>@lang('game.gym_no')</th>
         <th>@lang('game.referee')</th>
     </tr>
   </thead>
   <tbody style="font-size: 12px;text-align:center;">
   @foreach($games as $game)

       @if ( $gdate != $game->game_date )
       <tr>
         @php $gdate = $game->game_date; @endphp
         <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->game_date->locale( app()->getLocale())->isoFormat('ddd L') }}</td>
         <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
         <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
         <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
         <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
         <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
         <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
         <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
       @endif
       <tr>
         <td style="border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
         @if ($game->game_time != null)
         <td style="border: 1px solid #FFFFFF;padding: 3px 2px;">{{ Carbon\Carbon::parse($game->game_time)->isoFormat('LT')}}</td>
         @else
         <td style="border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
          @endif
         <td style="text-align: right;border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->league->shortname }}</td>
         <td style="border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->game_no }}</td>
         <td style="border: 1px solid #FFFFFF;padding: 3px 2px;">
           @if (strpos($game->team_home, $club->shortname) !== false)<strong>{{ $game->team_home}}</strong>
           @else {{ $game->team_home}}
           @endif</td>
         <td style="border: 1px solid #FFFFFF;padding: 3px 2px;">
           @if (strpos($game->team_guest, $club->shortname) !== false)<strong>{{ $game->team_guest}}</strong>
           @else {{ $game->team_guest}}
           @endif</td>
         <td style="border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->gym_no}}</td>
         <td style="border: 1px solid #FFFFFF;padding: 3px 2px;">{{ ($game->referee_1 == '' or $game->referee_1 == '****') ? $game->referee_1 : $game->referee_1." / ".$game->referee_2}}</td>
       </tr>
   @endforeach
   </tbody>
</table>
