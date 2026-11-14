@extends('layouts.app', ['title' => 'Bilik Suara'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

    <!-- Header Halaman Voting (Bersih, Smooth & Profesional) -->
    <div class="mb-8 sm:mb-10 text-center sm:text-left">
        <span class="text-xs font-semibold uppercase tracking-wider text-[#CE9501]">
            PEMIRA BEM UBSI Karawang &bull; Tahun Akademik {{ $activePeriod->academic_year }}
        </span>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-normal mt-1.5">
            Gunakan Hak Suara Anda
        </h1>
        <p class="text-sm font-normal text-gray-500 mt-2 max-w-2xl leading-relaxed">
            Silakan pilih pasangan calon di bawah ini untuk melihat profil lengkap, visi, misi, program kerja unggulan, dan menyalurkan hak suara Anda.
        </p>
    </div>

    <!-- Candidate Ballot Cards Grid (3 Sejajar ke Kanan, Full Foto, Nomor di Kiri Atas) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        @foreach($candidates as $candidate)
            <a href="{{ route('student.candidate.detail', $candidate->id) }}"
               class="group relative block aspect-[3/4] sm:aspect-[4/5] rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 border-2 border-slate-200 hover:border-transparent bg-slate-100 transform hover:-translate-y-1.5 cursor-pointer">
                
                <!-- Badge Nomor Urut di Pojok Kiri Atas (Semula) -->
                <div class="absolute top-4 left-4 z-20">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-[#940c10] text-[#CE9501] border-2 border-[#CE9501] flex flex-col items-center justify-center shadow-lg">
                        <span class="text-[9px] sm:text-[10px] uppercase tracking-wider font-semibold text-white leading-none">NOMOR</span>
                        <span class="text-2xl sm:text-3xl leading-none font-bold text-[#CE9501]">0{{ $candidate->candidate_number }}</span>
                    </div>
                </div>

                <!-- Foto Paslon Full Card (Storage & Fallback) -->
                <img src="{{ $candidate->photo }}" 
                     alt="Pasangan Calon No. 0{{ $candidate->candidate_number }}" 
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                <!-- Gradient Overlay & Info Paslon di Bagian Bawah (Nama 1 di Atas & Nama 2 di Bawah) -->
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/95 via-black/70 to-transparent p-5 sm:p-6 text-white pt-24 z-10">
                    <!-- Nama Kandidat (Ketua di Atas, Wakil di Bawah, Halus & Berkelas) -->
                    <div class="space-y-1 mb-3">
                        <h2 class="text-base sm:text-lg font-bold text-white group-hover:text-[#CE9501] transition-colors leading-snug">
                            {{ $candidate->chairman_name }}
                        </h2>
                        <p class="text-xs sm:text-sm font-medium text-slate-300 group-hover:text-amber-200 transition-colors leading-snug">
                            {{ $candidate->vice_chairman_name }}
                        </p>
                    </div>

                    <!-- Tombol Aksi Bersih dengan Garis Halus -->
                    <div class="pt-2.5 border-t border-white/20 flex items-center justify-between">
                        <span class="text-xs text-amber-300 font-semibold flex items-center gap-1.5 group-hover:text-white transition-colors">
                            <span>Lihat Visi Misi & Coblos</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5 transform group-hover:translate-x-1.5 transition-transform text-[#CE9501]"></i>
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

</div>
@endsection
