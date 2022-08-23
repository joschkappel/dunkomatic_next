<table style="font-family: Tahoma, Geneva, sans-serif;border-collapse: collapse">
  <thead>
     <tr>
         <th style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">@lang('game.game_date')</th>
         <th style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">@lang('game.game_time')</th>
         @if( $with_league )
         <th style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">@lang('game.league')</th>
         @endif
         <th style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">@lang('game.game_no')</th>
         <th style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">@lang('game.team_home')</th>
         <th style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">@lang('game.team_guest')</th>
         <th style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">@lang('game.gym_no')</th>
         <th style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">@lang('game.referee')</th>
     </tr>
   </thead>
   <tbody style="font-size: 12px;text-align:center;">
    @php $toggle = true; @endphp
   @foreach($games as $game)
         @if ( $gdate != $game->game_date )
         <tr>
           @php $gdate = $game->game_date; @endphp
           <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;font-size: 12px;">{{ $game->game_date->locale( app()->getLocale())->isoFormat('ddd L') }}</td>
           <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
           @if( $with_league )
           <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
           @endif
           <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
           <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
           <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
           <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
           <td style="background-color: #D0E4F5;border: 1px solid #FFFFFF;padding: 3px 2px;"></td>
        @endif
        <tr>
            @if ( $gtime != $game->game_time )
                @php $toggle = ! $toggle; $gtime = $game->game_time; @endphp
            @endif
            @if ( $toggle )
                @php $rstyle='background: #ffbaba;'; @endphp
            @else
                @php $rstyle='background: #ede9e9;'; @endphp
            @endif

           <td style="border: 1px solid #ffffff;padding: 3px 2px;"></td>
           @if ($game->game_time != null)
             <td style="border: 1px solid #ffffff;padding: 3px 2px;font-size: 12px;{{$rstyle}}">{{ Carbon\Carbon::parse($game->game_time)->isoFormat('LT')}}</td>
           @else
             <td style="border: 1px solid #ffffff;padding: 3px 2px;font-size: 12px;"></td>
           @endif
           @if( $with_league )
           <td style="border: 1px solid #ffffff;padding: 3px 2px;font-size: 12px;{{$rstyle}}">{{ $game->league->shortname }}</td>
           @endif
           <td style="text-align: right;border: 1px solid #FFFFFF;padding: 3px 2px;font-size: 12px;{{$rstyle}}">{{ $game->game_no }}</td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;font-size: 14px;{{$rstyle}}">{{ $game->team_home}}</td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;font-size: 14px;{{$rstyle}}">{{ $game->team_guest}}</td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;font-size: 14px;{{$rstyle}}">{{ $game->gym_no}}</td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;font-size: 14px;{{$rstyle}}">{{ ($game->referee_1 == '' or $game->referee_1 == '****') ? $game->referee_1 : $game->referee_1." / ".$game->referee_2}}</td>
        </tr>
   @endforeach
   </tbody>
</table>

