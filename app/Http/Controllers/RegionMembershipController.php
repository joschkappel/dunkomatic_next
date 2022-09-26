<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Member;
use App\Models\Region;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegionMembershipController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\View\View
     */
    public function create($language, Region $region)
    {
        Log::info('create new region member', ['region-id' => $region->id]);

        return view('member/member_new', ['entity' => $region,  'entity_type' => Region::class]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request, Region $region, Member $member)
    {
        $data = $request->validate([
            'selRole' => ['required', new EnumValue(Role::class, false)],
            'function' => 'nullable|max:40',
            'email' => 'nullable|max:60|email:rfc,dns',
        ]);
        Log::info('region membership form data validated OK.', ['region-id' => $region->id, 'member-id' => $member->id]);

        // create a new membership
        $ms = $region->memberships()->create([
            'role_id' => $data['selRole'],
            'member_id' => $member->id,
            'function' => $data['function'],
            'email' => $data['email'],
        ]);
        Log::notice('new region membership created.', ['region-id' => $region->id, 'member-id' => $member->id]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Region  $region
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Region $region, Member $member)
    {
        $mships = $region->memberships()->where('member_id', $member->id)->get();
        foreach ($mships as $ms) {
            $ms->delete();
            Log::notice('region membership deleted.', ['region-id' => $region->id, 'member-id' => $member->id]);
        }

        return redirect()->back();
    }
}
