@extends('layouts.app', ['title' => 'Bilik Suara', 'darkTheme' => true])

@section('content')
<div class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4 lg:py-5 flex-1 flex flex-col justify-center my-auto">

    <!-- Header Halaman Voting Sesuai Desain Referensi -->
    <div class="mb-4 sm:mb-5 lg:mb-6">
        <!-- Identitas Acara & Periode Pemilihan (Teks Bersih Tanpa Badge) -->
        <span class="text-xs font-semibold uppercase tracking-wider text-[#CE9501] block mb-1.5 sm:mb-2">
            PEMIRA BEM UBSI KARAWANG &bull; TAHUN AKADEMIK {{ $activePeriod->academic_year }}
        </span>

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

    <!-- Candidate Ballot Cards Grid (Full Foto Sesuai Desain Awal & 1 Badge Paslon No.) -->
    <div class="grid grid-cols-1 md:grid-cols-2 {{ $candidates->count() > 2 ? 'xl:grid-cols-3' : '' }} gap-6 sm:gap-7 lg:gap-8">
        @foreach($candidates as $candidate)
            <a href="{{ route('student.candidate.detail', $candidate->id) }}"
               class="group relative block aspect-[3/4] sm:aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl shadow-black/80 transition-all duration-300 border border-white/10 hover:border-[#CE9501]/60 bg-gradient-to-b from-[#52000D] via-[#7A0A0D] to-[#940C10] transform hover:-translate-y-1.5 cursor-pointer">
                
                <!-- Badge Nomor Paslon Ramping Horizontal di Pojok Kiri Atas -->
                <div class="absolute top-3.5 left-3.5 z-20">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 sm:px-3.5 sm:py-1 rounded-xl bg-[#940c10]/95 border border-[#CE9501] shadow-lg shadow-black/50 backdrop-blur-xs">
                        <span class="text-[11px] sm:text-xs font-bold text-white tracking-wide">Paslon No.</span>
                        <span class="text-xs sm:text-sm font-black text-[#CE9501]">0{{ $candidate->candidate_number }}</span>
                    </div>
                </div>

                <!-- Foto Paslon Full Card (Storage & Fallback) -->
                <img src="{{ $candidate->photo }}" 
                     onerror="this.onerror=null;this.src='{{ asset('images/paslon-0' . $candidate->candidate_number . '.png') }}';"
                     alt="Pasangan Calon No. 0{{ $candidate->candidate_number }}" 
                     class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105">

                <!-- Gradient Overlay & Info Paslon di Bagian Bawah -->
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/95 via-black/80 to-transparent p-4 sm:p-5 text-white pt-28 z-10 flex flex-col justify-end">
                    <!-- Nama Kandidat (Ketua di Atas, Wakil di Bawah) -->
                    <div class="space-y-0.5 mb-3">
                        <h2 class="text-sm sm:text-base lg:text-lg font-black text-white group-hover:text-[#CE9501] transition-colors leading-tight">
                            {{ $candidate->chairman_name }}
                        </h2>
                        <p class="text-xs sm:text-sm font-medium text-slate-300 group-hover:text-amber-200 transition-colors leading-tight">
                            {{ $candidate->vice_chairman_name }}
                        </p>
                    </div>

                    <!-- Judul Aksi Tanpa Kotak Tombol dengan Panah Maju Saat Hover -->
                    <div class="pt-2.5 border-t border-white/20 flex items-center justify-between">
                        <span class="text-xs sm:text-sm text-amber-300 font-semibold flex items-center gap-1.5 group-hover:text-white transition-colors">
                            <span>Lihat Visi Misi & Coblos</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform duration-200 text-[#CE9501] group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

</div>
@endsection
