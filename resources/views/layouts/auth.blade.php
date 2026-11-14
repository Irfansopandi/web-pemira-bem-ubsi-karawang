<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Login' }} - PEMIRA BEM UBSI KARAWANG</title>

    <!-- Favicon Resmi BEM UBSI Karawang -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">

    <!-- Google Fonts: Plus Jakarta Sans -->
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
        html, body {
            height: 100%;
            overflow-x: hidden;
        }
        body, input, button, select, textarea {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }

        /* Ambient Wave Animations */
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.65; transform: scale(1); }
            50% { opacity: 0.95; transform: scale(1.04); }
        }
        @keyframes float-ribbon {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
        }
        .animate-pulse-glow {
            animation: pulse-glow 7s ease-in-out infinite;
        }
        .animate-float-ribbon {
            animation: float-ribbon 12s ease-in-out infinite;
        }
    </style>
</head>
<body class="h-screen w-full flex items-center justify-center font-sans antialiased text-[#1A1A1A] bg-[#0c0103] p-3 sm:p-4 lg:p-5 selection:bg-[#940c10] selection:text-[#FDFDFD] relative overflow-y-auto lg:overflow-hidden">

    <!-- LUXURY AMBIENT BACKGROUND: DEEP MAROON GRADIENT + FLOWING LIGHT RIBBONS & DOTTED PARTICLE WAVE -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10 bg-[radial-gradient(circle_at_50%_40%,#260408_0%,#130204_45%,#080102_100%)]">
        
        <!-- Glowing Ambient Lighting Orbs -->
        <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-[#940c10]/35 rounded-full blur-[140px] animate-pulse-glow"></div>
        <div class="absolute -bottom-40 -right-40 w-[650px] h-[650px] bg-[#ce9501]/25 rounded-full blur-[150px] animate-pulse-glow" style="animation-delay: 3s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-[#7a0a0d]/20 rounded-full blur-[160px]"></div>

        <!-- SVG Flowing Curved Waves & Light Ribbons (Matching Reference Image) -->
        <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080" preserveAspectRatio="none">
            <defs>
                <!-- Crimson Silk Ribbon Gradient -->
                <linearGradient id="crimsonRibbonLayout" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#ff334b" stop-opacity="0.85" />
                    <stop offset="40%" stop-color="#d71920" stop-opacity="0.65" />
                    <stop offset="80%" stop-color="#7a0a0d" stop-opacity="0.2" />
                    <stop offset="100%" stop-color="#300306" stop-opacity="0" />
                </linearGradient>

                <!-- Golden Edge Ribbon Gradient -->
                <linearGradient id="goldRibbonLayout" x1="0%" y1="0%" x2="100%" y2="80%">
                    <stop offset="0%" stop-color="#ffe27a" stop-opacity="0.9" />
                    <stop offset="30%" stop-color="#e5a900" stop-opacity="0.75" />
                    <stop offset="70%" stop-color="#b58200" stop-opacity="0.4" />
                    <stop offset="100%" stop-color="#7a0a0d" stop-opacity="0" />
                </linearGradient>

                <!-- Dotted Matrix Pattern Gradient -->
                <linearGradient id="dotGradLayout" x1="0%" y1="100%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#ffe680" stop-opacity="0.85" />
                    <stop offset="50%" stop-color="#e5a900" stop-opacity="0.55" />
                    <stop offset="100%" stop-color="#d71920" stop-opacity="0.15" />
                </linearGradient>

                <!-- Glow Filter -->
                <filter id="softGlowLayout" x="-20%" y="-20%" width="140%" height="140%">
                    <feGaussianBlur stdDeviation="8" result="blur" />
                    <feComposite in="SourceGraphic" in2="blur" operator="over" />
                </filter>
            </defs>

            <!-- Top Left Silk Wave Ribbon -->
            <path d="M -100,180 C 200,60 500,280 250,550 C 100,700 -50,650 -100,600 Z" fill="url(#crimsonRibbonLayout)" filter="url(#softGlowLayout)" opacity="0.45" />
            <path d="M -80,240 C 220,120 420,310 210,510" fill="none" stroke="url(#goldRibbonLayout)" stroke-width="2.5" opacity="0.6" filter="url(#softGlowLayout)" />
            <path d="M -100,280 C 180,180 360,350 160,540" fill="none" stroke="#ff4d5a" stroke-width="1.5" opacity="0.4" />

            <!-- Bottom Right Golden & Crimson Particle Mesh / Dotted Wave Flow -->
            <!-- Golden Wave Ribbon Curve -->
            <path d="M 1200,1180 C 1350,920 1600,750 2050,700 L 2050,1180 Z" fill="url(#crimsonRibbonLayout)" opacity="0.3" filter="url(#softGlowLayout)" />
            <path d="M 1150,1150 C 1380,880 1650,720 2080,680" fill="none" stroke="url(#goldRibbonLayout)" stroke-width="3" opacity="0.85" filter="url(#softGlowLayout)" />
            <path d="M 1200,1180 C 1420,910 1680,760 2100,730" fill="none" stroke="#ff3a4d" stroke-width="2" opacity="0.5" />

            <!-- 3D Dotted Particle Wave Matrix (Bottom Right) -->
            <g fill="url(#dotGradLayout)" opacity="0.6">
                <!-- Row 1 -->
                <circle cx="1320" cy="990" r="1.8" /><circle cx="1360" cy="970" r="1.9" /><circle cx="1400" cy="948" r="2.0" /><circle cx="1440" cy="925" r="2.1" /><circle cx="1480" cy="900" r="2.2" /><circle cx="1520" cy="875" r="2.3" /><circle cx="1560" cy="850" r="2.4" /><circle cx="1600" cy="828" r="2.4" /><circle cx="1640" cy="808" r="2.3" /><circle cx="1680" cy="790" r="2.2" /><circle cx="1720" cy="775" r="2.1" /><circle cx="1760" cy="762" r="2.0" /><circle cx="1800" cy="752" r="1.9" /><circle cx="1840" cy="744" r="1.8" /><circle cx="1880" cy="738" r="1.7" /><circle cx="1920" cy="734" r="1.6" />
                <!-- Row 2 -->
                <circle cx="1340" cy="1015" r="1.9" /><circle cx="1380" cy="992" r="2.0" /><circle cx="1420" cy="968" r="2.1" /><circle cx="1460" cy="943" r="2.2" /><circle cx="1500" cy="918" r="2.3" /><circle cx="1540" cy="892" r="2.4" /><circle cx="1580" cy="868" r="2.4" /><circle cx="1620" cy="845" r="2.3" /><circle cx="1660" cy="824" r="2.2" /><circle cx="1700" cy="805" r="2.1" /><circle cx="1740" cy="790" r="2.0" /><circle cx="1780" cy="778" r="1.9" /><circle cx="1820" cy="768" r="1.8" /><circle cx="1860" cy="760" r="1.7" /><circle cx="1900" cy="754" r="1.6" />
                <!-- Row 3 -->
                <circle cx="1360" cy="1040" r="2.0" /><circle cx="1400" cy="1015" r="2.1" /><circle cx="1440" cy="988" r="2.2" /><circle cx="1480" cy="962" r="2.3" /><circle cx="1520" cy="936" r="2.4" /><circle cx="1560" cy="910" r="2.4" /><circle cx="1600" cy="885" r="2.3" /><circle cx="1640" cy="862" r="2.2" /><circle cx="1680" cy="840" r="2.1" /><circle cx="1720" cy="820" r="2.0" /><circle cx="1760" cy="803" r="1.9" /><circle cx="1800" cy="790" r="1.8" /><circle cx="1840" cy="780" r="1.7" /><circle cx="1880" cy="772" r="1.6" />
                <!-- Row 4 -->
                <circle cx="1380" cy="1065" r="2.1" /><circle cx="1420" cy="1038" r="2.2" /><circle cx="1460" cy="1010" r="2.3" /><circle cx="1500" cy="982" r="2.4" /><circle cx="1540" cy="955" r="2.4" /><circle cx="1580" cy="928" r="2.3" /><circle cx="1620" cy="902" r="2.2" /><circle cx="1660" cy="878" r="2.1" /><circle cx="1700" cy="855" r="2.0" /><circle cx="1740" cy="834" r="1.9" /><circle cx="1780" cy="816" r="1.8" /><circle cx="1820" cy="802" r="1.7" /><circle cx="1860" cy="791" r="1.6" />
                <!-- Row 5 -->
                <circle cx="1440" cy="1060" r="2.2" /><circle cx="1480" cy="1030" r="2.3" /><circle cx="1520" cy="1002" r="2.4" /><circle cx="1560" cy="974" r="2.3" /><circle cx="1600" cy="946" r="2.2" /><circle cx="1640" cy="920" r="2.1" /><circle cx="1680" cy="895" r="2.0" /><circle cx="1720" cy="872" r="1.9" /><circle cx="1760" cy="850" r="1.8" /><circle cx="1800" cy="832" r="1.7" /><circle cx="1840" cy="818" r="1.6" />
                <!-- Row 6 -->
                <circle cx="1500" cy="1050" r="2.3" /><circle cx="1540" cy="1020" r="2.3" /><circle cx="1580" cy="990" r="2.2" /><circle cx="1620" cy="962" r="2.1" /><circle cx="1660" cy="935" r="2.0" /><circle cx="1700" cy="910" r="1.9" /><circle cx="1740" cy="886" r="1.8" /><circle cx="1780" cy="865" r="1.7" /><circle cx="1820" cy="848" r="1.6" />
            </g>
        </svg>
    </div>

    <!-- Main Auth Content Container -->
    <main class="w-full max-w-4xl xl:max-w-5xl my-auto relative z-10">
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
