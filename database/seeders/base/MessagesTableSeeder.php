<?php
namespace Database\Seeders\base;

use App\Enums\MessageType;
use Silber\Bouncer\Database\Role;
use App\Models\Region;
use App\Models\User;
use App\Models\Message;
use App\Models\MessageDestination;


use Illuminate\Database\Seeder;
use Silber\Bouncer\Database\Role as DatabaseRole;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name','admin')->first();
        foreach (Region::all() as $r){
            $msg = new Message();
            $msg->title = 'Welcome Message '.$r->name;
            $msg->greeting = 'Welcome';
            $msg->body = 'Welcome to a new release of Dunkomatic !';
            $msg->salutation = 'Have Fun !';
            $msg->send_at = now();
            $msg->to_users = [ strval(Role::where('name','guest')->first()->id) ];

            $msg->user()->associate($user);
            $msg->region()->associate($r);

            $msg->save();
        }
    }
}
