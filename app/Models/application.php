<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class application extends Model
{
    protected $table = 'applications';
    protected $fillable = ['status', 'user_id', 'pet_id', 'description'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(usersdata::class, 'user_id');
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(pets::class, 'pet_id');
    }
}
