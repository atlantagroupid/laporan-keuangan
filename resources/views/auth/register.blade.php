<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Buat Akun Baru</h2>
        <p class="text-gray-500 mt-2">Mulai kelola keuangan Anda dengan mudah</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700 font-medium" />
            <x-text-input id="name" class="block mt-2 w-full modern-input rounded-lg px-4 py-3" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-5">
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
            <x-text-input id="email" class="block mt-2 w-full modern-input rounded-lg px-4 py-3" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
            <x-text-input id="password" class="block mt-2 w-full modern-input rounded-lg px-4 py-3"
                            type="password"
                            name="password"
                            required autocomplete="new-password" 
                            placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-700 font-medium" />
            <x-text-input id="password_confirmation" class="block mt-2 w-full modern-input rounded-lg px-4 py-3"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" 
                            placeholder="Ulangi password Anda" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="mt-8">
            <button type="submit" class="w-full modern-btn text-white font-semibold py-3 px-6 rounded-lg text-sm uppercase tracking-wider">
                {{ __('Daftar') }}
            </button>
        </div>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <span class="text-gray-500 text-sm">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="text-sm modern-link font-semibold ms-1">
                {{ __('Masuk di sini') }}
            </a>
        </div>
    </form>
</x-guest-layout>
