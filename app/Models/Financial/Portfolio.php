<?php

namespace App\Models\Financial;

use App\Models\Market\Meme;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
     * Retrieve the user who owns this portfolio holding.
     *
     * @return BelongsTo<User, Portfolio>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrieve the meme that this portfolio holding represents.
     *
     * @return BelongsTo<Meme, Portfolio>
     */
    public function meme(): BelongsTo
    {
        return $this->belongsTo(Meme::class);
    }

    /**
     * Calculate and format the current market value of this holding.
     *
     * @return Attribute<string, never>
     */
    protected function netWorthFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                $currentValue = $this->quantity * $this->meme->current_price;

                return number_format($currentValue, 2).' CFU';
            }
        );
    }
}
