@extends('layouts.app', ['title' => 'Tanda Terima Hak Suara'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-lg space-y-0">
        <!-- Top Receipt Header -->
        <div class="bg-gradient-to-r from-[#940c10] to-[#7A0A0D] p-8 text-white flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-xs flex items-center justify-center text-white ring-2 ring-[#CE9501]/40">
                    <i data-lucide="shield-check" class="w-8 h-8 text-[#CE9501]"></i>
                </div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-[#CE9501]">Sertifikat Partisipasi Resmi</span>
                    <h1 class="text-2xl font-black">Tanda Terima Pemilih</h1>
                    <p class="text-xs text-red-100">PEMIRA BEM Universitas BSI Karawang {{ $activePeriod->academic_year }}</p>
                </div>
            </div>
            <div class="px-3.5 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400 text-emerald-200 text-xs font-bold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Suara Telah Sah Digunakan
            </div>
        </div>

        <!-- Receipt Body Details -->
        <div class="p-8 space-y-8">
            <!-- Student Voter Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 rounded-2xl bg-gray-50 border border-gray-100 text-xs text-gray-700">
                <div>
                    <span class="text-gray-400 block font-semibold uppercase tracking-wider text-[10px]">Nama Pemilih:</span>
                    <span class="font-bold text-sm text-gray-900">{{ $student->name }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-semibold uppercase tracking-wider text-[10px]">NIM Mahasiswa:</span>
                    <span class="font-bold font-mono text-sm text-gray-900">{{ $student->nim }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-semibold uppercase tracking-wider text-[10px]">Program Studi:</span>
                    <span class="font-medium text-gray-900">{{ $student->study_program }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-semibold uppercase tracking-wider text-[10px]">Waktu Pencoblosan:</span>
                    <span class="font-medium text-gray-900">{{ $participation->voted_at?->translatedFormat('d F Y, H:i:s') ?? '-' }} WIB</span>
                </div>
            </div>

            <!-- Chosen Candidate Summary (Decrypted from Isolated Encrypted Receipt) -->
            @if($chosenCandidate)
                <div class="space-y-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 block">Pilihan Suara Anda:</span>
                    <div class="p-6 rounded-2xl border-2 border-emerald-500/30 bg-emerald-50/30 flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-[#940c10] text-[#CE9501] font-black text-2xl flex items-center justify-center shadow-xs">
                                0{{ $chosenCandidate->candidate_number }}
                            </div>
                            <div>
                                <span class="text-xs font-bold text-[#059669] uppercase tracking-wider">Pasangan Calon Pilihan</span>
                                <p class="text-lg font-black text-gray-900">{{ $chosenCandidate->chairman_name }} & {{ $chosenCandidate->vice_chairman_name }}</p>
                                <p class="text-xs text-gray-600 mt-0.5">Calon Ketua & Wakil Ketua BEM UBSI Karawang</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                            Tersimpan Terenkripsi
                        </span>
                    </div>
                </div>
            @endif

            <!-- Security Assurance Note -->
            <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-xs text-amber-950 space-y-2">
                <div class="flex items-center gap-2 font-bold text-[#CE9501]">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    <span>Ketentuan Kerahasiaan & Keamanan:</span>
                </div>
                <p class="leading-relaxed text-[11px] text-gray-700">
                    Hak suara Anda telah digunakan dan berstatus final. Sistem tidak mengizinkan pemungutan suara ulang untuk menjaga prinsip keadilan dan integritas PEMIRA BEM UBSI. Di basis data utama, surat suara Anda telah dipisahkan secara anonim dari identitas NIM Anda.
                </p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100 flex-wrap gap-4">
                <a href="{{ route('student.voting') }}" class="text-xs font-bold text-gray-600 hover:text-[#940c10] flex items-center gap-1.5">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Kembali ke Halaman Bilik Suara</span>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-900 text-white text-xs font-bold hover:bg-black transition-colors shadow-xs">
                        <i data-lucide="log-out" class="w-4 h-4 text-[#CE9501]"></i>
                        <span>Keluar Sesi</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

