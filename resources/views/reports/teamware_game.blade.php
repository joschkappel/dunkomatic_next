<table>
   <tbody>
   @foreach($games as $g)
         <tr>
           <td>{{ $schemes[$g->game_no] }}</td>
           <td>{{ $g->game_no  }}</td>
           <td>{{ $g->game_date->format('d.m.Y') }}</td>
           <td>{{ Str::replaceLast(':00','', $g->game_time) }}</td>
           <td>{{ $g->team_home ?? '' }}</td>
           <td>{{ $g->team_guest ?? '' }}</td>
           @if ($g->club_home != null)
           <td>{{ Str::replaceFirst('HBV','', $g->club_home->region->code).'-'.$g->club_home->shortname.$g->gym_no}}</td>
           @else
           <td>{{ Str::replaceFirst('HBV','', 'HBV??').'-'.'????'.$g->gym_no}}</td>
           @endif
       </tr>
   @endforeach
   </tbody>
</table>
