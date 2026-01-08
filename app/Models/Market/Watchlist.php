<?php

namespace App\Models\Market;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Watchlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meme_id',
    ];

    /**
     * Retrieve the user who added this meme to their watchlist.
     *
     * @return BelongsTo<User, Watchlist>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrieve the meme being monitored in this watchlist entry.
     *
     * @return BelongsTo<Meme, Watchlist>
     */
    public function meme(): BelongsTo
    {
        return $this->belongsTo(Meme::class);
    }
}
