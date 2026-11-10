@extends('layouts.app', ['title' => 'Hak Suara Berhasil Digunakan — PEMIRA BEM UBSI'])

@section('content')
<div class="min-h-[calc(100vh-20rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    x-data="{
        secondsLeft: 30,
        timer: null,
        init() {
            this.timer = setInterval(() => {
                if (this.secondsLeft > 1) {
                    this.secondsLeft--;
                } else {
                    clearInterval(this.timer);
                    this.logoutNow();
                }
            }, 1000);
        },
        logoutNow() {
            document.getElementById('auto-logout-form').submit();
        }
    }">

    <div class="max-w-xl w-full space-y-8 bg-white p-8 sm:p-12 rounded-3xl border border-gray-200 shadow-xl text-center">
        <!-- Success Icon -->
        <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-xs ring-8 ring-emerald-50 animate-bounce">
            <i data-lucide="check-circle-2" class="w-10 h-10"></i>
        </div>

        <!-- Success Message -->
        <div class="space-y-2">
            <span class="text-xs font-bold uppercase tracking-widest text-[#059669]">Hak Suara Berhasil Tercatat</span>
            <h1 class="text-3xl font-black text-gray-900">Terima Kasih, {{ $student->name }}!</h1>
            <p class="text-sm text-gray-600 leading-relaxed max-w-md mx-auto">
                Partisipasi Anda dalam PEMIRA BEM Universitas BSI Karawang telah berhasil diamankan. Suara Anda telah dimasukkan ke dalam kotak suara secara anonim.
            </p>
        </div>

        <!-- 30-Second Countdown Timer Badge -->
        <div class="p-6 rounded-2xl bg-amber-50/80 border border-amber-200 text-amber-950 space-y-3">
            <div class="flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider text-[#CE9501]">
                <i data-lucide="timer" class="w-4 h-4 animate-spin"></i>
                <span>Keamanan Bilik Suara TPS</span>
            </div>
            
            <div class="text-center">
                <span class="text-5xl font-black text-[#940c10] font-mono tracking-tight" x-text="secondsLeft">30</span>
                <span class="text-xs font-bold text-gray-600 block mt-1">detik menuju logout otomatis</span>
            </div>

            <p class="text-[11px] text-gray-500 max-w-xs mx-auto">
                Sesi ini akan otomatis keluar demi menjaga kerahasiaan pilihan Anda dan mempercepat antrean bilik suara selanjutnya.
            </p>
        </div>

        <!-- Emergency / Immediate Action Buttons -->
        <div class="space-y-3 pt-2">
            <!-- Immediate Logout Button -->
            <form id="auto-logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-xl text-white bg-[#940c10] hover:bg-[#7A0A0D] text-base font-extrabold shadow-sm hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-[#CE9501]">
                    <i data-lucide="log-out" class="w-5 h-5 text-[#CE9501]"></i>
                    <span>Selesai & Keluar Sekarang</span>
                </button>
            </form>

            <a href="{{ route('student.receipt') }}"
                class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200 text-xs font-bold transition-colors">
                <i data-lucide="receipt" class="w-4 h-4 text-gray-500"></i>
                <span>Lihat Tanda Terima Digital</span>
            </a>
        </div>
    </div>
</div>
@endsection

