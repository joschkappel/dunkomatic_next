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
       </tr>
       @foreach($c['teams'] as $t)
         <tr>
           <td style="text-align:right"><strong>{{$t->league->shortname}}</strong></td>
           <td style="text-align:right"><strong>{{ $c->shortname}} {{ $t->team_no }}</strong></td>
           <td>{{ $t->coach_name }}</td>
           <td>@lang('team.shirtcolor'): {{ $t->shirt_color }}</td>
         </tr>
         <tr>
           <td></td>
           <td></td>
           <td>{{ $t->coach_email }}</td>
           <td>{{ ( $t->coach_phone2 == '') ? $t->coach_phone1 : $t->coach_phone1.' / '.$t->coach_phone2 }}</td>
         </tr>
        @endforeach
        @foreach($c['gyms'] as $g)
          <tr>
            <td></td>
            <td style="text-align:right"><strong>@lang('gym.no') {{ $g->gym_no }}</strong></td>
            <td><a target="_blank" href="https://www.google.de/maps/place/{!! $g->street !!},+{{$g->zip}},+{!! $g->city !!}">{{ $g->name }}</a></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td>{{ $g->street.', '.$g->zip.' '.$g->city }}</td>
          </tr>
         @endforeach
   @endforeach
   </tbody>
</table>