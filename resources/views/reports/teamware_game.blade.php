<table>
   <tbody>
   @foreach($games as $g)
         <tr>
           <td>{{ $schemes[$g->game_no] }}</td>
           <td>{{ $g->game_no  }}</td>
           <td>{{ $g->game_date->format('d.m.Y') }}</td>
           <td>{{ Str::replaceLast(':00','', $g->game_time) }}</td>
           <td>{{ $g->team_home }}</td>
           <td>{{ $g->team_guest }}</td>
           <td>{{ Str::replaceFirst('HBV','', $g->club_home->region->code).'-'.$g->club_home->shortname.$g->gym_no}}</td>
       </tr>
   @endforeach
   </tbody>
</table>
