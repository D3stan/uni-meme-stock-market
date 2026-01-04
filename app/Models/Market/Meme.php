<?php

namespace App\Models\Market;

use App\Models\User;
use App\Models\Financial\Portfolio;
use App\Models\Financial\Transaction;
use App\Models\Financial\PriceHistory;
use App\Models\Financial\DividendHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Meme extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'creator_id',
        'category_id',
        'title',
        'ticker',
        'image_path',
        'text_alt',
        'base_price',
        'slope',
        'current_price',
        'circulating_supply',
        'status',
        'approved_at',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:5',
            'slope' => 'decimal:5',
            'current_price' => 'decimal:5',
            'circulating_supply' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    public function dividendHistories(): HasMany
    {
        return $this->hasMany(DividendHistory::class);
    }

    // Scopes
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuspended(Builder $query): Builder
    {
        return $query->where('status', 'suspended');
    }

    // Accessors
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->current_price, 2) . ' CFU'
        );
    }

    protected function priceChangePercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Get the latest price history entry from 24h ago
                $priceHistory24h = $this->priceHistories()
                    ->where('recorded_at', '<=', now()->subDay())
                    ->orderBy('recorded_at', 'desc')
                    ->first();

                if (!$priceHistory24h) {
                    return [
                        'value' => '0.00',
                        'formatted' => '0.00%',
                        'color_class' => 'text-gray-500'
                    ];
                }

                $oldPrice = $priceHistory24h->price;
                $currentPrice = $this->current_price;
                
                if ($oldPrice == 0) {
                    return [
                        'value' => '0.00',
                        'formatted' => '0.00%',
                        'color_class' => 'text-gray-500'
                    ];
                }

                $percentageChange = (($currentPrice - $oldPrice) / $oldPrice) * 100;
                $sign = $percentageChange > 0 ? '+' : '';
                $colorClass = $percentageChange > 0 ? 'text-green-500' : ($percentageChange < 0 ? 'text-red-500' : 'text-gray-500');

                return [
                    'value' => number_format($percentageChange, 2),
                    'formatted' => $sign . number_format($percentageChange, 2) . '%',
                    'color_class' => $colorClass
                ];
            }
        );
    }

    // Getter for calculated current price based on bonding curve
    public function calculateCurrentPrice(): float
    {
        return $this->base_price + ($this->slope * $this->circulating_supply);
    }
}
