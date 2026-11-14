@extends('layouts.app', ['title' => 'Detail Paslon 0' . $candidate->candidate_number, 'hideNavbar' => true, 'bodyClass' => 'bg-slate-50/70'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 pb-16">

    <!-- Header Bar Detail Paslon (Tombol Kembali & Identitas Acara) -->
    <div class="mb-5 flex items-center justify-between">
        <a href="{{ route('student.voting') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold shadow-md shadow-slate-200/80 border border-slate-200/80 transition-all hover:-translate-y-0.5 cursor-pointer">
            <span class="text-base leading-none">&larr;</span>
            <span>Kembali ke Bilik Suara</span>
        </a>

        <div class="hidden sm:flex items-center gap-2 text-xs font-medium text-slate-400">
            <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI" class="w-5 h-5 object-contain opacity-70">
            <span>PEMIRA BEM UBSI Karawang</span>
        </div>
    </div>

    <!-- Layout Detail Paslon (2 Kolom: Foto & Profil Lengkap) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- KOLOM KIRI (FOTO PASLON & TOMBOL PILIH - MELAYANG & STICKY PADA LAYAR ATAS) -->
        <div class="lg:col-span-5 space-y-4 lg:sticky lg:top-4 lg:self-start">
            <!-- Card Foto Paslon Full (Lebar Penuh Sejajar Sempurna dengan Tombol Coblos) -->
            <div class="relative w-full h-[460px] sm:h-[500px] lg:h-[calc(100vh-135px)] max-h-[580px] rounded-3xl overflow-hidden shadow-2xl shadow-slate-300/60 border border-slate-200/80 bg-white">
                <!-- Badge Nomor Urut di Pojok Kiri Atas (Sama dengan Halaman Voting) -->
                <div class="absolute top-4 left-4 z-20">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-[#940c10] text-[#CE9501] border-2 border-[#CE9501] flex flex-col items-center justify-center shadow-lg">
                        <span class="text-[9px] sm:text-[10px] uppercase tracking-wider font-semibold text-white leading-none">NOMOR</span>
                        <span class="text-2xl sm:text-3xl leading-none font-bold text-[#CE9501]">0{{ $candidate->candidate_number }}</span>
                    </div>
                </div>

                <!-- Foto Paslon (Storage & Fallback) -->
                <img src="{{ $candidate->photo }}" alt="Paslon {{ $candidate->candidate_number }}" class="w-full h-full object-cover">
            </div>

            <!-- Tombol Pilih Paslon Ini (Konfirmasi Menggunakan SweetAlert2) -->
            <button type="button" onclick="confirmVote()"
                class="w-full py-3.5 sm:py-4 px-6 rounded-2xl text-white bg-[#940c10] hover:bg-[#7A0A0D] text-base font-semibold shadow-xl shadow-[#940c10]/25 transition-all transform hover:-translate-y-0.5 flex items-center justify-center cursor-pointer">
                <span>COBLOS PASLON 0{{ $candidate->candidate_number }}</span>
            </button>
        </div>

        <!-- KOLOM KANAN (PROFIL, VISI, MISI, PROGRAM KERJA MENYATU DALAM 1 CARD TUNGGAL MELAYANG) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-2xl shadow-slate-300/60 space-y-6 sm:space-y-8">
                
                <!-- 1. Header Paslon & Data Calon -->
                <div>
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-900 tracking-normal leading-tight">
                        {{ $candidate->chairman_name }} & {{ $candidate->vice_chairman_name }}
                    </h1>
                    <p class="text-xs text-slate-500 mt-1.5 font-normal">
                        PEMIRA BEM UBSI Karawang &bull; Periode {{ $activePeriod->academic_year }}
                    </p>

                    <!-- Data Calon Ketua & Calon Wakil Ketua (Dibatasi Garis Lurus Vertikal) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8 mt-6 pt-6 border-t border-slate-100">
                        <!-- Calon Ketua -->
                        <div class="sm:pr-6 sm:border-r border-slate-200">
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-[#940c10]">Calon Ketua</span>
                            <p class="text-base font-bold text-slate-900 mt-1">{{ $candidate->chairman_name }}</p>
                            <p class="text-xs text-slate-500 font-mono mt-0.5">NIM: {{ $candidate->chairman_nim }}</p>
                            <p class="text-xs text-slate-600 font-medium">{{ $candidate->chairman_study_program }}</p>
                        </div>
                        <!-- Calon Wakil Ketua -->
                        <div class="pt-4 sm:pt-0 border-t sm:border-t-0 border-slate-200 sm:pl-2">
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-[#CE9501]">Calon Wakil Ketua</span>
                            <p class="text-base font-bold text-slate-900 mt-1">{{ $candidate->vice_chairman_name }}</p>
                            <p class="text-xs text-slate-500 font-mono mt-0.5">NIM: {{ $candidate->vice_chairman_nim }}</p>
                            <p class="text-xs text-slate-600 font-medium">{{ $candidate->vice_chairman_study_program }}</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Visi Pasangan Calon (Teks Mengalir Langsung Tanpa Dibungkus Card Tambahan) -->
                <div class="pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-[#940c10] mb-3">
                        Visi Pasangan Calon
                    </h3>
                    <p class="text-sm sm:text-base italic text-slate-700 leading-relaxed font-normal">
                        "{{ $candidate->vision }}"
                    </p>
                </div>

                <!-- 3. Misi Pasangan Calon (Daftar Bersih Mengalir Tanpa Dibungkus Card) -->
                <div class="pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-[#940c10] mb-4">
                        Misi Pasangan Calon
                    </h3>
                    <ol class="space-y-3">
                        @foreach($candidate->mission ?? [] as $index => $m)
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full bg-red-50 text-[#940c10] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-sm text-slate-700 leading-relaxed font-normal">{{ $m }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <!-- 4. Program Kerja Unggulan (Mengalir Langsung di Dalam Card Utama) -->
                @if(!empty($candidate->work_programs))
                    <div class="pt-6 border-t border-slate-100">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-[#940c10] mb-4">
                            Program Kerja Unggulan
                        </h3>
                        <ul class="space-y-3">
                            @foreach($candidate->work_programs as $program)
                                <li class="flex items-start gap-3">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#CE9501] shrink-0 mt-2"></span>
                                    <span class="text-sm text-slate-700 font-normal leading-relaxed">{{ $program }}</span>
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

    <!-- Script Dialog Konfirmasi SweetAlert2 (Sesuai Tema UBSI, Lebar Proporsional, Tanpa Icon & Tanpa Wrap) -->
    <script>
        function confirmVote() {
            Swal.fire({
                title: 'Konfirmasi Pilihan',
                html: `
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif;" class="text-center">
                        <p class="text-xs text-slate-500 font-normal mb-2 mt-1">Anda akan memberikan suara untuk:</p>
                        
                        <!-- Badge Paslon (Kuning Emas UBSI yang Jelas & Kontras Tinggi) -->
                        <div class="inline-flex items-center px-4 py-1 rounded-full bg-[#CE9501] text-white text-xs font-bold shadow-xs whitespace-nowrap">
                            Pasangan Calon No. 0{{ $candidate->candidate_number }}
                        </div>

                        <!-- Nama Paslon (Satu Baris, Tidak Ke-Enter) -->
                        <p class="text-base sm:text-lg font-bold text-slate-900 mt-2.5 mb-1 leading-snug whitespace-nowrap overflow-hidden text-ellipsis">
                            {{ addslashes($candidate->chairman_name) }} & {{ addslashes($candidate->vice_chairman_name) }}
                        </p>

                        <!-- Deskripsi Ringkas & Efisien -->
                        <p class="text-xs text-slate-500 leading-relaxed mt-2 px-2">
                            Pilihan bersifat <strong>final & anonim</strong>. Surat suara akan langsung dimasukkan ke kotak suara digital.
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
                    popup: 'rounded-3xl border border-slate-200 shadow-2xl p-6 sm:p-8 max-w-lg w-full',
                    title: 'text-xl font-bold text-slate-900 p-0 m-0',
                    htmlContainer: 'p-0 m-0 mt-3',
                    actions: 'mt-6 flex items-center justify-center gap-3 w-full',
                    confirmButton: 'whitespace-nowrap px-5 py-3 rounded-xl font-semibold text-xs sm:text-sm text-white bg-[#940c10] hover:bg-[#7A0A0D] shadow-md transition-all cursor-pointer',
                    cancelButton: 'whitespace-nowrap px-5 py-3 rounded-xl font-semibold text-xs sm:text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim Suara...',
                        text: 'Mohon tunggu, surat suara digital sedang diproses secara aman.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
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
