<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - Admin PEMIRA BEM UBSI' : 'Panel Administrator - PEMIRA BEM UBSI Karawang' }}</title>

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

    <!-- Vite Assets / Fallback Tailwind CSS + Lucide Icons -->
    @if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
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

        /* Critical Sidebar & Instant Scroll Rules (Zero-Flicker) */
        #admin-sidebar-nav {
            scroll-behavior: auto !important;
            overscroll-behavior: contain !important;
        }

        /* Smooth SPA Content Transition */
        .admin-main-view {
            transition: opacity 0.15s ease-in-out;
        }
        .admin-main-view.is-loading {
            opacity: 0.35;
            pointer-events: none;
        }

        /* Custom Modern Scrollbar */
        .admin-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 12, 16, 0.3) transparent;
        }
        .admin-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .admin-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .admin-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 12, 16, 0.3);
            border-radius: 9999px;
        }
        .admin-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 12, 16, 0.6);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 font-sans antialiased selection:bg-[#940c10] selection:text-white"
    x-data="{ sidebarOpen: false }">

    <!-- ======================================================== -->
    <!-- MOBILE SIDEBAR BACKDROP OVERLAY                          -->
    <!-- ======================================================== -->
    <div 
        x-show="sidebarOpen" 
        x-cloak 
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/60 backdrop-blur-xs z-40 lg:hidden">
    </div>

    <!-- ======================================================== -->
    <!-- SIDEBAR KIRI (OFFICIAL UBSI INSTITUTIONAL DARK THEME)     -->
    <!-- ======================================================== -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed top-0 bottom-0 left-0 z-50 w-64 xl:w-72 bg-[#120204] text-white border-r border-white/10 flex flex-col justify-between transition-transform duration-300 ease-in-out shadow-2xl shadow-black/80 lg:shadow-none">
        
        <!-- Header Sidebar: 3 Logo Resmi & Brand Title -->
        <div class="shrink-0">
            <div class="relative p-5 xl:p-6 border-b border-white/10 bg-gradient-to-b from-[#220308] to-[#120204]">
                <!-- Tombol Tutup Sidebar di Mobile -->
                <button @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <!-- 3 Logo Resmi UBSI Karawang (Tengah / Centered) -->
                <div class="flex items-center justify-center gap-3">
                    <img src="{{ asset('images/logo-ubsi.png') }}" alt="Logo Universitas BSI" class="w-9 h-9 xl:w-10 xl:h-10 object-contain drop-shadow-sm hover:scale-105 transition-transform">
                    <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI Karawang" class="w-9 h-9 xl:w-10 xl:h-10 object-contain drop-shadow-sm hover:scale-105 transition-transform">
                    <img src="{{ asset('images/logo-kabinet.png') }}" alt="Logo Kabinet PEMIRA" class="w-9 h-9 xl:w-10 xl:h-10 object-contain drop-shadow-sm hover:scale-105 transition-transform">
                </div>

                <!-- Judul Brand Sidebar (Tengah / Centered) -->
                <div class="mt-3 text-center">
                    <h2 class="text-sm xl:text-base font-extrabold tracking-wider text-white uppercase leading-tight">
                        PEMIRA BEM UBSI
                    </h2>
                    <p class="text-[10px] xl:text-[11px] font-bold text-[#E5A900] tracking-widest uppercase mt-0.5">
                        KAMPUS KARAWANG
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigasi Menu Sidebar (Flex-1 Scrollable) -->
        <nav id="admin-sidebar-nav" class="flex-1 p-3.5 xl:p-4 space-y-5 xl:space-y-6 overflow-y-auto admin-scrollbar">
            
            <!-- ======================================================== -->
            <!-- 1. MENU UTAMA                                            -->
            <!-- ======================================================== -->
            <div class="space-y-1">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Menu Utama
                </span>

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 xl:px-3.5 py-2 xl:py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-[#940C10] to-[#7A0A0D] text-white shadow-md shadow-red-950/60 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/[0.07]' }}">
                    <svg class="w-4 h-4 xl:w-4.5 xl:h-4.5 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-[#CE9501]' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="9" rx="1"></rect>
                        <rect x="14" y="3" width="7" height="5" rx="1"></rect>
                        <rect x="14" y="12" width="7" height="9" rx="1"></rect>
                        <rect x="3" y="16" width="7" height="5" rx="1"></rect>
                    </svg>
                    <span class="truncate">Dashboard</span>
                </a>
            </div>

            <!-- ======================================================== -->
            <!-- 2. MANAJEMEN PEMILIHAN                                   -->
            <!-- ======================================================== -->
            <div class="space-y-1">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Manajemen Pemilihan
                </span>

                <!-- Kelola Periode -->
                <a href="{{ route('admin.periods.index') }}"
                    class="flex items-center gap-3 px-3 xl:px-3.5 py-2 xl:py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.periods.*') ? 'bg-gradient-to-r from-[#940C10] to-[#7A0A0D] text-white shadow-md shadow-red-950/60 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/[0.07]' }}">
                    <svg class="w-4 h-4 xl:w-4.5 xl:h-4.5 shrink-0 {{ request()->routeIs('admin.periods.*') ? 'text-[#CE9501]' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                        <polyline points="12 14 12 17 14 17"></polyline>
                    </svg>
                    <span class="truncate">Kelola Periode</span>
                </a>

                <!-- Kelola Paslon -->
                <a href="{{ route('admin.candidates.index') }}"
                    class="flex items-center gap-3 px-3 xl:px-3.5 py-2 xl:py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.candidates.*') ? 'bg-gradient-to-r from-[#940C10] to-[#7A0A0D] text-white shadow-md shadow-red-950/60 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/[0.07]' }}">
                    <svg class="w-4 h-4 xl:w-4.5 xl:h-4.5 shrink-0 {{ request()->routeIs('admin.candidates.*') ? 'text-[#CE9501]' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="truncate">Kelola Paslon</span>
                </a>

                <!-- Kelola Mahasiswa -->
                <a href="{{ route('admin.voters.index') }}"
                    class="flex items-center gap-3 px-3 xl:px-3.5 py-2 xl:py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.voters.*') ? 'bg-gradient-to-r from-[#940C10] to-[#7A0A0D] text-white shadow-md shadow-red-950/60 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/[0.07]' }}">
                    <svg class="w-4 h-4 xl:w-4.5 xl:h-4.5 shrink-0 {{ request()->routeIs('admin.voters.*') ? 'text-[#CE9501]' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                    <span class="truncate">Kelola Mahasiswa</span>
                </a>

                <!-- Verifikasi Kehadiran Pemilih (Registrasi & Tanda Tangan TPS) -->
                <a href="{{ route('admin.verification.index') }}"
                    class="flex items-center gap-3 px-3 xl:px-3.5 py-2 xl:py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.verification.*') ? 'bg-gradient-to-r from-[#940C10] to-[#7A0A0D] text-white shadow-md shadow-red-950/60 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/[0.07]' }}">
                    <svg class="w-4 h-4 xl:w-4.5 xl:h-4.5 shrink-0 {{ request()->routeIs('admin.verification.*') ? 'text-[#CE9501]' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <polyline points="16 11 18 13 22 9"></polyline>
                    </svg>
                    <span class="truncate">Verifikasi Kehadiran</span>
                </a>
            </div>

            <!-- ======================================================== -->
            <!-- 4. MONITORING & LAPORAN                                   -->
            <!-- ======================================================== -->
            <div class="space-y-1">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Monitoring & Laporan
                </span>

                <!-- Monitoring Pemungutan Suara -->
                <a href="{{ route('admin.elections.monitoring') }}"
                    class="flex items-center gap-3 px-3 xl:px-3.5 py-2 xl:py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.elections.monitoring') ? 'bg-gradient-to-r from-[#940C10] to-[#7A0A0D] text-white shadow-md shadow-red-950/60 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/[0.07]' }}">
                    <svg class="w-4 h-4 xl:w-4.5 xl:h-4.5 shrink-0 {{ request()->routeIs('admin.elections.monitoring') ? 'text-[#CE9501]' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                    <span class="truncate">Monitoring Pemungutan Suara</span>
                </a>

                <!-- Hasil Pemilihan -->
                <a href="{{ route('admin.elections.results') }}"
                    class="flex items-center gap-3 px-3 xl:px-3.5 py-2 xl:py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.elections.results') ? 'bg-gradient-to-r from-[#940C10] to-[#7A0A0D] text-white shadow-md shadow-red-950/60 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/[0.07]' }}">
                    <svg class="w-4 h-4 xl:w-4.5 xl:h-4.5 shrink-0 {{ request()->routeIs('admin.elections.results') ? 'text-[#CE9501]' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    <span class="truncate">Hasil Pemilihan</span>
                </a>

                <!-- Audit Trail & Log -->
                <a href="{{ route('admin.audit.index') }}"
                    class="flex items-center gap-3 px-3 xl:px-3.5 py-2 xl:py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.audit.*') ? 'bg-gradient-to-r from-[#940C10] to-[#7A0A0D] text-white shadow-md shadow-red-950/60 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/[0.07]' }}">
                    <svg class="w-4 h-4 xl:w-4.5 xl:h-4.5 shrink-0 {{ request()->routeIs('admin.audit.*') ? 'text-[#CE9501]' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        <path d="M12 8v4"></path>
                        <path d="M12 16h.01"></path>
                    </svg>
                    <span class="truncate">Audit Trail & Log</span>
                </a>
            </div>

            <!-- ======================================================== -->
            <!-- 5. SETTING                                               -->
            <!-- ======================================================== -->
            <div class="space-y-1">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Setting
                </span>

                <!-- Profile -->
                <a href="{{ route('admin.profile.index') }}"
                    class="flex items-center gap-3 px-3 xl:px-3.5 py-2 xl:py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.profile.*') ? 'bg-gradient-to-r from-[#940C10] to-[#7A0A0D] text-white shadow-md shadow-red-950/60 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/[0.07]' }}">
                    <svg class="w-4 h-4 xl:w-4.5 xl:h-4.5 shrink-0 {{ request()->routeIs('admin.profile.*') ? 'text-[#CE9501]' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="truncate">Profile</span>
                </a>

                <!-- Pengaturan -->
                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center gap-3 px-3 xl:px-3.5 py-2 xl:py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-[#940C10] to-[#7A0A0D] text-white shadow-md shadow-red-950/60 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/[0.07]' }}">
                    <svg class="w-4 h-4 xl:w-4.5 xl:h-4.5 shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-[#CE9501]' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <span class="truncate">Pengaturan</span>
                </a>
            </div>

            <!-- ======================================================== -->
            <!-- 6. AKSES PEMILIHAN                                       -->
            <!-- ======================================================== -->
            <div class="space-y-1 pt-2 border-t border-white/10">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Akses Pemilihan
                </span>

                <!-- Bilik Suara Mahasiswa -->
                <a href="{{ route('login') }}" target="_blank" rel="noopener noreferrer"
                    class="flex items-center gap-3 px-3 xl:px-3.5 py-2 xl:py-2.5 rounded-xl text-xs xl:text-sm font-semibold text-slate-300 hover:text-white hover:bg-white/[0.07] transition-all duration-200 group">
                    <svg class="w-4 h-4 xl:w-4.5 xl:h-4.5 text-[#CE9501] group-hover:scale-110 transition-transform shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                    <div class="flex-1 min-w-0">
                        <span class="block truncate">Bilik Suara Mahasiswa</span>
                        <span class="text-[10px] text-slate-400 block font-normal">Buka di tab baru</span>
                    </div>
                </a>
            </div>
        </nav>
        <script>
            // Restore scroll position immediately to prevent visual jump
            try {
                var navEl = document.getElementById('admin-sidebar-nav');
                if (navEl) {
                    var savedScrollPos = sessionStorage.getItem('admin_sidebar_scroll_top');
                    if (savedScrollPos !== null) {
                        navEl.scrollTop = parseInt(savedScrollPos, 10);
                    }
                }
            } catch(e) {}
        </script>

        <!-- Footer Sidebar: Profil Admin & Tombol Logout -->
        <div class="shrink-0 p-3.5 xl:p-4 border-t border-white/10 bg-[#0d0103]">
            @php $admin = Auth::guard('admin')->user(); @endphp
            <div class="bg-white/[0.05] border border-white/10 rounded-2xl p-3 flex items-center justify-between gap-3">
                <a href="{{ route('admin.profile.index') }}" title="Buka Profil Administrator" class="flex items-center gap-2.5 min-w-0 group hover:opacity-95 transition-opacity">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#940C10] to-[#CE9501] text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md">
                        {{ strtoupper(substr($admin->name ?? 'AD', 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-white truncate leading-tight group-hover:text-[#CE9501] transition-colors">
                            {{ $admin->name ?? 'Administrator' }}
                        </p>
                        <span class="text-[10px] font-semibold text-[#E5A900] tracking-wide block truncate">
                            KPUM UBSI
                        </span>
                    </div>
                </a>

                <form id="logout-form-sidebar" action="{{ route('admin.logout') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="button" onclick="confirmLogout('logout-form-sidebar')" title="Keluar / Logout"
                        class="w-8 h-8 rounded-lg bg-red-600/20 hover:bg-red-600/40 text-red-300 hover:text-white border border-red-500/30 flex items-center justify-center transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ======================================================== -->
    <!-- AREA KONTEN KANAN: TOP HEADER + MAIN CONTENT CANVAS       -->
    <!-- ======================================================== -->
    <div class="lg:pl-64 xl:pl-72 flex flex-col min-h-screen">
        
        <!-- TOP HEADER ADMIN BAR -->
        <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between shadow-2xs">
            
            <!-- Left Header: Toggle Sidebar (Mobile) & Judul Halaman -->
            <div class="flex items-center gap-3 sm:gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>

                <div>
                    <h1 id="admin-header-title" class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        {{ $title ?? 'Dashboard Administrator' }}
                    </h1>
                    <p class="text-[11px] text-slate-500 font-medium hidden sm:block">
                        Komisi Pemilihan Umum Mahasiswa — BEM Universitas BSI Karawang
                    </p>
                </div>
            </div>

            <!-- Right Header: Period Selector, Jam Realtime, Status & Logout Quick Action -->
            <div class="flex items-center gap-3 sm:gap-4" x-data="adminClock()">
                @php
                    $activePeriodSystem = app(\App\Services\ElectionService::class)->getActivePeriod();
                    $selectedPeriodLayout = app(\App\Services\ElectionService::class)->getSelectedPeriod();
                    $allPeriodsLayout = \App\Models\ElectionPeriod::orderByDesc('is_active')->orderByDesc('id')->get();
                @endphp

                <!-- Period Selector Dropdown (Konteks Multi-Periode) -->
                @if($allPeriodsLayout->count() > 0)
                <div class="relative" x-data="{ openPeriodDropdown: false }">
                    <button @click="openPeriodDropdown = !openPeriodDropdown" type="button"
                        class="inline-flex items-center gap-2 px-3 py-1.5 sm:py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200/90 text-xs font-bold text-slate-800 transition-all cursor-pointer">
                        <span class="w-2 h-2 rounded-full {{ ($selectedPeriodLayout && $selectedPeriodLayout->isSystemActive()) ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                        <span class="truncate max-w-[110px] sm:max-w-[160px] md:max-w-[200px]">
                            {{ $selectedPeriodLayout ? $selectedPeriodLayout->academic_year . ' (' . ($selectedPeriodLayout->isSystemActive() ? 'Aktif' : 'Arsip') . ')' : 'Pilih Periode' }}
                        </span>
                        <svg class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200" :class="openPeriodDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"></path></svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="openPeriodDropdown" x-cloak @click.away="openPeriodDropdown = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50">
                        <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Pilih Periode Pemilihan</span>
                            <span class="text-[10px] text-slate-400">{{ $allPeriodsLayout->count() }} Periode</span>
                        </div>
                        <div class="max-h-60 overflow-y-auto divide-y divide-slate-50 admin-scrollbar">
                            @foreach($allPeriodsLayout as $itemPeriod)
                                <form action="{{ route('admin.periods.switch') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="period_id" value="{{ $itemPeriod->id }}">
                                    <button type="submit" class="w-full text-left px-4 py-2.5 hover:bg-red-50/50 flex items-center justify-between text-xs transition-colors cursor-pointer {{ ($selectedPeriodLayout && $selectedPeriodLayout->id === $itemPeriod->id) ? 'bg-red-50/70 text-[#940C10] font-bold' : 'text-slate-700' }}">
                                        <div class="min-w-0 pr-2">
                                            <div class="font-bold truncate">{{ $itemPeriod->name }}</div>
                                            <div class="text-[10px] text-slate-400">Tahun: {{ $itemPeriod->academic_year }}</div>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase shrink-0 {{ $itemPeriod->isSystemActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $itemPeriod->isSystemActive() ? 'Aktif' : 'Arsip' }}
                                        </span>
                                    </button>
                                </form>
                            @endforeach
                        </div>
                        <div class="px-3 pt-2 border-t border-slate-100">
                            <a href="{{ route('admin.periods.index') }}" class="block text-center py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 text-[11px] font-bold text-[#940C10] transition-colors">
                                Kelola Seluruh Periode &rarr;
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Live Clock (WIB) -->
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100/80 border border-slate-200/70 text-slate-700 text-xs">
                    <svg class="w-3.5 h-3.5 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span x-text="dateString" class="font-medium text-[11px]"></span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <span x-text="timeString" class="font-mono font-bold text-slate-900"></span>
                </div>

                <!-- Tombol Logout Quick Button -->
                <form id="logout-form-header" action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="button" onclick="confirmLogout('logout-form-header')" 
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 transition-all cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span class="hidden md:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Archived Period Banner Notice -->
        @if($selectedPeriodLayout && $selectedPeriodLayout->isArchived())
        <div class="bg-amber-500/10 border-b border-amber-500/20 px-4 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between gap-4 text-xs text-amber-900">
            <div class="flex items-center gap-2 min-w-0">
                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="truncate"><strong>Mode Arsip Historis:</strong> Anda sedang melihat data periode <strong>{{ $selectedPeriodLayout->name }}</strong>.</span>
            </div>
            @if($activePeriodSystem)
                <form action="{{ route('admin.periods.switch') }}" method="POST" class="shrink-0">
                    @csrf
                    <input type="hidden" name="period_id" value="{{ $activePeriodSystem->id }}">
                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold text-[11px] transition-colors cursor-pointer">
                        Kembali ke Periode Aktif
                    </button>
                </form>
            @endif
        </div>
        @endif

        <!-- MAIN CONTENT CANVAS -->
        <main id="admin-main-content" class="admin-main-view flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    <!-- Scripts: Live Clock & SweetAlert2 Logout Confirmation -->
    <script>
        function adminClock() {
            return {
                dateString: '',
                timeString: '',
                init() {
                    this.tick();
                    setInterval(() => this.tick(), 1000);
                },
                tick() {
                    const now = new Date();
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                    this.dateString = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
                    const h = String(now.getHours()).padStart(2, '0');
                    const m = String(now.getMinutes()).padStart(2, '0');
                    const s = String(now.getSeconds()).padStart(2, '0');
                    this.timeString = `${h}:${m}:${s} WIB`;
                }
            }
        }

        function confirmLogout(formId) {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin keluar dari Panel Administrator KPUM?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#940c10',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl font-bold px-5 py-2.5',
                    cancelButton: 'rounded-xl font-semibold px-5 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // ========================================================
        // ULTRA-SMOOTH SIDEBAR NAVIGATION & SCROLL PERSISTENCE
        // ========================================================
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide && window.lucide.createIcons) {
                window.lucide.createIcons();
            }

            const nav = document.getElementById('admin-sidebar-nav');
            const mainContent = document.getElementById('admin-main-content');
            if (!nav) return;

            // 1. Pulihkan posisi scroll secara instan tanpa jeda
            const savedScroll = sessionStorage.getItem('admin_sidebar_scroll_top');
            if (savedScroll !== null) {
                nav.style.scrollBehavior = 'auto';
                nav.scrollTop = parseInt(savedScroll, 10);
            }

            // Simpan posisi scroll saat pengguna men-scroll sidebar
            nav.addEventListener('scroll', () => {
                sessionStorage.setItem('admin_sidebar_scroll_top', nav.scrollTop);
            }, { passive: true });

            if (!mainContent) return;

            // 2. Transisi Halaman Mulus (Seamless SPA Navigation)
            // Menghilangkan jeda reload browser penuh saat memilih menu sidebar
            nav.addEventListener('click', async (e) => {
                const link = e.target.closest('a');
                if (!link) return;

                // Abaikan tab baru, aksi javascript, atau tombol modifier keyboard
                if (link.target === '_blank' || link.href.startsWith('javascript:') || e.ctrlKey || e.metaKey || e.shiftKey) {
                    return;
                }

                const targetUrl = link.href;
                const currentUrl = window.location.href;

                // Simpan posisi scroll sidebar saat ini sebelum aksi
                sessionStorage.setItem('admin_sidebar_scroll_top', nav.scrollTop);

                // Jika hanya anchor hash pada halaman yang sama (misal: #visi-misi)
                if (targetUrl.includes('#') && targetUrl.split('#')[0] === currentUrl.split('#')[0]) {
                    const hash = targetUrl.split('#')[1];
                    const targetEl = document.getElementById(hash);
                    if (targetEl) {
                        targetEl.scrollIntoView({ behavior: 'smooth' });
                        return;
                    }
                }

                // Jangan reload jika URL sama persis
                if (targetUrl === currentUrl) {
                    e.preventDefault();
                    return;
                }

                e.preventDefault();

                // Feedback visual seketika: jadikan menu yang diklik aktif langsung
                const allLinks = nav.querySelectorAll('a');
                allLinks.forEach(l => {
                    if (l === link) {
                        l.classList.remove('text-slate-300', 'hover:text-white', 'hover:bg-white/[0.07]');
                        l.classList.add('bg-gradient-to-r', 'from-[#940C10]', 'to-[#7A0A0D]', 'text-white', 'shadow-md', 'shadow-red-950/60', 'font-bold');
                        const svg = l.querySelector('svg');
                        if (svg) {
                            svg.classList.remove('text-slate-400', 'group-hover:text-white', 'group-hover:text-[#CE9501]');
                            svg.classList.add('text-[#CE9501]');
                        }
                    } else if (l.target !== '_blank') {
                        l.classList.remove('bg-gradient-to-r', 'from-[#940C10]', 'to-[#7A0A0D]', 'text-white', 'shadow-md', 'shadow-red-950/60', 'font-bold');
                        l.classList.add('text-slate-300', 'hover:text-white', 'hover:bg-white/[0.07]');
                        const svg = l.querySelector('svg');
                        if (svg) {
                            svg.classList.remove('text-[#CE9501]');
                            svg.classList.add('text-slate-400', 'group-hover:text-white');
                        }
                    }
                });

                // Efek transisi halus pada area konten
                mainContent.classList.add('is-loading');

                try {
                    const response = await fetch(targetUrl, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) {
                        window.location.href = targetUrl;
                        return;
                    }

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Ganti konten area main secara mulus
                    const newMain = doc.getElementById('admin-main-content') || doc.querySelector('main');
                    if (newMain) {
                        mainContent.innerHTML = newMain.innerHTML;
                    } else {
                        window.location.href = targetUrl;
                        return;
                    }

                    // Sinkronisasi judul header dan judul dokumen
                    const newHeaderTitle = doc.getElementById('admin-header-title');
                    const currentHeaderTitle = document.getElementById('admin-header-title');
                    if (newHeaderTitle && currentHeaderTitle) {
                        currentHeaderTitle.innerHTML = newHeaderTitle.innerHTML;
                    }
                    if (doc.title) {
                        document.title = doc.title;
                    }

                    // Perbarui riwayat browser (URL bar)
                    window.history.pushState({ path: targetUrl }, '', targetUrl);

                    // Sinkronisasi kelas menu sidebar dari server
                    const newNavLinks = doc.querySelectorAll('#admin-sidebar-nav a');
                    if (newNavLinks.length === allLinks.length) {
                        allLinks.forEach((curLink, idx) => {
                            curLink.className = newNavLinks[idx].className;
                            curLink.innerHTML = newNavLinks[idx].innerHTML;
                        });
                    }

                    // Tutup sidebar di perangkat mobile jika sedang terbuka
                    if (window.innerWidth < 1024) {
                        const body = document.querySelector('body');
                        if (body && body._x_dataStack && body._x_dataStack[0]) {
                            body._x_dataStack[0].sidebarOpen = false;
                        }
                    }

                    // Re-evaluate script elements inside swapped main
                    const scripts = mainContent.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        const s = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => s.setAttribute(attr.name, attr.value));
                        s.appendChild(document.createTextNode(oldScript.innerHTML));
                        oldScript.parentNode.replaceChild(s, oldScript);
                    });

                    // Inisialisasi ulang icon & alpine untuk konten baru
                    if (window.lucide && window.lucide.createIcons) {
                        window.lucide.createIcons();
                    }
                    if (window.Alpine && window.Alpine.initTree) {
                        window.Alpine.initTree(mainContent);
                    }

                    // Jika target memiliki hash, scroll ke anchor tersebut
                    if (targetUrl.includes('#')) {
                        const hash = targetUrl.split('#')[1];
                        const hashEl = document.getElementById(hash);
                        if (hashEl) {
                            setTimeout(() => hashEl.scrollIntoView({ behavior: 'smooth' }), 60);
                        }
                    } else {
                        window.scrollTo({ top: 0, behavior: 'instant' });
                    }

                } catch (err) {
                    console.error('Seamless navigation error, fallback to full reload:', err);
                    window.location.href = targetUrl;
                } finally {
                    mainContent.classList.remove('is-loading');
                }
            });

            // Dukungan tombol Back / Forward browser secara mulus
            window.addEventListener('popstate', async () => {
                const url = window.location.href;
                mainContent.classList.add('is-loading');
                try {
                    const res = await fetch(url);
                    if (!res.ok) { window.location.reload(); return; }
                    const html = await res.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const newMain = doc.getElementById('admin-main-content') || doc.querySelector('main');
                    if (newMain) mainContent.innerHTML = newMain.innerHTML;
                    
                    const newHeaderTitle = doc.getElementById('admin-header-title');
                    const currentHeaderTitle = document.getElementById('admin-header-title');
                    if (newHeaderTitle && currentHeaderTitle) currentHeaderTitle.innerHTML = newHeaderTitle.innerHTML;
                    if (doc.title) document.title = doc.title;

                    const allLinks = nav.querySelectorAll('a');
                    const newNavLinks = doc.querySelectorAll('#admin-sidebar-nav a');
                    if (newNavLinks.length === allLinks.length) {
                        allLinks.forEach((curLink, idx) => {
                            curLink.className = newNavLinks[idx].className;
                            curLink.innerHTML = newNavLinks[idx].innerHTML;
                        });
                    }

                    // Re-evaluate script elements inside swapped main
                    const scripts = mainContent.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        const s = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => s.setAttribute(attr.name, attr.value));
                        s.appendChild(document.createTextNode(oldScript.innerHTML));
                        oldScript.parentNode.replaceChild(s, oldScript);
                    });

                    if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();
                    if (window.Alpine && window.Alpine.initTree) window.Alpine.initTree(mainContent);
                } catch (e) {
                    window.location.reload();
                } finally {
                    mainContent.classList.remove('is-loading');
                }
            });
        });
    </script>
    @stack('scripts')

    <!-- SweetAlert2 Toast Notifications (Top-End) -->
    @if (session('status') || session('success') || session('error') || session('info'))
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
                        icon: 'info',
                        title: {!! json_encode(session('status')) !!}
                    });
                @elseif (session('success'))
                    Toast.fire({
                        icon: 'success',
                        title: {!! json_encode(session('success')) !!}
                    });
                @elseif (session('error'))
                    Toast.fire({
                        icon: 'error',
                        title: {!! json_encode(session('error')) !!}
                    });
                @elseif (session('info'))
                    Toast.fire({
                        icon: 'info',
                        title: {!! json_encode(session('info')) !!}
                    });
                @endif
            });
        </script>
    @endif
</body>
</html>

