<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    // Scopes
    public function scopeValid(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }

    public function scopeForEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
    }

    // Helper method to check if OTP is expired
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    // Helper method to verify code
    public function verifyCode(string $code): bool
    {
        return !$this->isExpired() && hash_equals($this->code_hash, hash('sha256', $code));
    }
}
