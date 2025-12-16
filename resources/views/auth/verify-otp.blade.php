{{-- resources/views/auth/verify-otp.blade.php --}}
<x-layouts.app title="Verifikasi Email - SertiKu">

    <div class="min-h-screen flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-md">

            {{-- Card --}}
            <div class="bg-white/10 border border-white/20 rounded-3xl p-8 shadow-2xl">

                {{-- Header --}}
                <div class="text-center mb-8">
                    <div
                        class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white mb-2">Verifikasi Email</h1>
                    <p class="text-[#94A3B8] text-sm">
                        Masukkan kode 6 digit yang dikirim ke<br>
                        <span class="text-blue-400 font-medium">{{ $email }}</span>
                    </p>
                </div>

                {{-- Success/Error Messages --}}
                @if(session('success'))
                    <div
                        class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400 text-sm text-center">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div
                        class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm text-center">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- OTP Form --}}
                <form action="{{ route('verification.otp.verify') }}" method="POST" id="otpForm">
                    @csrf

                    {{-- OTP Input Fields --}}
                    <div class="flex justify-center gap-2 mb-6" x-data="otpInput()">
                        @for($i = 0; $i < 6; $i++)
                            <input type="text" maxlength="1" class="w-12 h-14 text-center text-2xl font-bold rounded-xl border-2 
                                              bg-white/5 text-white
                                              border-white/20 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50
                                              transition-all duration-200" x-ref="input{{ $i }}"
                                @input="handleInput($event, {{ $i }})" @keydown="handleKeydown($event, {{ $i }})"
                                @paste="handlePaste($event)" inputmode="numeric" pattern="[0-9]*">
                        @endfor
                        <input type="hidden" name="otp" x-ref="otpValue">
                    </div>

                    @error('otp')
                        <p class="mb-4 text-center text-sm text-red-400">{{ $message }}</p>
                    @enderror

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 
                                   text-white font-semibold hover:from-blue-600 hover:to-purple-700
                                   transition-all duration-300 shadow-lg shadow-blue-500/25">
                        Verifikasi
                    </button>
                </form>

                {{-- Resend OTP --}}
                <div class="mt-6 text-center" x-data="resendTimer()">
                    <p class="text-sm text-[#94A3B8]">
                        Tidak menerima kode?
                    </p>
                    <form action="{{ route('verification.otp.resend') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-blue-400 hover:text-blue-300 font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="countdown > 0"
                            x-text="countdown > 0 ? `Kirim ulang (${countdown}s)` : 'Kirim Ulang OTP'">
                        </button>
                    </form>
                </div>

                {{-- Change Email (for wallet users) --}}
                @if(str_ends_with($email, '@wallet.local'))
                    <div class="mt-4 text-center">
                        <a href="{{ route('verification.email.input') }}"
                            class="text-sm text-[#94A3B8] hover:text-white transition-colors">
                            Ubah alamat email
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Alpine.js Scripts --}}
    <script>
        function otpInput() {
            return {
                handleInput(event, index) {
                    const value = event.target.value.replace(/[^0-9]/g, '');
                    event.target.value = value;

                    if (value && index < 5) {
                        this.$refs[`input${index + 1}`].focus();
                    }

                    this.updateOtpValue();
                },
                handleKeydown(event, index) {
                    if (event.key === 'Backspace' && !event.target.value && index > 0) {
                        this.$refs[`input${index - 1}`].focus();
                    }
                },
                handlePaste(event) {
                    event.preventDefault();
                    const paste = (event.clipboardData || window.clipboardData).getData('text');
                    const digits = paste.replace(/[^0-9]/g, '').slice(0, 6);

                    for (let i = 0; i < 6; i++) {
                        this.$refs[`input${i}`].value = digits[i] || '';
                    }

                    if (digits.length > 0) {
                        this.$refs[`input${Math.min(digits.length, 5)}`].focus();
                    }

                    this.updateOtpValue();
                },
                updateOtpValue() {
                    let otp = '';
                    for (let i = 0; i < 6; i++) {
                        otp += this.$refs[`input${i}`].value;
                    }
                    this.$refs.otpValue.value = otp;
                }
            }
        }

        function resendTimer() {
            return {
                countdown: 0,
                init() {
                    // Start with 60 second cooldown if just sent
                    @if(session('success'))
                        this.countdown = 60;
                        this.startCountdown();
                    @endif
                },
                startCountdown() {
                    const interval = setInterval(() => {
                        this.countdown--;
                        if (this.countdown <= 0) {
                            clearInterval(interval);
                        }
                    }, 1000);
                }
            }
        }
    </script>

</x-layouts.app>