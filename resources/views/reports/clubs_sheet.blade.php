<table style="font-family: Tahoma, Geneva, sans-serif;border-collapse: collapse">
   <thead>
   </thead>
   <tbody>
    <tr>
        <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"><strong>Spielleitung</strong></td>
        <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
        <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
        <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
    </tr>
    <tr></tr>
       @foreach($leagues as $l)
           <tr>
               <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"><strong>{{ $l->shortname}}</strong></td>
               <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
               <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
               <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
           </tr>
           @foreach($l->members as $m)
             <tr>
               <td style="text-align:right; font-size: 12px;"><strong>{{ App\Enums\Role::coerce($m->pivot->role_id)->description}}</strong></td>
               <td style="text-align:right; font-size: 12px;">{{ $m->name }}</td>
               <td style="font-size: 12px;">{{ $m->email1 }}</td>
               <td style="font-size: 12px;">{{ $m->address }}</td>
             </tr>
             <tr>
               <td></td>
               <td></td>
               <td style="font-size: 12px;">{{ $m->email2 }}</td>
               <td style="font-size: 12px;">{{ ( $m->phone == '') ? $m->mobile : $m->mobile.' / '.$m->phone }}</td>
             </tr>
            @endforeach
            <tr></tr>
       @endforeach
       <tr></tr>
       <tr></tr>
       <tr></tr>
   </tbody>
</table>
<table style="font-family: Tahoma, Geneva, sans-serif;border-collapse: collapse">
    <thead>
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"><strong>Betreuer und Hallen</strong></td>
            <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
            <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
            <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
        </tr>
        <tr></tr>
   @foreach($clubs as $c)
       <tr>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"><strong>{{ $c->shortname}}</strong></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
       </tr>
       @foreach($c['teams'] as $t)
         <tr>
           <td style="text-align:right; font-size: 12px;"><strong>{{$t->league->shortname}}</strong></td>
           <td style="text-align:right; font-size: 12px;"><strong>{{ $c->shortname}} {{ $t->team_no }}</strong></td>
           <td style="font-size: 12px;">{{ $t->coach_name }}</td>
           <td style="font-size: 12px;">@lang('team.shirtcolor'): {{ $t->shirt_color }}</td>
         </tr>
         <tr>
           <td></td>
           <td></td>
           <td style="font-size: 12px;">{{ $t->coach_email }}</td>
           <td style="font-size: 12px;">{{ ( $t->coach_phone2 == '') ? $t->coach_phone1 : $t->coach_phone1.' / '.$t->coach_phone2 }}</td>
         </tr>
        @endforeach
        @foreach($c['gyms'] as $g)
          <tr>
            <td></td>
            <td style="text-align:right; font-size: 12px;"><strong>@lang('gym.no') {{ $g->gym_no }}</strong></td>
            <td style="font-size: 12px;"><a target="_blank" href="https://www.google.de/maps/place/{!! $g->street !!},+{{$g->zip}},+{!! $g->city !!}">{{ $g->name }}</a></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td style="font-size: 12px;">{{ $g->street.', '.$g->zip.' '.$g->city }}</td>
          </tr>
         @endforeach
   @endforeach
</tbody>
</table>
