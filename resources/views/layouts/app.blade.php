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

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

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
    </style>
</head>
<body class="min-h-full flex flex-col bg-[#FDFDFD] text-[#1A1A1A] font-sans antialiased selection:bg-[#940c10] selection:text-white">

    <!-- Top Institutional Banner -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand / Logo Section -->
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center p-1 shadow-sm ring-1 ring-gray-200">
                        <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI" class="w-10 h-10 object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold uppercase tracking-widest text-[#CE9501]">Pesta Demokrasi Mahasiswa</span>
                        <span class="text-lg font-extrabold tracking-tight text-[#940c10]">PEMIRA BEM UBSI</span>
                        <span class="text-[11px] font-medium text-gray-500 -mt-1">Universitas Bina Sarana Informatika Karawang</span>
                    </div>
                </div>

                <!-- Authentication CTA Actions -->
                <div class="flex items-center gap-3">
                    @if(Auth::guard('student')->check())
                        @php $student = Auth::guard('student')->user(); @endphp
                        <div class="flex items-center gap-3 pl-3 border-l border-gray-200">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-bold text-gray-900 leading-none">{{ $student->name }}</p>
                                <p class="text-[11px] font-mono text-gray-500 mt-0.5">NIM: {{ $student->nim }}</p>
                            </div>
                            <a href="{{ route('student.voting') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-[#940c10] text-white text-xs font-bold hover:bg-[#7A0A0D] shadow-xs transition-all">
                                <i data-lucide="box" class="w-4 h-4"></i>
                                Bilik Suara
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Keluar" class="p-2 text-gray-500 hover:text-[#940c10] hover:bg-red-50 rounded-lg transition-colors cursor-pointer">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    @elseif(Auth::guard('admin')->check())
                        @php $user = Auth::guard('admin')->user(); @endphp
                        <div class="flex items-center gap-2 sm:gap-3 pl-3 border-l border-gray-200">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-bold text-gray-900 leading-none">{{ $user->name }}</p>
                                <span class="inline-block mt-0.5 px-2 py-0.5 text-[10px] font-bold rounded-full {{ $user->isAdmin() ? 'bg-red-100 text-[#940c10]' : 'bg-amber-100 text-[#CE9501]' }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </div>
                            <a href="{{ $user->isAdmin() ? route('admin.dashboard') : route('committee.dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') || request()->routeIs('committee.dashboard') ? 'bg-[#940c10] text-white' : 'bg-gray-900 text-white hover:bg-black' }} text-xs font-bold transition-all">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                Panel {{ $user->isAdmin() ? 'Admin' : 'TPS' }}
                            </a>
                            @if($user->isAdmin())
                                <a href="{{ route('admin.candidates.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg {{ request()->routeIs('admin.candidates.*') ? 'bg-[#940c10] text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }} text-xs font-bold transition-all" title="Kelola Paslon & Visi Misi">
                                    <i data-lucide="users" class="w-4 h-4 text-[#CE9501]"></i>
                                    <span class="hidden md:inline">Kelola Paslon</span>
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Keluar" class="p-2 text-gray-500 hover:text-[#940c10] hover:bg-red-50 rounded-lg transition-colors cursor-pointer">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-[#940c10] text-white text-xs sm:text-sm font-bold hover:bg-[#7A0A0D] shadow-sm transition-all focus:ring-2 focus:ring-[#CE9501] focus:outline-none">
                            <i data-lucide="log-in" class="w-4 h-4 text-[#CE9501]"></i>
                            <span>Masuk Sistem</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Global Flash Notification Messages -->
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

    <!-- Main Content Area -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Official Institutional Footer -->
    <footer class="mt-20 border-t border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-900">PEMIRA BEM UBSI KARAWANG</span>
                    <span>&bull;</span>
                    <span>Komisi Pemilihan Umum Mahasiswa</span>
                </div>
                <p class="font-medium text-gray-600">&copy; {{ date('Y') }} Sistem E-Voting Terenkripsi &bull; Full Laravel 13</p>
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide && window.lucide.createIcons) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
