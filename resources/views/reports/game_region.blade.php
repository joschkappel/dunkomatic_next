<table style="font-family: Tahoma, Geneva, sans-serif;">
  <thead>
     <tr>
         <th>@lang('game.game_date')</th>
         <th>@lang('game.game_time')</th>
         <th>@lang('game.league')</th>
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
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->league->shortname }}</td>
           <td style="text-align: right;border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->game_no }}</td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->team_home}}</td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->team_guest}}</td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->gym_no}}</td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;">{{ ($game->referee_1 == '' or $game->referee_1 == '****') ? $game->referee_1 : $game->referee_1." / ".$game->referee_2}}</td>
       </tr>
   @endforeach
   </tbody>
</table>
<table>
   <thead>
   </thead>
   <tbody>
   @foreach($clubs as $c)
       <tr>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"><strong>{{ $c->shortname}}</strong></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
       </tr>
       @foreach($c['teams'] as $t)
         <tr>
           <td style="text-align:right"><strong>{{ $c->shortname}} {{ $t->team_no }}</strong></td>
           <td>{{ $t->coach_name }}</td>
           <td></td>
           <td></td>
           <td></td>
           <td>@lang('team.shirtcolor'): {{ $t->shirt_color }}</td>
         </tr>
         <tr>
           <td></td>
           <td>{{ $t->coach_email }}</td>
           <td></td>
           <td></td>
           <td></td>
           <td>{{ ( $t->coach_phone2 == '') ? $t->coach_phone1 : $t->coach_phone1.' / '.$t->coach_phone2 }}</td>
         </tr>
        @endforeach
        @foreach($c['gyms'] as $g)
          <tr>
            <td style="text-align:right"><strong>@lang('gym.no') {{ $g->gym_no }}</strong></td>
            <td><a target="_blank" href="https://www.google.de/maps/place/{!! $g->street !!},+{{$g->zip}},+{!! $g->city !!}">{{ $g->name }}</a></td>
          </tr>
          <tr>
            <td></td>
            <td>{{ $g->street.', '.$g->zip.' '.$g->city }}</td>
          </tr>
         @endforeach
   @endforeach
   </tbody>
</table>
