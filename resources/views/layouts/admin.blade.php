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
        <div>
            <div class="p-5 xl:p-6 border-b border-white/10 bg-gradient-to-b from-[#220308] to-[#120204]">
                <div class="flex items-center justify-between">
                    <!-- 3 Logo Resmi UBSI Karawang -->
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo-ubsi.png') }}" alt="Logo Universitas BSI" class="w-8 h-8 xl:w-9 xl:h-9 object-contain drop-shadow-sm hover:scale-105 transition-transform">
                        <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI Karawang" class="w-8 h-8 xl:w-9 xl:h-9 object-contain drop-shadow-sm hover:scale-105 transition-transform">
                        <img src="{{ asset('images/logo-kabinet.png') }}" alt="Logo Kabinet PEMIRA" class="w-8 h-8 xl:w-9 xl:h-9 object-contain drop-shadow-sm hover:scale-105 transition-transform">
                    </div>

                    <!-- Tombol Tutup Sidebar di Mobile -->
                    <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="mt-3.5">
                    <h2 class="text-sm xl:text-base font-extrabold tracking-wider text-white uppercase leading-tight">
                        PEMIRA BEM UBSI
                    </h2>
                    <p class="text-[10px] xl:text-[11px] font-bold text-[#E5A900] tracking-widest uppercase mt-0.5">
                        KAMPUS KARAWANG
                    </p>
                    <div class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-0.5 rounded-full bg-[#CE9501]/15 border border-[#CE9501]/30 text-[10px] font-bold text-[#FFD466] uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#CE9501] animate-pulse"></span>
                        <span>PANEL ADMINISTRATOR</span>
                    </div>
                </div>
            </div>

            <!-- Navigasi Menu Sidebar -->
            <nav class="p-3.5 xl:p-4 space-y-6 overflow-y-auto admin-scrollbar max-h-[calc(100vh-250px)]">
                
                <!-- Section 1: Menu Utama -->
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Menu Utama
                    </span>

                    <!-- Menu Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-[#940C10] to-[#7A0A0D] text-white shadow-md shadow-red-950/60 font-bold border-l-4 border-[#CE9501]' : 'text-slate-300 hover:text-white hover:bg-white/[0.07]' }}">
                        <svg class="w-4 h-4 xl:w-5 xl:h-5 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-[#CE9501]' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="9" rx="1"></rect>
                            <rect x="14" y="3" width="7" height="5" rx="1"></rect>
                            <rect x="14" y="12" width="7" height="9" rx="1"></rect>
                            <rect x="3" y="16" width="7" height="5" rx="1"></rect>
                        </svg>
                        <span class="flex-1">Dashboard Utama</span>
                    </a>

                    <!-- Menu Kelola Paslon -->
                    <a href="{{ route('admin.candidates.index') }}"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.candidates.*') ? 'bg-gradient-to-r from-[#940C10] to-[#7A0A0D] text-white shadow-md shadow-red-950/60 font-bold border-l-4 border-[#CE9501]' : 'text-slate-300 hover:text-white hover:bg-white/[0.07]' }}">
                        <svg class="w-4 h-4 xl:w-5 xl:h-5 shrink-0 {{ request()->routeIs('admin.candidates.*') ? 'text-[#CE9501]' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="flex-1">Paslon & Visi Misi</span>
                    </a>

                    <!-- Menu Daftar Kehadiran & Partisipasi DPT -->
                    <a href="{{ route('admin.dashboard') }}#tabel-dpt"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold text-slate-300 hover:text-white hover:bg-white/[0.07] transition-all duration-200 group">
                        <svg class="w-4 h-4 xl:w-5 xl:h-5 text-slate-400 group-hover:text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <polyline points="16 11 18 13 22 9"></polyline>
                        </svg>
                        <span class="flex-1">Kehadiran & DPT TPS</span>
                    </a>

                    <!-- Menu Log Aktivitas / Audit Trail -->
                    <a href="{{ route('admin.dashboard') }}#audit-trail"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold text-slate-300 hover:text-white hover:bg-white/[0.07] transition-all duration-200 group">
                        <svg class="w-4 h-4 xl:w-5 xl:h-5 text-slate-400 group-hover:text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <path d="M12 8v4"></path>
                            <path d="M12 16h.01"></path>
                        </svg>
                        <span class="flex-1">Audit Trail & Log</span>
                    </a>
                </div>

                <!-- Section 2: Bilik Suara & Akses Mahasiswa -->
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Akses Pemilihan
                    </span>

                    <a href="{{ route('login') }}" target="_blank"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs xl:text-sm font-semibold text-slate-300 hover:text-white hover:bg-white/[0.07] transition-all duration-200 group">
                        <svg class="w-4 h-4 xl:w-5 xl:h-5 text-[#CE9501] group-hover:scale-110 transition-transform shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
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
        </div>

        <!-- Footer Sidebar: Profil Admin & Tombol Logout -->
        <div class="p-3.5 xl:p-4 border-t border-white/10 bg-[#0d0103]">
            @php $admin = Auth::guard('admin')->user(); @endphp
            <div class="bg-white/[0.05] border border-white/10 rounded-2xl p-3 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#940C10] to-[#CE9501] text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md">
                        {{ strtoupper(substr($admin->name ?? 'AD', 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-white truncate leading-tight">
                            {{ $admin->name ?? 'Administrator' }}
                        </p>
                        <span class="text-[10px] font-semibold text-[#E5A900] tracking-wide block truncate">
                            KPUM UBSI
                        </span>
                    </div>
                </div>

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
                    <h1 class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        {{ $title ?? 'Dashboard Administrator' }}
                    </h1>
                    <p class="text-[11px] text-slate-500 font-medium hidden sm:block">
                        Komisi Pemilihan Umum Mahasiswa — BEM Universitas BSI Karawang
                    </p>
                </div>
            </div>

            <!-- Right Header: Jam Realtime, Status & Logout Quick Action -->
            <div class="flex items-center gap-3 sm:gap-5" x-data="adminClock()">
                
                <!-- Live Clock (WIB) -->
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100/80 border border-slate-200/70 text-slate-700 text-xs">
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

        <!-- Flash Alert Messages -->
        @if(session('success') || session('error') || session('status'))
        <div class="px-4 sm:px-6 lg:px-8 pt-4">
            @if(session('success'))
                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2.5 shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm font-semibold flex items-center gap-2.5 shadow-xs">
                    <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('status'))
                <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs sm:text-sm font-semibold flex items-center gap-2.5 shadow-xs">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif
        </div>
        @endif

        <!-- MAIN CONTENT CANVAS -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
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

        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide && window.lucide.createIcons) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>

