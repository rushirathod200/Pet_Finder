<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LostPetRequest extends Model
{
    protected $fillable = [
        'user_id',
        'pet_name',
        'city',
        'last_seen_area',
        'description',
        'contact_phone',
        'photos',
        'status',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(usersdata::class, 'user_id');
    }

    /**
     * @return array<int, string>
     */
    public function getPhotoUrlsAttribute(): array
    {
        $photos = is_array($this->photos) ? $this->photos : [];
        $urls = [];

        foreach ($photos as $photo) {
            $normalized = self::normalizeMediaPath((string) $photo);
            if ($normalized !== null) {
                $urls[] = $normalized;
            }
        }

        return $urls;
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
