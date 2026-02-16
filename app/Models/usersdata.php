<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class usersdata extends Model
{
    protected $table = 'usersdatas';

    protected $fillable = [
        'fullname',
        'email',
        'phone',
        'location',
        'password',
        'profile_picture',
    ];

    public function pets(): HasMany
    {
        return $this->hasMany(pets::class, 'user_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(application::class, 'user_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function getProfilePictureDisplayUrlAttribute(): ?string
    {
        return self::normalizeMediaPath($this->profile_picture);
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
