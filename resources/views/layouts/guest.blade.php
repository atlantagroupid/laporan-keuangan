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
    <body class="font-sans antialiased text-slate-900 overflow-x-hidden">
        <!-- Vibrant Mesh Background -->
        <div class="vibrant-mesh-bg"></div>

        <div class="min-h-screen flex flex-col items-center justify-center p-6 relative">
            <!-- Background Aura Glows (Contained) -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
                <div class="aura-glow" style="width: 600px; height: 600px; background: rgba(85, 86, 145, 0.4); top: -100px; left: -100px;"></div>
                <div class="aura-glow" style="width: 500px; height: 500px; background: rgba(14, 165, 233, 0.3); bottom: -100px; right: -100px; animation-delay: -5s;"></div>
            </div>

            <!-- Branding Header (Subtle) -->
            <div class="text-center mb-10 animate-entrance relative z-10">
                @if(isset($setting) && $setting->app_logo && file_exists(public_path($setting->app_logo)))
                    <div class="w-16 h-16 mx-auto mb-5 bg-white rounded-2xl shadow-lg flex items-center justify-center border border-slate-100 p-3">
                        <img src="{{ asset($setting->app_logo) }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                @endif
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                    {{ $setting->app_title ?? 'Laporan Keuangan' }}
                </h1>
                <p class="text-sm text-slate-500 font-medium">Platform Manajemen Keuangan Modern</p>
            </div>

            <!-- Centered Vibrant Glass Card -->
            <div class="w-full max-w-md animate-entrance relative z-10" style="animation-delay: 0.1s;">
                <div class="glass-card-vibrant p-8 sm:p-12">
                    {{ $slot }}
                </div>
            </div>

            <!-- Subtle Footer -->
            <div class="mt-12 text-center text-xs text-slate-400 font-medium animate-entrance relative z-10" style="animation-delay: 0.2s;">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Built for Precision.
            </div>

        </div>
    </body>
</html>
