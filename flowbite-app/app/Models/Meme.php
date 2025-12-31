<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Meme extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'creator_id',
        'category_id',
        'title',
        'ticker',
        'image_path',
        'base_price',
        'slope',
        'current_price',
        'circulating_supply',
        'status',
        'approved_at',
        'approved_by',
        'trading_starts_at',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:5',
            'slope' => 'decimal:5',
            'current_price' => 'decimal:5',
            'circulating_supply' => 'integer',
            'approved_at' => 'datetime',
            'trading_starts_at' => 'datetime',
        ];
    }

    /**
     * Get the full image path for storage
     */
    public function getImageUrlAttribute(): string
    {
        return Storage::disk('memes')->url($this->image_path);
    }

    /**
     * Get the full storage path
     */
    public function getFullImagePathAttribute(): string
    {
        return storage_path('memes/' . $this->image_path);
    }

    /**
     * Calculate current price based on bonding curve
     * P = P_base + (M * S)
     */
    public function calculatePrice(): float
    {
        return (float) $this->base_price + ((float) $this->slope * $this->circulating_supply);
    }

    /**
     * Calculate cost to buy k shares using integral formula
     * Cost = P_base * k + (M/2) * ((S+k)^2 - S^2)
     */
    public function calculateBuyCost(int $quantity): float
    {
        $s = $this->circulating_supply;
        $k = $quantity;
        $pBase = (float) $this->base_price;
        $m = (float) $this->slope;

        return ($pBase * $k) + ($m / 2) * (pow($s + $k, 2) - pow($s, 2));
    }

    /**
     * Calculate revenue from selling k shares using integral formula
     * Revenue = P_base * k + (M/2) * (S^2 - (S-k)^2)
     */
    public function calculateSellRevenue(int $quantity): float
    {
        $s = $this->circulating_supply;
        $k = $quantity;
        $pBase = (float) $this->base_price;
        $m = (float) $this->slope;

        if ($k > $s) {
            throw new \InvalidArgumentException('Cannot sell more shares than in circulation');
        }

        return ($pBase * $k) + ($m / 2) * (pow($s, 2) - pow($s - $k, 2));
    }

    /**
     * Check if trading is allowed
     */
    public function isTradingAllowed(): bool
    {
        return $this->status === 'approved' 
            && $this->trading_starts_at 
            && $this->trading_starts_at->isPast();
    }

    /**
     * Creator of this meme
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Category of this meme
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Admin who approved this meme
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Price history records
     */
    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    /**
     * Portfolio entries for this meme
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    /**
     * Transactions for this meme
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Users watching this meme
     */
    public function watchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'watchlists')->withTimestamps();
    }

    /**
     * Dividend histories for this meme
     */
    public function dividendHistories(): HasMany
    {
        return $this->hasMany(DividendHistory::class);
    }

    /**
     * Scope for approved memes
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for tradeable memes
     */
    public function scopeTradeable($query)
    {
        return $query->where('status', 'approved')
            ->whereNotNull('trading_starts_at')
            ->where('trading_starts_at', '<=', now());
    }

    /**
     * Scope for pending memes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
