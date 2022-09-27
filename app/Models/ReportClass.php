<?php

namespace App\Models;

use App\Enums\Report;
use Illuminate\Database\Eloquent\Model;

class ReportClass extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'report_id' => Report::class,
    ];
}
