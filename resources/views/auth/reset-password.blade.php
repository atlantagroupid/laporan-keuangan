<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Reset Password</h2>
        <p class="text-gray-500 mt-2">Buat password baru untuk akun Anda</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
            <x-text-input id="email" class="block mt-2 w-full modern-input rounded-lg px-4 py-3" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" :value="__('Password Baru')" class="text-gray-700 font-medium" />
            <x-text-input id="password" class="block mt-2 w-full modern-input rounded-lg px-4 py-3" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" class="text-gray-700 font-medium" />
            <x-text-input id="password_confirmation" class="block mt-2 w-full modern-input rounded-lg px-4 py-3"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" 
                            placeholder="Ulangi password baru" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="mt-8">
            <button type="submit" class="w-full modern-btn text-white font-semibold py-3 px-6 rounded-lg text-sm uppercase tracking-wider">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>
