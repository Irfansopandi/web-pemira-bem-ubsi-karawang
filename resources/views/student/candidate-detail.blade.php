@extends('layouts.app', ['title' => 'Detail Paslon 0' . $candidate->candidate_number . ' — ' . $candidate->chairman_name . ' & ' . $candidate->vice_chairman_name, 'darkTheme' => true, 'allowScroll' => true])

@section('content')
<div class="w-full max-w-5xl lg:max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-3 pb-8 flex-1 flex flex-col">

    <!-- Layout Detail Paslon (2 Kolom: Kiri Foto & Coblos Sticky Mentok Navbar, Kanan Profil Lengkap Scrollable) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-7 items-start">
        
        <!-- ======================================================== -->
        <!-- KOLOM KIRI (TOMBOL KEMBALI, CARD FOTO, & TOMBOL COBLOS)   -->
        <!-- STICKY MENTOK NAVBAR SAAT DI-SCROLL KE ATAS              -->
        <!-- ======================================================== -->
        <div class="lg:col-span-5 space-y-3.5 lg:sticky lg:top-16 lg:self-start z-30">
            
            <!-- Bar Atas Kiri: Tombol Kembali ke Bilik Suara -->
            <div class="h-9 flex items-center">
                <a href="{{ route('student.voting') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#1e0306]/90 hover:bg-[#2e0509] text-white text-xs sm:text-sm font-semibold border border-white/20 shadow-md transition-all hover:-translate-y-0.5 cursor-pointer">
                    <span class="text-sm leading-none">&larr;</span>
                    <span>Kembali ke Bilik Suara</span>
                </a>
            </div>

            <!-- Card Foto Paslon Full (Sejajar Sempurna dengan Card Kanan) -->
            <div class="group relative w-full aspect-[3/4] sm:aspect-[4/5] lg:aspect-auto lg:h-[480px] max-h-[500px] rounded-3xl overflow-hidden border border-white/10 shadow-2xl shadow-black/80 bg-gradient-to-b from-[#52000D] via-[#7A0A0D] to-[#940C10] transition-all duration-300">
                
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
                    <div class="flex items-center justify-between gap-3 border-t border-white/20 pt-3">
                        <!-- Calon Ketua -->
                        <div class="flex-1 text-center pr-2">
                            <h3 class="text-xs sm:text-sm font-bold text-white leading-tight truncate">
                                {{ $candidate->chairman_name }}
                            </h3>
                            <p class="text-[10px] sm:text-[11px] font-medium text-[#CE9501] mt-0.5">
                                Calon Ketua BEM
                            </p>
                        </div>

                        <!-- Garis Pemisah Vertikal Halus -->
                        <div class="h-7 w-px bg-white/20"></div>

                        <!-- Calon Wakil Ketua -->
                        <div class="flex-1 text-center pl-2">
                            <h3 class="text-xs sm:text-sm font-bold text-white leading-tight truncate">
                                {{ $candidate->vice_chairman_name }}
                            </h3>
                            <p class="text-[10px] sm:text-[11px] font-medium text-[#CE9501] mt-0.5">
                                Calon Wakil Ketua BEM
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tombol Coblos Paslon (Gradasi Merah Marun Lebar Penuh) -->
            <button type="button" onclick="confirmVote()"
                class="w-full py-3 sm:py-3.5 px-6 rounded-2xl text-white bg-gradient-to-r from-[#940C10] via-[#B51016] to-[#7A0A0D] hover:from-[#7A0A0D] hover:via-[#940C10] hover:to-[#B51016] text-sm sm:text-base font-bold shadow-xl shadow-red-950/50 hover:shadow-red-900/60 transition-all transform hover:-translate-y-0.5 active:scale-[0.99] flex items-center justify-center gap-2.5 cursor-pointer">
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
        <!-- BISA DI-SCROLL DENGAN BATAS AKHIR CARD YANG JELAS         -->
        <!-- ======================================================== -->
        <div class="lg:col-span-7 space-y-3.5">
            
            <!-- Bar Atas Kanan: Identitas Acara (Tinggi & Posisi Sejajar Sempurna dengan Bar Kiri) -->
            <div class="h-9 hidden sm:flex items-center justify-end">
                <div class="flex items-center gap-2 text-xs font-medium text-slate-300">
                    <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI" class="w-5 h-5 object-contain">
                    <span class="tracking-wide">PEMIRA BEM UBSI Karawang</span>
                </div>
            </div>

            <!-- Card Putih Profil Lengkap Paslon -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 lg:p-8 shadow-2xl shadow-black/70 border border-white/20 space-y-6 sm:space-y-7">
                
                <!-- 1. Header Judul Paslon & Periode -->
                <div>
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                        {{ $candidate->chairman_name }} & {{ $candidate->vice_chairman_name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1.5 font-medium">
                        PEMIRA BEM UBSI Karawang &bull; Periode {{ $activePeriod->academic_year }}
                    </p>
                </div>

                <!-- 2. Grid Identitas Calon Ketua & Calon Wakil Ketua -->
                <div class="bg-[#F8F9FC] rounded-2xl p-4 sm:p-5 border border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Calon Ketua -->
                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-red-100 text-[#940C10] flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#940C10] block">
                                CALON KETUA
                            </span>
                            <p class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug mt-0.5">
                                {{ $candidate->chairman_name }}
                            </p>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                NIM: {{ $candidate->chairman_nim }}
                            </p>
                            <p class="text-xs text-slate-600 font-medium mt-0.5">
                                {{ $candidate->chairman_study_program }}
                            </p>
                        </div>
                    </div>

                    <!-- Calon Wakil Ketua -->
                    <div class="flex items-start gap-3.5 sm:border-l sm:border-slate-200 sm:pl-5">
                        <div class="w-9 h-9 rounded-xl bg-red-100 text-[#940C10] flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#940C10] block">
                                CALON WAKIL KETUA
                            </span>
                            <p class="text-xs sm:text-sm font-extrabold text-slate-900 leading-snug mt-0.5">
                                {{ $candidate->vice_chairman_name }}
                            </p>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                NIM: {{ $candidate->vice_chairman_nim }}
                            </p>
                            <p class="text-xs text-slate-600 font-medium mt-0.5">
                                {{ $candidate->vice_chairman_study_program }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 3. Visi Pasangan Calon (Diberi Batas & Jarak Proporsional) -->
                <div class="pt-5 sm:pt-6 border-t border-slate-100 space-y-2">
                    <div class="flex items-center gap-2 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-[#940C10]">
                        <!-- Target / Vision SVG Icon -->
                        <svg class="w-4 h-4 text-[#940C10] shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <circle cx="12" cy="12" r="6"/>
                            <circle cx="12" cy="12" r="2"/>
                        </svg>
                        <span>VISI PASANGAN CALON</span>
                    </div>
                    <p class="text-xs sm:text-sm italic text-slate-800 leading-relaxed font-medium pl-1">
                        “{{ $candidate->vision }}”
                    </p>
                </div>

                <!-- 4. Misi Pasangan Calon (Diberi Batas & Jarak Antar Poin Nyaman) -->
                <div class="pt-5 sm:pt-6 border-t border-slate-100 space-y-3">
                    <div class="flex items-center gap-2 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-[#940C10]">
                        <!-- Flag SVG Icon -->
                        <svg class="w-4 h-4 text-[#940C10] shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                            <line x1="4" y1="22" x2="4" y2="15"/>
                        </svg>
                        <span>MISI PASANGAN CALON</span>
                    </div>
                    <ol class="space-y-3">
                        @foreach($candidate->mission ?? [] as $index => $m)
                            <li class="flex items-start gap-3">
                                <span class="w-5 h-5 rounded-full bg-red-100 text-[#940C10] font-bold text-[11px] flex items-center justify-center shrink-0 mt-0.5">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-xs sm:text-sm text-slate-700 leading-relaxed font-normal">
                                    {{ $m }}
                                </span>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <!-- 5. Program Kerja Unggulan (Diberi Batas & Jarak Antar Program Nyaman) -->
                @if(!empty($candidate->work_programs))
                    <div class="pt-5 sm:pt-6 border-t border-slate-100 space-y-3">
                        <div class="flex items-center gap-2 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-[#940C10]">
                            <!-- Briefcase SVG Icon -->
                            <svg class="w-4 h-4 text-[#940C10] shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                            <span>PROGRAM KERJA UNGGULAN</span>
                        </div>
                        <ul class="space-y-2.5">
                            @foreach($candidate->work_programs as $program)
                                <li class="flex items-start gap-3">
                                    <span class="w-2 h-2 rounded-full bg-[#E5A900] shrink-0 mt-1.5"></span>
                                    <span class="text-xs sm:text-sm text-slate-700 leading-relaxed font-normal">
                                        {{ $program }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

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
                title: 'Konfirmasi Pilihan',
                html: `
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif;" class="text-center">
                        <p class="text-xs text-slate-300 font-normal mb-2 mt-1">Anda akan memberikan suara untuk:</p>
                        
                        <!-- Badge Paslon (Kuning Emas UBSI yang Jelas & Kontras Tinggi) -->
                        <div class="inline-flex items-center px-4 py-1 rounded-full bg-[#E5A900] text-slate-950 text-xs font-bold shadow-md whitespace-nowrap">
                            Pasangan Calon No. 0{{ $candidate->candidate_number }}
                        </div>

                        <!-- Nama Paslon (Satu Baris, Jelas & Berwibawa) -->
                        <p class="text-base sm:text-lg font-extrabold text-white mt-2.5 mb-1 leading-snug whitespace-nowrap overflow-hidden text-ellipsis">
                            {{ addslashes($candidate->chairman_name) }} & {{ addslashes($candidate->vice_chairman_name) }}
                        </p>

                        <!-- Deskripsi Kerahasiaan Suara -->
                        <p class="text-xs text-slate-400 leading-relaxed mt-2 px-2">
                            Pilihan bersifat <strong class="text-slate-200">final & anonim</strong>. Suara akan tercatat ke dalam bilik suara digital secara aman dan dijamin kerahasiaannya.
                        </p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Iya, Kirim Suara Sekarang',
                cancelButtonText: 'Batal / Periksa Kembali',
                focusCancel: true,
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-3xl border border-white/10 bg-[#1e0306] text-white shadow-2xl p-6 sm:p-8 max-w-lg w-full',
                    title: 'text-xl font-bold text-white p-0 m-0',
                    htmlContainer: 'p-0 m-0 mt-3',
                    actions: 'mt-6 flex items-center justify-center gap-3 w-full',
                    confirmButton: 'whitespace-nowrap px-5 py-3 rounded-xl font-semibold text-xs sm:text-sm text-white bg-[#940c10] hover:bg-[#7A0A0D] shadow-md transition-all cursor-pointer',
                    cancelButton: 'whitespace-nowrap px-5 py-3 rounded-xl font-semibold text-xs sm:text-sm text-slate-300 bg-white/10 hover:bg-white/20 transition-all cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim Suara...',
                        text: 'Mohon tunggu, surat suara digital sedang diproses secara aman.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-3xl border border-white/10 bg-[#1e0306] text-white shadow-2xl p-6',
                            title: 'text-lg font-bold text-white'
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
