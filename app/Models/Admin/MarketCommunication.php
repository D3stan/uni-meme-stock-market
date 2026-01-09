<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketCommunication extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'message',
        'is_active',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Retrieve the administrator who created this market communication.
     *
     * @return BelongsTo<User, MarketCommunication>
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Filter to only communications that are currently active and not expired.
     *
     * @param  Builder<MarketCommunication>  $query
     * @return Builder<MarketCommunication>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Filter to only communications that have passed their expiration date.
     *
     * @param  Builder<MarketCommunication>  $query
     * @return Builder<MarketCommunication>
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<=', now());
    }
}
