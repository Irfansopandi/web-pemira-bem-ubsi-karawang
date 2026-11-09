<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FDFDFD]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Login' }} — PEMIRA BEM UBSI Karawang</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col justify-between font-sans antialiased text-[#1A1A1A] bg-[#FDFDFD] selection:bg-[#940c10] selection:text-[#FDFDFD]">
    
    <!-- Top Decorative Institutional Bar -->
    <header class="w-full bg-[#940c10] border-b-2 border-[#CE9501] py-3 px-4 sm:px-8 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-bem-logo size="sm" />
                <div>
                    <h1 class="text-sm sm:text-base font-bold text-[#FDFDFD] tracking-wide uppercase leading-tight">
                        PEMIRA BEM UBSI Karawang
                    </h1>
                    <p class="text-xs text-[#CE9501] font-medium tracking-wider">
                        Komisi Pemilihan Umum Mahasiswa
                    </p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-xs font-semibold px-3 py-1 bg-[#7A0A0D] border border-[#CE9501]/40 text-[#FDFDFD] rounded-full">
                <span class="w-2 h-2 rounded-full bg-[#CE9501] animate-pulse"></span>
                <span>Portal Resmi TPS E-Voting</span>
            </div>
        </div>
    </header>

    <!-- Main Auth Content Container -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </main>

    <!-- Institutional Footer -->
    <footer class="w-full border-t border-slate-200 bg-[#FDFDFD] py-4 px-4 text-center text-xs text-slate-500">
        <p class="font-medium text-slate-700">
            &copy; {{ date('Y') }} Badan Eksekutif Mahasiswa (BEM) Universitas BSI Kampus Karawang.
        </p>
        <p class="text-[11px] text-slate-500 mt-1">
            Sistem E-Voting PEMIRA — Asas Langsung, Umum, Bebas, Rahasia, Jujur, dan Adil.
        </p>
    </footer>

</body>
</html>
