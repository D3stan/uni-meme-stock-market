<?php

namespace App\Models\Financial;

use App\Models\Market\Meme;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DividendHistory extends Model
{
    use HasFactory;

    // Disable default timestamps since we use distributed_at
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

    // Relationships
    public function meme(): BelongsTo
    {
        return $this->belongsTo(Meme::class);
    }
}
