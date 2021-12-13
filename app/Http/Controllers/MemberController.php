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
use Bouncer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

use App\Notifications\InviteUser;

class MemberController extends Controller
{

    public function index($language, Region $region)
    {
        Log::info('showing member list.');
        return view('member/member_list', ['region' => $region]);
    }

    public function datatable(Region $region)
    {
        $members = $region->clubs()->with('members')->get()->pluck('members.*.id')->flatten()->concat(
            $region->leagues()->with('members')->get()->pluck('members.*.id')->flatten()
        )->concat(
            $region->members->pluck('id')->flatten()
        )->unique();

        Log::info('preparing member list');
        $mlist = datatables()::of(Member::whereIn('id', $members)->get());

        return $mlist
            ->rawColumns(['user_account', 'email1', 'phone'])
            ->addColumn('name', function ($data) {
                return $data->lastname . ', ' . $data->firstname;
            })
            ->addColumn('clubs', function ($data) {
                return $data->memberofclubs;
            })
            ->addColumn('leagues', function ($data) {
                return $data->memberofleagues;
            })
            ->addColumn('user_account', function ($data) {
                if ($data->isuser) {
                    if (Bouncer::can('update-users')) {
                        return '<a href="' . route('admin.user.edit', ['language' => app()->getLocale(), 'user' => $data->user->id]) . '"><i class="fas fa-user text-info"></i></a>';
                    } else {
                        return '<i class="fas fa-user text-info"></i>';
                    }
                };
            })
            ->editColumn('email1', function ($m) {
                if (isset($m->email1)) {
                    return '<a href="mailto:' . $m->email1 . '" target="_blank">' . $m->email1 . '</a>';
                } else {
                    return "";
                }
            })
            ->editColumn('phone', function ($m) {
                if ((isset($m->mobile)) or (isset($m->phone))) {
                    $phone = isset($m->mobile) ? $m->mobile : $m->phone;
                    return '<a href="tel:' . $phone . '" target="_blank">' . $phone . '</a>';
                } else {
                    return "";
                }
            })
            ->make(True);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sb_region(Region $region)
    {
        if ($region->is_top_level) {
            Log::notice('getting members for top level region');
            $m_ids = collect();
            foreach ($region->childRegions as $r) {
                $m_ids = $m_ids->merge($r->clubs()->pluck('id'));
            };
        } else {
            Log::notice('getting members for base level region');
            $m_ids = $region->clubs()->pluck('id');
        }

        $members = Membership::whereIn('membership_id', $m_ids)
            ->where('membership_type', Club::class)
            ->with('member')
            ->get()
            ->sortBy('member.lastname')
            ->pluck('member.name', 'member.id');
        //Log::debug('got members '.count($members));

        Log::info('preparing select2 member list.', ['count' => count($members)] );
        $response = array();

        foreach ($members as $k => $v) {
            $response[] = array(
                "id" => $k,
                "text" => $v
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
        Log::info('get details of member', ['member-id'=>$member->id]);
        return Response::json($member);
    }

    public function store(Request $request)
    {
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
        Log::info('member form data validated OK.');

        $member_id = $data['member_id'];
        unset($data['member_id']);
        $mship = [];
        $mship['role_id'] = $data['role_id'];
        unset($data['role_id']);
        $mship['function'] = $data['function'];
        unset($data['function']);
        $mship['email'] = $data['email'];
        unset($data['email']);

        if (($member_id == null) or (! Member::find($member_id)->exists())) {
            $member = Member::create($data);
            Log::notice('new member created.', ['member-id'=>$member->id]);
        } else {
            $member = Member::findOrFail($member_id);
        }
        $member_id = $member->id;
        $mship['member_id'] = $member_id;

        $entity_type = $request['entity_type'];
        $entity_id = $request['entity_id'];
        if ($entity_type == Club::class) {
            $club = Club::findOrFail($entity_id);
            $club->memberships()->create($mship);
            return redirect()->route('club.dashboard', ['language' => app()->getLocale(), 'club' => $club]);
        } elseif ($entity_type == League::class) {
            $league = League::findOrFail($entity_id);
            $league->memberships()->create($mship);
            return redirect()->route('league.dashboard', ['language' => app()->getLocale(), 'league' => $league]);
        } elseif ($entity_type == Region::class) {
            $region = Region::findOrFail($entity_id);
            $region->memberships()->create($mship);
            // auto-invite rgeion admin
            if ($mship['role_id'] = Role::RegionLead()) {
                $member->notify(new InviteUser(Auth::user(), session('cur_region')));
                Log::info('[NOTIFICATION] invite user.',['member-id'=>$member->id]);
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
        Log::info('editing member.', ['member-id' => $member->id]);
        return view('member/member_edit', ['member' => $member, 'backto' => URL::previous()]);
    }

    public function update(Request $request, Member $member)
    {
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
        Log::info('member form data validated OK.');

        $check = $member->update($data);
        $member->refresh();
        Log::notice('member updated.', ['member-id'=> $member->id]);

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
        Log::info('[NOTIFICATION] invite user.',['member-id'=>$member->id]);

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
        Log::notice('member deleted',['member-id'=>$member->id]);

        return Response::json($check);
    }
}
