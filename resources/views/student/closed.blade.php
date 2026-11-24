@extends('layouts.app', ['title' => 'Bilik Suara Ditutup', 'darkTheme' => true])

@section('content')
<div class="w-full max-w-xl mx-auto px-4 py-12 flex-1 flex flex-col justify-center my-auto text-center">
    <!-- Card Bilik Ditutup -->
    <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 sm:p-10 border border-white/15 shadow-2xl shadow-black/60 relative overflow-hidden">
        <!-- Accent Glow -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-[#940C10] rounded-full blur-3xl opacity-40"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-[#CE9501] rounded-full blur-3xl opacity-30"></div>

        <div class="relative z-10">
            <!-- Icon Lock / Clock -->
            <div class="w-20 h-20 mx-auto rounded-3xl bg-gradient-to-br from-amber-500/20 to-red-600/20 border border-amber-400/30 flex items-center justify-center mb-6 shadow-inner">
                <svg class="w-10 h-10 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>

            <!-- Status Pill -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/10 border border-amber-400/30 text-amber-300 text-xs font-bold uppercase tracking-wider mb-4">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                Bilik Suara Sedang Ditutup
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight mb-3">
                Pemungutan Suara Belum Dibuka
            </h1>

            <p class="text-sm sm:text-base text-slate-300 leading-relaxed mb-6">
                Bilik suara digital untuk <strong class="text-white">{{ $activePeriod->name }}</strong> saat ini sedang ditutup atau belum dimulai oleh Panitia PEMIRA BEM UBSI Kab.Karawang.
            </p>

            <div class="p-4 rounded-2xl bg-black/30 border border-white/10 text-xs text-slate-400 mb-8 text-left space-y-1.5">
                <div class="flex items-center justify-between">
                    <span>Pemilih:</span>
                    <span class="font-bold text-white">{{ $student->name }} ({{ $student->nim }})</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Periode:</span>
                    <span class="font-semibold text-amber-300">{{ $activePeriod->academic_year }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Status Bilik:</span>
                    <span class="font-bold text-red-400">DITUTUP</span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-[#940C10] hover:bg-[#7A0A0D] text-white font-bold text-sm shadow-lg shadow-red-950/50 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar dari Bilik Suara</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
