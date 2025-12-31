<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DividendHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'meme_id',
        'amount_per_share',
        'total_distributed',
        'distributed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_per_share' => 'decimal:5',
            'total_distributed' => 'decimal:5',
            'distributed_at' => 'datetime',
        ];
    }

    /**
     * Meme that distributed this dividend
     */
    public function meme(): BelongsTo
    {
        return $this->belongsTo(Meme::class);
    }
}
