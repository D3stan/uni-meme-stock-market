<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\OtpVerificationRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Financial\Transaction;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
     * Displays the user registration form.
     *
     * @return View
     */
    public function showRegister(): View
    {
        return view('pages.auth.register');
    }

    /**
     * Processes a registration request by generating an OTP and storing partial user data in the session.
     *
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->otpService->generateAndSend($validated['email'], $validated['name']);

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
     * Displays the OTP verification form if a pending registration or password reset exists.
     *
     * @return View|RedirectResponse
     */
    public function showOtpVerification(): View|RedirectResponse
    {
        $pendingRegistration = session('pending_registration');
        $pendingPasswordReset = session('pending_password_reset');

        if (!$pendingRegistration && !$pendingPasswordReset) {
            return redirect()->route('auth.register')
                ->with('error', 'No pending registration found. Please register first.');
        }

        $email = $pendingRegistration['email'] ?? $pendingPasswordReset['email'] ?? '';

        return view('pages.auth.verify-otp', [
            'email' => $email,
            'isPasswordReset' => (bool)$pendingPasswordReset
        ]);
    }

    /**
     * Verifies the provided OTP code and finalizes the registration or password reset process.
     *
     * @param OtpVerificationRequest $request
     * @return RedirectResponse
     */
    public function verifyOtp(OtpVerificationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $pendingRegistration = session('pending_registration');
        $pendingPasswordReset = session('pending_password_reset');

        if (!$pendingRegistration && !$pendingPasswordReset) {
            return redirect()->route('auth.register')
                ->with('error', 'No pending registration found. Please register first.');
        }

        $email = $validated['email'] ?? ($pendingRegistration['email'] ?? $pendingPasswordReset['email']);

        if (!$this->otpService->verify($email, $validated['code'])) {
            return back()
                ->withErrors(['code' => 'Invalid or expired verification code.'])
                ->withInput();
        }

        if ($pendingPasswordReset) {
            $user = User::where('email', $email)->first();
            
            if ($user) {
                Auth::login($user);
                session()->forget('pending_password_reset');
                
                session()->flash('needs_password_change', true);
                session()->flash('toast', [
                    'type' => 'warning',
                    'message' => 'Per sicurezza, cambia subito la tua password dalle impostazioni del profilo.'
                ]);
                
                return redirect()->route('market');
            }
            
            return redirect()->route('auth.login')
                ->with('error', 'Utente non trovato.');
        }

        DB::transaction(function () use ($pendingRegistration) {
            $user = User::create([
                'name' => $pendingRegistration['name'],
                'email' => $pendingRegistration['email'],
                'password' => $pendingRegistration['password'],
                'email_verified_at' => now(),
                'cfu_balance' => 100.00,
                'role' => 'trader',
            ]);

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

            Auth::login($user);
        });

        session()->forget('pending_registration');

        session()->flash('show_onboarding_modal', true);

        return redirect()->route('market')
            ->with('success', 'Welcome to AlmaStreet! You have received 100 CFU to start trading.');
    }

    /**
     * Displays the login form.
     *
     * @return View
     */
    public function showLogin(): View
    {
        return view('pages.auth.login');
    }

    /**
     * Authenticates a user after verifying email status and account suspension state.
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'Impossibile trovare il tuo account Almastreet'])
                ->withInput($request->only('email'));
        }

        if (!$user->email_verified_at) {
            return back()
                ->withErrors(['email' => 'Questo account non è stato ancora verificato.'])
                ->withInput($request->only('email'));
        }

        if ($user->isSuspended()) {
            return back()
                ->withErrors(['email' => 'Il tuo account è stato sospeso. Contatta il supporto.'])
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (session('needs_password_change')) {
                session()->flash('toast', [
                    'type' => 'warning',
                    'message' => 'Per sicurezza, ti consigliamo di cambiare la tua password dalle impostazioni del profilo.'
                ]);
            }

            return redirect()->intended(route('market'));
        }

        return back()
            ->withErrors(['email' => 'Le credenziali fornite non corrispondono.'])
            ->withInput($request->only('email'));
    }

    /**
     * Initiates the password reset flow by sending an OTP to the user's email.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendPasswordResetOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ], [
            'email.exists' => 'Non esiste un account con questa email.'
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        $this->otpService->generateAndSend($email, $user->name);

        session([
            'pending_password_reset' => [
                'email' => $email,
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Codice di verifica inviato alla tua email.'
        ]);
    }

    /**
     * Logs out the currently authenticated user and invalidates the session.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('welcome')
            ->with('success', 'Ti sei disconnesso con successo.');
    }
}
