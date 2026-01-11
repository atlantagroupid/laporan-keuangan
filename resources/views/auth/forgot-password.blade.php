<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Lupa Password?</h2>
        <p class="text-gray-500 mt-2">Masukkan email Anda dan kami akan mengirimkan link untuk reset password.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
            <x-text-input id="email" class="block mt-2 w-full modern-input rounded-lg px-4 py-3" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="mt-8">
            <button type="submit" class="w-full modern-btn text-white font-semibold py-3 px-6 rounded-lg text-sm uppercase tracking-wider">
                {{ __('Kirim Link Reset') }}
            </button>
        </div>

        <!-- Back to Login -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm modern-link font-medium inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Kembali ke halaman login') }}
            </a>
        </div>
    </form>
</x-guest-layout>
