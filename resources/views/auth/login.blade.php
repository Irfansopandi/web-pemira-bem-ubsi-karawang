<x-layouts.auth title="Bilik Suara - PEMIRA BEM UBSI Karawang">
    <div class="w-full max-w-4xl lg:max-w-5xl xl:max-w-[1040px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-7 sm:gap-8 lg:gap-10 xl:gap-12 items-stretch justify-center">
        
        <!-- ======================================================== -->
        <!-- KARTU 1 (SEBELAH KIRI): TAHAPAN E-VOTING (GLASSMORPHISM) -->
        <!-- ======================================================== -->
        <div class="lg:col-span-5 bg-[#180306]/70 backdrop-blur-2xl border border-white/10 rounded-2xl xl:rounded-3xl shadow-2xl shadow-black/60 p-4 sm:p-5 xl:p-6 flex flex-col justify-between transition-all duration-300 hover:border-white/20">
            
            <!-- Header Kartu Kiri: Icon Ballot + Judul Tahapan -->
            <div class="flex items-center gap-3 mb-3 xl:mb-4">
                <!-- Red Glowing Square Icon Box -->
                <div class="w-10 h-10 xl:w-11 xl:h-11 rounded-xl bg-gradient-to-br from-[#D71920] to-[#7A0A0D] shadow-md shadow-red-700/50 border border-red-400/30 flex items-center justify-center shrink-0">
                    <!-- Ballot Box with Checkmark SVG -->
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 10h16v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10z" />
                        <path d="M2 10h20" />
                        <path d="M9 10v-1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1" />
                        <path d="M8 5h8v4H8z" fill="white" fill-opacity="0.25" />
                        <polyline points="10 7 11.5 8.5 14 6" stroke="white" stroke-width="1.8" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm xl:text-base font-extrabold text-white tracking-wider uppercase leading-tight">
                        ALUR E-VOTING TPS
                    </h3>
                    <p class="text-[10px] xl:text-[11px] font-bold text-[#E5A900] tracking-wider uppercase mt-0.5 truncate">
                        PEMIRA BEM UBSI Karawang
                    </p>
                </div>
            </div>

            <!-- Body Kartu Kiri: Daftar 4 Tahapan E-Voting dengan Garis Vertikal & Pill Transparan -->
            <div class="relative space-y-2 xl:space-y-2.5 my-auto">
                <!-- Garis Penghubung Vertikal Antar Tahap -->
                <div class="absolute left-3.5 top-3 bottom-3 w-[2px] bg-white/10 -z-0"></div>

                <!-- Tahap 1: Registrasi Buku Tamu & Verifikasi NIM -->
                <div class="relative z-10 flex items-center gap-2.5 group">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#FF5E3A] to-[#D71920] text-white font-bold text-[11px] flex items-center justify-center shadow-md shadow-red-900/50 ring-2 ring-[#180306] shrink-0">
                        1
                    </div>
                    <div class="flex-1 bg-white/[0.05] group-hover:bg-white/[0.08] border border-white/10 rounded-xl p-2.5 xl:p-3 flex items-center gap-2.5 transition-all duration-200">
                        <div class="w-8 h-8 rounded-lg bg-white/[0.07] border border-white/10 flex items-center justify-center text-white/90 shrink-0 group-hover:scale-105 group-hover:bg-white/[0.12] transition-transform">
                            <!-- User Check / Book Icon -->
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <polyline points="16 11 18 13 22 9" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-[11px] xl:text-xs font-bold text-white uppercase tracking-wide">
                                BUKU TAMU & INPUT NIM
                            </h4>
                            <p class="text-[10px] xl:text-[11px] text-slate-300 leading-snug font-normal mt-0.5">
                                Isi buku tamu di meja panitia TPS, lalu cukup ketik NIM Anda di bilik suara tanpa perlu sandi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tahap 2: Tinjau Paslon & Visi Misi -->
                <div class="relative z-10 flex items-center gap-2.5 group">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#FF5E3A] to-[#D71920] text-white font-bold text-[11px] flex items-center justify-center shadow-md shadow-red-900/50 ring-2 ring-[#180306] shrink-0">
                        2
                    </div>
                    <div class="flex-1 bg-white/[0.05] group-hover:bg-white/[0.08] border border-white/10 rounded-xl p-2.5 xl:p-3 flex items-center gap-2.5 transition-all duration-200">
                        <div class="w-8 h-8 rounded-lg bg-white/[0.07] border border-white/10 flex items-center justify-center text-white/90 shrink-0 group-hover:scale-105 group-hover:bg-white/[0.12] transition-transform">
                            <!-- Users / Paslon Icon -->
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-[11px] xl:text-xs font-bold text-white uppercase tracking-wide">
                                TINJAU PASLON BEM
                            </h4>
                            <p class="text-[10px] xl:text-[11px] text-slate-300 leading-snug font-normal mt-0.5">
                                Pelajari visi, misi, dan program kerja pasangan calon Ketua & Wakil Ketua BEM.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tahap 3: Gunakan Hak Suara -->
                <div class="relative z-10 flex items-center gap-2.5 group">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#FF5E3A] to-[#D71920] text-white font-bold text-[11px] flex items-center justify-center shadow-md shadow-red-900/50 ring-2 ring-[#180306] shrink-0">
                        3
                    </div>
                    <div class="flex-1 bg-white/[0.05] group-hover:bg-white/[0.08] border border-white/10 rounded-xl p-2.5 xl:p-3 flex items-center gap-2.5 transition-all duration-200">
                        <div class="w-8 h-8 rounded-lg bg-white/[0.07] border border-white/10 flex items-center justify-center text-white/90 shrink-0 group-hover:scale-105 group-hover:bg-white/[0.12] transition-transform">
                            <!-- Checklist / Vote Icon -->
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                <polyline points="3 6 4 7 6 5"></polyline>
                                <polyline points="3 12 4 13 6 11"></polyline>
                                <polyline points="3 18 4 19 6 17"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-[11px] xl:text-xs font-bold text-white uppercase tracking-wide">
                                COBLOS PILIHAN ANDA
                            </h4>
                            <p class="text-[10px] xl:text-[11px] text-slate-300 leading-snug font-normal mt-0.5">
                                Tentukan pilihan terbaik Anda di bilik suara digital secara langsung, bebas, dan rahasia.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tahap 4: Konfirmasi & Tanda Terima -->
                <div class="relative z-10 flex items-center gap-2.5 group">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#FF5E3A] to-[#D71920] text-white font-bold text-[11px] flex items-center justify-center shadow-md shadow-red-900/50 ring-2 ring-[#180306] shrink-0">
                        4
                    </div>
                    <div class="flex-1 bg-white/[0.05] group-hover:bg-white/[0.08] border border-white/10 rounded-xl p-2.5 xl:p-3 flex items-center gap-2.5 transition-all duration-200">
                        <div class="w-8 h-8 rounded-lg bg-white/[0.07] border border-white/10 flex items-center justify-center text-white/90 shrink-0 group-hover:scale-105 group-hover:bg-white/[0.12] transition-transform">
                            <!-- Confirmation / Shield Check Icon -->
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-[11px] xl:text-xs font-bold text-white uppercase tracking-wide">
                                SELESAI & TANDA TERIMA
                            </h4>
                            <p class="text-[10px] xl:text-[11px] text-slate-300 leading-snug font-normal mt-0.5">
                                Suara dienkripsi secara anonim dan Anda menerima bukti tanda terima resmi pemilih.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Kartu Kiri: Asas Pemira UBSI Karawang (Murni Tanpa Badge) -->
            <div class="mt-3 xl:mt-4 bg-white/[0.05] border border-white/10 rounded-xl py-2.5 px-3.5 xl:px-4 flex items-center gap-2.5">
                <div class="w-4 h-4 rounded-full bg-red-600/40 border border-red-500/60 text-red-300 flex items-center justify-center text-[10px] font-bold shrink-0">
                    i
                </div>
                <p class="text-[11px] text-slate-200">
                    <span class="text-[#FF4D4D] font-bold">Asas Pemilihan:</span> Langsung, Umum, Bebas, Rahasia, Jujur & Adil.
                </p>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- KARTU 2 (SEBELAH KANAN): FORMULIR BILIK SUARA MAHASISWA -->
        <!-- ======================================================== -->
        <div class="lg:col-span-7 bg-white rounded-2xl xl:rounded-3xl shadow-2xl shadow-black/70 overflow-hidden flex flex-col justify-between border border-white/10">
            
            <!-- Header Kartu Kanan: Background Gradient Maroon & Wave Divider -->
            <div class="bg-gradient-to-b from-[#52000D] via-[#7A0A0D] to-[#940C10] px-5 sm:px-6 xl:px-7 pt-5 sm:pt-6 pb-7 sm:pb-8 text-center text-white relative overflow-hidden">
                
                <!-- Foto Gedung Kampus UBSI Cikampek (Sebelah Kiri) dengan Gradasi Halus -->
                <div class="absolute left-0 top-0 bottom-0 w-5/12 sm:w-1/2 pointer-events-none select-none overflow-hidden z-0">
                    <img 
                        src="{{ asset('images/kampus/Cikampek.png') }}" 
                        alt="UBSI Kampus Cikampek" 
                        class="w-full h-full object-cover object-center opacity-30 mix-blend-luminosity filter contrast-125 [mask-image:linear-gradient(to_right,rgba(0,0,0,1)_20%,rgba(0,0,0,0)_100%)] [-webkit-mask-image:linear-gradient(to_right,rgba(0,0,0,1)_20%,rgba(0,0,0,0)_100%)]"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#7A0A0D]/50 to-[#7A0A0D]"></div>
                </div>

                <!-- Foto Gedung Kampus UBSI Karawang (Sebelah Kanan) dengan Gradasi Halus -->
                <div class="absolute right-0 top-0 bottom-0 w-5/12 sm:w-1/2 pointer-events-none select-none overflow-hidden z-0">
                    <img 
                        src="{{ asset('images/kampus/Karawang.png') }}" 
                        alt="UBSI Kampus Karawang" 
                        class="w-full h-full object-cover object-center opacity-30 mix-blend-luminosity filter contrast-125 [mask-image:linear-gradient(to_left,rgba(0,0,0,1)_20%,rgba(0,0,0,0)_100%)] [-webkit-mask-image:linear-gradient(to_left,rgba(0,0,0,1)_20%,rgba(0,0,0,0)_100%)]"
                    >
                    <div class="absolute inset-0 bg-gradient-to-l from-transparent via-[#7A0A0D]/50 to-[#7A0A0D]"></div>
                </div>

                <!-- Ambient Shading Tengah agar Teks Tetap Sangat Terbaca & Kontras Tinggi -->
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(122,10,13,0.85)_0%,rgba(82,0,13,0.4)_70%,transparent_100%)] pointer-events-none z-0"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-[#52000D]/70 via-transparent to-[#940C10] pointer-events-none z-0"></div>

                <!-- 3 Logo Resmi: Universitas BSI, BEM UBSI, Kabinet/PEMIRA -->
                <div class="relative z-10 flex items-center justify-center gap-3.5 sm:gap-4 mb-2 sm:mb-2.5">
                    <img src="{{ asset('images/logo-ubsi.png') }}" alt="Logo Universitas BSI" class="w-11 h-11 sm:w-12 sm:h-12 object-contain drop-shadow-md hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI Karawang" class="w-11 h-11 sm:w-12 sm:h-12 object-contain drop-shadow-md hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('images/logo-kabinet.png') }}" alt="Logo Kabinet BEM UBSI Karawang" class="w-11 h-11 sm:w-12 sm:h-12 object-contain drop-shadow-md hover:scale-105 transition-transform duration-200">
                </div>

                <!-- Judul Utama Diapit Golden Laurel Wreath (Daun Emas) -->
                <div class="relative z-10 flex items-center justify-center gap-1.5 sm:gap-2">
                    <!-- Left Laurel Branch -->
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

                    <!-- Title Text -->
                    <div class="text-center">
                        <h2 class="text-base sm:text-lg xl:text-xl font-extrabold tracking-wider text-white uppercase leading-tight">
                            PEMILIHAN UMUM RAYA
                        </h2>
                        <p class="text-[11px] sm:text-xs font-bold text-[#E5A900] tracking-widest uppercase mt-0.5">
                            BEM UBSI KAMPUS KARAWANG
                        </p>
                    </div>

                    <!-- Right Laurel Branch (Mirrored) -->
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

                <!-- Subtext Petunjuk Akses -->
                <p class="relative z-10 text-[11px] sm:text-xs text-slate-200 mt-1 sm:mt-1.5 font-normal max-w-sm mx-auto leading-relaxed">
                    Bilik Suara Digital — Silakan masukkan NIM Anda setelah mengisi buku tamu TPS.
                </p>

                <!-- Pemisah Garis Gelombang Organik (SVG Wave Divider) -->
                <div class="absolute -bottom-[1px] left-0 right-0 w-full overflow-hidden leading-none z-10 pointer-events-none">
                    <svg class="w-full h-6 sm:h-7 block text-white" viewBox="0 0 1000 100" preserveAspectRatio="none">
                        <path d="M 0,40 C 300,40 450,85 700,75 C 850,70 950,45 1000,35 L 1000,100 L 0,100 Z" fill="currentColor"></path>
                    </svg>
                </div>
            </div>

            <!-- Body Kartu Kanan: Form Input Putih Bersih (Pure White Section Khusus Mahasiswa) -->
            <div class="px-5 sm:px-6 xl:px-7 py-5 sm:py-6 flex-1 flex flex-col justify-center bg-white" x-data="{ isSubmitting: false }">
                
                <div class="w-full max-w-md mx-auto my-auto space-y-3.5">
                    <!-- ALERT INFO (Misal: setelah voting atau redirect) -->
                    @if (session('info'))
                        <div class="p-3 rounded-xl bg-amber-50/90 border border-amber-200 text-amber-800 flex items-start gap-2.5 shadow-sm">
                            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <div class="text-[11px] sm:text-xs">
                                <span class="font-bold block text-amber-900">Informasi Pemilih</span>
                                <span class="text-amber-700 font-normal leading-relaxed">{{ session('info') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- ALERT ERROR: Validasi / Buku Tamu -->
                    @if ($errors->any())
                        <div class="py-2 px-3 rounded-xl bg-red-50/90 border border-red-200 text-red-700 flex items-center gap-2 shadow-sm">
                            <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            <p class="text-[11px] sm:text-xs leading-tight whitespace-nowrap overflow-hidden text-ellipsis">
                                <span class="font-bold text-red-800">Perhatian:</span>
                                <span class="text-red-700 font-medium">
                                    {{ $errors->first('identifier') ?: $errors->first() }}
                                </span>
                            </p>
                        </div>
                    @endif

                    <!-- FORMULIR MASUK BILIK SUARA (HANYA SATU KOLOM INPUT NIM NUMERIK) -->
                    <form method="POST" action="{{ route('login.submit') }}" class="space-y-4" @submit="isSubmitting = true" novalidate>
                        @csrf

                        <div>
                            <label for="identifier" class="flex items-center gap-1.5 text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-700 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <span>Nomor Induk Mahasiswa (NIM)</span>
                            </label>

                            <input 
                                type="text" 
                                name="identifier" 
                                id="identifier" 
                                value="{{ old('identifier') }}"
                                placeholder="Contoh: 12220001" 
                                inputmode="numeric"
                                pattern="[0-9]*"
                                maxlength="20"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                autofocus
                                class="w-full px-4 py-3 bg-[#F4F5F8] border {{ $errors->has('identifier') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-slate-200 focus:ring-[#940C10] focus:border-transparent' }} rounded-xl text-sm sm:text-base font-bold text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 transition-all shadow-sm tracking-wider"
                            >
                            
                            <p class="text-[11px] text-slate-500 mt-1.5 font-normal flex items-center gap-1.5 whitespace-nowrap overflow-hidden text-ellipsis">
                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                <span>Pastikan sudah isi buku tamu di meja TPS.</span>
                            </p>
                        </div>

                        <!-- Tombol Buka Bilik Suara -->
                        <div class="pt-1">
                            <button 
                                type="submit" 
                                :disabled="isSubmitting"
                                class="w-full py-3 px-5 bg-gradient-to-r from-[#940C10] via-[#B51016] to-[#D71920] hover:from-[#7A0A0D] hover:via-[#940C10] hover:to-[#B51016] disabled:opacity-75 disabled:cursor-wait text-white font-bold rounded-xl shadow-lg shadow-red-900/35 hover:shadow-red-800/50 hover:brightness-105 active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#940C10]">
                                
                                <!-- State Normal -->
                                <span x-show="!isSubmitting" class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M9 11l3 3L22 4" />
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                                    </svg>
                                    <span class="text-xs sm:text-sm tracking-wide">Buka Bilik Suara</span>
                                </span>

                                <!-- State Loading Spinner -->
                                <span x-show="isSubmitting" x-cloak class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-xs sm:text-sm tracking-wide">Memverifikasi DPT & Kehadiran...</span>
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Footer Kartu Kanan: Bantuan TPS (Dinaikkan dekat tombol agar tidak menyisakan space kosong raksasa) -->
                    <div class="pt-3 mt-4 border-t border-slate-100 flex items-center justify-center text-xs text-slate-500">
                        <span class="flex items-center gap-1.5 whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4" />
                                <path d="M12 8h.01" />
                            </svg>
                            <span>Butuh bantuan? Hubungi panitia di lokasi TPS.</span>
                        </span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-layouts.auth>
