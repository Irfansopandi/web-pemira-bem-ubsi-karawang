@extends('layouts.app', ['title' => 'Voting Selesai', 'darkTheme' => true, 'hideNavbar' => true])

@section('content')
<div class="w-full max-w-lg sm:max-w-xl mx-auto px-4 py-6 sm:py-8 flex items-center justify-center my-auto"
    x-data="{
        secondsLeft: 5,
        timer: null,
        storageKey: 'pemira_completed_expires_{{ $student->nim }}',
        init() {
            const now = Date.now();
            let targetTime = sessionStorage.getItem(this.storageKey);

            if (!targetTime) {
                targetTime = now + (5 * 1000);
                sessionStorage.setItem(this.storageKey, targetTime);
            } else {
                targetTime = parseInt(targetTime, 10);
            }

            const initialRemaining = Math.max(0, Math.ceil((targetTime - now) / 1000));
            this.secondsLeft = initialRemaining;

            if (initialRemaining <= 0) {
                this.logoutNow();
                return;
            }

            this.timer = setInterval(() => {
                const remaining = Math.max(0, Math.ceil((targetTime - Date.now()) / 1000));
                this.secondsLeft = remaining;

                if (remaining <= 0) {
                    clearInterval(this.timer);
                    this.logoutNow();
                }
            }, 500);
        },
        logoutNow() {
            sessionStorage.removeItem(this.storageKey);
            document.getElementById('auto-logout-form').submit();
        }
    }">

    <div class="w-full space-y-6 sm:space-y-7 bg-white p-7 sm:p-10 rounded-3xl border border-white/20 shadow-2xl shadow-black/80 text-center">
        <!-- Success Icon -->
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-sm ring-8 ring-emerald-50">
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>

        <!-- Success Message -->
        <div class="space-y-2">
            <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-[#059669]">Hak Suara Berhasil Tercatat</span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-snug">Terima Kasih, {{ $student->name }}!</h1>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-md mx-auto font-normal">
                Partisipasi Anda dalam PEMIRA BEM UBSI Kab.Karawang telah berhasil diamankan. Suara Anda telah dimasukkan ke dalam kotak suara secara anonim.
            </p>
        </div>

        <!-- 5-Second Countdown Timer Badge -->
        <div class="p-5 sm:p-6 rounded-2xl bg-amber-50/80 border border-amber-200/80 text-amber-950 space-y-2.5">
            <div class="flex items-center justify-center gap-2 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-[#B58200]">
                <svg class="w-4 h-4 text-[#B58200] animate-spin" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>Keamanan Bilik Suara TPS</span>
            </div>
            
            <div class="text-center">
                <span class="text-4xl sm:text-5xl font-black text-[#940c10] font-mono tracking-tight" x-text="secondsLeft">5</span>
                <span class="text-xs font-medium text-slate-600 block mt-1">detik menuju logout otomatis</span>
            </div>

            <p class="text-[11px] text-slate-500 max-w-xs mx-auto font-normal">
                Sesi ini akan otomatis keluar demi menjaga kerahasiaan pilihan Anda dan mempercepat antrean bilik suara selanjutnya.
            </p>
        </div>

        <!-- Emergency / Immediate Action Buttons -->
        <div class="pt-1">
            <!-- Immediate Logout Button -->
            <form id="auto-logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-3.5 sm:py-4 px-6 rounded-2xl text-white bg-gradient-to-r from-[#940C10] via-[#B51016] to-[#7A0A0D] hover:from-[#7A0A0D] hover:via-[#940C10] hover:to-[#B51016] text-sm sm:text-base font-bold shadow-xl shadow-red-950/40 hover:shadow-red-900/50 transition-all transform hover:-translate-y-0.5 active:scale-[0.99] cursor-pointer">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Selesai & Keluar Sekarang</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const speechText = {!! json_encode($student->name . ' telah selesai mencoblos.') !!};

        // Seleksi khusus suara bahasa Indonesia (Google / Gadis / Damayanti / Andika)
        function findFemaleIndonesianVoice() {
            if (!('speechSynthesis' in window)) return null;

            const voices = window.speechSynthesis.getVoices();
            if (!voices || voices.length === 0) return null;

            // 1. Google Bahasa Indonesia (Suara resmi Google)
            let v = voices.find(v => /google/i.test(v.name) && (v.lang === 'id-ID' || v.lang.startsWith('id')));
            if (v) return v;

            // 2. Microsoft Gadis (Windows natural female)
            v = voices.find(v => /gadis/i.test(v.name));
            if (v) return v;

            // 3. Apple Damayanti
            v = voices.find(v => /damayanti/i.test(v.name));
            if (v) return v;

            // 4. Suara bahasa Indonesia dengan penanda female
            v = voices.find(v => (v.lang === 'id-ID' || v.lang.startsWith('id')) && /female|wanita/i.test(v.name));
            if (v) return v;

            // 5. Suara bahasa Indonesia apa pun yang tersedia di sistem (termasuk Microsoft Andika)
            v = voices.find(v => (v.lang === 'id-ID' || v.lang.startsWith('id')));
            if (v) return v;

            return null;
        }

        let hasExecuted = false;

        function executeSpeech() {
            if (!('speechSynthesis' in window)) return;
            if (hasExecuted) return;

            hasExecuted = true;

            try {
                window.speechSynthesis.cancel();

                const utterance = new SpeechSynthesisUtterance(speechText);
                utterance.lang = 'id-ID';
                utterance.rate = 1.0;

                const femaleVoice = findFemaleIndonesianVoice();
                if (femaleVoice) {
                    utterance.voice = femaleVoice;
                    utterance.pitch = 1.05;
                } else {
                    utterance.pitch = 1.0;
                }

                window.speechSynthesis.speak(utterance);
            } catch (err) {
                console.warn('SpeechSynthesis error:', err);
            }
        }

        // Tunggu suara selesai di-load oleh browser lalu otomatis putar suara
        function initSpeechPlayer() {
            if (!('speechSynthesis' in window)) return;

            // Jika suara sudah tersedia
            if (findFemaleIndonesianVoice()) {
                setTimeout(executeSpeech, 250);
                return;
            }

            // Jika browser memuat suara secara async
            let voicesChangedFired = false;
            window.speechSynthesis.onvoiceschanged = () => {
                if (voicesChangedFired) return;
                voicesChangedFired = true;
                setTimeout(executeSpeech, 200);
            };

            // Batas waktu maksimal 1200ms sebagai fallback otomatis
            setTimeout(() => {
                if (!hasExecuted) {
                    executeSpeech();
                }
            }, 1200);
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            initSpeechPlayer();
        } else {
            window.addEventListener('DOMContentLoaded', initSpeechPlayer);
        }

        // Pemicu interaksi awal jika Chromium membatasi autoplay suara sebelum ada klik
        const triggerOnFirstClick = function () {
            if (!hasExecuted) {
                executeSpeech();
            }
            window.removeEventListener('click', triggerOnFirstClick);
            window.removeEventListener('touchstart', triggerOnFirstClick);
        };
        window.addEventListener('click', triggerOnFirstClick, { once: true });
        window.addEventListener('touchstart', triggerOnFirstClick, { once: true });
    })();
</script>
@endsection

