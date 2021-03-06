<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use App\Models\Region;
use App\Models\League;
use App\Models\Club;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\Role;

use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

use App\Notifications\InviteUser;

class MemberController extends Controller
{

      public function index()
      {
        return view('member/member_list');
      }

      public function datatable(Region $region )
      {
        $members = $region->clubs()->with('members')->get()->pluck('members.*.id')->flatten()->concat(
                      $region->leagues()->with('members')->get()->pluck('members.*.id')->flatten()
                    )->concat(
                      $region->members->pluck('id')->flatten()
                    )->unique();
        $mlist = datatables()::of(Member::whereIn('id', $members)->get());

        return $mlist
              ->rawColumns(['user_account'])
              ->addColumn('name', function ($data) {
                      return $data->lastname.', '.$data->firstname;
                  })
              ->addColumn('clubs', function ($data) {
                      return $data->memberofclubs;
                  })
              ->addColumn('leagues', function ($data) {
                      return $data->memberofleagues;
                  })
              ->addColumn('user_account', function ($data) {
                      if ($data->isuser and !$data->user->isregionadmin){
                        return '<a href="' . route('admin.user.edit', ['language'=>app()->getLocale(), 'user'=>$data->user->id]) .'"><i class="fas fa-user text-info"></i></a>';
                      };
                  })
              ->make(True);
      }

      /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function sb_region( Region $region)
      {
        Log::info('members for region '.$region->name);

        if ($region->hq == null){
            $m_ids = collect();
            foreach ( $region->childRegions as $r){
                $m_ids = $m_ids->merge($r->clubs()->pluck('id'));
            };
        } else {
            $m_ids = $region->clubs()->pluck('id');
        }

        $members = Membership::whereIn('membership_id', $m_ids)
                              ->where('membership_type',Club::class)
                              ->with('member')
                              ->get()
                              ->sortBy('member.lastname')
                              ->pluck('member.name','member.id');
        //Log::debug('got members '.count($members));

        $response = array();

        foreach($members as $k => $v){
            $response[] = array(
                  "id"=>$k,
                  "text"=>$v
                );
        }

        return Response::json($response);
      }

    /**
     * Display the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show($language, Member $member)
    {
      //Log::debug(print_r($member,true));
      return Response::json($member);
    }

    public function store(Request $request)
    {
        Log::info(print_r($request->all(),true));
        $data = $request->validate([
          'member_id' => 'nullable|exists:members,id',
          'firstname' => 'required|max:20',
          'lastname' => 'required|max:60',
          'zipcode' => 'required|max:10',
          'city' => 'required|max:40',
          'street' => 'required|max:40',
          'mobile' => 'required_without:phone|max:40',
          'phone' => 'required_without:mobile|max:40',
          'fax' => 'max:40',
          'email1' => 'required|max:60|email:rfc,dns',
          'email2' => 'nullable|max:60|email:rfc,dns',
          'role_id' => ['required', new EnumValue(Role::class, false)],
          'function'  => 'nullable|max:40',
          'email'     => 'nullable|max:60|email:rfc,dns',
          ]);

        $member_id = $data['member_id'];
        unset($data['member_id']);
        $mship = [];
        $mship['role_id'] = $data['role_id'];
        unset($data['role_id']);
        $mship['function'] = $data['function'];
        unset($data['function']);
        $mship['email'] = $data['email'];
        unset($data['email']);

        if ( ($member_id == null) or (! Member::find($member_id)->exists()) ){
          $member = Member::create($data);
        } else {
          $member = Member::find($member_id);
        }
        $member_id = $member->id;
        $mship['member_id'] = $member_id;

        $entity_type = $request['entity_type'];
        $entity_id = $request['entity_id'];
        if ($entity_type == Club::class){
          $club = Club::find($entity_id);
          $club->memberships()->create($mship);
          return redirect()->route('club.dashboard', ['language' => app()->getLocale(), 'club' => $club]);
        } elseif ($entity_type == League::class){
          $league = League::find($entity_id);
          $league->memberships()->create($mship);
          return redirect()->route('league.dashboard', ['language' => app()->getLocale(), 'league' => $league]);
        } elseif ($entity_type == Region::class){        
          $region = Region::find($entity_id);
          $region->memberships()->create($mship);
          // auto-invite rgeion admin
          if ( $mship['role_id'] = Role::RegionLead()){
            $member->notify(new InviteUser(Auth::user(), Auth::user()->region));
          }
          return redirect()->route('region.index', ['language' => app()->getLocale()]);
        } else {
          return redirect()->back();
        }

      }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit($language, Member $member)
    {
      //Log::debug(print_r($member,true));
      return view('member/member_edit', ['member' => $member, 'backto' => URL::previous()]);

    }

    public function update(Request $request, Member $member)
    {
        Log::debug(print_r($request->all(),true));
        $backto = $request['backto'];
        unset($request['backto']);
        // $data = Validator::make($request->all(), Member::$createRules);
        $data = $request->validate([
          'firstname' => 'required|max:20',
          'lastname' => 'required|max:60',
          'zipcode' => 'required|max:10',
          'city' => 'required|max:40',
          'street' => 'required|max:40',
          'mobile' => 'required_without:phone|max:40',
          'phone' => 'required_without:mobile|max:40',
          'fax' => 'max:40',
          'email1' => 'required|max:60|email:rfc,dns',
          'email2' => 'nullable|max:60|email:rfc,dns',
          ]);

        $member->update($data);

        return redirect($backto);
    }
    /**
     * send a user registration invite to this member
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function invite(Member $member)
    {
      $member->notify(new InviteUser(Auth::user(), Auth::user()->region));
      return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
     public function destroy(Member $member)
     {
         $check = $member->delete();

         return Response::json($check);
     }

}
