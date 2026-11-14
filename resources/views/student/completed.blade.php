@extends('layouts.app', ['title' => 'Voting Selesai', 'hideNavbar' => true, 'bodyClass' => 'bg-slate-50/70'])

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    x-data="{
        secondsLeft: 30,
        timer: null,
        storageKey: 'pemira_completed_expires_{{ $student->nim }}',
        init() {
            const now = Date.now();
            let targetTime = sessionStorage.getItem(this.storageKey);

            if (!targetTime) {
                targetTime = now + (30 * 1000);
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

    <div class="max-w-xl w-full space-y-8 bg-white p-8 sm:p-12 rounded-3xl border border-gray-200 shadow-xl text-center">
        <!-- Success Icon -->
        <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-xs ring-8 ring-emerald-50 animate-bounce">
            <i data-lucide="check-circle-2" class="w-10 h-10"></i>
        </div>

        <!-- Success Message -->
        <div class="space-y-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-[#059669]">Hak Suara Berhasil Tercatat</span>
            <h1 class="text-3xl font-bold text-gray-900 tracking-normal">Terima Kasih, {{ $student->name }}!</h1>
            <p class="text-sm text-gray-600 leading-relaxed max-w-md mx-auto font-normal">
                Partisipasi Anda dalam PEMIRA BEM UBSI Karawang telah berhasil diamankan. Suara Anda telah dimasukkan ke dalam kotak suara secara anonim.
            </p>

            <!-- Tombol Manual Suara jika Autoplay Diblokir Browser -->
            <div id="manual-speech-container" class="hidden pt-1">
                <button type="button" onclick="playConfirmationVoice(true)"
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-medium shadow-xs transition-colors cursor-pointer">
                    <i data-lucide="volume-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Putar Suara Konfirmasi</span>
                </button>
            </div>
        </div>

        <!-- 30-Second Countdown Timer Badge -->
        <div class="p-6 rounded-2xl bg-amber-50/80 border border-amber-200 text-amber-950 space-y-3">
            <div class="flex items-center justify-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#CE9501]">
                <i data-lucide="timer" class="w-4 h-4 animate-spin"></i>
                <span>Keamanan Bilik Suara TPS</span>
            </div>
            
            <div class="text-center">
                <span class="text-5xl font-bold text-[#940c10] font-mono tracking-normal" x-text="secondsLeft">30</span>
                <span class="text-xs font-medium text-gray-600 block mt-1">detik menuju logout otomatis</span>
            </div>

            <p class="text-[11px] text-gray-500 max-w-xs mx-auto font-normal">
                Sesi ini akan otomatis keluar demi menjaga kerahasiaan pilihan Anda dan mempercepat antrean bilik suara selanjutnya.
            </p>
        </div>

        <!-- Emergency / Immediate Action Buttons -->
        <div class="pt-2">
            <!-- Immediate Logout Button -->
            <form id="auto-logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-xl text-white bg-[#940c10] hover:bg-[#7A0A0D] text-base font-semibold shadow-sm hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-[#CE9501] cursor-pointer">
                    <i data-lucide="log-out" class="w-5 h-5 text-[#CE9501]"></i>
                    <span>Selesai & Keluar Sekarang</span>
                </button>
            </form>
        </div>
    </div>
</div>

@if(session('voting_success'))
<script>
    (function () {
        const speechSessionKey = 'pemira_speech_played_{{ $participation->id ?? $student->nim }}';
        const speechText = 'Terima kasih telah memilih. Suara Anda berhasil direkam.';

        // Mencegah pemutaran ulang suara jika halaman di-refresh
        if (sessionStorage.getItem(speechSessionKey)) {
            return;
        }

        function showManualButton() {
            const container = document.getElementById('manual-speech-container');
            if (container) {
                container.classList.remove('hidden');
                if (window.lucide && window.lucide.createIcons) {
                    window.lucide.createIcons();
                }
            }
        }

        // Seleksi khusus suara perempuan bahasa Indonesia (Google / Gadis / Damayanti)
        function findFemaleIndonesianVoice() {
            if (!('speechSynthesis' in window)) return null;

            const voices = window.speechSynthesis.getVoices();
            if (!voices || voices.length === 0) return null;

            // 1. Google Bahasa Indonesia (Suara perempuan resmi Google)
            let v = voices.find(v => /google/i.test(v.name) && (v.lang === 'id-ID' || v.lang.startsWith('id')));
            if (v) return v;

            // 2. Microsoft Gadis (Suara perempuan natural Windows/Edge)
            v = voices.find(v => /gadis/i.test(v.name));
            if (v) return v;

            // 3. Apple Damayanti
            v = voices.find(v => /damayanti/i.test(v.name));
            if (v) return v;

            // 4. Suara bahasa Indonesia dengan penanda female
            v = voices.find(v => (v.lang === 'id-ID' || v.lang.startsWith('id')) && /female|wanita/i.test(v.name));
            if (v) return v;

            // 5. Suara bahasa Indonesia yang BUKAN suara pria (bukan Andika/David/Male)
            v = voices.find(v => (v.lang === 'id-ID' || v.lang.startsWith('id')) && !/andika|male|david/i.test(v.name));
            if (v) return v;

            return null;
        }

        let hasExecuted = false;

        function executeSpeech(isManual = false) {
            if (!('speechSynthesis' in window)) return;
            if (hasExecuted && !isManual) return;

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
                    // Fallback pitch tinggi agar terdengar feminin jika voice online belum tersedia
                    utterance.pitch = 1.3;
                }

                let hasStarted = false;

                utterance.onstart = function () {
                    hasStarted = true;
                    sessionStorage.setItem(speechSessionKey, '1');
                    const container = document.getElementById('manual-speech-container');
                    if (container && !isManual) {
                        container.classList.add('hidden');
                    }
                };

                utterance.onerror = function (event) {
                    console.warn('SpeechSynthesis terblokir autoplay atau gagal:', event);
                    showManualButton();
                };

                window.speechSynthesis.speak(utterance);

                if (!isManual) {
                    setTimeout(() => {
                        if (!hasStarted && !window.speechSynthesis.speaking) {
                            showManualButton();
                        }
                    }, 1200);
                }
            } catch (err) {
                console.warn('SpeechSynthesis error:', err);
                showManualButton();
            }
        }

        // Ekspos ke window untuk pemutaran manual
        window.playConfirmationVoice = function (isManual = false) {
            hasExecuted = false;
            executeSpeech(true);
        };

        // Tunggu suara Google selesai di-load oleh browser (Chromium memuat voices secara asynchronous)
        function initSpeechPlayer() {
            if (!('speechSynthesis' in window)) return;

            // Coba periksa apakah suara Google sudah siap
            if (findFemaleIndonesianVoice()) {
                setTimeout(() => executeSpeech(false), 300);
                return;
            }

            // Jika belum siap, tunggu event onvoiceschanged saat browser selesai mengunduh suara Google
            let voicesChangedFired = false;
            window.speechSynthesis.onvoiceschanged = () => {
                if (voicesChangedFired) return;
                voicesChangedFired = true;
                setTimeout(() => executeSpeech(false), 200);
            };

            // Batas waktu maksimal 1500ms sebagai fallback
            setTimeout(() => {
                if (!hasExecuted) {
                    executeSpeech(false);
                }
            }, 1500);
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            initSpeechPlayer();
        } else {
            window.addEventListener('DOMContentLoaded', initSpeechPlayer);
        }
    })();
</script>
@endif
@endsection

