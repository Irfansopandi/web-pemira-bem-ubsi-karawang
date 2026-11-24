@extends('layouts.admin', ['title' => 'Profile Administrator'])

@section('content')
<div class="space-y-6 sm:space-y-8" x-data="{ isSubmittingProfile: false, isSubmittingPassword: false }">

    <!-- Header Halaman -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-start sm:items-center gap-3.5 sm:gap-4">
            <!-- Icon Tanpa Border Sesuai Standar Minimalis UBSI -->
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-[#940C10] shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">Setting</span>
                    <span class="text-slate-300">•</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Akun Aktif: {{ $admin->role === 'admin' ? 'Super Administrator' : 'Panitia TPS' }}
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">Profile Administrator</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Kelola identitas akun penyelenggara PEMIRA, kredensial login & keamanan kata sandi</p>
            </div>
        </div>

        <div>
            <!-- Button Kembali ke Dashboard dihapus sesuai permintaan -->
        </div>
    </div>

    <!-- Error Validation Alert -->
    @if($errors->any())
    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold shadow-xs">
        <div class="flex items-center gap-2 font-bold mb-1">
            <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>Terdapat beberapa kesalahan input:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-red-700 ml-6">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Profile & Security Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
        
        <!-- Kartu Identitas Akun & Update Profil (7 Cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#940C10] to-[#CE9501] text-white font-extrabold text-base flex items-center justify-center shrink-0 shadow-md">
                        {{ strtoupper(substr($admin->name ?? 'AD', 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 leading-tight">{{ $admin->name }}</h2>
                        <span class="text-xs text-slate-500 font-medium">{{ $admin->email }}</span>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-red-50 text-[#940C10] border border-red-200">
                    KPUM UBSI
                </span>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" @submit="isSubmittingProfile = true" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Username Login <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username', $admin->username) }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all bg-slate-50/50 hover:bg-white focus:bg-white font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Penyelenggara <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                        <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">Peran Akun</span>
                        <span class="font-bold text-slate-900 mt-0.5 block">
                            {{ $admin->role === 'admin' ? 'Administrator KPUM (Penuh)' : 'Panitia TPS (Verifikator)' }}
                        </span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                        <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">Terdaftar Sejak</span>
                        <span class="font-bold text-slate-900 mt-0.5 block font-mono">
                            {{ $admin->created_at ? $admin->created_at->format('d M Y, H:i') : '-' }} WIB
                        </span>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-slate-100">
                    <button type="submit" :disabled="isSubmittingProfile"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#940C10] hover:bg-[#7A0A0D] text-white text-xs font-bold shadow-md shadow-red-950/20 transition-all disabled:opacity-75 disabled:cursor-not-allowed min-w-[140px] cursor-pointer">
                        <template x-if="isSubmittingProfile">
                            <svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="isSubmittingProfile ? 'Menyimpan...' : 'Simpan Profil'">Simpan Profil</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Kartu Keamanan Sandi (5 Cols) -->
        <div class="lg:col-span-5 bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6"
            x-data="{ showCurrentPassword: false, showNewPassword: false, showConfirmPassword: false }">
            <div class="pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <h3 class="text-base font-black text-slate-900 leading-tight">Perbarui Kata Sandi</h3>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-1">Gunakan kombinasi sandi yang kuat untuk menjaga integritas akun</p>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST" @submit="isSubmittingPassword = true" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Field 1: Kata Sandi Saat Ini -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Saat Ini <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input :type="showCurrentPassword ? 'text' : 'password'" name="current_password" required placeholder="Masukkan kata sandi lama"
                            class="w-full pl-4 pr-11 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                        <button type="button" @click="showCurrentPassword = !showCurrentPassword"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors cursor-pointer"
                            title="Tampilkan / Sembunyikan Kata Sandi">
                            <template x-if="!showCurrentPassword">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </template>
                            <template x-if="showCurrentPassword">
                                <svg class="w-4 h-4 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                    <line x1="2" x2="22" y1="2" y2="22"></line>
                                </svg>
                            </template>
                        </button>
                    </div>
                </div>

                <!-- Field 2: Kata Sandi Baru -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input :type="showNewPassword ? 'text' : 'password'" name="password" required placeholder="Minimal 6 karakter"
                            class="w-full pl-4 pr-11 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                        <button type="button" @click="showNewPassword = !showNewPassword"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors cursor-pointer"
                            title="Tampilkan / Sembunyikan Kata Sandi">
                            <template x-if="!showNewPassword">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </template>
                            <template x-if="showNewPassword">
                                <svg class="w-4 h-4 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                    <line x1="2" x2="22" y1="2" y2="22"></line>
                                </svg>
                            </template>
                        </button>
                    </div>
                </div>

                <!-- Field 3: Ulangi Kata Sandi Baru -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Ulangi Kata Sandi Baru <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required placeholder="Konfirmasi kata sandi baru"
                            class="w-full pl-4 pr-11 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                        <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors cursor-pointer"
                            title="Tampilkan / Sembunyikan Kata Sandi">
                            <template x-if="!showConfirmPassword">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </template>
                            <template x-if="showConfirmPassword">
                                <svg class="w-4 h-4 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                    <line x1="2" x2="22" y1="2" y2="22"></line>
                                </svg>
                            </template>
                        </button>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-amber-50/80 border border-amber-200/80 text-[11px] text-amber-900 leading-relaxed">
                    <strong class="font-bold">Keamanan Akun:</strong> Seluruh aktivitas perubahan profil dan kata sandi dicatat dalam log audit sistem untuk menjamin akuntabilitas penyelenggara.
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-slate-100">
                    <button type="submit" :disabled="isSubmittingPassword"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md shadow-slate-900/20 transition-all disabled:opacity-75 disabled:cursor-not-allowed min-w-[150px] cursor-pointer">
                        <template x-if="isSubmittingPassword">
                            <svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="isSubmittingPassword ? 'Memproses...' : 'Ubah Kata Sandi'">Ubah Kata Sandi</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
