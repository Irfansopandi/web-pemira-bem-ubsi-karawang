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
    </style>
</head>
<body class="min-h-full flex flex-col {{ $bodyClass ?? 'bg-[#FDFDFD]' }} text-[#1A1A1A] font-sans antialiased selection:bg-[#940c10] selection:text-white">

    @if(!($hideNavbar ?? false))
    <!-- Top Institutional Banner (UBSI Official Red Theme) -->
    <header class="sticky top-0 z-40 bg-gradient-to-r from-[#940c10] via-[#7A0A0D] to-[#940c10] text-[#FDFDFD] border-b-2 border-[#CE9501] shadow-lg shadow-[#940c10]/20 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand / Logo Section (Murni Logo Tanpa Border Box) -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI" class="w-12 h-12 sm:w-14 sm:h-14 object-contain drop-shadow-md">
                    <div class="flex flex-col">
                        <span class="text-base sm:text-lg font-bold text-[#FDFDFD] leading-tight">PEMIRA BEM UBSI</span>
                        <span class="text-[11px] sm:text-xs text-amber-200/90 font-medium">Universitas Bina Sarana Informatika Karawang</span>
                    </div>
                </div>

                <!-- User Profile, Live Clock & Actions Section -->
                <div class="flex items-center gap-3 sm:gap-4">
                    
                    <!-- Hari, Tanggal, Tahun & Jam, Menit, Detik (Live Realtime) -->
                    <div x-data="liveClock()" class="text-right hidden sm:flex flex-col justify-center">
                        <div class="text-[11px] sm:text-xs font-semibold text-amber-300/95 leading-tight" x-text="dateString">
                            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </div>
                        <div class="text-xs sm:text-sm font-mono font-bold text-white tracking-wider mt-0.5" x-text="timeString">
                            {{ \Carbon\Carbon::now()->format('H:i:s') }} WIB
                        </div>
                    </div>

                    @if(Auth::guard('student')->check())
                        @php $student = Auth::guard('student')->user(); @endphp
                        
                        <!-- Garis lurus pembatas vertikal -->
                        <div class="h-9 w-px bg-white/25 hidden sm:block"></div>

                        <!-- Data Mahasiswa (Nama & NIM) Bersebelahan dengan Logout -->
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="text-right">
                                <p class="text-xs sm:text-sm font-semibold text-white leading-tight">{{ $student->name }}</p>
                                <p class="text-[11px] font-mono text-amber-300 font-medium mt-0.5">NIM: {{ $student->nim }}</p>
                            </div>
                            <form id="logout-form-student" action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="button" onclick="confirmLogout('logout-form-student')" title="Keluar / Logout" class="p-2 sm:p-2.5 text-white/80 hover:text-white hover:bg-white/15 rounded-xl transition-all cursor-pointer flex items-center justify-center">
                                    <i data-lucide="log-out" class="w-5 h-5"></i>
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
                                <button type="button" onclick="confirmLogout('logout-form-admin')" title="Keluar / Logout" class="p-2 sm:p-2.5 text-white/80 hover:text-white hover:bg-white/15 rounded-xl transition-all cursor-pointer flex items-center justify-center">
                                    <i data-lucide="log-out" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-[#CE9501] hover:bg-[#B58200] text-[#1A1A1A] text-xs sm:text-sm font-semibold shadow-md transition-all">
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900 flex items-start gap-3 shadow-xs mb-4">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-900 flex items-start gap-3 shadow-xs mb-4">
                <i data-lucide="alert-octagon" class="w-5 h-5 text-[#940c10] shrink-0 mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900 flex items-start gap-3 shadow-xs mb-4">
                <i data-lucide="info" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('status') }}</div>
            </div>
        @endif
    </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-1">
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

        // Dialog Konfirmasi Keluar / Logout Menggunakan SweetAlert2 (Tanpa Icon)
        function confirmLogout(formOrId) {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                html: `
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif;" class="text-center">
                        <p class="text-xs sm:text-sm text-slate-600 font-normal leading-relaxed mt-1">
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
                    popup: 'rounded-3xl border border-slate-200 shadow-2xl p-6 sm:p-7 max-w-md w-full',
                    title: 'text-xl font-bold text-slate-900 p-0 m-0',
                    htmlContainer: 'p-0 m-0 mt-3',
                    actions: 'mt-5 flex items-center justify-center gap-3 w-full',
                    confirmButton: 'whitespace-nowrap px-5 py-3 rounded-xl font-semibold text-xs sm:text-sm text-white bg-[#940c10] hover:bg-[#7A0A0D] shadow-md transition-all cursor-pointer',
                    cancelButton: 'whitespace-nowrap px-5 py-3 rounded-xl font-semibold text-xs sm:text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengeluarkan Sesi...',
                        text: 'Mohon tunggu, sesi Anda sedang ditutup dengan aman.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
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
