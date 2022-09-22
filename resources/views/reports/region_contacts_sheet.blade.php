<table style="font-family: Tahoma, Geneva, sans-serif;border-collapse: collapse">
   <thead>
   </thead>
   <tbody>
    <tr>
        <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"><strong>{{ $region->code}}</strong></td>
        <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">{{ $region->name}}</td>
        <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
        <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
    </tr>
    @foreach($region->memberships->sortBy('role_id') as $m)
        <tr>
        <td style="text-align:left"><strong>{{ App\Enums\Role::coerce($m->role_id)->description }}</strong></td>
        <td style="text-align:left"><strong>{{ $m->member->name }}</strong></td>
        <td>{{ $m->master_email }}</td>
        <td>{{ $m->member->address }}</td>
        </tr>
        <tr>
        <td>{{ ($m->function == '') ? '' : '('.$m->function.')' }}</td>
        <td></td>
        <td>{{ ($m->member->mobile != '') ? $m->member->mobile : $m->member->phone }}</td>
        <td></td>
        </tr>
    @endforeach
    @foreach( $region->childRegions->sortBy('code') as $cr)
    <tr>
        <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"><strong>{{ $cr->code}}</strong></td>
        <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;">{{ $cr->name}}</td>
        <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
        <td style="border: 1px solid #FFFFFF;padding: 3px 2px;background: #0B6FA4;border-bottom: 5px solid #FFFFFF; font-size: 14px;color: #FFFFFF;border-left: 2px solid #FFFFFF;"></td>
    </tr>
    @foreach($cr->memberships->sortBy('role_id') as $m)
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
    @endforeach
   </tbody>
</table>
