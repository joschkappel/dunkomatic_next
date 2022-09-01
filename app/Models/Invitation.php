<?php

namespace App\Models;

use App\Models\Member;
use App\Models\User;
use App\Models\Region;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Invitation extends Model
{
    use HasFactory, Prunable;

    protected $with = ['region','user','member'];

    protected $fillable = [
        'id', 'user_id', 'member_id', 'region_id', 'email_invitee','provider'
    ];
    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
    /**
     * Get the prunable model query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function prunable()
    {
        Log::notice('[JOB][DB CLEANUP] pruning invitations older than a week.');
        return static::where('created_at', '<', now()->subWeek());
    }
}
