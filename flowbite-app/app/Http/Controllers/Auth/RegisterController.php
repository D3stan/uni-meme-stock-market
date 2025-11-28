<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\GlobalSetting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle a registration request.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $registrationBonus = GlobalSetting::where('key', 'registration_bonus')->first()?->value ?? 100;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'trader',
            'cfu_balance' => $registrationBonus,
            'is_suspended' => false,
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => 'Registration successful! Please verify your email.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'cfu_balance' => $user->cfu_balance,
            ],
        ], 201);
    }
}
