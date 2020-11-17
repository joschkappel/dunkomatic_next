<h2>{{ $league->name }}</h2>
<div>
<table style="font-family: Tahoma, Geneva, sans-serif;">
  <thead>
     <tr>
         <th>@lang('game.game_date')</th>
         <th>@lang('game.game_time')</th>
         <th>@lang('game.game_no')</th>
         <th>@lang('game.team_home')</th>
         <th>@lang('game.team_guest')</th>
         <th>@lang('game.gym_no')</th>
         <th>@lang('game.referee')</th>
     </tr>
   </thead>
   <tbody style="font-size: 12px;text-align:center;">
   @foreach($games as $game)
     <tr>
           <td style="background-color: {{ $loop->index % 2 == 0 ? '#D0E4F5': '#ffffff' }};border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->game_date->locale( app()->getLocale())->isoFormat('ddd L') }}</td>
           <td style="background-color: {{ $loop->index % 2 == 0 ? '#D0E4F5': '#ffffff' }};border: 1px solid #FFFFFF;padding: 3px 2px;">{{ Carbon\Carbon::parse($game->game_time)->isoFormat('LT')}}</td>
           <td style="text-align: right; background-color: {{ $loop->index % 2 == 0 ? '#D0E4F5': '#ffffff' }};border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->game_no }}</td>
           <td style="background-color: {{ $loop->index % 2 == 0 ? '#D0E4F5': '#ffffff' }};border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->team_home}}</td>
           <td style="background-color: {{ $loop->index % 2 == 0 ? '#D0E4F5': '#ffffff' }};border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->team_guest}}</td>
           <td style="background-color: {{ $loop->index % 2 == 0 ? '#D0E4F5': '#ffffff' }};border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->gym_no}}</td>
           <td style="background-color: {{ $loop->index % 2 == 0 ? '#D0E4F5': '#ffffff' }};border: 1px solid #FFFFFF;padding: 3px 2px;">{{ $game->referee_1}}</td>
       </tr>
   @endforeach
   </tbody>
</table>
</div>
<h2>Gegner und Hallen</h2>
<div>
<table>
   <tbody>
   @foreach($clubs as $c)
       <tr>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 16px;font-weight: bold;color: #FFFFFF;border-left: 2px solid #FFFFFF;"><strong>{{ $c->shortname}}</strong></td>
       </tr>
       @foreach($c['teams'] as $t)
         <tr>
           <td></td>
           <td style="text-align:right">Teams</td>
           <td><strong>{{ $c->shortname}}{{ $t->team_no }}</strong></td>
           <td>{{ $t->shirt_color }}</td>
         </tr>
         <tr>
           <td></td>
           <td></td>
           <td>{{ $t->coach_name }}</td>
           <td>{{ $t->coach_email }}</td>
           <td>{{ $t->coach_phone }}</td>
         </tr>
        @endforeach
        @foreach($c['gyms'] as $g)
          <tr>
            <td></td>
            <td style="text-align:right">Hallen</td>
            <td style="text-align:left"><strong>{{ $g->gym_no }}</strong></td>
            <td><a target="_blank" href="https://www.google.de/maps/place/{!! $g->street !!},+{{$g->zip}},+{!! $g->city !!}">{{ $g->name }}</a></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $g->zip.' '.$g->city.', '.$g->street}}</td>
          </tr>
         @endforeach
   @endforeach
   </tbody>
</table>
</div>
