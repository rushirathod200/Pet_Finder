<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class pets extends Model
{
    protected $table = 'pets';
    protected $fillable = [
        'name',
        'breed',
        'age',
        'gender',
        'size',
        'description',
        'image_url',
        'city',
        'adopted',
        'user_id',
    ];

    protected $casts = [
        'adopted' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(usersdata::class, 'user_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(application::class, 'pet_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'pet_id');
    }

    public function getImageDisplayUrlAttribute(): ?string
    {
        return self::normalizeMediaPath($this->image_url);
    }

    private static function normalizeMediaPath(?string $path): ?string
    {
        $value = trim((string) $path);
        if ($value === '') {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        if (str_starts_with($value, '/storage/')) {
            return $value;
        }

        if (str_starts_with($value, 'storage/')) {
            return '/'.$value;
        }

        return '/storage/'.ltrim($value, '/');
    }
}
