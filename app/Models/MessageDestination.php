<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageDestination extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','scope','type', 'region', 'message_id'
    ];

    public function region()
    {
        return $this->belongsTo('App\Models\Region','region','code');
    }

    public function message()
    {
        return $this->belongsTo('App\Models\Message','message_id','id');
    }

    public function club()
    {
        return $this->belongsTo('App\Models\Club','club_id','id');
    }
    public function league()
    {
        return $this->belongsTo('App\Models\League','league_id','id');
    }
}
