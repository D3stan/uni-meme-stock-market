<?php

namespace App\Models\Gamification;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserBadge extends Pivot
{
    // The table associated with the model
    protected $table = 'user_badges';

    // Enable timestamps for the pivot
    public $timestamps = true;

    protected function casts(): array
    {
        return [
            'awarded_at' => 'datetime',
        ];
    }

    /**
     * Format the badge awarded date as a readable string.
     *
     * @return Attribute<string|null, never>
     */
    protected function awardedAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->awarded_at ? $this->awarded_at->format('M d, Y') : null
        );
    }
}
