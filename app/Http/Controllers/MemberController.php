<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use App\Models\Region;
use App\Models\League;
use App\Models\Club;
use App\Models\User;
use App\Models\Team;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\Role;
use App\Models\Invitation;
use Datatables;
use Silber\Bouncer\BouncerFacade as Bouncer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use App\Notifications\InviteUser;

class MemberController extends Controller
{

    /**
     * display member list
     *
     * @param string $language
     * @param Region $region
     * @return \Illuminate\View\View
     *
     */
    public function index(string $language, Region $region)
    {
        Log::info('showing member list.');
        return view('member/member_list', ['region' => $region]);
    }

    /**
     * datatables.net listing all members of a region
     *
     * @param Region $region
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function datatable(Region $region)
    {
        // rgional members of lcub, leagues and region
        $members = $region->clubs()->with('members')->get()->pluck('members.*.id')->flatten()->concat(
            $region->leagues()->with('members')->get()->pluck('members.*.id')->flatten()
        )->concat(
            $region->members->pluck('id')->flatten()
        )->unique();
        // add clubs and leagues that are top level leagues from other regions
        $leagues = $region->teams()->whereNotNull('league_id')->pluck('league_id')->unique();
        $toplevel_teams = Team::whereIn('league_id', $leagues)->whereRelation('club.region','id', '!=', $region->id)->with('club','league')->get();

        $members = $members->concat( League::whereIn('id', $leagues)->with('members')->get()->pluck('members.*.id')->flatten());
        $members = $members->concat( Club::whereIn('id', $toplevel_teams->pluck('club_id'))->with('members')->get()->pluck('members.*.id')->flatten() );

        $members = Member::whereIn('id', $members)->with('user', 'memberships')->get();
        // add coaches
        $coaches = Team::whereIn('league_id',$leagues)->with('club','league')->get()->concat($toplevel_teams)->unique();
        foreach ( $coaches as $co ){
            $m = new Member([
                'lastname' => $co->coach_name,
                'firstname' => '',
                'mobile' => $co->coach_phone1,
                'phone' => $co->coach_phone2,
                'email1' => $co->coach_email,
                'id' => mt_rand(100000, 999999)
            ]);
            $ms = $m->memberships()->make();
            $ms->membership_type = Team::class;
            $ms->membership_id = $co->id;
            $m->memberships->push($ms);

            $members->push($m);
        }


        Log::info('preparing member list',['cnt'=>$members->count()]);
        $mlist = datatables()::of($members);

        return $mlist
            ->rawColumns(['user_account', 'email1', 'email2','phone', 'name'])
            ->addColumn('action', function ($data) {
                return '<button type="button" id="copyAddress" name="copyAddress" class="btn btn-outline-primary btn-sm m-2" data-member-id="' . $data->id . '"
                ><i class="far fa-clipboard"></i></button>';
            })
            ->addColumn('name', function ($data) {
                 //return $data->lastname . ', ' . $data->firstname;
                 return '<a href="#copyAddress" id="copyAddress" name="copyAddress" data-member-id="' . $data->id . '">'.$data->lastname.', '.$data->firstname.'</a>';
            })
            ->addColumn('clubs', function ($data) {
                return $data->memberofclubs;
            })
            ->addColumn('leagues', function ($data) {
                return $data->memberofleagues;
            })
            ->addColumn('roles', function ($data) {
                $roles = $data->memberships->pluck('role_title');
                return $roles->implode(', ');
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
            ->editColumn('email2', function ($m) {
                if (isset($m->email2)) {
                    return '<a href="mailto:' . $m->email2 . '" target="_blank">' . $m->email2 . '</a>';
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
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function sb_region(Region $region)
    {
        if ($region->is_top_level) {
            Log::notice('getting members for top level region');
            $m_ids = collect();
            foreach ($region->childRegions as $r) {
                $m_ids = $m_ids->concat($r->clubs()->pluck('id'));
                $m_ids = $m_ids->concat($r->leagues()->pluck('id'));
            };
        } else {
            Log::notice('getting members for base level region');
            $m_ids = $region->clubs()->pluck('id');
            $m_ids = $m_ids->concat($region->leagues()->pluck('id'));
        }

        $members = Membership::whereIn('membership_id', $m_ids)
            ->whereIn('membership_type', [Club::class, League::class])
            ->with('member')
            ->get()
            ->sortBy('member.lastname')
            ->pluck('member.name', 'member.id');
        //Log::debug('got members '.count($members));

        Log::info('preparing select2 member list.', ['count' => count($members)]);
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
     * @param string $language
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function show($language, Member $member)
    {
        Log::info('get details of member', ['member-id' => $member->id]);
        return Response::json($member);
    }

    /**
     * store a new member in the DB
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     */
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

        if (($member_id == null) or (!Member::find($member_id)->exists())) {
            $member = Member::create($data);
            Log::notice('new member created.', ['member-id' => $member->id]);
        } else {
            $member = Member::findOrFail($member_id);
        }

        // check if a user existst
        $user = User::where('email', $member->email1)->first();
        if ($user){
            $user->member()->associate($member);
            $user->save();
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
            return redirect()->route('region.index', ['language' => app()->getLocale()]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $language
     * @param  \App\Models\Member  $member
     * @return \Illuminate\View\View
     *
     */
    public function edit($language, Member $member)
    {
        Log::info('editing member.', ['member-id' => $member->id]);
        return view('member/member_edit', ['member' => $member, 'backto' => URL::previous()]);
    }

    /**
     * update a member in the DB
     *
     * @param Request $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function update(Request $request, Member $member)
    {
        $backto = $request['backto'];
        unset($request['backto']);
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
        Log::notice('member updated.', ['member-id' => $member->id]);

        return redirect($backto);
    }
    /**
     * send a user registration invite to this member
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function invite(Member $member)
    {
        // cehck if invite existsts already
        if ( ($member->invitation()->exists() ) or ( Invitation::where('email_invitee',$member->email1)->exists() )){
            // do nothing return false
            return Redirect::back()->with(['error' => __('club.invitation.exists')]);
        } else {
            // create invitation
            DB::transaction(function () use($member) {
                $invite = Invitation::create(['email_invitee'=>$member->email1]);
                Auth::user()->invitations()->save($invite);
                $member->invitation()->save($invite);
                session('cur_region')->invitations()->save($invite);

                $member->notify(new InviteUser($invite));
                Log::info('[NOTIFICATION] invite user.', ['invitation' => $invite]);
            });


            return Redirect::back();

        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function destroy(Member $member)
    {
        // first remove all memberships
        foreach ($member->memberships as $mship){
            $mship->delete();
        }
        // delete the member now
        if ($member->user()->exists()){
            // unlink from user
            $user = $member->user;
            $user->member()->dissociate();
            $user->save();
            Log::notice('[] member deleted - associated user kept', ['user-id' => $user->id, 'member-id' => $member->id]);
        }
        $check = $member->delete();
        Log::notice('member deleted', ['member-id' => $member->id]);

        return Response::json(['deleted'=>$check]);
    }
}
