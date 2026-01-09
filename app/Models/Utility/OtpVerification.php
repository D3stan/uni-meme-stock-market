<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    use HasFactory;

    // Only created_at, no updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'email',
        'code_hash',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Filter to only OTP codes that have not expired yet.
     *
     * @param  Builder<OtpVerification>  $query
     * @return Builder<OtpVerification>
     */
    public function scopeValid(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Filter to only OTP codes for a specific email address.
     *
     * @param  Builder<OtpVerification>  $query
     * @return Builder<OtpVerification>
     */
    public function scopeForEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
    }

    /**
     * Determine if this OTP code has passed its expiration timestamp.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Verify if the provided code matches the stored hash and is not expired.
     */
    public function verifyCode(string $code): bool
    {
        return ! $this->isExpired() && hash_equals($this->code_hash, hash('sha256', $code));
    }
}
