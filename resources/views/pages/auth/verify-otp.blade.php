<x-guest title="Verifica Email - AlmaStreet">
    <div class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('auth.register') }}" class="inline-flex items-center text-sm text-gray-400 hover:text-white transition-colors">
                    <span class="material-icons text-xl mr-1">arrow_back</span>
                    Indietro
                </a>
            </div>

            <!-- Logo & Icon -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-green-900/30 rounded-full flex items-center justify-center">
                    <span class="material-icons text-4xl text-green-500">email</span>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Controlla la tua email</h1>
                <p class="text-sm text-gray-400">
                    Abbiamo inviato un codice a 6 cifre a<br>
                    <span class="text-white font-medium">{{ session('otp_email', 'tua email') }}</span>
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    Inserisci il codice per verificare il tuo account
                </p>
            </div>

            <!-- OTP Form -->
            <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-6 sm:p-8 border border-gray-800 shadow-xl">
                <form method="POST" action="{{ route('auth.verify-otp.post') }}" id="otp-form">
                    @csrf

                    <!-- OTP Input Container -->
                    <div class="flex justify-center gap-2 mb-6" id="otp-inputs">
                        @for($i = 0; $i < 6; $i++)
                        <input
                            type="text"
                            maxlength="1"
                            pattern="[0-9]"
                            inputmode="numeric"
                            class="w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-bold bg-input-background border-2 border-gray-700 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-500/20 outline-none transition-all"
                            data-index="{{ $i }}"
                            autocomplete="off"
                        >
                        @endfor
                    </div>

                    <!-- Hidden input to store complete OTP -->
                    <input type="hidden" name="otp" id="otp-value">

                    <!-- Error Display -->
                    <x-forms.validation-error field="otp" />
                    <x-forms.validation-error />

                    <!-- Loading State -->
                    <div id="loading-state" class="hidden text-center mb-4">
                        <div class="inline-flex items-center gap-2 text-sm text-gray-400">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Verifica in corso...</span>
                        </div>
                    </div>

                    <!-- Resend Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-400">
                            Non hai ricevuto il codice? 
                            <button type="button" id="resend-btn" class="text-green-500 hover:text-green-400 font-medium transition-colors">
                                Invia di nuovo
                            </button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('page-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('#otp-inputs input');
            const form = document.getElementById('otp-form');
            const hiddenInput = document.getElementById('otp-value');
            const loadingState = document.getElementById('loading-state');
            const resendBtn = document.getElementById('resend-btn');

            // Focus first input
            inputs[0].focus();

            inputs.forEach((input, index) => {
                // Handle input
                input.addEventListener('input', function(e) {
                    const value = e.target.value;
                    
                    // Only allow digits
                    if (!/^\d$/.test(value)) {
                        e.target.value = '';
                        return;
                    }

                    // Move to next input
                    if (value && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }

                    // Check if all inputs are filled
                    checkComplete();
                });

                // Handle backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                // Handle paste
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');
                    
                    pastedData.split('').forEach((char, i) => {
                        if (index + i < inputs.length) {
                            inputs[index + i].value = char;
                        }
                    });

                    // Focus last filled input
                    const lastIndex = Math.min(index + pastedData.length, inputs.length - 1);
                    inputs[lastIndex].focus();
                    
                    checkComplete();
                });
            });

            function checkComplete() {
                const otp = Array.from(inputs).map(input => input.value).join('');
                
                if (otp.length === 6) {
                    hiddenInput.value = otp;
                    
                    // Visual feedback
                    inputs.forEach(input => {
                        input.classList.remove('border-gray-700');
                        input.classList.add('border-green-500', 'bg-green-900/20');
                    });

                    // Auto-submit after brief delay
                    setTimeout(() => {
                        loadingState.classList.remove('hidden');
                        form.submit();
                    }, 300);
                }
            }

            // Resend OTP
            let cooldown = false;
            resendBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (cooldown) return;
                
                cooldown = true;
                const originalText = resendBtn.textContent;
                let seconds = 60;
                
                resendBtn.textContent = `Riprova tra ${seconds}s`;
                resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
                
                const countdown = setInterval(() => {
                    seconds--;
                    resendBtn.textContent = `Riprova tra ${seconds}s`;
                    
                    if (seconds <= 0) {
                        clearInterval(countdown);
                        resendBtn.textContent = originalText;
                        resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        cooldown = false;
                    }
                }, 1000);

                // TODO: Make actual API call to resend OTP
                fetch('{{ route('auth.verify-otp.show') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                }).then(response => {
                    if (response.ok) {
                        // Show success feedback
                        console.log('OTP resent successfully');
                    }
                });
            });
        });
    </script>
    @endpush
</x-guest>
