<?php

namespace App\Models\Gamification;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    // Accessor for formatted awarded date
    protected function awardedAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->awarded_at ? $this->awarded_at->format('M d, Y') : null
        );
    }
}
