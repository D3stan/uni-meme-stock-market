<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meme_id',
        'quantity',
        'avg_buy_price',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'avg_buy_price' => 'decimal:5',
        ];
    }

    /**
     * Calculate current value of this position
     */
    public function getCurrentValueAttribute(): float
    {
        return $this->quantity * (float) $this->meme->current_price;
    }

    /**
     * Calculate profit/loss for this position
     */
    public function getProfitLossAttribute(): float
    {
        $currentValue = $this->current_value;
        $costBasis = $this->quantity * (float) $this->avg_buy_price;
        return $currentValue - $costBasis;
    }

    /**
     * Calculate profit/loss percentage
     */
    public function getProfitLossPercentAttribute(): float
    {
        $costBasis = $this->quantity * (float) $this->avg_buy_price;
        if ($costBasis == 0) return 0;
        return ($this->profit_loss / $costBasis) * 100;
    }

    /**
     * User who owns this portfolio entry
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Meme in this portfolio entry
     */
    public function meme(): BelongsTo
    {
        return $this->belongsTo(Meme::class);
    }

    /**
     * Update average buy price after a new purchase
     * new_avg = ((old_qty * old_avg) + (new_qty * new_price)) / (old_qty + new_qty)
     */
    public function updateAverageBuyPrice(int $newQuantity, float $newPrice): void
    {
        $oldTotal = $this->quantity * (float) $this->avg_buy_price;
        $newTotal = $newQuantity * $newPrice;
        $totalQuantity = $this->quantity + $newQuantity;

        if ($totalQuantity > 0) {
            $this->avg_buy_price = ($oldTotal + $newTotal) / $totalQuantity;
            $this->quantity = $totalQuantity;
            $this->save();
        }
    }
}
