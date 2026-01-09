<?php

namespace App\Models\Market;

use App\Models\Financial\DividendHistory;
use App\Models\Financial\Portfolio;
use App\Models\Financial\PriceHistory;
use App\Models\Financial\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    /**
     * Retrieve the user who originally proposed this meme for listing.
     *
     * @return BelongsTo<User, Meme>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Retrieve the admin user who approved this meme for trading.
     *
     * @return BelongsTo<User, Meme>
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Retrieve the category this meme is classified under.
     *
     * @return BelongsTo<Category, Meme>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Retrieve all portfolio holdings of this meme across all users.
     *
     * @return HasMany<Portfolio>
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    /**
     * Retrieve all buy and sell transactions performed on this meme.
     *
     * @return HasMany<Transaction>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Retrieve all historical price records for this meme.
     *
     * @return HasMany<PriceHistory>
     */
    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    /**
     * Retrieve all watchlist entries tracking this meme.
     *
     * @return HasMany<Watchlist>
     */
    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * Retrieve all dividend distributions made by this meme to shareholders.
     *
     * @return HasMany<DividendHistory>
     */
    public function dividendHistories(): HasMany
    {
        return $this->hasMany(DividendHistory::class);
    }

    // Scopes
    public function scopeWith24hStats(Builder $query): Builder
    {
        return $query->addSelect([
            'price_24h_ago' => PriceHistory::select('price')
                ->whereColumn('meme_id', 'memes.id')
                ->where('recorded_at', '>=', now()->subHours(24))
                ->orderBy('recorded_at', 'asc')
                ->limit(1),
            'volume_24h' => Transaction::selectRaw('COALESCE(SUM(total_amount), 0)')
                ->whereColumn('meme_id', 'memes.id')
                ->whereIn('type', ['buy', 'sell'])
                ->where('executed_at', '>=', now()->subHours(24))
        ]);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    /**
     * Filter to only memes that are awaiting admin approval.
     *
     * @param  Builder<Meme>  $query
     * @return Builder<Meme>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Filter to only memes that have been suspended from trading.
     *
     * @param  Builder<Meme>  $query
     * @return Builder<Meme>
     */
    public function scopeSuspended(Builder $query): Builder
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Format the meme's current price as a human-readable CFU string.
     *
     * @return Attribute<string, never>
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->current_price, 2).' CFU'
        );
    }

    /**
     * Calculate the 24-hour price change
     */
    protected function pctChange24h(): Attribute
    {
        return Attribute::make(
            get: function () {
                $price24hAgo = $this->price_24h_ago ?? $this->base_price;
                
                if ($price24hAgo <= 0) {
                    return 0;
                }

                return (($this->current_price - $price24hAgo) / $price24hAgo) * 100;
            }
        );
    }

    /**
     * Calculate the current price using the bonding curve formula.
     */
    public function calculateCurrentPrice(): float
    {
        return $this->base_price + ($this->slope * $this->circulating_supply);
    }
}
