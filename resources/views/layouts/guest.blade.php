<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $setting->app_title ?? config('app.name', 'Laporan Keuangan') }}</title>
        
        <!-- Favicon -->
        @if(isset($setting) && $setting->app_logo && file_exists(public_path($setting->app_logo)))
        <link rel="icon" href="{{ asset($setting->app_logo) }}">
        @else
        <link rel="icon" href="https://via.placeholder.com/32x32.png?text=LK"> 
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900 bg-white">
        <div class="min-h-screen flex flex-col lg:flex-row">
            
            <!-- LEFT COLUMN: BRANDING (Hidden on Mobile, Visible on Desktop) -->
            <div class="lg:w-1/2 brand-gradient relative hidden lg:flex flex-col justify-between p-12 text-white">
                <!-- Top: Logo -->
                <div class="relative z-10 flex items-center gap-3">
                     @if(isset($setting) && $setting->app_logo && file_exists(public_path($setting->app_logo)))
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                            <img src="{{ asset($setting->app_logo) }}" alt="Logo" class="w-6 h-6 object-contain">
                        </div>
                    @endif
                    <span class="text-xl font-bold tracking-tight">{{ $setting->app_title ?? 'Laporan Keuangan' }}</span>
                </div>

                <!-- Center: Hero Text -->
                <div class="relative z-10 max-w-lg">
                    <h1 class="text-4xl font-bold mb-6 leading-tight">
                        Kelola Keuangan Anda dengan Lebih Cerdas.
                    </h1>
                    <p class="text-blue-100 text-lg leading-relaxed">
                        Platform manajemen keuangan modern untuk membantu Anda melacak pemasukan, pengeluaran, dan mencapai target finansial.
                    </p>
                </div>

                <!-- Bottom: Footer -->
                <div class="relative z-10 text-sm text-blue-200">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </div>
            </div>

            <!-- RIGHT COLUMN: CONTENT (FORM) -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white">
                <div class="w-full max-w-md form-enter">
                    <!-- Mobile Logo (Visible only on smaller screens) -->
                    <div class="lg:hidden text-center mb-8">
                         @if(isset($setting) && $setting->app_logo && file_exists(public_path($setting->app_logo)))
                            <img src="{{ asset($setting->app_logo) }}" alt="Logo" class="w-16 h-16 mx-auto mb-4 object-contain">
                        @endif
                        <h2 class="text-2xl font-bold text-slate-900">{{ $setting->app_title ?? 'Laporan Keuangan' }}</h2>
                    </div>

                    <!-- Slot Content (Login/Register Forms) -->
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>
