<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'date',
        'time',
        'location',
        'capacity',
        'organizer',
        'is_starpoints',
        'participation_link',
        'image_path',
        'user_id',
    ];

    protected $casts = [
        'is_starpoints' => 'boolean',
        'date' => 'date',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function isSavedBy(?User $user): bool
    {
        if (!$user)
            return false;
        return $this->registrations()
            ->where('user_id', $user->id)
            ->where('type', 'saved')
            ->exists();
    }
}
