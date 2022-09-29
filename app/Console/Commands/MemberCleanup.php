<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MemberCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmatic:membercleanup';

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
        $key_for_duplicates = $this->choice(
            'Which key shall I use to detect duplicates?',
            ['email1', 'lastname', 'firstname lastname'],
            0
        );
        if ($key_for_duplicates == 'email1') {
            $duplicates = Member::whereIn('email1', function ($query) {
                $query->selectRaw('email1')->from('members')->groupBy('email1')->havingRaw('count(email1) = ?', [2]);
            })->orderBy('email1')->get()->chunk(2);
        } elseif ($key_for_duplicates == 'lastname') {
            $duplicates = Member::whereIn('lastname', function ($query) {
                $query->selectRaw('lastname')->from('members')->groupBy('lastname')->havingRaw('count(lastname) = ?', [2]);
            })->orderBy('lastname')->get()->chunk(2);
        } elseif ($key_for_duplicates == 'firstname lastname') {
            $duplicates = Member::whereIn('id', function ($query) {
                $query->selectRaw('concat(firstname, lastname) as name')->from('members')->groupBy('name')->havingRaw('count(name) = ?', [2]);
            })->orderBy('lastname')->get()->chunk(2);
        } else {
            $this->error('Can search for key '.$key_for_duplicates);
        }
        if ($duplicates->count() > 0) {
            $this->info('Found '.$duplicates->count().' members with duplicate ');
            $this->info('We will loop through these one by one for you to decide on the approach for merging.');
            $this->newLine(2);

            foreach ($duplicates as $dup) {
                $this->newLine(2);
                $this->table(
                    ['Name', 'Address', 'Email', 'Phone', 'has account', 'club mships', 'team mships', 'league mships'],
                    $dup->map->only('name', 'address', 'email1', 'phone', 'is_user', 'member_of_clubs', 'member_of_teams', 'member_of_leagues')->toArray()
                );
                $member_to_keep = $this->choice(
                    'Which member do you want to keep?',
                    [$dup->first()->id.': '.$dup->first()->name ?? 'not set', $dup->last()->id.': '.$dup->last()->name ?? 'not set', 'all'],
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
                    if ($this->confirm('Copy '.$key.' '.$value.' -> '.($keep[$key] ?? '?').'?', false)) {
                        $keep[$key] = $value;
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
