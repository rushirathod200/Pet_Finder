<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'pet_id',
        'body',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(usersdata::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(usersdata::class, 'receiver_id');
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(pets::class, 'pet_id');
    }
}
