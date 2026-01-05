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
     * Serve avatar image from storage
     */
    public function serveAvatar($userId, $filename)
    {
        $path = 'data/' . $userId . '/' . $filename;
        
        if (!Storage::exists($path)) {
            abort(404);
        }
        
        $file = Storage::get($path);
        $mimeType = Storage::mimeType($path);
        
        return response($file, 200)->header('Content-Type', $mimeType);
    }

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

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        // Define max file size in KB for validation
        $maxFileSizeKB = 2048; // 2MB
        $maxFileSizeBytes = $maxFileSizeKB * 1024;
        $maxFileSizeMB = $maxFileSizeKB / 1024;

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', "max:{$maxFileSizeKB}"],
                'notify_dividends' => ['nullable'],
            ], [
                'avatar.max' => "L'immagine è troppo grande. Dimensione massima: {$maxFileSizeMB}MB.",
                'avatar.image' => 'Il file deve essere un\'immagine.',
                'avatar.mimes' => 'Formato immagine non supportato. Usa: jpeg, png, jpg, gif.',
            ]);

            // Update name
            $user->name = $validated['name'];

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                
                // Additional file size check in bytes
                if ($file->getSize() > $maxFileSizeBytes) {
                    if ($request->expectsJson() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => "L'immagine è troppo grande. Dimensione massima: {$maxFileSizeMB}MB."
                        ], 422);
                    }
                    return redirect()->route('profile.settings')
                        ->with('error', "L'immagine è troppo grande. Dimensione massima: {$maxFileSizeMB}MB.");
                }
                
                // Delete old avatar if exists
                $userDir = 'data/' . $user->id;
                if ($user->avatar && Storage::exists($userDir . '/' . $user->avatar)) {
                    Storage::delete($userDir . '/' . $user->avatar);
                }

                // Ensure user directory exists
                Storage::makeDirectory($userDir);
                
                // Get the file extension
                $extension = $file->getClientOriginalExtension();
                
                // Store with fixed name: avatar.{extension}
                $filename = 'avatar.' . $extension;
                $file->storeAs($userDir, $filename);
                $user->avatar = $filename;
            }

            $user->save();

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Modifiche salvate con successo!',
                    'user' => [
                        'name' => $user->name,
                        'avatar' => $user->avatarUrl(),
                    ]
                ]);
            }

            return redirect()->route('profile.settings')
                ->with('success', 'Modifiche salvate con successo!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => collect($e->errors())->flatten()->first()
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
            $userDir = 'data/' . $user->id;
            if ($user->avatar && Storage::exists($userDir . '/' . $user->avatar)) {
                Storage::delete($userDir . '/' . $user->avatar);
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
