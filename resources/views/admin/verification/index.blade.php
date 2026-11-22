<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Buku Tamu Digital PEMIRA - BEM UBSI Kab. Karawang</title>

    <!-- Favicon Resmi -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=2">

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Vite Assets / Fallback Tailwind CSS + Alpine.js -->
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
    @endif

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-[#FDFDFD] text-slate-800 antialiased selection:bg-[#940c10] selection:text-white flex flex-col justify-between"
      x-data="bukuTamuApp({
          lookupUrl: '{{ route('guestbook.lookup') }}',
          storeUrl: '{{ route('guestbook.store') }}',
          csrfToken: '{{ csrf_token() }}'
      })">

    <!-- ======================================================== -->
    <!-- TOP NAVIGATION BAR (PERSISTENT ACROSS ALL SCREENS)       -->
    <!-- ======================================================== -->
    <header class="w-full bg-white border-b border-slate-200/80 px-4 sm:px-8 py-3.5 sticky top-0 z-40 shadow-2xs">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            
            <!-- Left: Back Button & Institutional Branding -->
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Tombol Kembali (Hanya tampil jika sedang dalam langkah wizard formulir) -->
                <button type="button" 
                        x-show="currentStep > 0" 
                        x-cloak
                        @click="prevStep()"
                        class="w-10 h-10 rounded-xl border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-600 flex items-center justify-center transition-all shadow-2xs cursor-pointer"
                        title="Kembali ke langkah sebelumnya">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </button>

                <!-- Logo & Identitas Resmi (Logo BEM & Kabinet) -->
                <div class="flex items-center gap-3">
                    <span class="sr-only">UNIVERSITAS BINA SARANA INFORMATIKA</span>
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI" class="w-9 h-9 sm:w-10 sm:h-10 object-contain drop-shadow-2xs">
                        <img src="{{ asset('images/logo-kabinet.png') }}" alt="Logo Kabinet PEMIRA UBSI" class="w-9 h-9 sm:w-10 sm:h-10 object-contain drop-shadow-2xs">
                    </div>
                    <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
                        <span class="text-sm sm:text-base font-extrabold text-[#940c10] tracking-tight">
                            BEM UBSI KAB. KARAWANG
                        </span>
                        <span class="text-slate-300 font-light hidden sm:inline">|</span>
                        <span class="text-xs sm:text-sm font-bold text-slate-900 tracking-tight hidden sm:inline">
                            Buku Tamu Digital <span class="text-[#940c10] font-black">PEMIRA</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Live Date & Clock Pill + Admin Lock Icon -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <!-- Date & Clock Pill -->
                <div class="inline-flex items-center gap-3 sm:gap-3.5 px-4 sm:px-5 py-2 rounded-2xl sm:rounded-full bg-white border border-slate-200/90 shadow-2xs">
                    <!-- Red Calendar Icon -->
                    <svg class="w-5 h-5 text-[#940c10] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2.5" ry="2.5"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                        <circle cx="8" cy="14" r="0.75" fill="currentColor"></circle>
                        <circle cx="12" cy="14" r="0.75" fill="currentColor"></circle>
                        <circle cx="16" cy="14" r="0.75" fill="currentColor"></circle>
                        <circle cx="8" cy="17.5" r="0.75" fill="currentColor"></circle>
                        <circle cx="12" cy="17.5" r="0.75" fill="currentColor"></circle>
                        <circle cx="16" cy="17.5" r="0.75" fill="currentColor"></circle>
                    </svg>
                    <!-- Date String (Dark Charcoal, Clean Sans-Serif) -->
                    <span class="text-sm sm:text-[15px] font-medium text-slate-900 tracking-normal whitespace-nowrap" x-text="dateString">Minggu, 22 Nov 2026</span>
                    <!-- Divider Line -->
                    <span class="text-slate-300 font-light text-sm sm:text-base select-none">|</span>
                    <!-- Time String (Deep Red UBSI, Bold Sans-Serif) -->
                    <span class="text-sm sm:text-[15px] font-extrabold text-[#940c10] tracking-normal whitespace-nowrap" x-text="timeString">18 : 48 : 41 WIB</span>
                </div>

                <!-- Header Action: Dashboard Admin atau Akses Cepat Bilik Suara -->
                @if(Auth::guard('admin')->check())
                    <a href="{{ route('admin.dashboard') }}" 
                       class="w-10 h-10 rounded-xl border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-400 hover:text-[#940c10] flex items-center justify-center transition-all shadow-2xs shrink-0"
                       title="Kembali ke Dashboard Administrator">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 hover:border-[#940c10] hover:bg-red-50 text-slate-700 hover:text-[#940c10] text-xs font-bold transition-all shadow-2xs"
                       title="Buka Halaman Bilik Suara">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                        </svg>
                        <span>Ke Bilik Suara</span>
                    </a>
                @endif
            </div>

        </div>
    </header>

    <!-- ======================================================== -->
    <!-- MAIN CONTAINER                                           -->
    <!-- ======================================================== -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 flex flex-col justify-center">

        <!-- ==================================================== -->
        <!-- SCREEN 0: WELCOME SCREEN (BERANDA - PERSIS DESAIN)  -->
        <!-- ==================================================== -->
        <div x-show="currentStep === 0" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-98"
             x-transition:enter-end="opacity-100 scale-100"
             class="w-full my-auto py-2 sm:py-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Left Column: Hero Text, CTA Button & Logos -->
                <div class="lg:col-span-7 space-y-6 sm:space-y-7">
                    
                    <!-- Top Pill Tag -->
                    <div>
                        <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-red-50 border border-red-200/70 text-[#940c10] text-[11px] sm:text-xs font-bold uppercase tracking-wider">
                            PEMIRA BEM UBSI KAMPUS KABUPATEN KARAWANG 2026/2027
                        </div>
                    </div>

                    <!-- Main Heading -->
                    <div>
                        <h1 class="text-3xl sm:text-4xl lg:text-[44px] font-extrabold text-slate-900 tracking-tight leading-[1.18]">
                            Selamat Datang di<br>
                            <span class="text-[#940c10] font-black">Buku Tamu Digital</span><br>
                            <span class="text-[#940c10] font-black">PEMIRA BEM UBSI</span><br>
                            <span class="text-[#940c10] font-black">Kab. Karawang!</span>
                        </h1>
                    </div>

                    <!-- Subtitle / Instruction -->
                    <p class="text-sm sm:text-base text-slate-600 font-normal leading-relaxed max-w-xl">
                        Silakan isi buku tamu sebagai bukti kehadiran Anda dalam rangkaian kegiatan Pesta Demokrasi Mahasiswa (PEMIRA) BEM UBSI Kampus Kabupaten Karawang.
                    </p>

                    <!-- Big Red CTA Button ("Isi Buku Tamu" / "Sentuh untuk memulai") -->
                    <div>
                        <button type="button" 
                                @click="goToStep(1)"
                                class="inline-flex items-center gap-4 px-6 sm:px-8 py-4 sm:py-4.5 rounded-2xl bg-gradient-to-r from-[#940c10] to-[#a30f14] hover:from-[#7a0a0d] hover:to-[#940c10] active:scale-[0.98] text-white shadow-xl shadow-red-950/20 transition-all duration-200 group cursor-pointer">
                            <!-- Icon Squircle -->
                            <div class="w-12 h-12 rounded-xl bg-white/15 border border-white/20 group-hover:bg-white/25 flex items-center justify-center transition-colors shrink-0 shadow-2xs">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </div>
                            <!-- Text -->
                            <div class="text-left">
                                <span class="text-lg sm:text-xl font-black tracking-tight block text-white leading-tight">
                                    Isi Buku Tamu
                                </span>
                                <span class="text-xs sm:text-sm text-red-100/90 font-medium block mt-0.5 leading-tight">
                                    Sentuh untuk memulai
                                </span>
                            </div>
                        </button>
                    </div>



                </div>

                <!-- Right Column: Official PEMIRA Banner Card -->
                <div class="lg:col-span-5 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-lg lg:max-w-none rounded-[28px] overflow-hidden shadow-2xl shadow-slate-900/10 border border-slate-200/60 group">
                        <img src="{{ asset('images/buku-tamu-banner-2x.png') }}" 
                             alt="PEMIRA BEM UBSI Kampus Kabupaten Karawang" 
                             class="w-full h-auto object-contain block select-none group-hover:scale-[1.01] transition-transform duration-500">
                    </div>
                </div>

            </div>

        </div>

        <!-- ==================================================== -->
        <!-- FORMULIR STEPPER WIZARD (STEPS 1 - 3)                -->
        <!-- ==================================================== -->
        <div x-show="currentStep > 0" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-3"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-6 sm:space-y-8 my-auto">

            <!-- ================================================ -->
            <!-- STEPPER HEADER (PROGRESS INDIKATOR)              -->
            <!-- ================================================ -->
            <div class="max-w-2xl mx-auto px-4">
                <div class="relative flex items-center justify-between">
                    <!-- Connecting Background Line -->
                    <div class="absolute top-5 left-10 right-10 h-0.5 bg-slate-200 -z-0"></div>
                    <!-- Connecting Progress Line -->
                    <div class="absolute top-5 left-10 h-0.5 bg-[#940c10] -z-0 transition-all duration-300"
                         :style="'width: ' + ((currentStep - 1) * 50) + '%'"></div>

                    <!-- Step 1: Asal Kampus -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all shadow-2xs"
                             :class="{
                                 'bg-[#940c10] text-white shadow-md shadow-red-950/20': currentStep === 1 || currentStep > 1,
                                 'bg-white border-2 border-slate-300 text-slate-400': currentStep < 1
                             }">
                            <template x-if="currentStep > 1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </template>
                            <template x-if="currentStep <= 1">
                                <span>1</span>
                            </template>
                        </div>
                        <span class="mt-2 text-xs font-bold text-center"
                              :class="currentStep >= 1 ? 'text-[#940c10]' : 'text-slate-400'">
                            Asal Kampus
                        </span>
                    </div>

                    <!-- Step 2: Data Diri -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all shadow-2xs"
                             :class="{
                                 'bg-[#940c10] text-white shadow-md shadow-red-950/20': currentStep >= 2,
                                 'bg-white border-2 border-slate-300 text-slate-400': currentStep < 2
                             }">
                            <template x-if="currentStep > 2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </template>
                            <template x-if="currentStep <= 2">
                                <span>2</span>
                            </template>
                        </div>
                        <span class="mt-2 text-xs font-bold text-center"
                              :class="currentStep >= 2 ? 'text-[#940c10]' : 'text-slate-400'">
                            Data Diri
                        </span>
                    </div>

                    <!-- Step 3: Konfirmasi -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all shadow-2xs"
                             :class="{
                                 'bg-[#940c10] text-white shadow-md shadow-red-950/20': currentStep === 3,
                                 'bg-white border-2 border-slate-300 text-slate-400': currentStep < 3
                             }">
                            <span>3</span>
                        </div>
                        <span class="mt-2 text-xs font-bold text-center"
                              :class="currentStep === 3 ? 'text-[#940c10]' : 'text-slate-400'">
                            Konfirmasi
                        </span>
                    </div>
                </div>
            </div>

            <!-- ================================================ -->
            <!-- STEP 1: PILIH ASAL KAMPUS                        -->
            <!-- ================================================ -->
            <div x-show="currentStep === 1" 
                 x-transition:enter="transition ease-out duration-300"
                 class="max-w-3xl mx-auto w-full">
                
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-10 space-y-8">
                    <div class="text-center space-y-1">
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                            Pilih Asal Kampus
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-normal">
                            Pilih asal kampus yang sesuai dengan status Anda saat ini.
                        </p>
                    </div>

                    <!-- Grid 2 Pilihan: Karawang dan Cikampek -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                        
                        <!-- Pilihan 1: UBSI Karawang -->
                        <div @click="selectCampus('UBSI Karawang')"
                             class="rounded-2xl border-2 p-6 sm:p-8 text-center cursor-pointer transition-all duration-200 select-none group relative"
                             :class="selectedCampus === 'UBSI Karawang' 
                                 ? 'border-[#940c10] bg-red-50/40 ring-4 ring-red-500/10 shadow-md' 
                                 : 'border-slate-200/90 hover:border-red-300 hover:bg-slate-50/60'">
                            
                            <!-- Checkmark Badge on Selected -->
                            <div x-show="selectedCampus === 'UBSI Karawang'" 
                                 class="absolute top-4 right-4 w-6 h-6 rounded-full bg-[#940c10] text-white flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </div>

                            <!-- Avatar Circle Icon -->
                            <div class="w-16 h-16 rounded-full bg-red-100 text-[#940c10] flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>

                            <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                                UBSI Karawang
                            </h3>
                            <p class="text-xs text-slate-500 font-normal mt-1">
                                Mahasiswa / Pemilih UBSI Kampus Kabupaten Karawang
                            </p>
                        </div>

                        <!-- Pilihan 2: UBSI Cikampek -->
                        <div @click="selectCampus('UBSI Cikampek')"
                             class="rounded-2xl border-2 p-6 sm:p-8 text-center cursor-pointer transition-all duration-200 select-none group relative"
                             :class="selectedCampus === 'UBSI Cikampek' 
                                 ? 'border-[#940c10] bg-red-50/40 ring-4 ring-red-500/10 shadow-md' 
                                 : 'border-slate-200/90 hover:border-red-300 hover:bg-slate-50/60'">
                            
                            <!-- Checkmark Badge on Selected -->
                            <div x-show="selectedCampus === 'UBSI Cikampek'" 
                                 class="absolute top-4 right-4 w-6 h-6 rounded-full bg-[#940c10] text-white flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </div>

                            <!-- Avatar Circle Icon (Accent Gold) -->
                            <div class="w-16 h-16 rounded-full bg-amber-100 text-[#CE9501] flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>

                            <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                                UBSI Cikampek
                            </h3>
                            <p class="text-xs text-slate-500 font-normal mt-1">
                                Mahasiswa / Pemilih UBSI Kampus Cikampek
                            </p>
                        </div>

                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                        <button type="button" 
                                @click="goToStep(0)"
                                class="px-6 sm:px-8 py-3 rounded-xl border border-slate-300 hover:bg-slate-50 text-xs sm:text-sm font-bold text-slate-700 transition-colors cursor-pointer">
                            Batal
                        </button>
                        
                        <button type="button" 
                                @click="goToStep(2)"
                                :disabled="!selectedCampus"
                                class="px-6 sm:px-8 py-3 rounded-xl bg-[#940c10] hover:bg-[#7a0a0d] disabled:opacity-40 disabled:cursor-not-allowed text-xs sm:text-sm font-bold text-white transition-all shadow-md shadow-red-950/20 flex items-center gap-2 cursor-pointer">
                            <span>Selanjutnya</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

            </div>

            <!-- ================================================ -->
            <!-- STEP 2: DATA DIRI MAHASISWA                      -->
            <!-- ================================================ -->
            <div x-show="currentStep === 2" 
                 x-transition:enter="transition ease-out duration-300"
                 class="max-w-3xl mx-auto w-full">
                
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-10 space-y-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                            Data Diri
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-normal mt-1">
                            Mohon lengkapi data di bawah ini dengan benar.
                        </p>
                    </div>

                    <!-- Status Notification Bar -->
                    <template x-if="statusMessage">
                        <div class="p-4 rounded-xl border text-xs sm:text-sm flex items-start gap-3 transition-all"
                             :class="{
                                 'bg-emerald-50 border-emerald-200 text-emerald-900': status === 'ready',
                                 'bg-blue-50 border-blue-200 text-blue-900': status === 'already_verified',
                                 'bg-amber-50 border-amber-200 text-amber-900': status === 'already_voted',
                                 'bg-red-50 border-red-200 text-red-900': status === 'not_found' || status === 'error'
                             }">
                            <div class="mt-0.5 shrink-0">
                                <template x-if="status === 'ready'">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="9 12 11 14 15 10"></polyline></svg>
                                </template>
                                <template x-if="status === 'already_verified'">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                </template>
                                <template x-if="status === 'already_voted'">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                </template>
                                <template x-if="status === 'not_found' || status === 'error'">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                </template>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-xs uppercase tracking-wider" 
                                   x-text="status === 'ready' ? 'Data DPT Terverifikasi' : (status === 'already_verified' ? 'Sudah Pernah Hadir' : (status === 'already_voted' ? 'Hak Suara Sudah Digunakan' : 'Peringatan Data'))"></p>
                                <p class="mt-0.5 font-medium leading-relaxed" x-text="statusMessage"></p>
                            </div>
                        </div>
                    </template>

                    <!-- Form Inputs (2 Kolom Layout) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                        
                        <!-- NIM (Nomor Induk Mahasiswa) -->
                        <div class="space-y-1.5 sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-800">
                                Nomor Induk Mahasiswa (NIM) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <input type="text" 
                                       x-model="nim" 
                                       @input="onNimInput()"
                                       @keydown.enter.prevent="searchStudent()"
                                       placeholder="Masukkan 8 digit NIM Anda (Contoh: 12220001)"
                                       maxlength="20"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm font-mono font-bold text-slate-900 placeholder:font-sans placeholder:font-normal placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940c10] focus:border-transparent transition-all shadow-2xs">
                                
                                <div class="absolute right-2.5 flex items-center gap-1.5">
                                    <template x-if="isLoading">
                                        <svg class="animate-spin w-5 h-5 text-[#940c10]" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                    </template>
                                    <button type="button" 
                                            @click="searchStudent()"
                                            :disabled="isLoading || nim.trim().length === 0"
                                            class="px-3.5 py-1.5 bg-[#940c10] hover:bg-[#7a0a0d] disabled:opacity-40 text-white rounded-lg text-xs font-bold transition-colors cursor-pointer">
                                        Cek DPT
                                    </button>
                                </div>
                            </div>
                            <span class="text-[11px] text-slate-400 block">Ketik NIM Anda untuk memuat nama, prodi, dan kelas dari DPT secara otomatis.</span>
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-800">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="fullName" 
                                   readonly
                                   placeholder="Nama lengkap otomatis dari DPT"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none cursor-default shadow-2xs">
                        </div>

                        <!-- Asal Kampus / Alamat -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-800">
                                Asal Kampus / Alamat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   :value="selectedCampus" 
                                   readonly
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-900 cursor-default shadow-2xs">
                        </div>

                        <!-- Program Studi -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-800">
                                Program Studi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="studyProgram" 
                                   readonly
                                   placeholder="Program studi mahasiswa"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-900 cursor-default shadow-2xs">
                        </div>

                        <!-- Kelas / Semester -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-800">
                                Kelas / Semester <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="className" 
                                   readonly
                                   placeholder="Kelas mahasiswa"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-900 cursor-default shadow-2xs">
                        </div>

                        <!-- Informasi Kontak Tambahan: No Telepon / WA -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-800">
                                No. Telepon / WhatsApp <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="text" 
                                   x-model="phone" 
                                   placeholder="Contoh: 081234567890"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940c10] shadow-2xs">
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-800">
                                Email <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="email" 
                                   x-model="email" 
                                   placeholder="nama@email.com"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940c10] shadow-2xs">
                        </div>

                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                        <button type="button" 
                                @click="goToStep(1)"
                                class="px-6 sm:px-8 py-3 rounded-xl border border-slate-300 hover:bg-slate-50 text-xs sm:text-sm font-bold text-slate-700 transition-colors cursor-pointer">
                            Kembali
                        </button>
                        
                        <button type="button" 
                                @click="goToStep(3)"
                                :disabled="status !== 'ready' || !student"
                                class="px-6 sm:px-8 py-3 rounded-xl bg-[#940c10] hover:bg-[#7a0a0d] disabled:opacity-40 disabled:cursor-not-allowed text-xs sm:text-sm font-bold text-white transition-all shadow-md shadow-red-950/20 flex items-center gap-2 cursor-pointer">
                            <span>Selanjutnya</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

            </div>

            <!-- ================================================ -->
            <!-- STEP 3: KONFIRMASI DATA & TANDA TANGAN DIGITAL   -->
            <!-- ================================================ -->
            <div x-show="currentStep === 3" 
                 x-transition:enter="transition ease-out duration-300"
                 class="max-w-3xl mx-auto w-full">
                
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-10 space-y-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                            Konfirmasi Data
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-normal mt-1">
                            Periksa kembali data Anda sebelum membubuhkan tanda tangan kehadiran.
                        </p>
                    </div>

                    <!-- Ringkasan Data Card -->
                    <div class="bg-[#FDFDFD] rounded-2xl p-5 sm:p-6 border border-slate-200/80 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Asal Kampus</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <svg class="w-4 h-4 text-[#940c10]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                    </svg>
                                    <span class="font-extrabold text-slate-900 text-sm" x-text="selectedCampus"></span>
                                </div>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Nama Lengkap</span>
                                <span class="font-extrabold text-slate-900 text-sm mt-1 block" x-text="fullName"></span>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">NIM (Nomor Induk Mahasiswa)</span>
                                <span class="font-mono font-bold text-[#940c10] text-sm mt-1 block" x-text="nim"></span>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Program Studi</span>
                                <span class="font-bold text-slate-900 text-sm mt-1 block" x-text="studyProgram"></span>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Kelas / Semester</span>
                                <span class="font-bold text-slate-900 text-sm mt-1 block" x-text="className"></span>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">No. Telepon / WhatsApp</span>
                                <span class="font-semibold text-slate-700 text-sm mt-1 block" x-text="phone || '-'"></span>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Email</span>
                                <span class="font-semibold text-slate-700 text-sm mt-1 block" x-text="email || '-'"></span>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Status Partisipasi</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-300 mt-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Siap Menggunakan Hak Suara
                                </span>
                            </div>

                        </div>
                    </div>

                    <!-- Tanda Tangan Digital (Canvas Pad) -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-slate-900">
                                    Tanda Tangan Digital <span class="text-red-500">*</span>
                                </label>
                                <span class="text-xs text-slate-400 block">
                                    Coretkan tanda tangan Anda di kotak di bawah ini
                                </span>
                            </div>

                            <!-- Tombol Ulangi -->
                            <button type="button" 
                                    @click="clearSignature()"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-xs font-bold text-slate-600 transition-colors shadow-2xs cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                                <span>Ulangi</span>
                            </button>
                        </div>

                        <!-- Canvas Box -->
                        <div class="relative bg-white rounded-2xl border-2 border-dashed border-slate-300 overflow-hidden"
                             :class="{ 'ring-2 ring-red-500/20 border-[#940c10]': hasSignature }">
                            
                            <!-- Placeholder icon & text -->
                            <div x-show="!hasSignature" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-slate-400 select-none">
                                <svg class="w-7 h-7 text-slate-300 mb-1" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 1 1 3.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                <span class="text-xs font-medium text-slate-400">Coret di sini untuk tanda tangan</span>
                            </div>

                            <canvas id="signature-canvas" 
                                    class="w-full h-44 sm:h-52 cursor-crosshair touch-none bg-transparent"
                                    @mousedown="startDrawing($event)"
                                    @mousemove="draw($event)"
                                    @mouseup="stopDrawing()"
                                    @mouseleave="stopDrawing()"
                                    @touchstart="startDrawingTouch($event)"
                                    @touchmove="drawTouch($event)"
                                    @touchend="stopDrawing()"></canvas>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                        <button type="button" 
                                @click="goToStep(2)"
                                class="px-6 sm:px-8 py-3 rounded-xl border border-slate-300 hover:bg-slate-50 text-xs sm:text-sm font-bold text-slate-700 transition-colors cursor-pointer">
                            Kembali
                        </button>
                        
                        <button type="button" 
                                @click="submitVerification()"
                                :disabled="isSubmitting || !hasSignature"
                                class="px-7 sm:px-9 py-3.5 rounded-xl bg-[#940c10] hover:bg-[#7a0a0d] disabled:opacity-40 disabled:cursor-not-allowed text-xs sm:text-sm font-extrabold text-white transition-all shadow-md shadow-red-950/20 flex items-center gap-2 cursor-pointer">
                            <template x-if="isSubmitting">
                                <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                            </template>
                            <template x-if="!isSubmitting">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </template>
                            <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Kehadiran'">Simpan Kehadiran</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- ======================================================== -->
    <!-- FOOTER RESMI (PERSIS DESAIN)                             -->
    <!-- ======================================================== -->
    <footer class="w-full bg-white border-t border-slate-200/80 py-4 px-4 sm:px-8 text-center text-xs text-slate-500">
        <p>&copy; 2026 Panitia PEMIRA BEM UBSI Kampus Kabupaten Karawang. Seluruh hak cipta dilindungi.</p>
    </footer>

    <!-- ======================================================== -->
    <!-- POPUP MODAL SUKSES (HITUNG MUNDUR MENUJU BERANDA)        -->
    <!-- ======================================================== -->
    <div x-show="showSuccessModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl text-center space-y-5 border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
            <!-- Checklist Circle -->
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-md">
                <svg class="w-9 h-9 sm:w-11 sm:h-11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                    Kehadiran Berhasil Disimpan
                </span>
                <h3 class="text-lg sm:text-xl font-black text-slate-900 mt-2" x-text="lastVerifiedName"></h3>
                <p class="text-xs text-slate-500 font-mono mt-0.5" x-text="'NIM: ' + lastVerifiedNim"></p>
                <div class="mt-3 p-3.5 rounded-xl bg-red-50 text-xs text-[#940c10] font-medium border border-red-100">
                    Hak suara Anda telah <strong class="text-[#940c10]">AKTIF</strong>. Silakan segera menuju ke <strong>Bilik Suara</strong> untuk mencoblos.
                </div>
            </div>

            <!-- Auto Return Timer -->
            <div class="space-y-2">
                <div class="flex items-center justify-between text-[11px] text-slate-500">
                    <span>Kembali ke Beranda otomatis:</span>
                    <span class="font-bold text-slate-900" x-text="countdown + ' Detik'"></span>
                </div>
                <div class="w-full h-1.5 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full bg-[#940c10] transition-all duration-1000 ease-linear rounded-full"
                         :style="'width: ' + ((countdown / 5) * 100) + '%'"></div>
                </div>
            </div>

            <div class="space-y-2 pt-1">
                <a href="{{ route('login') }}" 
                   class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-[#940C10] to-[#7A0A0D] hover:from-[#7A0A0D] hover:to-[#5E0709] text-white text-xs font-bold transition-all shadow-md flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                    <span>Lanjut ke Login Bilik Suara &rarr;</span>
                </a>

                <button type="button" 
                        @click="closeSuccessModal()"
                        class="w-full py-2.5 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-semibold transition-colors cursor-pointer">
                    Selesai & Isi Mahasiswa Lain
                </button>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- JAVASCRIPT & ALPINE CONTROLLER                           -->
    <!-- ======================================================== -->
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bukuTamuApp', (config) => ({
            currentStep: 0, // 0: Beranda, 1: Asal Kampus, 2: Data Diri, 3: Konfirmasi
            selectedCampus: '',
            nim: '',
            fullName: '',
            studyProgram: '',
            className: '',
            phone: '',
            email: '',
            
            isLoading: false,
            isSubmitting: false,
            status: 'idle', // idle, loading, ready, already_verified, already_voted, not_found, error
            statusMessage: '',
            student: null,
            hasSignature: false,

            // Clock State
            timeString: '00 : 00 : 00 WIB',
            dateString: 'Minggu, 22 Nov 2026',

            // Modal State
            showSuccessModal: false,
            lastVerifiedName: '',
            lastVerifiedNim: '',
            countdown: 5,
            countdownInterval: null,

            // Canvas
            canvas: null,
            ctx: null,
            isDrawing: false,

            init() {
                this.updateClock();
                setInterval(() => this.updateClock(), 1000);
            },

            updateClock() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                this.timeString = `${hours} : ${minutes} : ${seconds} WIB`;

                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                const dayName = days[now.getDay()];
                const dayDate = now.getDate();
                const monthName = months[now.getMonth()];
                const year = now.getFullYear();
                this.dateString = `${dayName}, ${dayDate} ${monthName} ${year}`;
            },

            goToStep(step) {
                this.currentStep = step;
                if (step === 3) {
                    this.$nextTick(() => {
                        this.initCanvas();
                    });
                }
            },

            prevStep() {
                if (this.currentStep > 0) {
                    this.currentStep--;
                }
            },

            selectCampus(campus) {
                this.selectedCampus = campus;
                // Otomatis lanjut ke step 2 setelah memilih kampus
                setTimeout(() => {
                    this.goToStep(2);
                }, 200);
            },

            onNimInput() {
                const clean = this.nim.trim();
                if (clean.length === 8 && /^\d+$/.test(clean)) {
                    this.searchStudent();
                }
            },

            async searchStudent() {
                const clean = this.nim.trim();
                if (!clean) return;

                this.isLoading = true;
                this.status = 'loading';
                this.statusMessage = '';

                try {
                    const response = await fetch(config.lookupUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': config.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ nim: clean })
                    });

                    const data = await response.json();
                    this.status = data.status;
                    this.statusMessage = data.message;
                    this.student = data.student || null;

                    if (data.student) {
                        this.fullName = data.student.name;
                        this.studyProgram = data.student.study_program;
                        this.className = `${data.student.class_name || '-'} (Semester ${data.student.semester || '-'})`;
                        if (data.student.email_kampus) {
                            this.email = data.student.email_kampus;
                        }
                        if (data.student.asal_kampus && !this.selectedCampus) {
                            this.selectedCampus = data.student.asal_kampus;
                        }
                    } else {
                        this.fullName = '';
                        this.studyProgram = '';
                        this.className = '';
                    }
                } catch (err) {
                    this.status = 'error';
                    this.statusMessage = 'Gagal menghubungi server verifikasi. Periksa koneksi internet Anda.';
                    this.student = null;
                } finally {
                    this.isLoading = false;
                }
            },

            // Canvas Drawing Logic
            initCanvas() {
                this.canvas = document.getElementById('signature-canvas');
                if (!this.canvas) return;

                const rect = this.canvas.getBoundingClientRect();
                const dpr = window.devicePixelRatio || 1;
                this.canvas.width = rect.width * dpr;
                this.canvas.height = rect.height * dpr;

                this.ctx = this.canvas.getContext('2d');
                this.ctx.scale(dpr, dpr);
                this.ctx.strokeStyle = '#940c10'; // UBSI Red digital ink
                this.ctx.lineWidth = 2.8;
                this.ctx.lineCap = 'round';
                this.ctx.lineJoin = 'round';
            },

            startDrawing(e) {
                this.isDrawing = true;
                const pos = this.getCanvasPos(e.clientX, e.clientY);
                this.ctx.beginPath();
                this.ctx.moveTo(pos.x, pos.y);
                this.hasSignature = true;
            },

            draw(e) {
                if (!this.isDrawing) return;
                const pos = this.getCanvasPos(e.clientX, e.clientY);
                this.ctx.lineTo(pos.x, pos.y);
                this.ctx.stroke();
                this.hasSignature = true;
            },

            startDrawingTouch(e) {
                e.preventDefault();
                if (e.touches.length > 0) {
                    this.isDrawing = true;
                    const touch = e.touches[0];
                    const pos = this.getCanvasPos(touch.clientX, touch.clientY);
                    this.ctx.beginPath();
                    this.ctx.moveTo(pos.x, pos.y);
                    this.hasSignature = true;
                }
            },

            drawTouch(e) {
                e.preventDefault();
                if (!this.isDrawing || e.touches.length === 0) return;
                const touch = e.touches[0];
                const pos = this.getCanvasPos(touch.clientX, touch.clientY);
                this.ctx.lineTo(pos.x, pos.y);
                this.ctx.stroke();
                this.hasSignature = true;
            },

            stopDrawing() {
                if (this.isDrawing) {
                    this.isDrawing = false;
                    this.ctx.closePath();
                }
            },

            getCanvasPos(clientX, clientY) {
                const rect = this.canvas.getBoundingClientRect();
                return {
                    x: clientX - rect.left,
                    y: clientY - rect.top
                };
            },

            clearSignature() {
                if (!this.canvas || !this.ctx) return;
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                this.hasSignature = false;
            },

            async submitVerification() {
                if (!this.student || !this.hasSignature || this.isSubmitting) return;

                const signatureData = this.canvas.toDataURL('image/png');
                this.isSubmitting = true;

                try {
                    const response = await fetch(config.storeUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': config.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            nim: this.student.nim,
                            signature: signatureData,
                            tps_location: this.selectedCampus,
                            phone: this.phone,
                            email: this.email
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.lastVerifiedName = this.student.name;
                        this.lastVerifiedNim = this.student.nim;
                        this.openSuccessModal();
                    } else {
                        alert(data.message || 'Gagal menyimpan kehadiran.');
                    }
                } catch (err) {
                    alert('Terjadi kesalahan jaringan saat menyimpan.');
                } finally {
                    this.isSubmitting = false;
                }
            },

            openSuccessModal() {
                this.showSuccessModal = true;
                this.countdown = 5;

                if (this.countdownInterval) clearInterval(this.countdownInterval);

                this.countdownInterval = setInterval(() => {
                    this.countdown--;
                    if (this.countdown <= 0) {
                        this.closeSuccessModal();
                    }
                }, 1000);
            },

            closeSuccessModal() {
                if (this.countdownInterval) clearInterval(this.countdownInterval);
                this.showSuccessModal = false;
                this.resetAll();
            },

            resetAll() {
                this.currentStep = 0;
                this.selectedCampus = '';
                this.nim = '';
                this.fullName = '';
                this.studyProgram = '';
                this.className = '';
                this.phone = '';
                this.email = '';
                this.status = 'idle';
                this.statusMessage = '';
                this.student = null;
                this.clearSignature();
            }
        }));
    });
    </script>
</body>
</html>
