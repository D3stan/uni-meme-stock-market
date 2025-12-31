<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'code_hash',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Create a new OTP for email
     */
    public static function createForEmail(string $email, int $expiresInMinutes = 10): array
    {
        // Generate 6-digit code
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete any existing OTPs for this email
        static::where('email', $email)->delete();

        // Create new OTP
        static::create([
            'email' => $email,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes($expiresInMinutes),
        ]);

        return ['code' => $code, 'expires_at' => now()->addMinutes($expiresInMinutes)];
    }

    /**
     * Verify OTP code
     */
    public static function verify(string $email, string $code): bool
    {
        $otp = static::where('email', $email)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) return false;

        if (Hash::check($code, $otp->code_hash)) {
            // Delete OTP after successful verification
            $otp->delete();
            return true;
        }

        return false;
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
