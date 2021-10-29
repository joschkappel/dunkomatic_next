<?php

namespace App\Models;

use App\Enums\MessageType;
use App\Enums\Role;
use App\Models\Message;

use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageDestination extends Model
{
    use HasFactory, CastsEnums;

    protected $fillable = [
        'id','role_id','type', 'message_id'
    ];

    protected $casts = [
        'role_id' => Role::class,
        'type' => MessageType::class
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

}
