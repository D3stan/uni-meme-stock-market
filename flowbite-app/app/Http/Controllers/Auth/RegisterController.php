<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\GlobalSetting;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * Handle a registration request.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $registrationBonus = GlobalSetting::where('key', 'registration_bonus')->first()?->value ?? 100;

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'trader',
            'cfu_balance' => $registrationBonus,
            'is_suspended' => false,
        ]);

        event(new Registered($user));

        // Generate OTP for email verification
        $otpData = OtpVerification::createForEmail($request->email);

        // In a real app, send email here
        // For development, log the code
        Log::info("OTP for {$request->email}: {$otpData['code']}");

        return response()->json([
            'message' => 'Registrazione effettuata! Verifica la tua email.',
            'redirect' => '/verify-otp?email=' . urlencode($request->email),
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'cfu_balance' => $user->cfu_balance,
            ],
            // Only in dev mode - remove in production
            'dev_code' => config('app.debug') ? $otpData['code'] : null,
        ], 201);
    }
}
