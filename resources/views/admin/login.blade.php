<x-layouts.auth title="Login Administrator">
    <div class="w-full max-w-4xl lg:max-w-5xl xl:max-w-[1040px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-7 sm:gap-8 lg:gap-10 xl:gap-12 items-stretch justify-center">
        
        <!-- ======================================================== -->
        <!-- KARTU 1 (SEBELAH KIRI): INFORMASI AKSES ADMINISTRATOR   -->
        <!-- ======================================================== -->
        <div class="lg:col-span-5 bg-[#180306]/70 backdrop-blur-2xl border border-white/10 rounded-2xl xl:rounded-3xl shadow-2xl shadow-black/60 p-4 sm:p-5 xl:p-6 flex flex-col justify-between transition-all duration-300 hover:border-white/20">
            
            <!-- Header Kartu Kiri: Icon Shield + Judul Portal -->
            <div class="flex items-center gap-3 mb-3 xl:mb-4">
                <div class="w-10 h-10 xl:w-11 xl:h-11 rounded-xl bg-gradient-to-br from-[#CE9501] to-[#7A0A0D] shadow-md shadow-amber-900/50 border border-amber-400/30 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm xl:text-base font-extrabold text-white tracking-wider uppercase leading-tight">
                        PORTAL ADMINISTRATOR
                    </h3>
                    <p class="text-[10px] xl:text-[11px] font-bold text-[#E5A900] tracking-wider uppercase mt-0.5 truncate">
                        KPUM BEM UBSI Kab.Karawang
                    </p>
                </div>
            </div>

            <!-- Body Kartu Kiri: 3 Pilar Otoritas Administrator -->
            <div class="relative space-y-2 xl:space-y-2.5 my-auto">
                <div class="absolute left-3.5 top-3 bottom-3 w-[2px] bg-white/10 -z-0"></div>

                <!-- Pilar 1: Master Data & Paslon -->
                <div class="relative z-10 flex items-center gap-2.5 group">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#E5A900] to-[#940C10] text-white font-bold text-[11px] flex items-center justify-center shadow-md shadow-amber-900/50 ring-2 ring-[#180306] shrink-0">
                        1
                    </div>
                    <div class="flex-1 bg-white/[0.05] group-hover:bg-white/[0.08] border border-white/10 rounded-xl p-2.5 xl:p-3 flex items-center gap-2.5 transition-all duration-200">
                        <div class="w-8 h-8 rounded-lg bg-white/[0.07] border border-white/10 flex items-center justify-center text-white/90 shrink-0">
                            <svg class="w-3.5 h-3.5 text-[#E5A900]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1" />
                                <rect x="14" y="3" width="7" height="7" rx="1" />
                                <rect x="14" y="14" width="7" height="7" rx="1" />
                                <rect x="3" y="14" width="7" height="7" rx="1" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-[11px] xl:text-xs font-bold text-white uppercase tracking-wide">
                                MASTER DATA & PERIODE
                            </h4>
                            <p class="text-[10px] xl:text-[11px] text-slate-300 leading-snug font-normal mt-0.5">
                                Manajemen periode pemilihan, profil paslon BEM, visi misi, dan program kerja.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pilar 2: Monitoring & Rekapitulasi -->
                <div class="relative z-10 flex items-center gap-2.5 group">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#E5A900] to-[#940C10] text-white font-bold text-[11px] flex items-center justify-center shadow-md shadow-amber-900/50 ring-2 ring-[#180306] shrink-0">
                        2
                    </div>
                    <div class="flex-1 bg-white/[0.05] group-hover:bg-white/[0.08] border border-white/10 rounded-xl p-2.5 xl:p-3 flex items-center gap-2.5 transition-all duration-200">
                        <div class="w-8 h-8 rounded-lg bg-white/[0.07] border border-white/10 flex items-center justify-center text-white/90 shrink-0">
                            <svg class="w-3.5 h-3.5 text-[#E5A900]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <line x1="18" y1="20" x2="18" y2="10" />
                                <line x1="12" y1="20" x2="12" y2="4" />
                                <line x1="6" y1="20" x2="6" y2="14" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-[11px] xl:text-xs font-bold text-white uppercase tracking-wide">
                                REKAPITULASI SUARA
                            </h4>
                            <p class="text-[10px] xl:text-[11px] text-slate-300 leading-snug font-normal mt-0.5">
                                Pemantauan partisipasi pemilih DPT secara real-time dan rekapitulasi hasil pemungutan suara.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pilar 3: Keamanan & Integritas -->
                <div class="relative z-10 flex items-center gap-2.5 group">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#E5A900] to-[#940C10] text-white font-bold text-[11px] flex items-center justify-center shadow-md shadow-amber-900/50 ring-2 ring-[#180306] shrink-0">
                        3
                    </div>
                    <div class="flex-1 bg-white/[0.05] group-hover:bg-white/[0.08] border border-white/10 rounded-xl p-2.5 xl:p-3 flex items-center gap-2.5 transition-all duration-200">
                        <div class="w-8 h-8 rounded-lg bg-white/[0.07] border border-white/10 flex items-center justify-center text-white/90 shrink-0">
                            <svg class="w-3.5 h-3.5 text-[#E5A900]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-[11px] xl:text-xs font-bold text-white uppercase tracking-wide">
                                INTEGRITAS & AUDIT
                            </h4>
                            <p class="text-[10px] xl:text-[11px] text-slate-300 leading-snug font-normal mt-0.5">
                                Seluruh aktivitas sensitif tercatat secara ketat dalam audit trail sistem.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Kartu Kiri -->
            <div class="mt-3 xl:mt-4 bg-white/[0.05] border border-white/10 rounded-xl py-2.5 px-3.5 xl:px-4 flex items-center gap-2.5">
                <div class="w-4 h-4 rounded-full bg-amber-500/30 border border-amber-400/50 text-[#E5A900] flex items-center justify-center text-[10px] font-bold shrink-0">
                    !
                </div>
                <p class="text-[11px] text-slate-200">
                    <span class="text-[#E5A900] font-bold">Akses Terbatas:</span> Khusus akun Administrator resmi KPUM UBSI.
                </p>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- KARTU 2 (SEBELAH KANAN): FORMULIR LOGIN ADMINISTRATOR    -->
        <!-- ======================================================== -->
        <div class="lg:col-span-7 bg-white rounded-2xl xl:rounded-3xl shadow-2xl shadow-black/70 overflow-hidden flex flex-col justify-between border border-white/10">
            
            <!-- Header Kartu Kanan: Background Gradient Maroon & Wave Divider -->
            <div class="bg-gradient-to-b from-[#52000D] via-[#7A0A0D] to-[#940C10] px-5 sm:px-6 xl:px-7 pt-5 sm:pt-6 pb-7 sm:pb-8 text-center text-white relative overflow-hidden">
                
                <!-- Foto Gedung Kampus UBSI Cikampek (Sebelah Kiri) -->
                <div class="absolute left-0 top-0 bottom-0 w-5/12 sm:w-1/2 pointer-events-none select-none overflow-hidden z-0">
                    <img 
                        src="{{ asset('images/kampus/Cikampek.png') }}" 
                        alt="UBSI Kampus Cikampek" 
                        class="w-full h-full object-cover object-center opacity-30 mix-blend-luminosity filter contrast-125 [mask-image:linear-gradient(to_right,rgba(0,0,0,1)_20%,rgba(0,0,0,0)_100%)] [-webkit-mask-image:linear-gradient(to_right,rgba(0,0,0,1)_20%,rgba(0,0,0,0)_100%)]"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#7A0A0D]/50 to-[#7A0A0D]"></div>
                </div>

                <!-- Foto Gedung Kampus UBSI Karawang (Sebelah Kanan) -->
                <div class="absolute right-0 top-0 bottom-0 w-5/12 sm:w-1/2 pointer-events-none select-none overflow-hidden z-0">
                    <img 
                        src="{{ asset('images/kampus/Karawang.png') }}" 
                        alt="UBSI Kampus Kabupaten Karawang" 
                        class="w-full h-full object-cover object-center opacity-30 mix-blend-luminosity filter contrast-125 [mask-image:linear-gradient(to_left,rgba(0,0,0,1)_20%,rgba(0,0,0,0)_100%)] [-webkit-mask-image:linear-gradient(to_left,rgba(0,0,0,1)_20%,rgba(0,0,0,0)_100%)]"
                    >
                    <div class="absolute inset-0 bg-gradient-to-l from-transparent via-[#7A0A0D]/50 to-[#7A0A0D]"></div>
                </div>

                <!-- Ambient Shading Tengah -->
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(122,10,13,0.85)_0%,rgba(82,0,13,0.4)_70%,transparent_100%)] pointer-events-none z-0"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-[#52000D]/70 via-transparent to-[#940C10] pointer-events-none z-0"></div>

                <!-- 3 Logo Resmi -->
                <div class="relative z-10 flex items-center justify-center gap-3.5 sm:gap-4 mb-2 sm:mb-2.5">
                    <img src="{{ asset('images/logo-ubsi.png') }}" alt="Logo Universitas BSI" class="w-11 h-11 sm:w-12 sm:h-12 object-contain drop-shadow-md hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI Kab.Karawang" class="w-11 h-11 sm:w-12 sm:h-12 object-contain drop-shadow-md hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('images/logo-kabinet.png') }}?v={{ file_exists(public_path('images/logo-kabinet.png')) ? filemtime(public_path('images/logo-kabinet.png')) : 1 }}" alt="Logo Kabinet BEM UBSI Kab.Karawang" class="w-11 h-11 sm:w-12 sm:h-12 object-contain drop-shadow-md hover:scale-105 transition-transform duration-200">
                </div>

                <!-- Judul Diapit Daun Emas -->
                <div class="relative z-10 flex items-center justify-center gap-1.5 sm:gap-2">
                    <svg class="w-6 h-9 sm:w-7 sm:h-10 text-[#E5A900] shrink-0" viewBox="0 0 32 54" fill="currentColor">
                        <path d="M26 50C22 36 14 24 18 4" stroke="#E5A900" stroke-width="1.8" fill="none" stroke-linecap="round"/>
                        <path d="M24 47C20 45 16 48 14 51C18 52 22 51 24 47Z"/>
                        <path d="M23 39C17 37 12 39 9 43C14 44 19 43 23 39Z"/>
                        <path d="M27 41C30 38 31 34 29 30C26 33 26 37 27 41Z"/>
                        <path d="M20 31C14 28 9 29 6 34C11 35 16 34 20 31Z"/>
                        <path d="M25 32C28 28 29 23 26 19C24 23 24 28 25 32Z"/>
                        <path d="M19 22C13 18 9 19 6 24C11 25 16 24 19 22Z"/>
                        <path d="M22 23C25 18 25 13 22 9C20 13 21 18 22 23Z"/>
                        <path d="M18 14C13 10 10 10 7 15C11 16 15 15 18 14Z"/>
                        <path d="M20 13C21 8 20 4 17 1C15 5 17 10 20 13Z"/>
                    </svg>

                    <div class="text-center">
                        <h2 class="text-base sm:text-lg xl:text-xl font-extrabold tracking-wider text-white uppercase leading-tight">
                            PORTAL ADMINISTRATOR
                        </h2>
                        <p class="text-[11px] sm:text-xs font-bold text-[#E5A900] tracking-widest uppercase mt-0.5">
                            KOMISI PEMILIHAN UMUM MAHASISWA
                        </p>
                    </div>

                    <svg class="w-6 h-9 sm:w-7 sm:h-10 text-[#E5A900] shrink-0 transform -scale-x-100" viewBox="0 0 32 54" fill="currentColor">
                        <path d="M26 50C22 36 14 24 18 4" stroke="#E5A900" stroke-width="1.8" fill="none" stroke-linecap="round"/>
                        <path d="M24 47C20 45 16 48 14 51C18 52 22 51 24 47Z"/>
                        <path d="M23 39C17 37 12 39 9 43C14 44 19 43 23 39Z"/>
                        <path d="M27 41C30 38 31 34 29 30C26 33 26 37 27 41Z"/>
                        <path d="M20 31C14 28 9 29 6 34C11 35 16 34 20 31Z"/>
                        <path d="M25 32C28 28 29 23 26 19C24 23 24 28 25 32Z"/>
                        <path d="M19 22C13 18 9 19 6 24C11 25 16 24 19 22Z"/>
                        <path d="M22 23C25 18 25 13 22 9C20 13 21 18 22 23Z"/>
                        <path d="M18 14C13 10 10 10 7 15C11 16 15 15 18 14Z"/>
                        <path d="M20 13C21 8 20 4 17 1C15 5 17 10 20 13Z"/>
                    </svg>
                </div>

                <p class="relative z-10 text-[11px] sm:text-xs text-slate-200 mt-1 sm:mt-1.5 font-normal max-w-sm mx-auto leading-relaxed">
                    Silakan masukkan Username / Email dan Kata Sandi Administrator KPUM.
                </p>

                <!-- SVG Wave Divider -->
                <div class="absolute -bottom-[1px] left-0 right-0 w-full overflow-hidden leading-none z-10 pointer-events-none">
                    <svg class="w-full h-6 sm:h-7 block text-white" viewBox="0 0 1000 100" preserveAspectRatio="none">
                        <path d="M 0,40 C 300,40 450,85 700,75 C 850,70 950,45 1000,35 L 1000,100 L 0,100 Z" fill="currentColor"></path>
                    </svg>
                </div>
            </div>

            <!-- Body Kartu Kanan: Form Input Administrator -->
            <div class="p-5 sm:p-6 xl:p-7 flex-1 flex flex-col justify-between bg-white" x-data="{ isSubmitting: false, showPassword: false }">
                
                <div class="space-y-3.5 my-auto">
                    <!-- ALERT ERROR -->
                    @if ($errors->any())
                        @php
                            $errorMessage = $errors->first('general') ?: ($errors->first('identifier') ?: $errors->first('password'));
                            $isLoginRequired = str_contains($errorMessage, 'Silakan masuk terlebih dahulu') || str_contains($errorMessage, 'Akses ditolak');
                        @endphp
                        <div class="p-3 rounded-xl {{ $isLoginRequired ? 'bg-amber-50/90 border border-amber-200 text-amber-800' : 'bg-red-50/90 border border-red-200 text-red-700' }} flex items-start gap-2.5 shadow-sm">
                            <svg class="w-4 h-4 {{ $isLoginRequired ? 'text-amber-600' : 'text-red-600' }} shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            <div class="text-xs">
                                <span class="font-bold block {{ $isLoginRequired ? 'text-amber-900' : 'text-red-800' }}">
                                    {{ $isLoginRequired ? 'Akses Terbatas — Memerlukan Login' : 'Autentikasi Administrator Gagal' }}
                                </span>
                                <span class="text-[11px] {{ $isLoginRequired ? 'text-amber-700' : 'text-red-600' }} font-normal leading-relaxed">
                                    {{ $errorMessage }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-3.5" @submit="isSubmitting = true" novalidate>
                        @csrf

                        <!-- Field 1: USERNAME / EMAIL ADMINISTRATOR -->
                        <div>
                            <label for="identifier" class="flex items-center gap-1.5 text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-700 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <span>Username / Email Administrator</span>
                            </label>
                            <input 
                                type="text" 
                                name="identifier" 
                                id="identifier" 
                                value="{{ old('identifier') }}"
                                placeholder="Contoh: admin / admin.pemira@bsi.ac.id" 
                                autofocus
                                class="w-full px-3.5 py-2.5 sm:py-3 bg-[#F4F5F8] border {{ $errors->has('identifier') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-slate-200 focus:ring-[#7A0A0D] focus:border-transparent' }} rounded-xl text-xs sm:text-sm font-medium text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 transition-all shadow-sm"
                            >
                        </div>

                        <!-- Field 2: PASSWORD DENGAN TOGGLE MATA -->
                        <div>
                            <label for="password" class="flex items-center gap-1.5 text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-700 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                                <span>Kata Sandi (Password)</span>
                            </label>
                            <div class="relative flex items-center">
                                <input 
                                    :type="showPassword ? 'text' : 'password'" 
                                    name="password" 
                                    id="password" 
                                    placeholder="••••••••" 
                                    class="w-full pl-3.5 pr-10 py-2.5 sm:py-3 bg-[#F4F5F8] border {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-slate-200 focus:ring-[#7A0A0D] focus:border-transparent' }} rounded-xl text-xs sm:text-sm font-medium text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 transition-all shadow-sm"
                                >
                                <button 
                                    type="button" 
                                    @click="showPassword = !showPassword"
                                    class="absolute right-2.5 p-1 text-slate-400 hover:text-slate-700 focus:outline-none transition-colors cursor-pointer"
                                    tabindex="-1"
                                    :title="showPassword ? 'Sembunyikan password' : 'Lihat password'"
                                >
                                    <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Tombol Masuk Administrator -->
                        <div class="pt-2">
                            <button 
                                type="submit" 
                                :disabled="isSubmitting"
                                class="w-full py-2.5 sm:py-3 px-5 bg-gradient-to-r from-[#7A0A0D] via-[#940C10] to-[#B51016] hover:from-[#52000D] hover:via-[#7A0A0D] hover:to-[#940C10] disabled:opacity-75 disabled:cursor-wait text-white font-bold rounded-xl shadow-lg shadow-red-950/40 hover:shadow-red-900/50 hover:brightness-105 active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#7A0A0D]">
                                
                                <span x-show="!isSubmitting" class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                        <polyline points="10 17 15 12 10 7" />
                                        <line x1="15" y1="12" x2="3" y2="12" />
                                    </svg>
                                    <span class="text-xs sm:text-sm tracking-wide">Masuk Dashboard Administrator</span>
                                </span>

                                <span x-show="isSubmitting" x-cloak class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-xs sm:text-sm tracking-wide">Mengautentikasi...</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Kartu Kanan: Tautan ke Bilik Suara Mahasiswa -->
                <div class="pt-3.5 mt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 16v-4" />
                            <path d="M12 8h.01" />
                        </svg>
                        <span>Sistem Keamanan KPUM UBSI</span>
                    </span>

                    <a href="{{ route('login') }}" class="text-[#940C10] hover:text-[#7A0A0D] font-bold hover:underline transition-colors flex items-center gap-1">
                        <span>Bilik Suara Mahasiswa</span>
                        <span>&rarr;</span>
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-layouts.auth>
