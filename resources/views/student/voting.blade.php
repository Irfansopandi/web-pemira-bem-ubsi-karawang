@extends('layouts.app', ['title' => 'Bilik Suara — Gunakan Hak Suara Anda', 'darkTheme' => true])

@section('content')
<div class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4 lg:py-5 flex-1 flex flex-col justify-center my-auto">

    <!-- Header Halaman Voting Sesuai Desain Referensi -->
    <div class="mb-4 sm:mb-5 lg:mb-6">
        <!-- Gold Pill Badge: Identitas Acara & Periode Pemilihan -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full border border-[#E5A900]/40 bg-[#E5A900]/10 text-[#E5A900] text-xs font-bold tracking-wider uppercase mb-2 sm:mb-2.5">
            <span>PEMIRA BEM UBSI KARAWANG &bull; TAHUN AKADEMIK {{ $activePeriod->academic_year }}</span>
        </div>

        <!-- Title dengan Red Glowing Ballot Box Icon -->
        <div class="flex items-center gap-3 mb-1.5 sm:mb-2">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-br from-[#D71920] to-[#7A0A0D] shadow-md shadow-red-700/50 border border-red-400/30 flex items-center justify-center shrink-0">
                <!-- Ballot Box with Checkmark SVG -->
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 10h16v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10z" />
                    <path d="M2 10h20" />
                    <path d="M9 10v-1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1" />
                    <path d="M8 5h8v4H8z" fill="white" fill-opacity="0.25" />
                    <polyline points="10 7 11.5 8.5 14 6" stroke="white" stroke-width="1.8" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-wide">
                Gunakan Hak Suara Anda
            </h1>
        </div>

        <!-- Deskripsi Petunjuk Pemilihan -->
        <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
            Silakan pilih pasangan calon di bawah ini untuk melihat profil lengkap, visi, misi, program kerja unggulan, dan menyalurkan hak suara Anda.
        </p>
    </div>

    <!-- Candidate Ballot Cards Grid (Proporsional, Mantap, & Pas 100% Layar) -->
    <div class="grid grid-cols-1 md:grid-cols-2 {{ $candidates->count() > 2 ? 'xl:grid-cols-3' : '' }} gap-6 sm:gap-7 lg:gap-8">
        @foreach($candidates as $candidate)
            <div class="group relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl shadow-black/70 bg-gradient-to-b from-[#52000D] via-[#7A0A0D] to-[#940C10] flex flex-col justify-between transition-all duration-300 hover:shadow-red-950/60 hover:-translate-y-1">
                
                <!-- BAGIAN ATAS: Foto Paslon dengan Latar Maroon, Watermark Gedung, & Lencana Nomor -->
                <div class="relative w-full aspect-[4/3] sm:aspect-[4/3.1] max-h-[230px] sm:max-h-[250px] lg:max-h-[270px] overflow-hidden">
                    
                    <!-- Foto Asli Gedung Kampus UBSI (Background Watermark Real Foto Gedung) -->
                    <div class="absolute right-0 top-0 bottom-0 w-3/5 pointer-events-none select-none overflow-hidden z-0">
                        <img 
                            src="{{ asset('images/kampus/' . ($candidate->candidate_number % 2 == 1 ? 'Karawang.png' : 'Cikampek.png')) }}" 
                            alt="Gedung Kampus UBSI" 
                            class="w-full h-full object-cover object-center opacity-30 mix-blend-luminosity filter contrast-125 [mask-image:linear-gradient(to_left,rgba(0,0,0,1)_20%,rgba(0,0,0,0)_100%)] [-webkit-mask-image:linear-gradient(to_left,rgba(0,0,0,1)_20%,rgba(0,0,0,0)_100%)]"
                        >
                        <div class="absolute inset-0 bg-gradient-to-l from-transparent via-[#7A0A0D]/50 to-[#7A0A0D]"></div>
                    </div>

                    <!-- Lencana Nomor Paslon di Pojok Kiri Atas -->
                    <div class="absolute top-3 left-3 sm:top-3.5 sm:left-3.5 z-20 flex flex-col items-start gap-1.5">
                        <!-- Pill Badge -->
                        <span class="bg-white text-slate-900 text-[10px] sm:text-xs font-bold px-3 py-1 rounded-full shadow-md tracking-wide">
                            Pasangan Calon No. 0{{ $candidate->candidate_number }}
                        </span>
                        <!-- Box Kotak Nomor: Merah Gelap dengan List Emas Tebal -->
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-[#52000D] border-2 border-[#E5A900] shadow-xl flex flex-col items-center justify-center text-center">
                            <span class="text-[8px] uppercase tracking-wider font-bold text-white leading-none">NOMOR</span>
                            <span class="text-lg sm:text-xl font-black text-[#E5A900] leading-none mt-0.5">0{{ $candidate->candidate_number }}</span>
                        </div>
                    </div>

                    <!-- Foto Paslon (Berjas Almamater Merah UBSI) -->
                    <img src="{{ $candidate->photo }}" 
                         onerror="this.onerror=null;this.src='{{ asset('images/paslon-0' . $candidate->candidate_number . '.png') }}';"
                         alt="Pasangan Calon No. 0{{ $candidate->candidate_number }}" 
                         class="w-full h-full object-cover object-bottom transition-transform duration-500 group-hover:scale-105 relative z-10">
                </div>

                <!-- BAGIAN BAWAH: Bodi Putih Bersih (Nama Paslon, Garis Halus, & Tombol Aksi) -->
                <div class="p-4 sm:p-5 bg-white flex flex-col justify-between">
                    <!-- Nama Paslon: Ketua (Atas, Bold Hitam), Wakil (Bawah, Abu Gelap) -->
                    <div class="space-y-0.5 mb-3">
                        <h2 class="text-sm sm:text-base lg:text-lg font-black text-slate-900 leading-tight">
                            {{ $candidate->chairman_name }}
                        </h2>
                        <p class="text-xs sm:text-sm font-semibold text-slate-600 leading-tight mt-0.5">
                            {{ $candidate->vice_chairman_name }}
                        </p>
                    </div>

                    <!-- Garis Halus Pembatas -->
                    <div class="border-b border-slate-200 mb-3"></div>

                    <!-- Tombol Aksi: Lihat Visi Misi & Coblos -->
                    <a href="{{ route('student.candidate.detail', $candidate->id) }}"
                       class="w-full py-2.5 sm:py-3 px-4 bg-gradient-to-r from-[#940C10] via-[#B51016] to-[#7A0A0D] hover:from-[#7A0A0D] hover:via-[#940C10] hover:to-[#B51016] text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-red-900/30 hover:shadow-red-800/40 hover:brightness-105 active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                        <span>Lihat Visi Misi & Coblos</span>
                        <svg class="w-4 h-4 text-white transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>

            </div>
        @endforeach
    </div>

</div>
@endsection
