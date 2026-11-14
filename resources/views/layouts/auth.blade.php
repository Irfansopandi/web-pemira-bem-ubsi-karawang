<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Login' }} - PEMIRA BEM UBSI KARAWANG</title>

    <!-- Favicon Resmi BEM UBSI Karawang (Full-Bleed Maksimal & Tajam) -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">

    <!-- Google Fonts: Plus Jakarta Sans (Modern, Smooth & Clean) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback Tailwind CSS & Alpine.js while Vite dev server / build is pending -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        },
                        colors: {
                            'ubsi-red': '#940c10',
                            'ubsi-red-dark': '#7A0A0D',
                            'ubsi-gold': '#CE9501',
                            'ubsi-gold-dark': '#B58200',
                            'ubsi-white': '#FDFDFD',
                        }
                    }
                }
            }
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif

    <style>
        [x-cloak] { display: none !important; }
        body, input, button, select, textarea {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }

        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) translateX(0px) scale(1); }
            50% { transform: translateY(-38px) translateX(18px) scale(1.08); }
        }
        @keyframes float-reverse {
            0%, 100% { transform: translateY(0px) translateX(0px) scale(1); }
            50% { transform: translateY(32px) translateX(-22px) scale(0.95); }
        }
        @keyframes float-diagonal {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-30px) translateX(-30px); }
        }
        @keyframes float-drift {
            0%, 100% { transform: translateY(0px) translateX(0px) scale(1); }
            50% { transform: translateY(-20px) translateX(25px) scale(1.05); }
        }
        @keyframes float-pulse {
            0%, 100% { transform: scale(1) translateY(0); opacity: 0.6; }
            50% { transform: scale(1.15) translateY(-15px); opacity: 0.9; }
        }
        @keyframes float-gentle {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(22px) translateX(16px); }
        }

        .animate-bubble-1 { animation: float-slow 8s ease-in-out infinite; }
        .animate-bubble-2 { animation: float-reverse 10s ease-in-out infinite 1s; }
        .animate-bubble-3 { animation: float-diagonal 12s ease-in-out infinite 2s; }
        .animate-bubble-4 { animation: float-drift 9s ease-in-out infinite 1.5s; }
        .animate-bubble-5 { animation: float-reverse 11s ease-in-out infinite 2.5s; }
        .animate-bubble-6 { animation: float-pulse 7s ease-in-out infinite 0.5s; }
        .animate-bubble-7 { animation: float-gentle 13s ease-in-out infinite 3s; }
        .animate-bubble-8 { animation: float-slow 10.5s ease-in-out infinite 2s; }
    </style>
