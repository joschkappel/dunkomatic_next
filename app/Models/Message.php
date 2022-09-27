<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property bool|null $notify_users
 * @property \Illuminate\Support\Carbon|null $send_at
 * @property \Illuminate\Support\Carbon|null $sent_at
 * @property-read Region $region
 * @property-read User $user
 *
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
        'id', 'title', 'body', 'greeting', 'salutation', 'send_at', 'sent_at', 'delete_at', 'user_id', 'region_id',
        'to_members', 'cc_members', 'notify_users',
    ];

    protected $dates = ['send_at', 'sent_at', 'delete_at'];

    protected $casts = [
        'to_members' => 'array',
        'cc_members' => 'array',
        'notify_users' => 'boolean',
    ];

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
        Log::notice('[JOB][DB CLEANUP] pruning messages that have been deleted more than a week ago.');

        return static::where('delete_at', '<', now()->subWeek());
    }
}
