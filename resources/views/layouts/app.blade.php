<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - PEMIRA BEM UBSI KARAWANG' : 'PEMIRA BEM UBSI KARAWANG' }}</title>

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

    <!-- Vite Assets with Fallback to CDN -->
    @if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback Tailwind CSS, Alpine.js & Lucide while Vite dev server / build is pending -->
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
        <script src="https://unpkg.com/lucide@latest"></script>
    @endif

    <style>
        [x-cloak] { display: none !important; }
        body, input, button, select, textarea, .swal2-popup {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif !important;
        }

        /* Ambient Wave Animations */
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.65; transform: scale(1); }
            50% { opacity: 0.95; transform: scale(1.04); }
        }
        .animate-pulse-glow {
            animation: pulse-glow 7s ease-in-out infinite;
        }

        /* Custom Sleek Scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 12, 16, 0.4) transparent;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 12, 16, 0.4);
            border-radius: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(206, 149, 1, 0.8);
        }
    </style>
</head>
<body class="min-h-screen {{ ($darkTheme ?? false) ? 'lg:h-screen lg:overflow-hidden' : '' }} flex flex-col {{ $bodyClass ?? ($darkTheme ?? false ? 'bg-[#0c0103] text-white' : 'bg-[#FDFDFD] text-[#1A1A1A]') }} font-sans antialiased selection:bg-[#940c10] selection:text-white relative overflow-x-hidden overflow-y-auto">

    @if($darkTheme ?? false)
    <!-- LUXURY AMBIENT BACKGROUND: DEEP MAROON GRADIENT + FLOWING LIGHT RIBBONS & DOTTED PARTICLE WAVE -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10 bg-[radial-gradient(circle_at_50%_40%,#260408_0%,#130204_45%,#080102_100%)]">
        <!-- Glowing Ambient Lighting Orbs -->
        <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-[#940c10]/35 rounded-full blur-[140px] animate-pulse-glow"></div>
        <div class="absolute -bottom-40 -right-40 w-[650px] h-[650px] bg-[#ce9501]/25 rounded-full blur-[150px] animate-pulse-glow" style="animation-delay: 3s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-[#7a0a0d]/20 rounded-full blur-[160px]"></div>

        <!-- SVG Flowing Curved Waves & Light Ribbons -->
        <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080" preserveAspectRatio="none">
            <defs>
                <linearGradient id="appCrimsonRibbon" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#ff334b" stop-opacity="0.85" />
                    <stop offset="40%" stop-color="#d71920" stop-opacity="0.65" />
                    <stop offset="80%" stop-color="#7a0a0d" stop-opacity="0.2" />
                    <stop offset="100%" stop-color="#300306" stop-opacity="0" />
                </linearGradient>

                <linearGradient id="appGoldRibbon" x1="0%" y1="0%" x2="100%" y2="80%">
                    <stop offset="0%" stop-color="#ffe27a" stop-opacity="0.9" />
                    <stop offset="30%" stop-color="#e5a900" stop-opacity="0.75" />
                    <stop offset="70%" stop-color="#b58200" stop-opacity="0.4" />
                    <stop offset="100%" stop-color="#7a0a0d" stop-opacity="0" />
                </linearGradient>

                <linearGradient id="appDotGrad" x1="0%" y1="100%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#ffe680" stop-opacity="0.85" />
                    <stop offset="50%" stop-color="#e5a900" stop-opacity="0.55" />
                    <stop offset="100%" stop-color="#d71920" stop-opacity="0.15" />
                </linearGradient>

                <filter id="appSoftGlow" x="-20%" y="-20%" width="140%" height="140%">
                    <feGaussianBlur stdDeviation="8" result="blur" />
                    <feComposite in="SourceGraphic" in2="blur" operator="over" />
                </filter>
            </defs>

            <!-- Top Left Silk Wave Ribbon -->
            <path d="M -100,180 C 200,60 500,280 250,550 C 100,700 -50,650 -100,600 Z" fill="url(#appCrimsonRibbon)" filter="url(#appSoftGlow)" opacity="0.45" />
            <path d="M -80,240 C 220,120 420,310 210,510" fill="none" stroke="url(#appGoldRibbon)" stroke-width="2.5" opacity="0.6" filter="url(#appSoftGlow)" />
            <path d="M -100,280 C 180,180 360,350 160,540" fill="none" stroke="#ff4d5a" stroke-width="1.5" opacity="0.4" />

            <!-- Bottom Right Golden & Crimson Particle Mesh / Dotted Wave Flow -->
            <path d="M 1200,1180 C 1350,920 1600,750 2050,700 L 2050,1180 Z" fill="url(#appCrimsonRibbon)" opacity="0.3" filter="url(#appSoftGlow)" />
            <path d="M 1150,1150 C 1380,880 1650,720 2080,680" fill="none" stroke="url(#appGoldRibbon)" stroke-width="3" opacity="0.85" filter="url(#appSoftGlow)" />
            <path d="M 1200,1180 C 1420,910 1680,760 2100,730" fill="none" stroke="#ff3a4d" stroke-width="2" opacity="0.5" />

            <!-- 3D Dotted Particle Wave Matrix (Bottom Right) -->
            <g fill="url(#appDotGrad)" opacity="0.6">
                <circle cx="1320" cy="990" r="1.8" /><circle cx="1360" cy="970" r="1.9" /><circle cx="1400" cy="948" r="2.0" /><circle cx="1440" cy="925" r="2.1" /><circle cx="1480" cy="900" r="2.2" /><circle cx="1520" cy="875" r="2.3" /><circle cx="1560" cy="850" r="2.4" /><circle cx="1600" cy="828" r="2.4" /><circle cx="1640" cy="808" r="2.3" /><circle cx="1680" cy="790" r="2.2" /><circle cx="1720" cy="775" r="2.1" /><circle cx="1760" cy="762" r="2.0" /><circle cx="1800" cy="752" r="1.9" /><circle cx="1840" cy="744" r="1.8" /><circle cx="1880" cy="738" r="1.7" /><circle cx="1920" cy="734" r="1.6" />
                <circle cx="1340" cy="1015" r="1.9" /><circle cx="1380" cy="992" r="2.0" /><circle cx="1420" cy="968" r="2.1" /><circle cx="1460" cy="943" r="2.2" /><circle cx="1500" cy="918" r="2.3" /><circle cx="1540" cy="892" r="2.4" /><circle cx="1580" cy="868" r="2.4" /><circle cx="1620" cy="845" r="2.3" /><circle cx="1660" cy="824" r="2.2" /><circle cx="1700" cy="805" r="2.1" /><circle cx="1740" cy="790" r="2.0" /><circle cx="1780" cy="778" r="1.9" /><circle cx="1820" cy="768" r="1.8" /><circle cx="1860" cy="760" r="1.7" /><circle cx="1900" cy="754" r="1.6" />
                <circle cx="1360" cy="1040" r="2.0" /><circle cx="1400" cy="1015" r="2.1" /><circle cx="1440" cy="988" r="2.2" /><circle cx="1480" cy="962" r="2.3" /><circle cx="1520" cy="936" r="2.4" /><circle cx="1560" cy="910" r="2.4" /><circle cx="1600" cy="885" r="2.3" /><circle cx="1640" cy="862" r="2.2" /><circle cx="1680" cy="840" r="2.1" /><circle cx="1720" cy="820" r="2.0" /><circle cx="1760" cy="803" r="1.9" /><circle cx="1800" cy="790" r="1.8" /><circle cx="1840" cy="780" r="1.7" /><circle cx="1880" cy="772" r="1.6" />
                <circle cx="1380" cy="1065" r="2.1" /><circle cx="1420" cy="1038" r="2.2" /><circle cx="1460" cy="1010" r="2.3" /><circle cx="1500" cy="982" r="2.4" /><circle cx="1540" cy="955" r="2.4" /><circle cx="1580" cy="928" r="2.3" /><circle cx="1620" cy="902" r="2.2" /><circle cx="1660" cy="878" r="2.1" /><circle cx="1700" cy="855" r="2.0" /><circle cx="1740" cy="834" r="1.9" /><circle cx="1780" cy="816" r="1.8" /><circle cx="1820" cy="802" r="1.7" /><circle cx="1860" cy="791" r="1.6" />
                <circle cx="1440" cy="1060" r="2.2" /><circle cx="1480" cy="1030" r="2.3" /><circle cx="1520" cy="1002" r="2.4" /><circle cx="1560" cy="974" r="2.3" /><circle cx="1600" cy="946" r="2.2" /><circle cx="1640" cy="920" r="2.1" /><circle cx="1680" cy="895" r="2.0" /><circle cx="1720" cy="872" r="1.9" /><circle cx="1760" cy="850" r="1.8" /><circle cx="1800" cy="832" r="1.7" /><circle cx="1840" cy="818" r="1.6" />
                <circle cx="1500" cy="1050" r="2.3" /><circle cx="1540" cy="1020" r="2.3" /><circle cx="1580" cy="990" r="2.2" /><circle cx="1620" cy="962" r="2.1" /><circle cx="1660" cy="935" r="2.0" /><circle cx="1700" cy="910" r="1.9" /><circle cx="1740" cy="886" r="1.8" /><circle cx="1780" cy="865" r="1.7" /><circle cx="1820" cy="848" r="1.6" />
            </g>
        </svg>
    </div>
    @endif

    @if(!($hideNavbar ?? false))
    <!-- Top Institutional Banner (Ramping & Proporsional H-16) -->
    <header class="sticky top-0 z-40 {{ ($darkTheme ?? false) ? 'bg-[#180306]/85 backdrop-blur-xl border-b border-white/10 shadow-lg shadow-black/40 text-white' : 'bg-gradient-to-r from-[#940c10] via-[#7A0A0D] to-[#940c10] text-[#FDFDFD] border-b-2 border-[#CE9501] shadow-lg shadow-[#940c10]/20 backdrop-blur-md' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo Section -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI" class="w-10 h-10 sm:w-11 sm:h-11 object-contain drop-shadow">
                    <div class="flex flex-col">
                        <span class="text-sm sm:text-base font-bold text-white leading-tight">PEMIRA BEM UBSI</span>
                        <span class="text-[11px] text-slate-300 font-normal hidden sm:block">Universitas Bina Sarana Informatika Karawang</span>
                    </div>
                </div>

                <!-- User Profile, Live Clock & Actions Section -->
                <div class="flex items-center gap-4 sm:gap-6">
                    
                    <!-- Hari, Tanggal & Jam (Live Realtime Horizontal Sesuai Desain) -->
                    <div x-data="liveClock()" class="hidden md:flex items-center gap-4 text-xs text-slate-200">
                        <!-- Tanggal -->
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span x-text="dateString" class="font-medium">
                                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                            </span>
                        </div>

                        <!-- Jam -->
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span x-text="timeString" class="font-mono font-bold tracking-wider">
                                {{ \Carbon\Carbon::now()->format('H:i:s') }} WIB
                            </span>
                        </div>
                    </div>

                    @if(Auth::guard('student')->check())
                        @php $student = Auth::guard('student')->user(); @endphp
                        
                        <!-- Data Mahasiswa (Nama & NIM) Bersebelahan dengan Logout -->
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="text-right">
                                <p class="text-xs sm:text-sm font-bold text-white leading-tight">{{ $student->name }}</p>
                                <p class="text-[11px] font-semibold text-[#E5A900] tracking-wider mt-0.5">NIM: {{ $student->nim }}</p>
                            </div>
                            <form id="logout-form-student" action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="button" onclick="confirmLogout('logout-form-student')" title="Keluar / Logout" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white flex items-center justify-center transition-all cursor-pointer">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @elseif(Auth::guard('admin')->check())
                        @php $user = Auth::guard('admin')->user(); @endphp

                        <!-- Garis lurus pembatas vertikal -->
                        <div class="h-9 w-px bg-white/25 hidden sm:block"></div>

                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="text-right">
                                <p class="text-xs sm:text-sm font-semibold text-white leading-tight">{{ $user->name }}</p>
                                <span class="inline-block mt-0.5 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-amber-400 text-slate-900">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </div>
                            <a href="{{ $user->isAdmin() ? route('admin.dashboard') : route('committee.dashboard') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/15 hover:bg-white/25 text-white text-xs font-semibold transition-all">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                <span class="hidden md:inline">Panel {{ $user->isAdmin() ? 'Admin' : 'TPS' }}</span>
                            </a>
                            <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="button" onclick="confirmLogout('logout-form-admin')" title="Keluar / Logout" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white flex items-center justify-center transition-all cursor-pointer">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#CE9501] hover:bg-[#B58200] text-[#1A1A1A] text-xs sm:text-sm font-semibold shadow-md transition-all">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            <span>Masuk Sistem</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>
    @endif

    <!-- Global Flash Notification Messages -->
    @if(session('success') || session('error') || session('status'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-3">
        @if(session('success'))
            <div class="rounded-xl border border-emerald-500/40 bg-emerald-950/60 backdrop-blur-md p-3.5 text-emerald-100 flex items-start gap-3 shadow-lg mb-3">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-500/40 bg-red-950/60 backdrop-blur-md p-3.5 text-red-100 flex items-start gap-3 shadow-lg mb-3">
                <i data-lucide="alert-octagon" class="w-5 h-5 text-red-400 shrink-0 mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('status'))
            <div class="rounded-xl border border-emerald-500/40 bg-emerald-950/60 backdrop-blur-md p-3.5 text-emerald-100 flex items-start gap-3 shadow-lg mb-3">
                <i data-lucide="info" class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('status') }}</div>
            </div>
        @endif
    </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-1 min-h-0 flex flex-col {{ ($darkTheme ?? false) ? 'overflow-y-auto custom-scrollbar justify-center' : '' }}">
        @yield('content')
    </main>

    <!-- Initialize Lucide Icons & Live Clock -->
    <script>
        function liveClock() {
            return {
                dateString: '',
                timeString: '',
                init() {
                    this.update();
                    setInterval(() => this.update(), 1000);
                },
                update() {
                    const now = new Date();
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                    const dayName = days[now.getDay()];
                    const date = now.getDate();
                    const monthName = months[now.getMonth()];
                    const year = now.getFullYear();

                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');

                    this.dateString = `${dayName}, ${date} ${monthName} ${year}`;
                    this.timeString = `${hours}:${minutes}:${seconds} WIB`;
                }
            };
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide && window.lucide.createIcons) {
                window.lucide.createIcons();
            }
        });

        // Dialog Konfirmasi Keluar / Logout Menggunakan SweetAlert2
        function confirmLogout(formOrId) {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                html: `
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif;" class="text-center">
                        <p class="text-xs sm:text-sm text-slate-300 font-normal leading-relaxed mt-1">
                            Apakah Anda yakin ingin mengakhiri sesi dan keluar dari sistem?
                        </p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Iya, Keluar Sekarang',
                cancelButtonText: 'Batal',
                focusCancel: true,
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-3xl border border-white/10 bg-[#1e0306] text-white shadow-2xl p-6 sm:p-7 max-w-md w-full',
                    title: 'text-xl font-bold text-white p-0 m-0',
                    htmlContainer: 'p-0 m-0 mt-3',
                    actions: 'mt-5 flex items-center justify-center gap-3 w-full',
                    confirmButton: 'whitespace-nowrap px-5 py-3 rounded-xl font-semibold text-xs sm:text-sm text-white bg-[#940c10] hover:bg-[#7A0A0D] shadow-md transition-all cursor-pointer',
                    cancelButton: 'whitespace-nowrap px-5 py-3 rounded-xl font-semibold text-xs sm:text-sm text-slate-300 bg-white/10 hover:bg-white/20 transition-all cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengeluarkan Sesi...',
                        text: 'Mohon tunggu, sesi Anda sedang ditutup dengan aman.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-3xl border border-white/10 bg-[#1e0306] text-white shadow-2xl p-6',
                            title: 'text-lg font-bold text-white'
                        },
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    const form = typeof formOrId === 'string' ? document.getElementById(formOrId) : formOrId;
                    if (form) {
                        form.submit();
                    }
                }
            });
        }
    </script>
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
