@extends('layouts.app', ['title' => 'Detail Paslon 0' . $candidate->candidate_number, 'darkTheme' => true])

@section('content')
<div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex-1 min-h-0 flex flex-col justify-center">

    <!-- Layout Detail Paslon (2 Kolom Fixed Viewport di Desktop) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-stretch lg:h-[calc(100vh-6rem)]">
        
        <!-- ======================================================== -->
        <!-- KOLOM KIRI (TOMBOL KEMBALI, CARD FOTO, & TOMBOL COBLOS)   -->
        <!-- ======================================================== -->
        <div class="lg:col-span-5 flex flex-col gap-3 min-h-0">
            
            <!-- Bar Atas Kiri: Tombol Kembali ke Bilik Suara -->
            <div class="h-8 flex items-center shrink-0">
                <a href="{{ route('student.voting') }}" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#1e0306]/90 hover:bg-[#2e0509] text-white text-xs sm:text-sm font-semibold border border-white/20 shadow-md transition-all hover:-translate-y-0.5 cursor-pointer">
                    <span class="text-sm leading-none">&larr;</span>
                    <span>Kembali ke Bilik Suara</span>
                </a>
            </div>

            <!-- Card Foto Paslon Full -->
            <div class="group relative w-full aspect-[3/4] sm:aspect-[4/5] lg:aspect-auto lg:flex-1 lg:min-h-0 rounded-3xl overflow-hidden border border-white/10 shadow-2xl shadow-black/80 bg-gradient-to-b from-[#52000D] via-[#7A0A0D] to-[#940C10] transition-all duration-300">
                
                <!-- Badge Nomor Paslon Ramping Horizontal di Pojok Kiri Atas -->
                <div class="absolute top-3.5 left-3.5 z-20">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-3.5 sm:py-1 rounded-xl bg-[#940c10]/95 border border-[#CE9501] shadow-lg shadow-black/50 backdrop-blur-xs">
                        <span class="text-[11px] sm:text-xs font-bold text-white tracking-wide">Paslon No.</span>
                        <span class="text-xs sm:text-sm font-black text-[#CE9501]">0{{ $candidate->candidate_number }}</span>
                    </div>
                </div>

                <!-- Foto Paslon Lengkap (Full Card) -->
                <img src="{{ $candidate->photo }}" 
                     onerror="this.onerror=null;this.src='{{ asset('images/paslon-0' . $candidate->candidate_number . '.png') }}';"
                     alt="Paslon 0{{ $candidate->candidate_number }}" 
                     class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105">

                <!-- Overlay Nama Calon Ketua & Wakil di Bagian Bawah Foto (Tanpa Background Putih) -->
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/95 via-black/80 to-transparent p-4 sm:p-5 text-white pt-24 z-10">
                    <div class="flex items-stretch justify-between gap-3 border-t border-white/20 pt-3">
                        <!-- Calon Ketua -->
                        <div class="flex-1 flex flex-col justify-end text-center pr-2 min-w-0">
                            <h3 class="text-xs sm:text-sm font-bold text-white leading-snug break-words">
                                {{ $candidate->chairman_name }}
                            </h3>
                            <p class="text-[10px] sm:text-[11px] font-medium text-[#CE9501] mt-1 whitespace-nowrap">
                                Calon Ketua BEM
                            </p>
                        </div>

                        <!-- Garis Pemisah Vertikal Halus (Tinggi Menyesuaikan Teks Otomatis) -->
                        <div class="self-stretch w-px bg-white/20 shrink-0 my-0.5"></div>

                        <!-- Calon Wakil Ketua -->
                        <div class="flex-1 flex flex-col justify-end text-center pl-2 min-w-0">
                            <h3 class="text-xs sm:text-sm font-bold text-white leading-snug break-words">
                                {{ $candidate->vice_chairman_name }}
                            </h3>
                            <p class="text-[10px] sm:text-[11px] font-medium text-[#CE9501] mt-1 whitespace-nowrap">
                                Calon Wakil Ketua BEM
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tombol Coblos Paslon (Gradasi Merah Marun Lebar Penuh) -->
            <button type="button" onclick="confirmVote()"
                class="w-full shrink-0 py-3 px-6 rounded-2xl text-white bg-gradient-to-r from-[#940C10] via-[#B51016] to-[#7A0A0D] hover:from-[#7A0A0D] hover:via-[#940C10] hover:to-[#B51016] text-sm sm:text-base font-bold shadow-xl shadow-red-950/50 hover:shadow-red-900/60 transition-all transform hover:-translate-y-0.5 active:scale-[0.99] flex items-center justify-center gap-2.5 cursor-pointer">
                <!-- Ballot Box SVG Icon -->
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 10h16v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10z" />
                    <path d="M2 10h20" />
                    <path d="M9 10v-1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1" />
                    <path d="M8 5h8v4H8z" fill="white" fill-opacity="0.25" />
                    <polyline points="10 7 11.5 8.5 14 6" stroke="white" stroke-width="1.8" />
                </svg>
                <span>Coblos Paslon 0{{ $candidate->candidate_number }}</span>
            </button>
        </div>

        <!-- ======================================================== -->
        <!-- KOLOM KANAN (PROFIL LENGKAP, VISI, MISI, & PROGRAM KERJA) -->
        <!-- ======================================================== -->
        <div class="lg:col-span-7 flex flex-col gap-3 min-h-0">
            
            <!-- Bar Atas Kanan: Identitas Acara (Tinggi & Posisi Sejajar Sempurna dengan Bar Kiri) -->
            <div class="h-8 hidden lg:flex items-center justify-end shrink-0">
                <div class="flex items-center gap-2 text-xs font-medium text-slate-300">
                    <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI" class="w-5 h-5 object-contain">
                    <span class="tracking-wide">PEMIRA BEM UBSI Kab.Karawang</span>
                </div>
            </div>

            <!-- Card Putih Profil Lengkap Paslon (Tanpa Scrollbar Visual + Tombol Panah Scroll) -->
            <div class="relative lg:flex-1 lg:min-h-0"
                 x-data="{
                     canScrollDown: false,
                     atBottom: false,
                     updateScrollState() {
                         const el = this.$refs.detailScroll;
                         if (!el) return;
                         const isScrollable = el.scrollHeight > el.clientHeight + 8;
                         this.canScrollDown = isScrollable && (el.scrollTop + el.clientHeight < el.scrollHeight - 16);
                         this.atBottom = isScrollable && (el.scrollTop + el.clientHeight >= el.scrollHeight - 16);
                     },
                     scrollDown() {
                         const el = this.$refs.detailScroll;
                         if (el) el.scrollBy({ top: 180, behavior: 'smooth' });
                     },
                     scrollTop() {
                         const el = this.$refs.detailScroll;
                         if (el) el.scrollTo({ top: 0, behavior: 'smooth' });
                     }
                 }"
                 x-init="$nextTick(() => updateScrollState())"
                 @resize.window="updateScrollState()">

                <div x-ref="detailScroll"
                     @scroll="updateScrollState()"
                     class="bg-white rounded-3xl p-5 sm:p-6 lg:p-7 shadow-2xl shadow-black/70 border border-slate-200/80 space-y-4 lg:h-full lg:overflow-y-auto no-scrollbar">
                
                <!-- 1. Header Judul Paslon & Periode -->
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold mb-2 border border-slate-200/80">
                        <span class="w-2 h-2 rounded-full bg-[#940C10]"></span>
                        <span>Pasangan Calon No. 0{{ $candidate->candidate_number }}</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                        {{ $candidate->chairman_name }} & {{ $candidate->vice_chairman_name }}
                    </h1>
                    <p class="text-xs text-slate-500 mt-1 font-medium">
                        PEMIRA BEM UBSI Kab.Karawang &bull; Periode {{ $activePeriod->academic_year }}
                    </p>
                </div>

                <!-- 2. Profil Identitas Calon Ketua & Wakil Ketua (Bersih, Rapi & Institusional) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-3.5 border-y border-slate-200/80">
                    <!-- Calon Ketua -->
                    <div class="space-y-0.5">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">
                            Calon Ketua BEM
                        </span>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">
                            {{ $candidate->chairman_name }}
                        </h2>
                        <div class="text-xs text-slate-600 font-medium space-y-0.5 pt-0.5">
                            <p class="font-mono text-slate-500">NIM: {{ $candidate->chairman_nim }}</p>
                            <p>{{ $candidate->chairman_study_program }}</p>
                        </div>
                    </div>

                    <!-- Calon Wakil Ketua -->
                    <div class="space-y-0.5 sm:border-l sm:border-slate-200 sm:pl-5">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">
                            Calon Wakil Ketua BEM
                        </span>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">
                            {{ $candidate->vice_chairman_name }}
                        </h2>
                        <div class="text-xs text-slate-600 font-medium space-y-0.5 pt-0.5">
                            <p class="font-mono text-slate-500">NIM: {{ $candidate->vice_chairman_nim }}</p>
                            <p>{{ $candidate->vice_chairman_study_program }}</p>
                        </div>
                    </div>
                </div>

                <!-- 3. Visi Pasangan Calon -->
                <div class="space-y-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Visi
                    </h3>
                    <blockquote class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium border-l-2 border-[#940C10] bg-slate-50/70 p-3.5 rounded-r-2xl">
                        “{{ $candidate->vision }}”
                    </blockquote>
                </div>

                <!-- 4. Misi Pasangan Calon -->
                <div class="space-y-2 pt-1">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Misi
                    </h3>
                    <ol class="space-y-2">
                        @foreach($candidate->mission ?? [] as $index => $m)
                            <li class="flex items-start gap-2.5">
                                <span class="font-mono font-bold text-xs text-[#940C10] shrink-0 pt-0.5 w-6">
                                    {{ sprintf('%02d.', $index + 1) }}
                                </span>
                                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed">
                                    {{ $m }}
                                </p>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <!-- 5. Program Kerja Unggulan -->
                @if(!empty($candidate->work_programs))
                    <div class="space-y-2 pt-1">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            Program Kerja Unggulan
                        </h3>
                        <ul class="space-y-2">
                            @foreach($candidate->work_programs as $program)
                                <li class="flex items-start gap-2.5">
                                    <span class="text-[#940C10] font-bold text-sm shrink-0 leading-tight">&ndash;</span>
                                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed">
                                        {{ $program }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                </div>

                <!-- Indikator & Tombol Scroll Bawah (Bouncing) -->
                <div x-show="canScrollDown"
                     x-cloak
                     x-transition.opacity.duration.200ms
                     class="hidden lg:flex pointer-events-none absolute inset-x-0 bottom-0 h-16 rounded-b-3xl bg-gradient-to-t from-white via-white/90 to-transparent items-end justify-center pb-3">
                    <button type="button"
                            @click="scrollDown()"
                            title="Gulir ke bawah"
                            class="pointer-events-auto w-9 h-9 rounded-full bg-[#940c10] hover:bg-[#7a0a0d] text-white shadow-lg shadow-red-950/30 flex items-center justify-center animate-bounce cursor-pointer transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                </div>

                <!-- Tombol Kembali ke Atas saat sudah di dasar -->
                <div x-show="atBottom"
                     x-cloak
                     x-transition.opacity.duration.200ms
                     class="hidden lg:flex pointer-events-none absolute inset-x-0 bottom-0 h-12 rounded-b-3xl bg-gradient-to-t from-white/90 to-transparent items-end justify-center pb-2.5">
                    <button type="button"
                            @click="scrollTop()"
                            title="Kembali ke atas"
                            class="pointer-events-auto w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 shadow-sm flex items-center justify-center cursor-pointer transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <polyline points="18 15 12 9 6 15"></polyline>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

    </div>

    <!-- Form Pengiriman Suara (Terproteksi CSRF & Terenkripsi) -->
    <form id="voting-form" action="{{ route('student.voting.store') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="candidate_pair_id" value="{{ $candidate->id }}">
    </form>

    <!-- Script Dialog Konfirmasi SweetAlert2 Bertema Gelap & Elegan -->
    <script>
        function confirmVote() {
            Swal.fire({
                html: `
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif;" class="text-center pt-2">
                        <!-- Icon Container: Gold/Red Ballot Box -->
                        <div class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center justify-center mx-auto mb-4 text-[#CE9501] shadow-2xs">
                            <svg class="w-8 h-8 text-[#940c10]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 10h16v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10z" />
                                <path d="M2 10h20" />
                                <path d="M9 10v-1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1" />
                                <path d="M8 5h8v4H8z" fill="#CE9501" fill-opacity="0.25" />
                                <polyline points="10 7 11.5 8.5 14 6" stroke="#940c10" stroke-width="2" />
                            </svg>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-snug">
                            Konfirmasi Pilihan Suara
                        </h3>

                        <p class="text-xs text-slate-500 font-normal mb-3 mt-1.5">Anda akan menyalurkan hak suara resmi untuk:</p>
                        
                        <!-- Badge Paslon -->
                        <div class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-[#940c10] text-white text-xs font-black shadow-xs mb-2.5">
                            <span class="w-2 h-2 rounded-full bg-[#CE9501] animate-pulse"></span>
                            <span>Pasangan Calon No. 0{{ $candidate->candidate_number }}</span>
                        </div>

                        <!-- Nama Paslon -->
                        <p class="text-base sm:text-lg font-black text-slate-900 mt-1 mb-2 leading-snug break-words px-2">
                            {{ addslashes($candidate->chairman_name) }} & {{ addslashes($candidate->vice_chairman_name) }}
                        </p>

                        <!-- Box Peringatan Kerahasiaan Suara -->
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-[11px] sm:text-xs text-slate-600 leading-relaxed mt-3 max-w-sm mx-auto text-left flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-[#CE9501] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span>Pilihan bersifat <strong class="text-slate-900">final & anonim</strong>. Suara akan dicatat secara otomatis ke dalam kotak suara digital terenkripsi.</span>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Iya, Kirim Suara Sekarang',
                cancelButtonText: 'Batal / Periksa Kembali',
                focusCancel: true,
                reverseButtons: true,
                buttonsStyling: false,
                background: '#ffffff',
                backdrop: 'rgba(15, 23, 42, 0.75)',
                customClass: {
                    popup: 'rounded-3xl border border-slate-100 bg-white shadow-2xl p-6 sm:p-8 max-w-lg w-full',
                    htmlContainer: 'p-0 m-0',
                    actions: 'mt-6 flex flex-col-reverse sm:flex-row items-center justify-center gap-3 w-full',
                    confirmButton: 'w-full sm:flex-1 whitespace-nowrap px-5 py-3.5 rounded-xl font-bold text-xs sm:text-sm text-white bg-gradient-to-r from-[#940c10] to-[#7a0a0d] hover:from-[#7a0a0d] hover:to-[#5e0709] shadow-md shadow-red-950/20 active:scale-[0.98] transition-all cursor-pointer text-center',
                    cancelButton: 'w-full sm:flex-1 whitespace-nowrap px-5 py-3.5 rounded-xl font-bold text-xs sm:text-sm text-slate-700 bg-slate-100 hover:bg-slate-200 active:bg-slate-300 border border-slate-200 transition-all cursor-pointer text-center'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim Suara...',
                        text: 'Mohon tunggu, surat suara digital sedang diproses secara aman.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        background: '#ffffff',
                        backdrop: 'rgba(15, 23, 42, 0.75)',
                        customClass: {
                            popup: 'rounded-3xl border border-slate-100 bg-white shadow-2xl p-6 text-slate-900',
                            title: 'text-lg font-bold text-slate-900',
                            htmlContainer: 'text-xs text-slate-600 mt-1'
                        },
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('voting-form').submit();
                }
            });
        }
    </script>

</div>
@endsection
