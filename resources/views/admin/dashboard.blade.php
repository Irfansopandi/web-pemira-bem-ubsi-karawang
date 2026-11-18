@extends('layouts.admin', ['title' => 'Dashboard Utama'])

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Top Admin Bar: Kontrol Periode & Aksi Cepat -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-5 sm:p-6 xl:p-7 shadow-xs flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5">
        <div class="flex items-center gap-3.5 sm:gap-4">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-[#940C10] to-[#7A0A0D] text-white flex items-center justify-center font-bold shadow-md shadow-red-950/20 shrink-0">
                <i data-lucide="layout-dashboard" class="w-6 h-6 sm:w-7 sm:h-7 text-[#CE9501]"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#940C10]">KPUM BEM UBSI Karawang</span>
                    @if($period)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $period->status === 'active' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : '' }}
                            {{ $period->status === 'draft' ? 'bg-slate-100 text-slate-800 border border-slate-300' : '' }}
                            {{ $period->status === 'closed' ? 'bg-red-100 text-red-800 border border-red-300' : '' }}
                            {{ $period->status === 'archived' ? 'bg-amber-100 text-amber-800 border border-amber-300' : '' }}">
                            ● {{ strtoupper($period->status) }}
                        </span>
                        @if($period->is_result_published)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300">
                                HASIL DITERBITKAN
                            </span>
                        @endif
                    @endif
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    {{ $period->name ?? 'Belum Ada Periode Pemilihan' }}
                </h2>
                <p class="text-xs text-slate-500 font-medium">Monitoring Partisipasi, Rekapitulasi Suara & Manajemen Periode</p>
            </div>
        </div>

        <!-- Controls & Status Actions -->
        <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap w-full lg:w-auto justify-start lg:justify-end">
            <a href="{{ route('admin.candidates.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-[#940c10] text-white hover:bg-[#7A0A0D] text-xs font-bold shadow-sm shadow-red-900/30 transition-all">
                <i data-lucide="users" class="w-4 h-4 text-[#CE9501]"></i>
                <span>Kelola Paslon & Visi Misi</span>
            </a>

            @if($period)
            <!-- Toggle Publish Results Button -->
            <form action="{{ route('admin.periods.toggle-publish', $period) }}" method="POST">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all shadow-xs cursor-pointer {{ $period->is_result_published ? 'bg-amber-100 text-amber-900 hover:bg-amber-200 border border-amber-300' : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-emerald-900/20' }}">
                    <i data-lucide="{{ $period->is_result_published ? 'eye-off' : 'eye' }}" class="w-4 h-4"></i>
                    <span>{{ $period->is_result_published ? 'Tarik Publikasi Hasil' : 'Publikasikan Hasil' }}</span>
                </button>
            </form>

            <!-- Toggle Period Status -->
            <form action="{{ route('admin.periods.toggle-status', $period) }}" method="POST" class="inline">
                @csrf
                <select name="status" onchange="this.form.submit()"
                    class="px-3 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 bg-white shadow-2xs focus:ring-2 focus:ring-[#940c10] cursor-pointer">
                    <option value="draft" {{ $period->status === 'draft' ? 'selected' : '' }}>Status: Draft</option>
                    <option value="active" {{ $period->status === 'active' ? 'selected' : '' }}>Status: Aktif (Bilik Buka)</option>
                    <option value="closed" {{ $period->status === 'closed' ? 'selected' : '' }}>Status: Ditutup</option>
                    <option value="archived" {{ $period->status === 'archived' ? 'selected' : '' }}>Status: Diarsipkan</option>
                </select>
            </form>
            @endif
        </div>
    </div>

    @if($period)
        <!-- Stats Summary Grid -->
        @if($stats)
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-xs">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total DPT Mahasiswa</span>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_students']) }}</p>
                <p class="text-xs text-gray-500 mt-1 font-normal">Pemilih Aktif Terdaftar</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-xs">
                <span class="text-xs font-semibold text-[#940c10] uppercase tracking-wider">Suara Masuk</span>
                <p class="text-3xl font-bold text-[#940c10] mt-2">{{ number_format($stats['total_voted']) }}</p>
                <p class="text-xs text-gray-500 mt-1 font-normal">Surat Suara di Kotak</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-xs">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Belum Memilih</span>
                <p class="text-3xl font-bold text-gray-700 mt-2">{{ number_format($stats['total_not_voted']) }}</p>
                <p class="text-xs text-gray-500 mt-1 font-normal">Sisa Hak Suara</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-xs">
                <span class="text-xs font-semibold text-[#CE9501] uppercase tracking-wider">Tingkat Partisipasi</span>
                <p class="text-3xl font-bold text-[#CE9501] mt-2">{{ $stats['turnout_percentage'] }}%</p>
                <p class="text-xs text-gray-500 mt-1 font-normal">Partisipasi Pemilih</p>
            </div>
        </div>
        @endif

        <!-- Realtime Tally Cards -->
        <div class="bg-white rounded-3xl border border-gray-200 p-8 shadow-xs space-y-6">
            <div class="flex items-center justify-between flex-wrap gap-4 pb-4 border-b border-gray-100">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Rekapitulasi Suara Anonim Realtime</h2>
                    <p class="text-xs text-gray-500 font-normal">Dihitung murni dari tabel kotak suara tanpa foreign key mahasiswa</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.candidates.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:text-[#940c10] hover:border-[#940c10] transition-colors">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-[#940c10]"></i>
                        <span>Edit Paslon & Visi Misi</span>
                    </a>
                    <div class="flex items-center gap-2 pl-3 border-l border-gray-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs font-semibold text-gray-600">Sinkronisasi Otomatis</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($tally as $item)
                    <div class="p-6 rounded-2xl bg-gray-50 border border-gray-200 space-y-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-[#940c10] text-[#CE9501] font-bold flex items-center justify-center text-xl shadow-xs">
                                    0{{ $item['candidate_number'] }}
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-[#940c10] uppercase">Paslon 0{{ $item['candidate_number'] }}</span>
                                    <p class="font-bold text-base text-gray-900">{{ $item['chairman_name'] }}</p>
                                    <p class="text-xs text-gray-500 font-normal">& {{ $item['vice_chairman_name'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-3xl font-bold text-[#940c10]">{{ $item['percentage'] }}%</span>
                                <p class="text-xs font-semibold text-gray-600">{{ number_format($item['total_votes']) }} Suara</p>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-3.5 overflow-hidden">
                            <div class="bg-gradient-to-r from-[#940c10] to-[#CE9501] h-3.5 rounded-full transition-all duration-700" style="width: {{ $item['percentage'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Voter Participation Table -->
        @if($participations)
        <div id="tabel-dpt" class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs space-y-4 p-6 sm:p-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Daftar Kehadiran & Partisipasi Pemilih</h2>
                    <p class="text-xs text-gray-500">Mencatat status penggunaan hak pilih dan log TPS</p>
                </div>
                <!-- Filters -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ !request('status') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Semua</a>
                    <a href="{{ route('admin.dashboard', ['status' => 'voted']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ request('status') === 'voted' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Sudah Memilih</a>
                    <a href="{{ route('admin.dashboard', ['status' => 'not_voted']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ request('status') === 'not_voted' ? 'bg-[#940c10] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Belum Memilih</a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-400 uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4">NIM</th>
                            <th class="py-3 px-4">Nama Mahasiswa</th>
                            <th class="py-3 px-4">Program Studi</th>
                            <th class="py-3 px-4">Status Hak Suara</th>
                            <th class="py-3 px-4">Waktu Voting</th>
                            <th class="py-3 px-4">Verifikasi TPS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($participations as $part)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-gray-900">{{ $part->student->nim }}</td>
                                <td class="py-3.5 px-4 font-medium text-gray-900">{{ $part->student->name }}</td>
                                <td class="py-3.5 px-4">{{ $part->student->study_program }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ $part->has_voted ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $part->has_voted ? 'Sudah Memilih' : 'Belum Memilih' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-gray-500">
                                    {{ $part->voted_at ? $part->voted_at->translatedFormat('d M Y, H:i') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-500">
                                    {{ $part->verifiedBy ? $part->verifiedBy->name : 'Mandiri Online' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-gray-100">
                {{ $participations->links() }}
            </div>
        </div>
        @endif

        <!-- Audit Log Stream -->
        <div id="audit-trail" class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-gray-900">Log Aktivitas Sistem Terkini (Audit Trail)</h3>
            <div class="space-y-3">
                @forelse($recentLogs as $log)
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <i data-lucide="activity" class="w-4 h-4 text-[#940c10]"></i>
                            <div>
                                <span class="font-bold text-gray-900">{{ $log->action }}</span>
                                <span class="text-gray-500">oleh {{ $log->user->name ?? 'System' }}</span>
                            </div>
                        </div>
                        <span class="text-[11px] text-gray-400 font-mono">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">Belum ada aktivitas tercatat.</p>
                @endforelse
            </div>
        </div>
    @endif

</div>
@endsection

