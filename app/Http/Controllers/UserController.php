<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\User;
use App\Notifications\ApproveUser;
use App\Notifications\RejectUser;
use App\Traits\UserAccessManager;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use OwenIt\Auditing\Models\Audit;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UserController extends Controller
{
    use UserAccessManager;

    /**
     * display view with all users of a region
     *
     * @param  string  $language
     * @param  Region  $region
     * @return \Illuminate\View\View
     */
    public function index(string $language, Region $region)
    {
        Log::info('showing user list.');

        return view('auth/user_list', ['region' => $region]);
    }

    /**
     * datatables.net listing all users of a region
     *
     * @param  string  $language
     * @param  Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(string $language, Region $region)
    {
        $users = $region->users();

        Log::info('preparing user list');
        $userlist = datatables()::of($users);

        return $userlist
            ->addIndexColumn()
            ->rawColumns(['name', 'action'])
            ->addColumn('action', function ($data) {
                $state = ($data->approved_at == null) ? 'disabled' : '';
                if (Bouncer::can('update-users')) {
                    $btn = '<span data-toggle="tooltip" title="'.__('auth.user.tooltip.block', ['name' => $data->name]).'"><button type="button" id="blockUser" name="blockUser" class="btn btn-outline-primary btn-sm" data-user-id="'.$data->id.'"
                        data-user-name="'.$data->name.'" data-toggle="modal" data-target="#modalBlockUser" '.$state.'><i class="fas fa-ban"></i></button></span>  ';
                } else {
                    $btn = '';
                }
                if (Bouncer::can('create-users')) {
                    $btn .= '<span data-toggle="tooltip" title="'.__('auth.user.tooltip.delete', ['name' => $data->name]).'"><button type="button" id="deleteUser" name="deleteUser" class="btn btn-outline-danger btn-sm" data-user-id="'.$data->id.'"
                       data-user-name="'.$data->name.'" data-toggle="modal" data-target="#modalDeleteUser" ><i class="fa fa-trash"></i></button></span>';
                }

                return $btn;
            })
            ->editColumn('name', function ($userlist) use ($language) {
                if ($userlist->approved_at == null) {
                    return '<i class="fas fa-exclamation-triangle text-warning"></i>  '.$userlist->name;
                } else {
                    if (Bouncer::can('update-users')) {
                        return '<a href="'.route('admin.user.edit', ['language' => $language, 'user' => $userlist->id]).'">'.$userlist->name.'</a>';
                    } else {
                        return $userlist->name;
                    }
                }
            })
            ->addColumn('roles', function ($u) {
                $roles = '';
                foreach ($u->getRoles() as $ur) {
                    $roles .= __('auth.user.role.'.$ur).', ';
                }

                return substr($roles, 0, -2);
            })
            ->addColumn('clubs', function ($u) {
                if ($u->isAn('superadmin', 'regionadmin')) {
                    return __('All clubs in region');
                } else {
                    return $u->clubs()->implode('shortname', ', ');
                }
            })
            ->addColumn('leagues', function ($u) {
                if ($u->isAn('superadmin', 'regionadmin')) {
                    return __('All leagues in region');
                } else {
                    return $u->leagues()->implode('shortname', ', ');
                }
            })
            ->addColumn('regions', function ($u) {
                return $u->regions()->implode('code', ', ');
            })
            ->editColumn('created_at', function ($userlist) use ($language) {
                if ($userlist->created_at) {
                    return [
                        'display' => Carbon::parse($userlist->created_at)->locale($language)->isoFormat('lll'),
                        'ts' => Carbon::parse($userlist->created_at)->timestamp,
                        'filter' => Carbon::parse($userlist->created_at)->locale($language)->isoFormat('lll'),
                    ];
                } else {
                    return [
                        'display' => null,
                        'ts' => 0,
                        'filter' => null,
                    ];
                }
            })
            ->addColumn('lastlogin_at', function ($userlist) use ($language) {
                if ($userlist->lastSuccessfulLoginAt()) {
                    return [
                        'display' => Carbon::parse($userlist->lastSuccessfulLoginAt())->locale($language)->isoFormat('lll'),
                        'ts' => Carbon::parse($userlist->lastSuccessfulLoginAt())->timestamp,
                        'filter' => Carbon::parse($userlist->lastSuccessfulLoginAt())->locale($language)->isoFormat('lll'),
                    ];
                } else {
                    return [
                        'display' => null,
                        'ts' => 0,
                        'filter' => null,
                    ];
                }
            })
            ->editColumn('email_verified_at', function ($userlist) use ($language) {
                if ($userlist->email_verified_at) {
                    return [
                        'display' => Carbon::parse($userlist->email_verified_at)->locale($language)->isoFormat('lll'),
                        'ts' => Carbon::parse($userlist->email_verified_at)->timestamp,
                        'filter' => Carbon::parse($userlist->email_verified_at)->locale($language)->isoFormat('lll'),
                    ];
                } else {
                    return [
                        'display' => null,
                        'ts' => 0,
                        'filter' => null,
                    ];
                }
            })
            ->editColumn('approved_at', function ($userlist) use ($language) {
                if ($userlist->approved_at) {
                    return [
                        'display' => Carbon::parse($userlist->approved_at)->locale($language)->isoFormat('lll'),
                        'ts' => Carbon::parse($userlist->approved_at)->timestamp,
                        'filter' => Carbon::parse($userlist->approved_at)->locale($language)->isoFormat('lll'),
                    ];
                } else {
                    return [
                        'display' => null,
                        'ts' => 0,
                        'filter' => null,
                    ];
                }
            })
            ->editColumn('rejected_at', function ($userlist) use ($language) {
                if ($userlist->rejected_at) {
                    return [
                        'display' => Carbon::parse($userlist->rejected_at)->locale($language)->isoFormat('lll'),
                        'ts' => Carbon::parse($userlist->rejected_at)->timestamp,
                        'filter' => Carbon::parse($userlist->rejected_at)->locale($language)->isoFormat('lll'),
                    ];
                } else {
                    return [
                        'display' => null,
                        'ts' => 0,
                        'filter' => null,
                    ];
                }
            })

            ->make(true);
    }

    /**
     * dispplay view with users waiting for approval for a region
     *
     * @param  string  $language
     * @param  Region  $region
     * @return \Illuminate\View\View
     */
    public function index_new(string $language, Region $region)
    {
        //  DB::enableQueryLog(); // Enable query log

        $users = $region->users()
            ->whereNull('approved_at')
            ->whereNull('rejected_at');
        //  dd(DB::getQueryLog());
        Log::info('showing waiting new user list.');

        return view('auth/users', ['users' => $users, 'region' => $region]);
    }

    /**
     * dispplay view with details of a user
     *
     * @param  string  $language
     * @param  User  $user
     * @return \Illuminate\View\View
     */
    public function show(string $language, User $user)
    {
        if ($user->can('manage', $user)) {
            $member = $user->member;
            $audits = Audit::where('user_id', $user->id)->orderBy('created_at')->get();
            Log::info('show user details', ['user-id' => $user->id]);

            return view('auth/user_profile', ['user' => $user, 'member' => $member, 'audits' => $audits]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * dispplay view with details of a user for modification
     *
     * @param  string  $language
     * @param  User  $user
     * @return \Illuminate\View\View
     */
    public function edit(string $language, User $user)
    {
        if ($user->approved_at == null) {
            $member = $user->member;
            $this->cloneMemberAccessRights($user);

            $abilities['clubs'] = $user->clubs()->implode('shortname', ', ');
            $abilities['leagues'] = $user->leagues()->implode('shortname', ', ');
            $abilities['regions'] = $user->regions()->implode('code', ', ');

            Log::info('show user approval form', ['user-id' => $user->id]);

            return view('auth/user_approve', ['user' => $user, 'member' => $member, 'abilities' => $abilities]);
        } else {
            Log::info('show user edit form', ['user-id' => $user->id]);

            return view('auth/user_edit', ['user' => $user]);
        }
    }

    /**
     * update user in DB
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        if ($user->can('manage', $user)) {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'locale' => ['required', 'string', 'max:2'],
            ]);
            Log::info('user form data validated OK.');

            $old_email = $user->email;
            if ($data['email'] != $user->email) {
                $data['email_verified_at'] = null;
            }

            $user->update($data);
            Log::notice('user updated.', ['user-id' => $user->id]);
            app()->setLocale($data['locale']);

            if ($data['email'] != $old_email) {
                $user->sendEmailVerificationNotification();
                //Auth::logout();
                $request->session()->flush();
                Auth::logout();
                Log::notice('user has modifed his email.', ['user-id' => $user->id, 'old-email' => $old_email, 'new-email' => $data['email']]);

                return redirect()->route('login', app()->getLocale());
            }

            return redirect()->route('home', app()->getLocale());
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * approve user for a region
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $data = $request->validate([
            'reason_reject' => 'exclude_if:approved,"on"|required|string',
            'region_ids' => 'sometimes|required|array',
            'region_ids.*' => 'nullable|exists:regions,id',
            'club_ids' => 'sometimes|required|array',
            'club_ids.*' => 'nullable|exists:clubs,id',
            'league_ids' => 'sometimes|required|array',
            'league_ids.*' => 'nullable|exists:leagues,id',
            'member_id' => 'sometimes|required|exists:members,id',
            'regionadmin' => 'sometimes|required|accepted',
            'clubadmin' => 'sometimes|required|accepted',
            'leagueadmin' => 'sometimes|required|accepted',
            'approved' => 'sometimes|required|accepted',
        ]);
        Log::info('approval form data validated OK.', ['user-id' => $user->id]);

        if (isset($data['approved'])) {
            $user->update(['approved_at' => now()]);
            Log::notice('user approved.', ['user-id' => $user->id]);

            $this->setAccessRights(
                $user,
                session('cur_region'),
                $data['regionadmin'] ?? false,
                $data['clubadmin'] ?? false,
                $data['club_ids'] ?? null,
                $data['leagueadmin'] ?? false,
                $data['league_ids'] ?? null
            );

            $user->notify(new ApproveUser(session('cur_region')));
        } else {
            $user->update(['rejected_at' => now(), 'approved_at' => null, 'reason_reject' => $data['reason_reject']]);
            Log::notice('user rejected.', ['user-id' => $user->id]);

            $user->notify(new RejectUser(Auth::user(), $user, session('cur_region')));
            Log::info('user notified - REJECTUSER.', ['user-id' => $user->id]);
        }

        return redirect()->route('admin.user.index.new', ['language' => app()->getLocale(), 'region' => session('cur_region')])->withMessage('User approved successfully');
    }

    /**
     * modify user access rights
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function allowance(Request $request, User $user)
    {
        $data = $request->validate([
            'region_ids' => 'sometimes|required|array',
            'region_ids.*' => 'nullable|exists:regions,id',
            'club_ids' => 'sometimes|required|array',
            'club_ids.*' => 'nullable|exists:clubs,id',
            'league_ids' => 'sometimes|required|array',
            'league_ids.*' => 'nullable|exists:leagues,id',
            'regionadmin' => 'sometimes|required|accepted',
            'clubadmin' => 'sometimes|required|accepted',
            'leagueadmin' => 'sometimes|required|accepted',
        ]);
        Log::info('allowance form data validated OK.', ['user-id' => $user->id]);

        $this->setAccessRights(
            $user,
            session('cur_region'),
            $data['regionadmin'] ?? false,
            $data['clubadmin'] ?? false,
            $data['club_ids'] ?? null,
            $data['leagueadmin'] ?? false,
            $data['league_ids'] ?? null
        );

        return redirect()->route('admin.user.index', ['language' => app()->getLocale(), 'region' => session('cur_region')]);
    }

    /**
     * delete a user from the DB
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, User $user)
    {
        $this->removeAllAccessRights($user);
        $user->delete();

        Log::notice('user deleted.', ['user-id' => $user->id]);

        return redirect()->route('admin.user.index', ['language' => app()->getLocale(), 'region' => session('cur_region')]);
    }

    /**
     * block user access
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function block(Request $request, User $user)
    {
        $user->update(['approved_at' => null]);
        // RBAC remove old roles and set guest
        $this->blockUser($user);
        Log::notice('user blocked.', ['user-id' => $user->id]);

        return redirect()->route('admin.user.index', ['language' => app()->getLocale(), 'region' => session('cur_region')]);
    }
}
