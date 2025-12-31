<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    use HasFactory;

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
            'pct_change_24h' => 'decimal:4',
        ];
    }

    /**
     * The meme this price record belongs to
     */
    public function meme(): BelongsTo
    {
        return $this->belongsTo(Meme::class);
    }
}
