<x-layouts.auth title="Masuk">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden">
        
        <!-- Header Card: BEM UBSI Karawang Identity -->
        <div class="bg-gradient-to-b from-[#940c10] to-[#7A0A0D] p-6 sm:p-7 text-center text-[#FDFDFD] relative border-b-2 border-[#CE9501]">
            <div class="flex justify-center mb-3">
                <x-bem-logo size="lg" />
            </div>
            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-[#FDFDFD]">
                PEMILIHAN UMUM RAYA
            </h2>
            <p class="text-xs font-semibold text-[#CE9501] tracking-widest uppercase mt-0.5">
                BEM UBSI Kampus Karawang
            </p>
            <p class="text-xs text-slate-200 mt-2 font-normal max-w-xs mx-auto">
                Silakan masuk menggunakan kredensial resmi untuk mengakses sistem pemilihan.
            </p>
        </div>

        <!-- Single Unified Authentication Form -->
        <div class="p-6 sm:p-8">
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                @csrf

                <!-- Session Flash Alerts -->
                @if ($errors->any())
                    <div class="p-3.5 rounded-lg bg-red-50 border border-red-200 text-xs text-red-700">
                        <div class="flex items-center gap-2 font-bold mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>Autentikasi Gagal</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-xs text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Field 1: Identifier (NIM for Student, Username/Email for Admin/Committee) -->
                <div>
                    <label for="identifier" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        NIM atau Username / Email
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="identifier" 
                            id="identifier" 
                            value="{{ old('identifier') }}"
                            placeholder="Contoh: 12210123 atau username" 
                            required 
                            autofocus
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-[#1A1A1A] placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#940c10] focus:border-[#940c10] transition-colors"
                        >
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">
                        Masukkan NIM (untuk Mahasiswa DPT) atau Username/Email (untuk Petugas/Admin).
                    </p>
                </div>

                <!-- Field 2: Password / Credential -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi / Kredensial TPS
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        placeholder="••••••••" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-[#1A1A1A] placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#940c10] focus:border-[#940c10] transition-colors"
                    >
                    <p class="text-[11px] text-slate-500 mt-1">
                        Kredensial resmi yang diterbitkan oleh Panitia PEMIRA BEM UBSI Karawang.
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-3 px-4 bg-[#940c10] hover:bg-[#7A0A0D] text-[#FDFDFD] font-bold rounded-lg shadow-sm border border-[#7A0A0D] transition-colors duration-150 flex items-center justify-center gap-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#940c10]">
                        <span>Masuk ke Sistem</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#CE9501]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </div>
            </form>

            <!-- Automatic Authorization & Help Box -->
            <div class="mt-6 pt-5 border-t border-slate-200 space-y-3">
                <div class="flex items-start gap-2.5 p-3 rounded-lg bg-slate-50 border border-slate-200 text-xs text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#940c10] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <div>
                        <span class="font-bold text-slate-900">Otorisasi Otomatis:</span>
                        <p class="mt-0.5 text-[11px] text-slate-600 leading-relaxed">
                            Sistem akan secara otomatis memverifikasi dan mengarahkan Anda ke Bilik Suara Mahasiswa atau Panel Pengelola sesuai kewenangan hak akses Anda.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-2.5 p-3 rounded-lg bg-amber-50/70 border border-amber-200/60 text-xs text-amber-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#CE9501] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <div>
                        <span class="font-bold">Kendala Login?</span>
                        <p class="mt-0.5 text-[11px] text-amber-800 leading-relaxed">
                            Bagi mahasiswa yang belum terdaftar di DPT atau mengalami kendala kata sandi, silakan hubungi Panitia di meja verifikasi TPS.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.auth>