</head>
<body class="min-h-full flex items-center justify-center font-sans antialiased text-[#1A1A1A] bg-gradient-to-br from-[#1a0204] via-[#3a060a] to-[#0f172a] p-4 sm:p-6 lg:p-8 selection:bg-[#940c10] selection:text-[#FDFDFD] relative overflow-x-hidden">
    
    <!-- BACKGROUND AMBIENT GLOW & FLOATING BUBBLES BERVARIASI -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <!-- Ambient Glowing Orbs Besar (Pencahayaan Utama) -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#940c10]/45 rounded-full blur-[110px] animate-bubble-1"></div>
        <div class="absolute top-1/4 -right-24 w-80 h-80 bg-[#CE9501]/30 rounded-full blur-[100px] animate-bubble-2"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-[#7A0A0D]/50 rounded-full blur-[120px] animate-bubble-3"></div>
        <div class="absolute bottom-10 -left-20 w-72 h-72 bg-[#CE9501]/25 rounded-full blur-[90px] animate-bubble-4"></div>
        <div class="absolute top-1/2 right-1/4 w-64 h-64 bg-[#940c10]/20 rounded-full blur-[100px] animate-bubble-6"></div>

        <!-- Floating Glassmorphic Bubble Spheres (Banyak & Melayang) -->
        <!-- Sisi Kiri Atas -->
        <div class="absolute top-12 left-[12%] w-28 h-28 rounded-full bg-white/10 border border-white/20 backdrop-blur-md shadow-lg shadow-[#940c10]/20 animate-bubble-1"></div>
        <div class="absolute top-28 left-[6%] w-14 h-14 rounded-full bg-[#CE9501]/15 border border-[#CE9501]/30 backdrop-blur-sm animate-bubble-4"></div>
        <div class="absolute top-8 left-[28%] w-10 h-10 rounded-full bg-white/15 border border-white/30 backdrop-blur-sm animate-bubble-6"></div>

        <!-- Sisi Kanan Atas -->
        <div class="absolute top-16 right-[14%] w-32 h-32 rounded-full bg-white/8 border border-[#CE9501]/35 backdrop-blur-md shadow-lg shadow-[#CE9501]/20 animate-bubble-2"></div>
        <div class="absolute top-36 right-[8%] w-16 h-16 rounded-full bg-gradient-to-br from-[#940c10]/25 to-white/5 border border-white/20 backdrop-blur-sm animate-bubble-5"></div>
        <div class="absolute top-10 right-[32%] w-12 h-12 rounded-full bg-white/15 border border-white/25 backdrop-blur-sm animate-bubble-3"></div>

        <!-- Sisi Tengah Kiri & Kanan (Dekat Card) -->
        <div class="absolute top-1/2 left-[4%] w-20 h-20 rounded-full bg-white/10 border border-white/25 backdrop-blur-md shadow-md animate-bubble-3"></div>
        <div class="absolute top-[58%] left-[12%] w-10 h-10 rounded-full bg-[#CE9501]/20 border border-[#CE9501]/40 backdrop-blur-sm animate-bubble-7"></div>
        <div class="absolute top-1/2 right-[5%] w-24 h-24 rounded-full bg-gradient-to-tr from-[#940c10]/30 to-white/10 border border-white/25 backdrop-blur-md animate-bubble-8"></div>
        <div class="absolute top-[42%] right-[10%] w-12 h-12 rounded-full bg-white/10 border border-white/20 backdrop-blur-sm animate-bubble-4"></div>

        <!-- Sisi Bawah Kiri -->
        <div class="absolute bottom-16 left-[18%] w-24 h-24 rounded-full bg-white/10 border border-white/20 backdrop-blur-md shadow-md animate-bubble-5"></div>
        <div class="absolute bottom-32 left-[8%] w-14 h-14 rounded-full bg-[#CE9501]/15 border border-[#CE9501]/30 backdrop-blur-sm animate-bubble-2"></div>
        <div class="absolute bottom-8 left-[30%] w-8 h-8 rounded-full bg-white/20 border border-white/30 backdrop-blur-sm animate-bubble-6"></div>

        <!-- Sisi Bawah Kanan -->
        <div class="absolute bottom-24 right-[22%] w-20 h-20 rounded-full bg-gradient-to-br from-[#CE9501]/20 to-transparent border border-[#CE9501]/35 backdrop-blur-sm animate-bubble-1"></div>
        <div class="absolute bottom-12 right-[12%] w-28 h-28 rounded-full bg-white/10 border border-white/25 backdrop-blur-md shadow-lg animate-bubble-7"></div>
        <div class="absolute bottom-40 right-[6%] w-10 h-10 rounded-full bg-white/15 border border-white/25 backdrop-blur-sm animate-bubble-3"></div>
        <div class="absolute bottom-6 right-[35%] w-12 h-12 rounded-full bg-[#940c10]/25 border border-white/20 backdrop-blur-sm animate-bubble-8"></div>
    </div>

    <!-- Main Auth Content Container -->
    <main class="w-full max-w-5xl my-auto relative z-10">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- SweetAlert2 Toast Notifications (Top-End) -->
    @if (session('status') || session('success') || session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                @if (session('status'))
                    Toast.fire({
                        icon: 'success',
                        title: "{{ session('status') }}"
                    });
                @elseif (session('success'))
                    Toast.fire({
                        icon: 'success',
                        title: "{{ session('success') }}"
                    });
                @elseif (session('error'))
                    Toast.fire({
                        icon: 'error',
                        title: "{{ session('error') }}"
                    });
                @endif
            });
        </script>
    @endif

</body>
</html>
