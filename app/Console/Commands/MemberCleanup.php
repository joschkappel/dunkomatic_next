<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MemberCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmatic:membercleanup {--count=2 : Number of duplicate items }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup and consolidate duplicate members';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dup_cnt = $this->option('count');

        $key_for_duplicates = $this->choice(
            'Which key shall I use to detect duplicates?',
            ['email1', 'email1 lastname', 'firstname lastname'],
            0
        );
        if ($key_for_duplicates == 'email1') {
            $duplicates = Member::whereIn('email1', function ($query) use ($dup_cnt) {
                $query->selectRaw('email1')->from('members')->groupBy('email1')->havingRaw('count(email1) = ?', [$dup_cnt]);
            })->orderBy('email1')->get()->chunk($dup_cnt);
        } elseif ($key_for_duplicates == 'email1 lastname') {
            $duplicates = Member::whereIn(DB::raw('concat(email1, lastname)'), function ($query) use ($dup_cnt) {
                $query->selectRaw('concat(email1, lastname) as name from members group by name having count(concat(email1, lastname)) = '.$dup_cnt);
            })->orderBy('lastname')->get()->chunk($dup_cnt);
        } elseif ($key_for_duplicates == 'firstname lastname') {
            $duplicates = Member::whereIn(DB::raw('concat(firstname, lastname)'), function ($query) use ($dup_cnt) {
                $query->selectRaw('concat(firstname, lastname) as name from members group by name having count(concat(firstname, lastname)) = '.$dup_cnt);
            })->orderBy('lastname')->get()->chunk($dup_cnt);
        } else {
            $this->error('Cannot search for key '.$key_for_duplicates);
        }
        if ($duplicates->count() > 0) {
            $this->info('Found '.$duplicates->count().' members with '.$dup_cnt.' '.$key_for_duplicates);
            $this->info('We will loop through these one by one for you to decide on the approach for merging.');

            foreach ($duplicates as $dup) {
                $mlist = $dup->map(function ($i) {
                    return $i->id.': '.$i->name;
                })->values()->toArray();
                $mlist[] = 'all';
                $mtable = $dup->map->only('name', 'address', 'email1', 'phone', 'is_user', 'member_of_clubs', 'member_of_teams', 'member_of_leagues')->toArray();
                $this->table(
                    ['Name', 'Address', 'Email', 'Phone', 'has account', 'club mships', 'team mships', 'league mships'],
                    $mtable
                );

                $member_to_keep = $this->choice(
                    'Which member do you want to keep?',
                    $mlist,
                    0
                );
                // $this->newline();
                if ($member_to_keep == 'all') {
                    continue;
                }

                $m_id = Str::of($member_to_keep)->explode(': ')->first();
                $m_to_keep = $dup->pull($dup->where('id', $m_id)->keys()->first());
                $m_to_merge = $dup->last();
                Log::info('keeping member', ['member' => $m_to_keep]);
                Log::info('merging member', ['member' => $m_to_merge]);
                $m_to_keep = $this->merge_properties($m_to_keep, $m_to_merge);
                $m_final = $this->merge_members($m_to_keep, $m_to_merge);
            }
        } else {
            $this->info('No duplicates found for '.$key_for_duplicates);

            return 0;
        }

        return 0;
    }

    protected function merge_properties(Member $keep, Member $merge): Member
    {
        $keep_prev = collect($keep)->flatten();

        foreach ($merge->getAttributes() as $key => $value) {
            if (! collect(['id', 'created_at', 'updated_at', 'member_of_clubs', 'member_of_leagues', 'member_of_teams', 'member_of_regions',
                'role_in_clubs', 'role_in_leagues', 'role_in_teams', 'role_in_regions', ])->contains($key)) {
                if (($value != null) and ($value != $keep[$key])) {
                    if ($keep[$key] == null) {
                        $keep[$key] = $value;
                    } else {
                        if ($this->confirm('Copy '.$key.' '.$value.' -> '.($keep[$key] ?? '?').'?', false)) {
                            $keep[$key] = $value;
                        }
                    }
                }
            }
        }

        // check lastname
        if ((Str::of($keep->lastname)->explode(' ')->count() > 0) and ($keep->firstname == null)) {
            if ($this->confirm('Split lastname '.$keep->lastname.'?', false)) {
                $name = Str::of($keep->lastname)->explode(' ');
                $keep->firstname = $name->first();
                $keep->lastname = $name->last();
            }
        }
        Log::info('member to keep new properties', [collect($keep)->flatten()->diffAssoc($keep_prev)]);

        return $keep;
    }

    protected function merge_members(Member $keep, Member $merge): Member
    {
        // copy non emtpy properties
        Log::debug($keep);
        $keep->update();

        // merge memberships
        if ($merge->memberships()->exists()) {
            Log::info('need to merge memberships', ['count' => $merge->memberships->pluck('id')]);
            foreach ($merge->memberships as $ms) {
                $ms->member()->associate($keep);
                $ms->save();
            }
        } else {
            Log::info('NO need to merge memberships');
        }

        // mnove user account
        if (($merge->is_user) and (! $keep->is_user)) {
            Log::info('need to move user account');
            $user = $merge->user;
            $user->member()->associate($keep);
            $user->save();
        } elseif (($merge->is_user) and ($keep->is_user)) {
            Log::info('need to delete second user account');
            $user = $merge->user;
            $user->member()->dissociate();
            $user->delete();
            $merge->refresh();
        } else {
            Log::info('NO need to move user account');
        }

        // remove member
        if ((! $merge->is_user) and (! $merge->memberships()->exists())) {
            $merge->delete();
            Log::notice('Duplicate member removed', ['member' => $merge]);
        }

        return $keep->refresh();
    }
}
