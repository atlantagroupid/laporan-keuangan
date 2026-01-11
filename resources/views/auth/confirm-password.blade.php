<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Konfirmasi Password</h2>
        <p class="text-gray-500 mt-2">Ini adalah area aman. Silakan konfirmasi password Anda sebelum melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
            <x-text-input id="password" class="block mt-2 w-full modern-input rounded-lg px-4 py-3"
                            type="password"
                            name="password"
                            required autocomplete="current-password" 
                            placeholder="Masukkan password Anda" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="mt-8">
            <button type="submit" class="w-full modern-btn text-white font-semibold py-3 px-6 rounded-lg text-sm uppercase tracking-wider">
                {{ __('Konfirmasi') }}
            </button>
        </div>
    </form>
</x-guest-layout>
