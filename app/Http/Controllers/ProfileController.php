<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the settings page
     */
    public function showSettings()
    {
        $user = Auth::user();
        
        return view('pages.profile.settings', [
            'user' => $user,
        ]);
    }

    /**
     * Update profile settings
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
                'notify_dividends' => ['nullable'],
            ]);

            // Update name
            $user->name = $validated['name'];

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                // Store new avatar
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
            }

            $user->save();

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Modifiche salvate con successo!',
                    'user' => [
                        'name' => $user->name,
                        'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                    ]
                ]);
            }

            return redirect()->route('profile.settings')
                ->with('success', 'Modifiche salvate con successo!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore di validazione: ' . collect($e->errors())->flatten()->first()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante il salvataggio: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('profile.settings')
                ->with('error', 'Errore durante il salvataggio delle modifiche.');
        }
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'La password attuale non è corretta.',
            'new_password.confirmed' => 'Le password non corrispondono.',
            'new_password.min' => 'La password deve contenere almeno 8 caratteri.',
        ]);

        try {
            $user->password = Hash::make($validated['new_password']);
            $user->save();

            return redirect()->route('profile.settings')
                ->with('success', 'Password modificata con successo!');

        } catch (\Exception $e) {
            return redirect()->route('profile.settings')
                ->with('error', 'Errore durante la modifica della password.');
        }
    }

    /**
     * Deactivate account
     */
    public function deactivate()
    {
        $user = Auth::user();

        try {
            $user->is_suspended = true;
            $user->save();

            Auth::logout();

            return redirect()->route('auth.login')
                ->with('success', 'Account disattivato con successo. Effettua il login per riattivarlo.');

        } catch (\Exception $e) {
            return redirect()->route('profile.settings')
                ->with('error', 'Errore durante la disattivazione dell\'account.');
        }
    }

    /**
     * Delete account permanently
     */
    public function delete()
    {
        $user = Auth::user();

        try {
            // Delete avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Soft delete or hard delete based on your requirements
            // For now, we'll use hard delete
            $user->delete();

            Auth::logout();

            return redirect()->route('welcome')
                ->with('success', 'Account eliminato definitivamente.');

        } catch (\Exception $e) {
            return redirect()->route('profile.settings')
                ->with('error', 'Errore durante l\'eliminazione dell\'account.');
        }
    }
}
