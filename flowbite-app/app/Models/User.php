<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'role',
        'cfu_balance',
        'is_suspended',
        'profile_picture',
        'status',
        'last_daily_bonus_at',
        'cached_net_worth',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'cfu_balance' => 'decimal:5',
            'cached_net_worth' => 'decimal:5',
            'is_suspended' => 'boolean',
            'last_daily_bonus_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is trader
     */
    public function isTrader(): bool
    {
        return $this->role === 'trader';
    }

    /**
     * Memes created by this user
     */
    public function createdMemes(): HasMany
    {
        return $this->hasMany(Meme::class, 'creator_id');
    }

    /**
     * User's portfolio entries
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    /**
     * User's transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * User's watchlist
     */
    public function watchlist(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * Memes in user's watchlist
     */
    public function watchedMemes(): BelongsToMany
    {
        return $this->belongsToMany(Meme::class, 'watchlists')->withTimestamps();
    }

    /**
     * User's notifications
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * User's badges
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('awarded_at');
    }

    /**
     * Admin actions performed by this user
     */
    public function adminActions(): HasMany
    {
        return $this->hasMany(AdminAction::class, 'admin_id');
    }

    /**
     * Market communications by this admin
     */
    public function marketCommunications(): HasMany
    {
        return $this->hasMany(MarketCommunication::class, 'admin_id');
    }

    /**
     * Calculate user's net worth (liquid + invested)
     */
    public function calculateNetWorth(): float
    {
        $investedValue = $this->portfolios()
            ->join('memes', 'portfolios.meme_id', '=', 'memes.id')
            ->selectRaw('SUM(portfolios.quantity * memes.current_price) as total')
            ->value('total') ?? 0;

        return (float) $this->cfu_balance + (float) $investedValue;
    }
}
