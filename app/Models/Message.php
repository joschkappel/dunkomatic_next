<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','title','body', 'greeting', 'salutation', 'send_at', 'sent_at', 'author'
    ];

    protected $dates = ['send_at', 'sent_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User','author','id');
    }

    public function destinations()
    {
        return $this->hasMany('App\Models\MessageDestination','message_id','id');
    }


}
