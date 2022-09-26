<?php

namespace App\Models;

use App\Enums\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportDownload extends Model
{
    protected $fillable = [
        'id', 'user_id', 'report_id', 'model_id', 'model_class',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'report_id' => Report::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByUserAndType($query, User $user, $type, $type_id)
    {
        return $query->where('user_id', $user->id)->where('model_class', $type)->where('model_id', $type_id);
    }
}
