<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    /**
     * Verify OTP code and authenticate user
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $email = $request->input('email');
        $code = $request->input('otp');

        // Verify OTP
        $isValid = OtpVerification::verify($email, $code);

        if (!$isValid) {
            return response()->json([
                'message' => 'Codice non valido o scaduto',
            ], 422);
        }

        // Find user and mark as verified
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Utente non trovato',
            ], 404);
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->save();

        // Log the user in
        Auth::login($user);

        return response()->json([
            'message' => 'Email verificata con successo! Benvenuto su AlmaStreet!',
            'redirect' => '/onboarding',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'cfu_balance' => $user->cfu_balance,
            ],
        ]);
    }

    /**
     * Resend OTP code
     */
    public function resend(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        // Check if user exists
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Utente non trovato',
            ], 404);
        }

        // Check if already verified
        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email già verificata',
            ], 400);
        }

        // Create new OTP
        $otpData = OtpVerification::createForEmail($email);

        // In a real app, send email here
        // For development, log the code
        Log::info("OTP resent for {$email}: {$otpData['code']}");

        return response()->json([
            'message' => 'Codice inviato nuovamente',
            // Only in dev mode - remove in production
            'dev_code' => config('app.debug') ? $otpData['code'] : null,
        ]);
    }
}
