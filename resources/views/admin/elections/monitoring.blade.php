@extends('layouts.admin', ['title' => 'Monitoring Pemungutan Suara'])

@section('content')
<div id="monitoring-live-container" x-data="liveMonitoring()" class="space-y-6 sm:space-y-8">

    <!-- Header Halaman -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-[#940C10] to-[#7A0A0D] text-white flex items-center justify-center font-bold shadow-md shadow-red-950/20 shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">Pelaksanaan Pemilihan</span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span x-text="isSyncing ? 'SINKRONISASI...' : 'LIVE AUTO-SYNC'">LIVE AUTO-SYNC</span>
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">Monitoring Pemungutan Suara</h1>
                <p class="text-xs text-slate-500 font-medium">
                    Pemantauan Partisipasi Mahasiswa & Rekapitulasi Masuk Periode {{ $period->name ?? '-' }}
                    <span class="text-slate-400">&bull; Terakhir disinkron: <span class="font-semibold text-slate-700" x-text="lastSync">Live</span></span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <button @click="syncNow()" :disabled="isSyncing"
                class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 active:scale-95 transition-all shadow-2xs cursor-pointer disabled:opacity-50">
                <svg class="w-4 h-4 text-slate-500" :class="isSyncing ? 'animate-spin text-[#940c10]' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                </svg>
                <span x-text="isSyncing ? 'Sinkronisasi...' : 'Sinkronkan Sekarang'">Sinkronkan Sekarang</span>
            </button>
            <a href="{{ route('admin.elections.results') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-[#940c10] text-white hover:bg-[#7A0A0D] text-xs font-bold shadow-sm shadow-red-900/30 transition-all">
                <svg class="w-4 h-4 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                <span>Hasil Resmi Rekapitulasi</span>
            </a>
        </div>
    </div>

    <!-- Stat Metrik Grid -->
    @if($stats)
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="bg-white p-5 rounded-2xl xl:rounded-3xl border border-slate-200/90 shadow-xs">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total DPT</span>
            <p class="text-3xl font-black text-slate-900 mt-2" x-text="formatNumber(stats.total_students)">{{ number_format($stats['total_students']) }}</p>
            <p class="text-xs text-slate-500 mt-1">Pemilih Aktif Terdaftar</p>
        </div>
        <div class="bg-white p-5 rounded-2xl xl:rounded-3xl border border-slate-200/90 shadow-xs">
            <span class="text-xs font-bold text-[#940c10] uppercase tracking-wider">Suara Masuk</span>
            <p class="text-3xl font-black text-[#940c10] mt-2" x-text="formatNumber(stats.total_voted)">{{ number_format($stats['total_voted']) }}</p>
            <p class="text-xs text-slate-500 mt-1">Surat Suara Sah Masuk</p>
        </div>
        <div class="bg-white p-5 rounded-2xl xl:rounded-3xl border border-slate-200/90 shadow-xs">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Belum Memilih</span>
            <p class="text-3xl font-black text-slate-700 mt-2" x-text="formatNumber(stats.total_not_voted)">{{ number_format($stats['total_not_voted']) }}</p>
            <p class="text-xs text-slate-500 mt-1">Sisa Hak Suara</p>
        </div>
        <div class="bg-white p-5 rounded-2xl xl:rounded-3xl border border-slate-200/90 shadow-xs">
            <span class="text-xs font-bold text-[#CE9501] uppercase tracking-wider">Tingkat Partisipasi</span>
            <p class="text-3xl font-black text-[#CE9501] mt-2" x-text="stats.turnout_percentage + '%'">{{ $stats['turnout_percentage'] }}%</p>
            <p class="text-xs text-slate-500 mt-1">Target Partisipasi Kampus</p>
        </div>
    </div>

    <!-- Turnout Visual Progress Bar -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Progres Kehadiran Pemilih</h3>
                <p class="text-xs text-slate-500">
                    <span x-text="formatNumber(stats.total_voted)">{{ number_format($stats['total_voted']) }}</span> dari 
                    <span x-text="formatNumber(stats.total_students)">{{ number_format($stats['total_students']) }}</span> mahasiswa telah menyalurkan hak suara
                </p>
            </div>
            <span class="text-xl font-black text-[#940c10]" x-text="stats.turnout_percentage + '%'">{{ $stats['turnout_percentage'] }}%</span>
        </div>
        <div class="w-full bg-slate-100 rounded-full h-4 overflow-hidden p-0.5 border border-slate-200">
            <div class="bg-gradient-to-r from-[#940c10] to-[#CE9501] h-3 rounded-full transition-all duration-700 ease-out"
                :style="'width: ' + stats.turnout_percentage + '%'"></div>
        </div>
    </div>
    @endif

    <!-- Grid: Quick Tally & Live Voter Stream -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Tally Sementara -->
        <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Rekapitulasi Suara Paslon Sementara</h3>
                <span class="text-xs text-slate-400 font-mono">Berdasarkan Kehadiran TPS</span>
            </div>

            <div class="space-y-4">
                <template x-for="item in tally" :key="item.id">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-3 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#940c10] text-[#CE9501] font-bold text-sm flex items-center justify-center shrink-0 shadow-xs"
                                    x-text="'0' + item.candidate_number">
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900" x-text="item.chairman_name + ' & ' + item.vice_chairman_name"></p>
                                    <span class="text-[10px] text-slate-500" x-text="'Paslon Nomor Urut 0' + item.candidate_number"></span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-base font-black text-[#940c10]" x-text="item.percentage + '%'"></span>
                                <p class="text-[10px] text-slate-500 font-semibold" x-text="formatNumber(item.total_votes) + ' Suara' + (item.total_present ? ' (dari ' + item.total_present + ' Hadir)' : '')"></p>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-gradient-to-r from-[#940c10] to-[#CE9501] h-2.5 rounded-full transition-all duration-700 ease-out"
                                :style="'width: ' + item.percentage + '%'"></div>
                        </div>
                    </div>
                </template>
                <template x-if="tally.length === 0">
                    <p class="text-xs text-slate-400 text-center py-6">Belum ada suara masuk.</p>
                </template>
            </div>
        </div>

        <!-- Stream Suara Terkini -->
        <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Aktivitas Suara Terkini (Stream TPS)</h3>
                <a href="{{ route('admin.voters.index') }}" 
                    class="inline-flex items-center gap-1 text-xs font-bold text-[#940C10] hover:text-[#7A0A0D] no-underline group/link transition-colors">
                    <span>Semua DPT</span>
                    <svg class="w-4 h-4 transform group-hover/link:translate-x-1.5 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>

            <div class="space-y-2.5 max-h-[380px] overflow-y-auto admin-scrollbar pr-1">
                <template x-for="(v, idx) in recent" :key="v.nim + '-' + idx">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs transition-all">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-slate-900 truncate" x-text="v.name"></p>
                                <span class="text-[10px] text-slate-400 font-mono" x-text="v.nim + ' • ' + v.study_program"></span>
                            </div>
                        </div>
                        <span class="text-[10px] text-slate-400 font-mono shrink-0 pl-2" x-text="v.voted_at_diff"></span>
                    </div>
                </template>
                <template x-if="recent.length === 0">
                    <p class="text-xs text-slate-400 text-center py-8">Belum ada suara tercatat di TPS.</p>
                </template>
            </div>
        </div>

    </div>

</div>

@php
    $recentFormatted = $recentVoters ? $recentVoters->map(function($v) {
        return [
            'name' => $v->student->name ?? 'Mahasiswa',
            'nim' => $v->student_nim,
            'study_program' => $v->student->study_program ?? '-',
            'voted_at_diff' => $v->voted_at ? $v->voted_at->diffForHumans() : '-',
        ];
    }) : [];
@endphp

<script>
function liveMonitoring() {
    return {
        stats: {
            total_students: {{ $stats['total_students'] ?? 0 }},
            total_voted: {{ $stats['total_voted'] ?? 0 }},
            total_not_voted: {{ $stats['total_not_voted'] ?? 0 }},
            turnout_percentage: {{ $stats['turnout_percentage'] ?? 0 }}
        },
        tally: @json($tally ?? []),
        recent: @json($recentFormatted),
        lastSync: '{{ now()->timezone("Asia/Jakarta")->format("H:i:s") }} WIB',
        isSyncing: false,
        timer: null,
        periodId: '{{ $period->id ?? "" }}',
        liveUrl: '{{ route("admin.elections.live-data") }}',

        init() {
            if (!this.periodId) return;
            if (this.timer) clearInterval(this.timer);
            // Polling background setiap 3 detik
            this.timer = setInterval(() => {
                if (!document.getElementById('monitoring-live-container')) {
                    clearInterval(this.timer);
                    return;
                }
                this.syncNow();
            }, 3000);
        },

        async syncNow() {
            if (!this.periodId) return;
            try {
                this.isSyncing = true;
                const response = await fetch(`${this.liveUrl}?period_id=${this.periodId}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) return;
                const data = await response.json();
                if (data.stats) {
                    this.stats = data.stats;
                    this.tally = data.tally;
                    this.recent = data.recent_participations || [];
                    this.lastSync = data.timestamp;
                }
            } catch (err) {
                console.warn('Live poll error:', err);
            } finally {
                setTimeout(() => { this.isSyncing = false; }, 350);
            }
        },

        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        }
    }
}
</script>
@endsection
