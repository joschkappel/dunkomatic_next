<table style="font-family: Tahoma, Geneva, sans-serif;border-collapse: collapse">
   <thead>
   </thead>
   <tbody>
   @foreach($leagues as $l)
       <tr>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"><strong>{{ $l->shortname}}</strong></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">{{ $l->name}}</td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
           <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
       </tr>
       @foreach($l->memberships->sortBy('role_id') as $m)
         <tr>
           <td style="text-align:left"><strong>{{ App\Enums\Role::coerce($m->role_id)->description }}</strong></td>
           <td style="text-align:left"><strong>{{ $m->member->name }}</strong></td>
           <td>{{ ($m->email != '') ?  $m->email : $m->member->email }}</td>
           <td>{{ $m->member->address }}</td>
         </tr>
         <tr>
           <td>{{ ($m->function == '') ? '' : '('.$m->function.')' }}</td>
           <td></td>
           <td>{{ ($m->member->mobile != '') ? $m->member->mobile : $m->member->phone }}</td>
           <td></td>
         </tr>
        @endforeach
        @foreach($l->teams->sortBy('name') as $t)
          <tr>
            <td></td>
            <td style="text-align:right"><strong>{{ $t->name }}</strong></td>
            <td>{{ $t->load('members')->members->pluck('name')->implode(', ') }}</td>
            <td>{{$t->load('members')->members->pluck('email')->implode(', ') }}</td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $t->load('members')->members->pluck('mobile')->implode(', ') }}</td>
          </tr>
         @endforeach
   @endforeach
   </tbody>
</table>
