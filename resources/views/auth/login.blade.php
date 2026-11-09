<x-layouts.auth title="Masuk">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden" x-data="{ role: 'student' }">
        
        <!-- Header Card: BEM UBSI Karawang Identity -->
        <div class="bg-gradient-to-b from-[#940c10] to-[#7A0A0D] p-6 text-center text-[#FDFDFD] relative border-b-2 border-[#CE9501]">
            <div class="flex justify-center mb-3">
                <x-bem-logo size="lg" />
            </div>
            <h2 class="text-xl font-bold tracking-tight text-[#FDFDFD]">
                PEMILIHAN UMUM RAYA
            </h2>
            <p class="text-xs font-semibold text-[#CE9501] tracking-widest uppercase mt-0.5">
                BEM UBSI Kampus Karawang
            </p>
            <p class="text-xs text-slate-200 mt-2 font-normal">
                Gunakan hak pilih Anda untuk masa depan demokrasi kampus yang berintegritas.
            </p>
        </div>

        <!-- Role Selector Tabs -->
        <div class="grid grid-cols-2 p-1.5 bg-slate-100 border-b border-slate-200 text-xs font-semibold">
            <button 
                type="button" 
                @click="role = 'student'" 
                :class="role === 'student' ? 'bg-white text-[#940c10] shadow-sm border border-slate-200 font-bold' : 'text-slate-600 hover:text-slate-900'"
                class="py-2.5 rounded-lg transition-all duration-150 flex items-center justify-center gap-1.5 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                <span>Mahasiswa (DPT)</span>
            </button>
            <button 
                type="button" 
                @click="role = 'staff'" 
                :class="role === 'staff' ? 'bg-white text-[#940c10] shadow-sm border border-slate-200 font-bold' : 'text-slate-600 hover:text-slate-900'"
                class="py-2.5 rounded-lg transition-all duration-150 flex items-center justify-center gap-1.5 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>Panitia / Admin</span>
            </button>
        </div>

        <!-- Tab 1: Student (Mahasiswa) Login Form -->
        <div x-show="role === 'student'" class="p-6 sm:p-8">
            <form method="POST" action="#" class="space-y-4">
                @csrf
                <input type="hidden" name="user_type" value="student">

                <div>
                    <label for="nim" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nomor Induk Mahasiswa (NIM)
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="nim" 
                            id="nim" 
                            placeholder="Contoh: 12210123" 
                            required 
                            autofocus
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-[#1A1A1A] placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#940c10] focus:border-[#940c10] transition-colors"
                        >
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">Masukkan NIM aktif Anda yang terdaftar pada DPT UBSI Karawang.</p>
                </div>

                <div>
                    <label for="password_student" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Password Kredensial TPS
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password_student" 
                        placeholder="••••••••" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-[#1A1A1A] placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#940c10] focus:border-[#940c10] transition-colors"
                    >
                    <p class="text-[11px] text-slate-500 mt-1">Kredensial resmi yang dibagikan oleh Panitia di bilik verifikasi TPS.</p>
                </div>

                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-3 px-4 bg-[#940c10] hover:bg-[#7A0A0D] text-[#FDFDFD] font-bold rounded-lg shadow-sm border border-[#7A0A0D] transition-colors duration-150 flex items-center justify-center gap-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#940c10]">
                        <span>Masuk ke Bilik Suara</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#CE9501]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </div>
            </form>

            <!-- Institutional Help Info -->
            <div class="mt-6 pt-5 border-t border-slate-200">
                <div class="flex items-start gap-2.5 p-3 rounded-lg bg-amber-50/70 border border-amber-200/60 text-xs text-amber-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#CE9501] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <div>
                        <span class="font-bold">Bantuan Mahasiswa:</span>
                        <p class="mt-0.5 text-[11px] text-amber-800 leading-relaxed">
                            Bagi mahasiswa yang belum memiliki kredensial atau kehilangan token TPS, silakan menghubungi Panitia PEMIRA BEM UBSI Karawang di meja verifikasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Staff (Panitia / Admin) Login Form -->
        <div x-show="role === 'staff'" x-cloak class="p-6 sm:p-8">
            <form method="POST" action="#" class="space-y-4">
                @csrf
                <input type="hidden" name="user_type" value="staff">

                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Username atau Email
                    </label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username" 
                        placeholder="admin / panitia_tps1" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-[#1A1A1A] placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#940c10] focus:border-[#940c10] transition-colors"
                    >
                </div>

                <div>
                    <label for="password_staff" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi Petugas
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password_staff" 
                        placeholder="••••••••" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-[#1A1A1A] placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#940c10] focus:border-[#940c10] transition-colors"
                    >
                </div>

                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-3 px-4 bg-[#1E3A8A] hover:bg-[#172554] text-[#FDFDFD] font-bold rounded-lg shadow-sm border border-[#172554] transition-colors duration-150 flex items-center justify-center gap-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1E3A8A]">
                        <span>Masuk Panel Pengelola</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-200 text-center">
                <p class="text-xs text-slate-500">
                    Panel ini khusus petugas Panitia TPS dan Administrator PEMIRA BEM UBSI Karawang.
                </p>
            </div>
        </div>

    </div>
</x-layouts.auth>
