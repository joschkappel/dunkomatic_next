<table style="font-family: Tahoma, Geneva, sans-serif;border-collapse: collapse">
   <thead>
   </thead>
   <tbody>
   @foreach($clubs as $c)
       <tr>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"><strong>{{ $c->shortname}}</strong></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">{{ $c->name}}</td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">{{ $c->club_no}}</td>
       </tr>
       @foreach($c->memberships->sortBy('role_id') as $m)
         <tr>
           <td style="text-align:left; font-size: 12px;"><strong>{{ App\Enums\Role::coerce($m->role_id)->description }}</strong></td>
           <td style="text-align:left; font-size: 12px;"><strong>{{ $m->member->name }}</strong></td>
           <td style="font-size: 12px;">{{ ($m->email != '') ?  $m->email : $m->member->email }}</td>
           <td style="font-size: 12px;">{{ $m->member->address }}</td>
         </tr>
         <tr>
           <td style="font-size: 12px;">{{ ($m->function == '') ? '' : '('.$m->function.')' }}</td>
           <td></td>
           <td style="font-size: 12px;">{{ ($m->member->mobile != '') ? $m->member->mobile : $m->member->phone }}</td>
           <td></td>
         </tr>
        @endforeach
        @foreach($c->gyms->sortBy('gym_no') as $g)
          <tr>
            <td></td>
            <td style="text-align:right; font-size: 12px;"><strong>@lang('gym.no') {{ $g->gym_no }}</strong></td>
            <td style="font-size: 12px;"><a target="_blank" href="https://www.google.de/maps/place/{!! $g->street !!},+{{$g->zip}},+{!! $g->city !!}">{{ $g->name }}</a></td>
            <td style="font-size: 12px;">{{ $g->address }}</td>
          </tr>
         @endforeach
   @endforeach
   </tbody>
</table>
