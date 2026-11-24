@extends('layouts.admin', ['title' => 'Hasil Pemilihan & Rekapitulasi'])

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Header Halaman -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 sm:gap-6">
        <div class="flex items-start sm:items-center gap-3.5 sm:gap-4">
            <!-- Icon Tanpa Border Sesuai Standar Minimalis UBSI -->
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-[#940C10] shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">Monitoring & Laporan</span>
                    @if($period)
                        <span class="text-slate-300">•</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ ($period->isSystemActive() ?? $period->is_active) ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ ($period->isSystemActive() ?? $period->is_active) ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                            Periode: {{ $period->academic_year ?? $period->name }}
                        </span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">Hasil Pemilihan & Rekapitulasi Suara</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Berita Acara dan Rekapitulasi Akhir Pemungutan Suara PEMIRA BEM UBSI Kab.Karawang</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 sm:gap-3 shrink-0 flex-wrap sm:flex-nowrap w-full sm:w-auto">
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200/90 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 active:scale-95 transition-all shadow-2xs">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"></path></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.elections.monitoring') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#940C10] hover:bg-[#7A0A0D] active:scale-95 text-white text-xs font-bold shadow-xs hover:shadow-md transition-all">
                <svg class="w-4 h-4 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                <span>Live Monitoring</span>
            </a>
        </div>
    </div>

    <!-- Catatan Rekapitulasi Internal & MUSMA -->
    @if($period)
    <div class="p-5 rounded-2xl border border-amber-200/90 bg-amber-50/70 text-amber-950 flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-[#CE9501] border border-amber-400/30 flex items-center justify-center font-bold shrink-0">
                <svg class="w-5 h-5 text-amber-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </div>
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-amber-900">Rekapitulasi Internal KPUM Universitas BSI Karawang</p>
                <p class="text-xs text-amber-800/90 mt-0.5">Hasil rekapitulasi surat suara bersifat internal tertutup untuk pembuatan Berita Acara Pemilihan dan pengumuman resmi pada Musyawarah Mahasiswa (MUSMA).</p>
            </div>
        </div>
        <div class="text-xs font-bold text-amber-900 bg-white/80 px-3 py-1.5 rounded-xl border border-amber-200">
            Total Suara Masuk: {{ number_format($period->ballots()->count()) }}
        </div>
    </div>
    @endif

    <!-- Kartu Hasil Paslon Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
        @forelse($tally as $item)
            <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs p-6 sm:p-8 space-y-6 flex flex-col justify-between">
                <div>
                    <!-- Header Kartu -->
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-14 h-14 rounded-2xl bg-[#940c10] text-[#CE9501] font-black flex items-center justify-center text-2xl shadow-sm shrink-0">
                                0{{ $item['candidate_number'] }}
                            </div>
                            <div>
                                <span class="text-xs font-bold text-[#940c10] uppercase tracking-wider">Kandidat Paslon No. 0{{ $item['candidate_number'] }}</span>
                                <h3 class="text-lg font-black text-slate-900 mt-0.5 leading-snug">{{ $item['chairman_name'] }}</h3>
                                <p class="text-xs text-slate-500 font-medium">& {{ $item['vice_chairman_name'] }}</p>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            <span class="text-3xl sm:text-4xl font-black text-[#940c10]">{{ $item['percentage'] }}%</span>
                            <p class="text-xs font-bold text-slate-600 mt-0.5">{{ number_format($item['total_votes']) }} Suara @if(!empty($item['total_present'])) <span class="font-normal text-slate-400">(dari {{ $item['total_present'] }} Hadir)</span> @endif</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-6 space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-500">
                            <span>Perolehan Suara (dari Pemilih Hadir di TPS)</span>
                            <span>{{ $item['percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-4 overflow-hidden p-0.5 border border-slate-200">
                            <div class="bg-gradient-to-r from-[#940c10] to-[#CE9501] h-3 rounded-full transition-all duration-700" style="width: {{ $item['percentage'] }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                    <span>Program Studi: {{ $item['chairman_study_program'] ?? 'Teknologi Informasi' }}</span>
                    <a href="{{ route('admin.candidates.index') }}" 
                        class="inline-flex items-center gap-1 font-bold text-[#940C10] hover:text-[#7A0A0D] no-underline group/link transition-colors">
                        <span>Detail Paslon</span>
                        <svg class="w-4 h-4 transform group-hover/link:translate-x-1.5 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-3xl p-12 text-center text-slate-400 border border-slate-200">
                Belum ada data paslon atau perolehan suara pada periode pemilihan ini.
            </div>
        @endforelse
    </div>

    <!-- Security & Privacy Guardrail Note -->
    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 flex items-start gap-3">
        <svg class="w-5 h-5 text-[#940c10] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
        <p class="leading-relaxed">
            <strong>Prinsip Kerahasiaan Suara (Anonymous Ballot):</strong> Seluruh rekapitulasi perolehan suara dihitung secara independen dari tabel surat suara anonim (<code>ballots</code>) tanpa relasi foreign key identitas mahasiswa pemilih (<code>students</code>), menjamin kerahasiaan pilihan (Luber Jurdil).
        </p>
    </div>

</div>
@endsection
