<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\OtpVerificationRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Financial\Transaction;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        private OtpService $otpService
    ) {
    }

    /**
     * Show the registration form
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Generate and send OTP
        $this->otpService->generateAndSend($validated['email'], $validated['name']);

        // Store registration data in session for later completion
        session([
            'pending_registration' => [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]
        ]);

        return redirect()->route('auth.verify-otp.show')
            ->with('success', 'Registration successful! Please check your email for the verification code.');
    }

    /**
     * Show the OTP verification form
     */
    public function showOtpVerification(): View
    {
        $pendingRegistration = session('pending_registration');

        if (!$pendingRegistration) {
            return redirect()->route('auth.register')
                ->with('error', 'No pending registration found. Please register first.');
        }

        return view('auth.verify-otp', [
            'email' => $pendingRegistration['email']
        ]);
    }

    /**
     * Verify OTP and complete registration
     */
    public function verifyOtp(OtpVerificationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $pendingRegistration = session('pending_registration');

        if (!$pendingRegistration) {
            return redirect()->route('auth.register')
                ->with('error', 'No pending registration found. Please register first.');
        }

        // Verify OTP
        if (!$this->otpService->verify($validated['email'], $validated['code'])) {
            return back()
                ->withErrors(['code' => 'Invalid or expired verification code.'])
                ->withInput();
        }

        // Create user and grant bonus in a transaction
        DB::transaction(function () use ($pendingRegistration) {
            $user = User::create([
                'name' => $pendingRegistration['name'],
                'email' => $pendingRegistration['email'],
                'password' => $pendingRegistration['password'],
                'email_verified_at' => now(),
                'cfu_balance' => 100.00,
                'role' => 'trader',
            ]);

            // Record the signup bonus transaction
            Transaction::create([
                'user_id' => $user->id,
                'meme_id' => null,
                'type' => 'bonus',
                'quantity' => null,
                'price_per_share' => null,
                'fee_amount' => 0,
                'total_amount' => 100.00,
                'cfu_balance_after' => 100.00,
                'executed_at' => now(),
            ]);

            // Log the user in
            Auth::login($user);
        });

        // Clear pending registration from session
        session()->forget('pending_registration');

        // Set flag for onboarding modal
        session()->flash('show_onboarding_modal', true);

        return redirect()->route('market')
            ->with('success', 'Welcome to AlmaStreet! You have received 100 CFU to start trading.');
    }

    /**
     * Show the login form
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Check if email is verified (user exists and email_verified_at is set)
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !$user->email_verified_at) {
            return back()
                ->withErrors(['email' => 'This email has not been verified yet. Please complete registration.'])
                ->withInput($request->only('email'));
        }

        // Check if user is suspended
        if ($user->isSuspended()) {
            return back()
                ->withErrors(['email' => 'Your account has been suspended. Please contact support.'])
                ->withInput($request->only('email'));
        }

        // Attempt to log the user in
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('market'));
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('email'));
    }

    /**
     * Handle logout request
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('welcome')
            ->with('success', 'You have been logged out successfully.');
    }
}
