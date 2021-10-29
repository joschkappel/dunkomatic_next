<?php

namespace App\Models;

use App\Models\User;
use App\Models\Region;
use App\Models\MessageDestination;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','title','body', 'greeting', 'salutation', 'send_at', 'sent_at', 'user_id', 'region_id'
    ];

    protected $dates = ['send_at', 'sent_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function message_destinations()
    {
        return $this->hasMany(MessageDestination::class);
    }


}
