<?php

namespace App\Services;

use App\Models\Utility\OtpVerification;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * OTP expiration time in minutes
     */
    private const OTP_EXPIRY_MINUTES = 10;

    /**
     * Development OTP code that's always accepted
     */
    private const DEV_OTP_CODE = '123456';

    /**
     * Whether to actually send emails or just log them
     */
    private bool $shouldSendEmail = false;

    /**
     * Generate and send OTP code to the given email
     */
    public function generateAndSend(string $email, string $userName): string
    {
        // Clean up any expired OTPs for this email
        $this->cleanupExpiredOtps($email);

        // Generate 6-digit OTP code
        $code = $this->generateCode();

        // Store hashed OTP in database
        OtpVerification::create([
            'email' => $email,
            'code_hash' => hash('sha256', $code),
            'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
        ]);

        // Send email (or log if in development mode)
        $this->sendOtpEmail($email, $userName, $code);

        return $code;
    }

    /**
     * Verify the OTP code for the given email
     */
    public function verify(string $email, string $code): bool
    {
        // Development bypass: always accept DEV_OTP_CODE
        if ($code === self::DEV_OTP_CODE) {
            Log::info('OTP verification bypassed with development code', ['email' => $email]);
            return true;
        }

        // Find the most recent valid OTP for this email
        $otp = OtpVerification::forEmail($email)
            ->valid()
            ->latest()
            ->first();

        if (!$otp) {
            return false;
        }

        // Verify the code
        if ($otp->verifyCode($code)) {
            // Delete the OTP after successful verification
            $otp->delete();
            return true;
        }

        return false;
    }

    /**
     * Generate a random 6-digit OTP code
     */
    private function generateCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP email or log it in development mode
     */
    private function sendOtpEmail(string $email, string $userName, string $code): void
    {
        if ($this->shouldSendEmail) {
            // Production: Actually send the email
            Mail::to($email)->send(new OtpMail($code, $userName));
            Log::info('OTP email sent', ['email' => $email]);
        } else {
            // Development: Just log the OTP code
            Log::info('OTP generated (email sending disabled)', [
                'email' => $email,
                'code' => $code,
                'dev_code_accepted' => self::DEV_OTP_CODE,
            ]);
        }
    }

    /**
     * Clean up expired OTPs for the given email
     */
    private function cleanupExpiredOtps(string $email): void
    {
        OtpVerification::forEmail($email)
            ->where('expires_at', '<', now())
            ->delete();
    }

    /**
     * Enable actual email sending (for production)
     */
    public function enableEmailSending(): void
    {
        $this->shouldSendEmail = true;
    }

    /**
     * Check if there's a valid OTP for the given email
     */
    public function hasValidOtp(string $email): bool
    {
        return OtpVerification::forEmail($email)
            ->valid()
            ->exists();
    }
}
