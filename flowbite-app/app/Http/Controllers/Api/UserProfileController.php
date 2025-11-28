<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Get the authenticated user's profile.
     */
    public function show(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'cfu_balance' => $user->cfu_balance,
                'is_suspended' => $user->is_suspended,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
            ],
        ]);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = Auth::user();

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->hasFile('profile_picture')) {
            // Store the profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            // You would need to add a profile_picture field to the users table
            // For now, we'll skip this or you can add it in a future migration
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'cfu_balance' => $user->cfu_balance,
            ],
        ]);
    }
}
