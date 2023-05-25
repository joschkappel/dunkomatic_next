<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'message_id', 'filename', 'location'
    ];

    public function messsage(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}
