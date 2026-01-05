/**
 * OTP Verification Page
 * Handles 6-digit OTP input with auto-advance, paste support, and resend functionality
 */

export function initOtpVerification() {
    const inputs = document.querySelectorAll('#otp-inputs input');
    const form = document.getElementById('otp-form');
    const hiddenInput = document.getElementById('otp-value');
    const loadingState = document.getElementById('loading-state');
    const resendBtn = document.getElementById('resend-btn');

    if (!inputs.length || !form) return;

    inputs[0].focus();

    inputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value;

            if (!/^\d$/.test(value)) {
                e.target.value = '';
                return;
            }

            if (value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            checkComplete();
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');
            
            pastedData.split('').forEach((char, i) => {
                if (index + i < inputs.length) {
                    inputs[index + i].value = char;
                }
            });

            const lastIndex = Math.min(index + pastedData.length, inputs.length - 1);
            inputs[lastIndex].focus();
            
            checkComplete();
        });
    });

    function checkComplete() {
        const otp = Array.from(inputs).map(input => input.value).join('');
        
        if (otp.length === 6) {
            hiddenInput.value = otp;

            inputs.forEach(input => {
                input.classList.remove('border-surface-200');
                input.classList.add('border-brand', 'bg-brand/20');
            });

            setTimeout(() => {
                if (loadingState) {
                    loadingState.classList.remove('hidden');
                }
                form.submit();
            }, 300);
        }
    }

    if (resendBtn) {
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

            const resendUrl = resendBtn.dataset.resendUrl;
            if (resendUrl) {
                fetch(resendUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                }).then(response => {
                    if (response.ok) {
                        console.log('OTP resent successfully');
                    }
                }).catch(error => {
                    console.error('Failed to resend OTP:', error);
                });
            }
        });
    }
}
