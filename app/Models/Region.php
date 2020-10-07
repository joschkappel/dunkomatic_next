<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
  use HasFactory;

  protected $fillable = [
      'id','code','name', 'hq'
  ];

  public function messages()
  {
      return $this->hasMany('App\Models\MessageDestination','region','code');
  }
}
