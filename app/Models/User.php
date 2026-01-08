<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Admin\AdminAction;
use App\Models\Admin\MarketCommunication;
use App\Models\Gamification\Badge;
use App\Models\Gamification\UserBadge;
use App\Models\Market\Meme;
use App\Models\Market\Watchlist;
use App\Models\Utility\Notification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'email',
        'password',
        'avatar',
        'role',
        'cfu_balance',
        'is_suspended',
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
     * Retrieve all portfolio holdings owned by this user.
     *
     * @return HasMany<\App\Models\Financial\Portfolio>
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(\App\Models\Financial\Portfolio::class);
    }

    /**
     * Retrieve all buy and sell transactions executed by this user.
     *
     * @return HasMany<\App\Models\Financial\Transaction>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(\App\Models\Financial\Transaction::class);
    }

    /**
     * Retrieve all memes initially proposed by this user.
     *
     * @return HasMany<Meme>
     */
    public function createdMemes(): HasMany
    {
        return $this->hasMany(Meme::class, 'creator_id');
    }

    /**
     * Retrieve all memes that this admin user has approved for trading.
     *
     * @return HasMany<Meme>
     */
    public function approvedMemes(): HasMany
    {
        return $this->hasMany(Meme::class, 'approved_by');
    }

    /**
     * Retrieve all gamification badges earned by this user.
     *
     * @return BelongsToMany<Badge>
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->using(UserBadge::class)
            ->withPivot('awarded_at')
            ->withTimestamps();
    }

    /**
     * Retrieve all notifications sent to this user.
     *
     * @return HasMany<Notification>
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Retrieve all memes added to this user's watchlist for monitoring.
     *
     * @return HasMany<Watchlist>
     */
    public function watchlist(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * Retrieve all market-wide communications created by this admin user.
     *
     * @return HasMany<MarketCommunication>
     */
    public function marketCommunications(): HasMany
    {
        return $this->hasMany(MarketCommunication::class, 'admin_id');
    }

    /**
     * Retrieve all administrative actions performed by this admin user.
     *
     * @return HasMany<AdminAction>
     */
    public function adminActions(): HasMany
    {
        return $this->hasMany(AdminAction::class, 'admin_id');
    }

    /**
     * Determine if this user has administrator privileges.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Generate the full URL for the user's avatar, falling back to a generated default.
     */
    public function avatarUrl(): string
    {
        if ($this->avatar) {
            return asset('storage/data/'.$this->id.'/'.$this->avatar);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=10b981&color=fff';
    }

    /**
     * Determine if this user has trader (non-admin) privileges.
     */
    public function isTrader(): bool
    {
        return $this->role === 'trader';
    }

    /**
     * Check if this user account is currently suspended and cannot trade.
     */
    public function isSuspended(): bool
    {
        return $this->is_suspended;
    }

    /**
     * Format the user's CFU balance as a human-readable string.
     *
     * @return Attribute<string, never>
     */
    protected function formattedBalance(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->cfu_balance, 2).' CFU'
        );
    }
}
