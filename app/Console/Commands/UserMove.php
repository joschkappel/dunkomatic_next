<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Region;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UserMove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmatic:usermove  {user : The ID of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move a user to another region';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user');

        $user = User::find($userId);
        if (!$user){
            $this->error('There is no user with ID '.$userId.' !');
            return Command::FAILURE;
        } else {
            $this->info('Targeting user '.$user->name);
        }

        // get regions
        $user_region = $user->regions();
        if ($user_region->count() > 1){
            $this->error('Cannot move users that are assigned to more than one region!');
            return Command::INVALID;
        } else {
            $user_region = $user_region->first();

            if ($user_region->is_base_level){
                // get top and all siblings
                $new_regions = collect();
                $new_regions->push($user_region->parentRegion);
                $new_regions = $new_regions->concat(
                    Region::where('hq', $user_region->parentRegion->code )
                            ->where('id','!=', $user_region->id)
                            ->get()
                );
            } else {
                // get all children
                $new_regions = $user_region->childRegions();
            }

            $target_region = $this->choice(
                'Please select the target region',
                $new_regions->pluck('code')->toArray(),
                0
            );
            $target_region = Region::where('code',$target_region)->first();

            // now move user:
            Bouncer::disallow($user)->to(['access'], $user_region);
            Bouncer::allow($user)->to(['access'], $target_region);
            Bouncer::refreshFor($user);
            $this->info('Moved user '.$user->name.' from region '.$user_region->code.' to region '.$target_region->code);

        }

        return Command::SUCCESS;
    }
}
