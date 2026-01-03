<?php

namespace App\Models\Financial;

use App\Models\User;
use App\Models\Market\Meme;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function meme(): BelongsTo
    {
        return $this->belongsTo(Meme::class);
    }

    // Accessors
    protected function netWorthFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                $currentValue = $this->quantity * $this->meme->current_price;
                return number_format($currentValue, 2) . ' CFU';
            }
        );
    }
}
