<?php

namespace App\Models\Financial;

use App\Models\Market\Meme;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    use HasFactory;

    // Disable default timestamps since we use recorded_at
    public $timestamps = false;

    protected $fillable = [
        'meme_id',
        'price',
        'circulating_supply_snapshot',
        'trigger_type',
        'recorded_at',
        'volume_24h',
        'pct_change_24h',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:5',
            'circulating_supply_snapshot' => 'integer',
            'recorded_at' => 'datetime',
            'volume_24h' => 'decimal:5',
            'pct_change_24h' => 'decimal:2',
        ];
    }

    /**
     * Retrieve the meme that this historical price data belongs to.
     *
     * @return BelongsTo<Meme, PriceHistory>
     */
    public function meme(): BelongsTo
    {
        return $this->belongsTo(Meme::class);
    }
}
