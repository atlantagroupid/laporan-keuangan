<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang Kembali!</h2>
        <p class="text-gray-500 mt-2">Silakan masuk ke akun Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
            <x-text-input id="email" class="block mt-2 w-full modern-input rounded-lg px-4 py-3" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
            <x-text-input id="password" class="block mt-2 w-full modern-input rounded-lg px-4 py-3"
                            type="password"
                            name="password"
                            required autocomplete="current-password" 
                            placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-5">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="modern-checkbox" name="remember">
                <span class="ms-3 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>


        </div>

        <!-- Submit Button -->
        <div class="mt-8">
            <button type="submit" class="w-full modern-btn text-white font-semibold py-3 px-6 rounded-lg text-sm uppercase tracking-wider">
                {{ __('Masuk') }}
            </button>
        </div>

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <span class="text-gray-500 text-sm">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="text-sm modern-link font-semibold ms-1">
                {{ __('Daftar sekarang') }}
            </a>
        </div>
    </form>
</x-guest-layout>
