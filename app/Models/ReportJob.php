<?php

namespace App\Models;

use App\Enums\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportJob extends Model
{
    protected $fillable = [
        'id', 'region_id', 'report_id',
        'lastrun_at', 'lastrun_ok', 'running',
        'version',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'report_id' => Report::class,
        'lastrun_at' => 'datetime',
        'running' => 'boolean',
        'lastrun_ok' => 'boolean',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
