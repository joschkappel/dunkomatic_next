<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
  use HasFactory;

  protected $fillable = [
      'id','code','name', 'hq'
  ];
}
