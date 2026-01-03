<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    // Relationships
    public function portfolios(): HasMany
    {
        return $this->hasMany(\App\Models\Financial\Portfolio::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(\App\Models\Financial\Transaction::class);
    }

    public function createdMemes(): HasMany
    {
        return $this->hasMany(\App\Models\Market\Meme::class, 'creator_id');
    }

    public function approvedMemes(): HasMany
    {
        return $this->hasMany(\App\Models\Market\Meme::class, 'approved_by');
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Gamification\Badge::class, 'user_badges')
            ->using(\App\Models\Gamification\UserBadge::class)
            ->withPivot('awarded_at')
            ->withTimestamps();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(\App\Models\Utility\Notification::class);
    }

    public function watchlist(): HasMany
    {
        return $this->hasMany(\App\Models\Market\Watchlist::class);
    }

    public function marketCommunications(): HasMany
    {
        return $this->hasMany(\App\Models\Admin\MarketCommunication::class, 'admin_id');
    }

    public function adminActions(): HasMany
    {
        return $this->hasMany(\App\Models\Admin\AdminAction::class, 'admin_id');
    }

    // Helper Methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function avatarUrl(): string
    {
        if ($this->avatar) {
            return asset('storage/data/' . $this->id . '/' . $this->avatar);
        }

        // Generate default avatar using ui-avatars.com API
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=10b981&color=fff';
    }

    public function isTrader(): bool
    {
        return $this->role === 'trader';
    }

    public function isSuspended(): bool
    {
        return $this->is_suspended;
    }

    // Accessors
    protected function formattedBalance(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->cfu_balance, 2) . ' CFU'
        );
    }
}
