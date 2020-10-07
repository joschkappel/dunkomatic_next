<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','title','body', 'valid_from', 'valid_to', 'author'
    ];

    protected $dates = ['valid_from', 'valid_to'];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function destinations()
    {
        return $this->hasMany('App\Models\MessageDestination','message_id','id');
    }

}
