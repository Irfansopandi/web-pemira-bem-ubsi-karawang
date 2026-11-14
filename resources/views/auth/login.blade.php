<x-layouts.auth title="Login">
    <div class="w-full max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-stretch">
        
        <!-- KARTU 1 (SEBELAH KIRI): TAHAPAN E-VOTING (TRANSPARAN MELAYANG - TANPA GARIS PUTIH) -->
        <div class="lg:col-span-5 bg-white/85 backdrop-blur-xl border-0 rounded-2xl shadow-2xl shadow-black/30 overflow-hidden flex flex-col justify-between transition-all duration-300 hover:bg-white/90">
            <!-- Header Kartu Kiri -->
            <div class="bg-gradient-to-b from-[#940c10]/95 to-[#7A0A0D]/95 backdrop-blur-md p-5 sm:p-6 text-[#FDFDFD] text-center border-b-2 border-[#CE9501]">
                <h3 class="text-base sm:text-lg font-bold tracking-tight text-[#FDFDFD] uppercase">
                    Tahapan E-Voting
                </h3>
                <p class="text-xs font-semibold text-[#CE9501] tracking-wider uppercase mt-0.5">
                    PEMIRA BEM UBSI Karawang
                </p>
                <p class="text-[11px] text-slate-200 mt-1.5 leading-relaxed px-2">
                    Alur resmi pemungutan suara pesta demokrasi mahasiswa:
                </p>
            </div>

            <!-- Body Kartu Kiri: Daftar 4 Tahapan -->
            <div class="p-6 sm:p-7 flex-1 flex flex-col justify-center space-y-4">
                <!-- Tahap 1 -->
                <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-full bg-[#940c10] text-[#FDFDFD] border border-[#CE9501] font-extrabold text-xs flex items-center justify-center shrink-0 shadow-sm">
                        1
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">
                            Verifikasi & Masuk Akun
                        </h4>
                        <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                            Masukkan NIM dan kata sandi resmi yang telah terdaftar pada DPT UBSI Karawang.
                        </p>
                    </div>
                </div>

                <!-- Tahap 2 -->
                <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-full bg-[#940c10] text-[#FDFDFD] border border-[#CE9501] font-extrabold text-xs flex items-center justify-center shrink-0 shadow-sm">
                        2
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">
                            Tinjau Paslon & Visi Misi
                        </h4>
                        <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                            Pelajari gagasan, visi, misi, dan program kerja pasangan calon Ketua & Wakil Ketua BEM.
                        </p>
                    </div>
                </div>

                <!-- Tahap 3 -->
                <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-full bg-[#940c10] text-[#FDFDFD] border border-[#CE9501] font-extrabold text-xs flex items-center justify-center shrink-0 shadow-sm">
                        3
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">
                            Gunakan Hak Suara
                        </h4>
                        <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                            Tentukan pilihan terbaik Anda di bilik suara digital secara langsung, bebas, dan rahasia.
                        </p>
                    </div>
                </div>

                <!-- Tahap 4 -->
                <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-full bg-[#940c10] text-[#FDFDFD] border border-[#CE9501] font-extrabold text-xs flex items-center justify-center shrink-0 shadow-sm">
                        4
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">
                            Konfirmasi & Selesai
                        </h4>
                        <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                            Konfirmasi pilihan Anda. Surat suara tercatat secara aman dan dijamin kerahasiaannya.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer Kartu Kiri: Asas Pemira -->
            <div class="px-6 py-3.5 bg-slate-50/70 backdrop-blur-sm border-t border-slate-200/50 text-center">
                <p class="text-[11px] text-slate-600">
                    <span class="font-bold text-[#940c10]">Asas Pemilihan:</span> Langsung, Umum, Bebas, Rahasia, Jujur & Adil.
                </p>
            </div>
        </div>

        <!-- KARTU 2 (SEBELAH KANAN): FORMULIR LOGIN (TRANSPARAN MELAYANG - TANPA GARIS PUTIH) -->
        <div class="lg:col-span-7 bg-white/85 backdrop-blur-xl border-0 rounded-2xl shadow-2xl shadow-black/30 overflow-hidden flex flex-col justify-between transition-all duration-300 hover:bg-white/90">
            <!-- Header Kartu Kanan: Identitas Resmi BEM UBSI Karawang -->
            <div class="bg-gradient-to-b from-[#940c10]/95 to-[#7A0A0D]/95 backdrop-blur-md p-6 text-center text-[#FDFDFD] relative border-b-2 border-[#CE9501]">
                <!-- 3 Logo Resmi: Ukuran Sama Rata & Jarak Rapat Proporsional -->
                <div class="flex items-center justify-center gap-3 sm:gap-4 mb-3">
                    <!-- 1. Logo Universitas BSI -->
                    <img src="{{ asset('images/logo-ubsi.png') }}" alt="Logo Universitas BSI" class="w-14 h-14 sm:w-16 sm:h-16 object-contain drop-shadow-md">
                    <!-- 2. Logo BEM UBSI Karawang -->
                    <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI Karawang" class="w-14 h-14 sm:w-16 sm:h-16 object-contain drop-shadow-md">
                    <!-- 3. Logo Kabinet BEM (Swarnadipa Pratibhana) -->
                    <img src="{{ asset('images/logo-kabinet.png') }}" alt="Logo Kabinet BEM UBSI Karawang" class="w-14 h-14 sm:w-16 sm:h-16 object-contain drop-shadow-md">
                </div>
                <h2 class="text-lg sm:text-xl font-bold tracking-tight text-[#FDFDFD] leading-tight">
                    PEMILIHAN UMUM RAYA
                </h2>
                <p class="text-xs font-semibold text-[#CE9501] tracking-widest uppercase mt-0.5">
                    BEM UBSI Kampus Karawang
                </p>
                <p class="text-xs text-slate-200 mt-1.5 font-normal tracking-normal px-2">
                    Silakan masuk menggunakan kredensial resmi untuk mengakses sistem pemilihan.
                </p>
            </div>

            <!-- Body Kartu Kanan: Form Input -->
            <div class="p-6 sm:p-8 flex-1 flex flex-col justify-center" x-data="{ isSubmitting: false, showPassword: false }">
                
                <!-- ALERT DI ATAS INPUT USERNAME: Tampil jika keduanya salah / format salah / kredensial tidak cocok -->
                @if ($errors->has('general') || ($errors->has('identifier') && $errors->has('password')))
                    <div class="mb-4 p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-700 flex items-start gap-2.5 shadow-sm">
                        <svg class="w-4 h-4 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <div class="text-xs">
                            <span class="font-bold block">Autentikasi Gagal</span>
                            <span class="text-[11px] text-red-600">
                                {{ $errors->first('general') ?: 'Username dan Kata Sandi yang Anda masukkan tidak sesuai.' }}
                            </span>
                        </div>
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-4" @submit="isSubmitting = true" novalidate>
                    @csrf

                    <!-- Field 1: USERNAME (NIM) -->
                    <div>
                        <label for="identifier" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                            USERNAME
                        </label>
                        <input 
                            type="text" 
                            name="identifier" 
                            id="identifier" 
                            value="{{ old('identifier') }}"
                            placeholder="12220001" 
                            autofocus
                            class="w-full px-3.5 py-2.5 bg-white/80 border {{ $errors->has('identifier') && !$errors->has('general') && !$errors->has('password') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-slate-300 focus:ring-[#940c10] focus:border-[#940c10]' }} rounded-lg text-sm text-[#1A1A1A] placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 transition-colors"
                        >
                        
                        <!-- Validasi khusus jika hanya Username yang salah / kosong -->
                        @if ($errors->has('identifier') && !$errors->has('general') && !$errors->has('password'))
                            <p class="text-[11px] text-red-600 font-semibold mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 7.5h.008v.008H12v-.008z" />
                                </svg>
                                <span>{{ $errors->first('identifier') }}</span>
                            </p>
                        @else
                            <p class="text-[11px] text-slate-500 mt-1">
                                Masukkan NIM anda untuk login.
                            </p>
                        @endif
                    </div>

                    <!-- Field 2: PASSWORD (DILENGKAPI TOGGLE SHOW / HIDE PASSWORD DENGAN ICON MATA) -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                            PASSWORD
                        </label>
                        <div class="relative flex items-center">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                name="password" 
                                id="password" 
                                placeholder="••••••••" 
                                class="w-full pl-3.5 pr-11 py-2.5 bg-white/80 border {{ $errors->has('password') && !$errors->has('general') && !$errors->has('identifier') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-slate-300 focus:ring-[#940c10] focus:border-[#940c10]' }} rounded-lg text-sm text-[#1A1A1A] placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 transition-colors"
                            >
                            <!-- Tombol Icon Mata (Show/Hide Password) -->
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute right-2.5 p-1 text-slate-400 hover:text-slate-700 focus:outline-none transition-colors cursor-pointer"
                                tabindex="-1"
                                :title="showPassword ? 'Sembunyikan password' : 'Lihat password'"
                            >
                                <!-- Icon Mata Terbuka (Saat Password Tampil) -->
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <!-- Icon Mata Tertutup (Saat Password Tersembunyi) -->
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>

                        <!-- Validasi khusus jika hanya Password yang salah / kosong -->
                        @if ($errors->has('password') && !$errors->has('general') && !$errors->has('identifier'))
                            <p class="text-[11px] text-red-600 font-semibold mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 7.5h.008v.008H12v-.008z" />
                                </svg>
                                <span>{{ $errors->first('password') }}</span>
                            </p>
                        @else
                            <p class="text-[11px] text-slate-500 mt-1">
                                Masukkan password menggunakan tanggal lahir dengan format YYYY-MM-DD.
                            </p>
                        @endif
                    </div>

                    <!-- Tombol Login dengan Status Loading & Spinner saat diklik -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            :disabled="isSubmitting"
                            class="w-full py-3 px-4 bg-[#940c10] hover:bg-[#7A0A0D] disabled:opacity-75 disabled:cursor-wait text-[#FDFDFD] font-bold rounded-lg shadow-lg shadow-[#940c10]/25 border-0 transition-all duration-150 flex items-center justify-center cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#940c10]">
                            
                            <!-- State Normal -->
                            <span x-show="!isSubmitting" class="flex items-center justify-center">
                                <span>Login</span>
                            </span>

                            <!-- State Loading dengan Spinner saat proses masuk -->
                            <span x-show="isSubmitting" x-cloak class="flex items-center justify-center gap-2.5">
                                <svg class="animate-spin h-4 w-4 text-[#FDFDFD]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Masuk...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.auth>
