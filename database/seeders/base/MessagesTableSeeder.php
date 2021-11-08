<?php
namespace Database\Seeders\base;

use App\Enums\MessageType;
use App\Enums\Role;
use App\Models\Region;
use App\Models\User;
use App\Models\Message;
use App\Models\MessageDestination;


use Illuminate\Database\Seeder;

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

            $msg->user()->associate($user);
            $msg->region()->associate($r);

            $msg->save();

            $msgd = new MessageDestination();
            $msgd->type = MessageType::to();
            $msgd->role_id = Role::User();

            $msg->message_destinations()->save($msgd);


        }
    }
}
