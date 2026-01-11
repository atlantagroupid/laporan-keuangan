<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Verifikasi Email</h2>
        <p class="text-gray-500 mt-2">Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-green-700 font-medium">Link verifikasi baru telah dikirim ke email Anda.</p>
            </div>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="modern-btn text-white font-semibold py-3 px-6 rounded-lg text-sm">
                {{ __('Kirim Ulang Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm modern-link font-medium">
                {{ __('Keluar') }}
            </button>
        </form>
    </div>
</x-guest-layout>
