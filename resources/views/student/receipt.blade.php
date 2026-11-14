@extends('layouts.app', ['title' => 'Tanda Terima Hak Suara', 'hideNavbar' => true, 'bodyClass' => 'bg-slate-50/70'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
    <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-xl space-y-0">
        <!-- Top Receipt Header (Logo BEM & Kabinet, Serta Badge Suara Sah Bersih) -->
        <div class="bg-gradient-to-r from-[#940c10] to-[#7A0A0D] p-6 sm:p-8 text-white flex items-start justify-between flex-wrap gap-4 border-b-2 border-[#CE9501]">
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

            <!-- Chosen Candidate Summary (Menggunakan Foto Paslon Pilihan) -->
            @if($chosenCandidate)
                <div class="space-y-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 block">Pilihan Suara Anda:</span>
                    <div class="p-5 sm:p-6 rounded-2xl border border-slate-200 bg-slate-50/70 flex items-center justify-between flex-wrap gap-5 shadow-xs">
                        <div class="flex items-center gap-4 sm:gap-5">
                            <!-- Foto Paslon Lengkap dengan Badge Nomor Urut -->
                            <div class="relative w-20 h-24 sm:w-24 sm:h-28 rounded-2xl overflow-hidden border-2 border-slate-200 bg-white shadow-md shrink-0">
                                <img src="{{ $chosenCandidate->photo }}" alt="Paslon 0{{ $chosenCandidate->candidate_number }}" class="w-full h-full object-cover">
                                <!-- Badge Nomor Urut di Pojok Kiri Atas Foto -->
                                <div class="absolute top-1.5 left-1.5 px-2 py-0.5 rounded-lg bg-[#940c10] text-[#CE9501] border border-[#CE9501] text-[10px] font-bold shadow-xs">
                                    0{{ $chosenCandidate->candidate_number }}
                                </div>
                            </div>

                            <div>
                                <span class="text-xs font-semibold text-[#CE9501] uppercase tracking-wider">Pasangan Calon Terpilih</span>
                                <p class="text-base sm:text-lg font-bold text-gray-900 mt-0.5 leading-snug">{{ $chosenCandidate->chairman_name }} & {{ $chosenCandidate->vice_chairman_name }}</p>
                                <p class="text-xs text-gray-500 mt-1 font-normal">Calon Ketua & Wakil Ketua BEM UBSI Karawang</p>
                                <div class="mt-2.5 inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-semibold">
                                    Tersimpan Terenkripsi
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Security Assurance Note -->
            <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-xs text-amber-950 space-y-2">
                <div class="flex items-center gap-2 font-semibold text-[#CE9501]">
                    <i data-lucide="lock" class="w-4 h-4"></i>
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
                    <button type="button" onclick="confirmLogout('logout-form-receipt')" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3 rounded-xl bg-[#940c10] hover:bg-[#7A0A0D] text-white text-xs sm:text-sm font-semibold shadow-md shadow-[#940c10]/20 transition-all transform hover:-translate-y-0.5 cursor-pointer">
                        <i data-lucide="log-out" class="w-4 h-4 text-[#CE9501]"></i>
                        <span>Keluar Sesi</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

