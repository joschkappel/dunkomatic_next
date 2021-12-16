<?php

namespace App\Models;

use App\Models\User;
use App\Models\Region;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

use Illuminate\Support\Facades\Log;

class Message extends Model
{
    use HasFactory, Prunable;

    protected $fillable = [
        'id','title','body', 'greeting', 'salutation', 'send_at', 'sent_at', 'user_id', 'region_id',
        'to_members', 'cc_members', 'to_users'
    ];

    protected $dates = ['send_at', 'sent_at'];

    protected $casts = [
        'to_members' => 'array',
        'cc_members' => 'array',
        'to_users' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function region()
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
        Log::notice('[JOB][DB CLEANUP] pruning messages.');
        return static::where('sent_at', '<', now()->subWeek());
    }

}
