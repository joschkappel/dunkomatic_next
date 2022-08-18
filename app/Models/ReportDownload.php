<?php

namespace App\Models;

use App\Enums\Report;
use App\Models\Region;
use App\Models\Club;
use App\Models\League;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportDownload extends Model
{

    protected $fillable = [
        'id', 'user_id', 'region_id', 'club_id', 'league_id', 'report_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'report_id' => Report::class
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
