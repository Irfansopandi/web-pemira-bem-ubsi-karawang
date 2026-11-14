@extends('layouts.app', ['title' => 'Tanda Terima Hak Suara — PEMIRA BEM UBSI Karawang', 'darkTheme' => true, 'allowScroll' => true, 'hideNavbar' => true])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12 my-auto">
    <div class="bg-white rounded-3xl border border-white/20 overflow-hidden shadow-2xl shadow-black/80 space-y-0">
        <!-- Top Receipt Header (Logo BEM & Kabinet, Serta Badge Suara Sah Bersih) -->
        <div class="bg-gradient-to-r from-[#940c10] via-[#B51016] to-[#7A0A0D] p-6 sm:p-8 text-white flex items-start justify-between flex-wrap gap-4 border-b-2 border-[#CE9501]">
            <div class="flex items-center gap-3.5 sm:gap-4">
                <!-- 2 Logo Resmi: BEM UBSI di Kiri & Kabinet di Kanannya -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI" class="w-12 h-12 sm:w-14 sm:h-14 object-contain drop-shadow-md">
                    <img src="{{ asset('images/logo-kabinet.png') }}" alt="Logo Kabinet BEM" class="w-12 h-12 sm:w-14 sm:h-14 object-contain drop-shadow-md">
                </div>
                <div>
                    <span class="text-[11px] sm:text-xs font-semibold uppercase tracking-wider text-[#CE9501]">Sertifikat Partisipasi Resmi</span>
                    <h1 class="text-xl sm:text-2xl font-bold text-white leading-tight">Tanda Terima Pemilih</h1>
                    <p class="text-xs text-red-100/90 font-normal mt-0.5">PEMIRA BEM Universitas BSI Karawang {{ $activePeriod->academic_year }}</p>
                </div>
            </div>

            <!-- Badge Suara Sah (Posisi Atas & Ukuran Kompak Lebih Rapi) -->
            <div class="px-3 py-1 rounded-full bg-emerald-600 text-white text-[11px] font-semibold shadow-xs tracking-normal whitespace-nowrap mt-0.5 sm:mt-1">
                Suara Telah Sah Digunakan
            </div>
        </div>

        <!-- Receipt Body Details -->
        <div class="p-6 sm:p-8 space-y-7">
            <!-- Student Voter Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 rounded-2xl bg-gray-50 border border-gray-100 text-xs text-gray-700">
                <div>
                    <span class="text-gray-400 block font-medium uppercase tracking-wider text-[10px]">Nama Pemilih:</span>
                    <span class="font-semibold text-sm text-gray-900">{{ $student->name }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium uppercase tracking-wider text-[10px]">NIM Mahasiswa:</span>
                    <span class="font-semibold font-mono text-sm text-gray-900">{{ $student->nim }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium uppercase tracking-wider text-[10px]">Program Studi:</span>
                    <span class="font-normal text-gray-900">{{ $student->study_program }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium uppercase tracking-wider text-[10px]">Waktu Pencoblosan:</span>
                    <span class="font-normal text-gray-900">{{ $participation->voted_at?->translatedFormat('d F Y, H:i:s') ?? '-' }} WIB</span>
                </div>
            </div>

            <!-- Chosen Candidate Summary (Menggunakan Foto Paslon Pilihan yang Besar & Jelas) -->
            @if($chosenCandidate)
                <div class="space-y-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Pilihan Suara Anda:</span>
                    <div class="p-5 sm:p-6 rounded-2xl border border-slate-200 bg-slate-50/80 flex flex-col sm:flex-row items-center sm:items-start gap-5 sm:gap-6 shadow-xs">
                        <!-- Foto Paslon Lebih Besar, Proporsional, & Bersih Tanpa Tertutup Badge -->
                        <div class="relative w-32 sm:w-36 md:w-40 aspect-[3/4] rounded-2xl overflow-hidden border-2 border-slate-200 bg-gradient-to-b from-[#52000D] via-[#7A0A0D] to-[#940C10] shadow-md shrink-0">
                            <img src="{{ $chosenCandidate->photo }}" 
                                 onerror="this.onerror=null;this.src='{{ asset('images/paslon-0' . $chosenCandidate->candidate_number . '.png') }}';"
                                 alt="Paslon 0{{ $chosenCandidate->candidate_number }}" 
                                 class="w-full h-full object-cover object-center">
                        </div>

                        <!-- Data Paslon Pilihan (Tanpa Badge, Langsung Label Pasangan Calon No.) -->
                        <div class="flex-1 text-center sm:text-left flex flex-col justify-center">
                            <span class="text-xs sm:text-sm font-bold text-[#CE9501] uppercase tracking-wider block">
                                Pasangan Calon No. 0{{ $chosenCandidate->candidate_number }}
                            </span>
                            <h3 class="text-base sm:text-lg lg:text-xl font-black text-slate-900 mt-1 leading-snug">
                                {{ $chosenCandidate->chairman_name }} & {{ $chosenCandidate->vice_chairman_name }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 font-medium">
                                Calon Ketua & Calon Wakil Ketua BEM UBSI Karawang
                            </p>

                            <!-- Rincian Calon Ketua & Wakil (NIM & Prodi Terpisah Baris Rapi) -->
                            <div class="mt-3 pt-3 border-t border-slate-200/80 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-slate-600">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#940C10] block">Calon Ketua</span>
                                    <p class="font-bold text-slate-900 mt-0.5 leading-snug">{{ $chosenCandidate->chairman_name }}</p>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">NIM: {{ $chosenCandidate->chairman_nim }}</p>
                                    <p class="text-[11px] text-slate-600 font-medium">{{ $chosenCandidate->chairman_study_program }}</p>
                                </div>
                                <div class="sm:border-l sm:border-slate-200/80 sm:pl-3">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#940C10] block">Calon Wakil Ketua</span>
                                    <p class="font-bold text-slate-900 mt-0.5 leading-snug">{{ $chosenCandidate->vice_chairman_name }}</p>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">NIM: {{ $chosenCandidate->vice_chairman_nim }}</p>
                                    <p class="text-[11px] text-slate-600 font-medium">{{ $chosenCandidate->vice_chairman_study_program }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Security Assurance Note -->
            <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-xs text-amber-950 space-y-2">
                <div class="flex items-center gap-2 font-semibold text-[#CE9501]">
                    <svg class="w-4 h-4 text-[#CE9501] shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Ketentuan Kerahasiaan & Keamanan:</span>
                </div>
                <p class="leading-relaxed text-[11px] text-gray-700 font-normal">
                    Hak suara Anda telah digunakan dan berstatus final. Sistem tidak mengizinkan pemungutan suara ulang untuk menjaga prinsip keadilan dan integritas PEMIRA BEM UBSI. Di basis data utama, surat suara Anda telah dipisahkan secara anonim dari identitas NIM Anda.
                </p>
            </div>

            <!-- Actions (Tombol Keluar Sesi Sesuai Warna Tema UBSI) -->
            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <form id="logout-form-receipt" action="{{ route('logout') }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="button" onclick="confirmLogout('logout-form-receipt')" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-[#940C10] via-[#B51016] to-[#7A0A0D] hover:from-[#7A0A0D] hover:via-[#940C10] hover:to-[#B51016] text-white text-xs sm:text-sm font-bold shadow-xl shadow-red-950/40 hover:shadow-red-900/50 transition-all transform hover:-translate-y-0.5 active:scale-[0.99] cursor-pointer">
                        <svg class="w-4 h-4 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Keluar Sesi</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

