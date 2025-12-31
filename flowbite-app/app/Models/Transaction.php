<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'meme_id',
        'type',
        'quantity',
        'price_per_share',
        'fee_amount',
        'total_amount',
        'cfu_balance_after',
        'executed_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price_per_share' => 'decimal:5',
            'fee_amount' => 'decimal:5',
            'total_amount' => 'decimal:5',
            'cfu_balance_after' => 'decimal:5',
            'executed_at' => 'datetime',
        ];
    }

    /**
     * User who made this transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Meme involved in this transaction (nullable for bonus/listing_fee)
     */
    public function meme(): BelongsTo
    {
        return $this->belongsTo(Meme::class);
    }

    /**
     * Scope for buy transactions
     */
    public function scopeBuys($query)
    {
        return $query->where('type', 'buy');
    }

    /**
     * Scope for sell transactions
     */
    public function scopeSells($query)
    {
        return $query->where('type', 'sell');
    }

    /**
     * Scope for dividend transactions
     */
    public function scopeDividends($query)
    {
        return $query->where('type', 'dividend');
    }

    /**
     * Check if transaction is a trade (buy or sell)
     */
    public function isTrade(): bool
    {
        return in_array($this->type, ['buy', 'sell']);
    }
}
