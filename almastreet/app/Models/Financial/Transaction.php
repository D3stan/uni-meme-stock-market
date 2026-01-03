<?php

namespace App\Models\Financial;

use App\Models\User;
use App\Models\Market\Meme;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Transaction extends Model
{
    use HasFactory;

    // Disable default timestamps since we use executed_at
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
            'price_per_share' => 'decimal:4',
            'fee_amount' => 'decimal:5',
            'total_amount' => 'decimal:5',
            'cfu_balance_after' => 'decimal:5',
            'executed_at' => 'datetime',
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
    protected function humanExecutedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->executed_at->diffForHumans()
        );
    }

    protected function netWorthFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->total_amount, 2) . ' CFU'
        );
    }
}
