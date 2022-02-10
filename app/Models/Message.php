<?php

namespace App\Models;

use App\Models\User;
use App\Models\Region;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

use Illuminate\Support\Facades\Log;

/**
 * App\Models\Message
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property int $region_id
 * @property string $title
 * @property string $greeting
 * @property string $body
 * @property string $salutation
 * @property array|null $to_members
 * @property array|null $cc_members
 * @property array|null $to_users
 * @property \Illuminate\Support\Carbon|null $send_at
 * @property \Illuminate\Support\Carbon|null $sent_at
 * @property-read Region $region
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCcMembers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereGreeting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSalutation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSendAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereToMembers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereToUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUserId($value)
 * @mixin \Eloquent
 */
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
