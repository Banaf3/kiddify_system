<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Verify OTP Code</h2>
        <p class="mt-2 text-sm text-gray-600">
            We sent a 6-digit code to your email: <strong>{{ $maskedEmail }}</strong>
        </p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <input type="hidden" name="purpose" value="{{ $purpose }}">
        @if ($redirect)
            <input type="hidden" name="redirect" value="{{ $redirect }}">
        @endif

        <!-- OTP Code -->
        <div>
            <x-input-label for="otp" :value="__('Enter 6-Digit Code')" />
            <x-text-input id="otp" class="block mt-1 w-full text-center text-2xl font-bold tracking-widest"
                type="text" name="otp" :value="old('otp')" required autofocus maxlength="6" pattern="\d{6}"
                placeholder="000000" style="letter-spacing: 0.5em;" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit"
                class="w-full px-4 py-3 text-white bg-kiddify-orange hover:bg-kiddify-orange/90 rounded-lg focus:outline-none focus:ring-2 focus:ring-kiddify-orange focus:ring-offset-2 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                Verify OTP
            </button>
        </div>
    </form>

    <!-- Resend OTP -->
    <div class="mt-4 text-center">
        <p class="text-sm text-gray-600">Didn't receive the code?</p>
        <form method="POST" action="{{ route('otp.resend') }}" class="inline">
            @csrf
            <input type="hidden" name="purpose" value="{{ $purpose }}">
            <button type="submit"
                class="text-kiddify-orange hover:text-kiddify-orange/80 font-semibold text-sm underline transition-colors duration-300">
                Resend OTP
            </button>
        </form>
    </div>

    <!-- Help Text -->
    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-sm text-blue-800">
            <strong>📧 Check your email</strong><br>
            The code will expire in 10 minutes. If you don't see it, check your spam folder.
        </p>
    </div>

    <!-- Cancel -->
    <div class="mt-4 text-center">
        <a href="{{ $purpose === 'login' ? route('login') : route('dashboard') }}"
            class="text-sm text-gray-600 hover:text-gray-800 underline">
            Cancel
        </a>
    </div>

    <script>
        // Auto-format OTP input to only accept numbers
        document.getElementById('otp').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '').substring(0, 6);
        });
    </script>
</x-guest-layout>
