<?php
namespace Database\Seeders\base;


use Silber\Bouncer\Database\Role;
use App\Models\Region;
use App\Models\User;
use App\Models\Message;



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
            $msg->delete_at = now()->addWeek();
            $msg->notify_users = true;

            $msg->user()->associate($user);
            $msg->region()->associate($r);

            $msg->save();
        }
    }
}
