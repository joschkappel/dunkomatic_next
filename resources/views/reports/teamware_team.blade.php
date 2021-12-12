<table>
   <tbody>
   @foreach($teams as $t)
         <tr>
           <td>{{ $t->club->name.' '.$t->team_no }}</td>
           <td>{{ $t->club->shortname.$t->team_no }}</td>
           <td>{{ $t->club->club_no }}</td>
           <td>{{ $t->team_no }}</td>
           <td>{{ Str::replaceFirst('HBV','', $t->club->region->code).'-'.$t->club->shortname.'1'}}</td>
       </tr>
   @endforeach
   </tbody>
</table>
