<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle a login request.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        $user = Auth::user();

        if ($user->is_suspended) {
            Auth::logout();

            return response()->json([
                'message' => 'Your account has been suspended. Please contact support.',
            ], 403);
        }

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login successful!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'cfu_balance' => $user->cfu_balance,
            ],
        ]);
    }

    /**
     * Handle a logout request.
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout successful!',
        ]);
    }
}
