@extends('layouts.admin', ['title' => 'Kelola DPT & Kehadiran Pemilih'])

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Header Halaman -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-[#940C10] to-[#7A0A0D] text-white flex items-center justify-center font-bold shadow-md shadow-red-950/20 shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">Manajemen Pemilih</span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">Daftar Pemilih Tetap (DPT) & Kehadiran</h1>
                <p class="text-xs text-slate-500 font-medium">Periode: {{ $period->name ?? 'Belum ada periode aktif' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 transition-all shadow-2xs">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"></path></svg>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Mini KPI Stat Bar -->
    @if($stats)
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total DPT</span>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total_students']) }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-bold text-[#940c10] uppercase tracking-wider">Sudah Memilih</span>
            <p class="text-2xl font-black text-[#940c10] mt-1">{{ number_format($stats['total_voted']) }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Belum Memilih</span>
            <p class="text-2xl font-black text-slate-700 mt-1">{{ number_format($stats['total_not_voted']) }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-bold text-[#CE9501] uppercase tracking-wider">Tingkat Kehadiran</span>
            <p class="text-2xl font-black text-[#CE9501] mt-1">{{ $stats['turnout_percentage'] }}%</p>
        </div>
    </div>
    @endif

    <!-- Filter & Data Table -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            
            <!-- Quick Status Filter Pills -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.voters.index', request()->except('status', 'page')) }}" 
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ !request('status') ? 'bg-[#940C10] text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Semua (DPT)
                </a>
                <a href="{{ route('admin.voters.index', array_merge(request()->except('page'), ['status' => 'voted'])) }}" 
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === 'voted' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Sudah Memilih
                </a>
                <a href="{{ route('admin.voters.index', array_merge(request()->except('page'), ['status' => 'not_voted'])) }}" 
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === 'not_voted' ? 'bg-[#940C10] text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Belum Memilih
                </a>
            </div>

            <!-- Form Pencarian -->
            <form action="{{ route('admin.voters.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative flex-1 md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIM atau Nama..."
                        class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-300 text-xs font-medium focus:ring-2 focus:ring-[#940c10] focus:border-[#940c10] bg-white">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-all cursor-pointer">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.voters.index', request()->except('search', 'page')) }}" class="p-2 text-slate-400 hover:text-slate-600 text-xs font-bold" title="Reset Pencarian">
                        &times;
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel DPT -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-400 uppercase tracking-wider text-[10px] font-bold">
                        <th class="py-3.5 px-5">NIM Mahasiswa</th>
                        <th class="py-3.5 px-5">Nama Lengkap</th>
                        <th class="py-3.5 px-5">Program Studi</th>
                        <th class="py-3.5 px-5">Status Hak Suara</th>
                        <th class="py-3.5 px-5">Waktu Voting</th>
                        <th class="py-3.5 px-5">Verifikasi TPS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($participations as $part)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-5 font-mono font-bold text-slate-900">{{ $part->student_nim }}</td>
                            <td class="py-3.5 px-5 font-medium text-slate-900">{{ $part->student->name ?? 'Mahasiswa' }}</td>
                            <td class="py-3.5 px-5 text-slate-600">{{ $part->student->study_program ?? '-' }}</td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $part->has_voted ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                    {{ $part->has_voted ? 'Sudah Memilih' : 'Belum Memilih' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-slate-500 font-mono">
                                {{ $part->voted_at ? $part->voted_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-slate-500">
                                {{ $part->verifiedBy ? $part->verifiedBy->name : 'Mandiri Online' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                Tidak ada data pemilih yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginasi -->
        @if($participations->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $participations->links() }}
        </div>
        @endif

    </div>

</div>
@endsection
