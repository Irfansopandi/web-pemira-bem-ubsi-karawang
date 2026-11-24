@extends('layouts.admin', ['title' => 'Kelola Periode Pemilihan'])

@section('content')
<div class="space-y-6 sm:space-y-8" x-data="periodManagement()">

    <!-- Header Halaman -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
        <div class="flex items-start sm:items-center gap-3.5 sm:gap-4">
            <!-- Icon Tanpa Border / Kotak Sesuai Standar Minimalis -->
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-[#940C10] shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
                <line x1="8" y1="14" x2="8.01" y2="14"></line>
                <line x1="12" y1="14" x2="12.01" y2="14"></line>
                <line x1="16" y1="14" x2="16.01" y2="14"></line>
                <line x1="8" y1="18" x2="8.01" y2="18"></line>
                <line x1="12" y1="18" x2="12.01" y2="18"></line>
                <line x1="16" y1="18" x2="16.01" y2="18"></line>
            </svg>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">Manajemen Pemilihan</span>
                    @if($activePeriod)
                        <span class="text-slate-300">•</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Aktif: {{ $activePeriod->academic_year }}
                        </span>
                        @if($activePeriod->isVotingOpen())
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-800">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Bilik Suara Dibuka
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-red-50 text-red-700">
                                <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                Bilik Suara Ditutup
                            </span>
                        @endif
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1 flex items-center gap-2 flex-wrap">
                    <span>Kelola Periode Pemilihan</span>
                    @if($activePeriod)
                        <span class="text-xs sm:text-sm font-semibold text-slate-500">({{ $activePeriod->name }})</span>
                    @endif
                </h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Sistem Multi-Periode, Bilik Suara Digital & Keamanan Integritas Pemilu</p>
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <button type="button" @click="openCreateModal()"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#940C10] text-white hover:bg-[#7A0A0D] text-xs font-bold shadow-sm shadow-red-900/30 transition-all cursor-pointer">
                <svg class="w-4 h-4 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Periode Baru</span>
            </button>
        </div>
    </div>

    <!-- Tabel Riwayat & Daftar Seluruh Periode Pemilihan -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Riwayat & Daftar Seluruh Periode Pemilihan</h3>
                <p class="text-xs text-slate-500">Isolasi data antarperiode: kandidat, DPT, suara, dan log pemilihan tersimpan mandiri</p>
            </div>
            @if($selectedPeriod)
            <div class="text-xs font-medium text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl flex items-center gap-1.5 shrink-0">
                <span class="text-slate-500">Konteks Tampilan:</span>
                <span class="font-bold text-slate-800">{{ $selectedPeriod->name }}</span>
            </div>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-400 uppercase tracking-wider text-[10px] font-bold">
                        <th class="py-3.5 px-5">ID</th>
                        <th class="py-3.5 px-5 min-w-[340px]">Nama Periode</th>
                        <th class="py-3.5 px-5 text-center min-w-[110px]">Status Periode</th>
                        <th class="py-3.5 px-5 text-center min-w-[110px]">Status Bilik</th>
                        <th class="py-3.5 px-5 text-center min-w-[100px]">Jumlah Paslon</th>
                        <th class="py-3.5 px-5 text-center min-w-[100px]">Suara Masuk</th>
                        <th class="py-3.5 px-5 text-center min-w-[260px] whitespace-nowrap">Aksi Pengelolaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($periods as $p)
                        @php
                            $isSelected = $selectedPeriod && $selectedPeriod->id === $p->id;
                            $isActive = $p->isSystemActive();
                            $ballotCount = $p->ballots_count ?? 0;
                            $hasBallots = $ballotCount > 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $isSelected ? 'bg-red-50/30' : '' }}">
                            <!-- ID -->
                            <td class="py-3.5 px-5 font-mono font-bold text-slate-400">#{{ $p->id }}</td>
                            
                            <!-- Nama Periode -->
                            <td class="py-3.5 px-5 min-w-[340px]">
                                <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                                    <span class="font-bold text-slate-900 text-xs sm:text-sm leading-snug">{{ $p->name }}</span>
                                    @if($isSelected)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-red-50 text-[#940C10] text-[10px] font-extrabold tracking-wide whitespace-nowrap shrink-0 shadow-2xs">
                                            <svg class="w-3 h-3 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span>Sedang Dilihat</span>
                                        </span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-500 mt-1 flex items-center gap-2 flex-wrap">
                                    <span>Tahun: <strong class="text-slate-700">{{ $p->academic_year }}</strong></span>
                                    @if($p->description)
                                        <span class="text-slate-300">&bull;</span>
                                        <span class="truncate max-w-sm">{{ $p->description }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Status Periode: Aktif vs Arsip -->
                            <td class="py-3.5 px-5 text-center">
                                @if($isActive)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Arsip
                                    </span>
                                @endif
                            </td>

                            <!-- Status Bilik: DIBUKA vs DITUTUP -->
                            <td class="py-3.5 px-5 text-center">
                                @if($p->isVotingOpen())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        DIBUKA
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-red-50 text-red-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        DITUTUP
                                    </span>
                                @endif
                            </td>

                            <!-- Jumlah Paslon -->
                            <td class="py-3.5 px-5 text-center font-bold text-slate-800">
                                {{ $p->candidate_pairs_count ?? 0 }} Paslon
                            </td>

                            <!-- Suara Masuk -->
                            <td class="py-3.5 px-5 text-center">
                                <span class="font-extrabold text-[#940C10] text-xs">
                                    {{ number_format($ballotCount) }}
                                </span>
                                <span class="text-[10px] text-slate-500 block">Suara Sah</span>
                            </td>

                            <!-- Aksi Pengelolaan (Sejajar Horizontal / Non-Wrapping) -->
                            <td class="py-3.5 px-5 text-center min-w-[260px] whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5 whitespace-nowrap justify-center flex-nowrap">
                                    <!-- 1. Tombol Pilih Konteks Tampilan -->
                                    <form action="{{ route('admin.periods.switch') }}" method="POST" class="inline shrink-0">
                                        @csrf
                                        <input type="hidden" name="period_id" value="{{ $p->id }}">
                                        <button type="submit" 
                                            title="Tampilkan data periode ini di seluruh dashboard & panel"
                                            class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold transition-all cursor-pointer whitespace-nowrap shrink-0 {{ $isSelected ? 'bg-slate-200 text-slate-700 cursor-default' : 'bg-slate-100 hover:bg-[#940C10] hover:text-white text-slate-700 border border-slate-300' }}"
                                            {{ $isSelected ? 'disabled' : '' }}>
                                            {{ $isSelected ? 'Sedang Dipilih' : 'Pilih Data' }}
                                        </button>
                                    </form>

                                    <!-- 2. Tombol Buka/Tutup Bilik (Hanya untuk periode aktif) -->
                                    @if($isActive)
                                        <button type="button" 
                                            @click="confirmToggleVoting({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ $p->voting_status }}')"
                                            title="{{ $p->isVotingOpen() ? 'Tutup Bilik Suara' : 'Buka Bilik Suara' }}"
                                            class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold transition-all cursor-pointer whitespace-nowrap shrink-0 border {{ $p->isVotingOpen() ? 'bg-amber-50 hover:bg-amber-100 text-amber-900 border-amber-300' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-900 border-emerald-300' }}">
                                            {{ $p->isVotingOpen() ? 'Kunci Bilik' : 'Buka Bilik' }}
                                        </button>
                                    @else
                                        <!-- Tombol Tetapkan sebagai Periode Aktif -->
                                        <button type="button" 
                                            @click="confirmSetActive({{ $p->id }}, '{{ addslashes($p->name) }}')"
                                            title="Jadikan sebagai periode pemilihan aktif sistem"
                                            class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-800 border border-emerald-300 transition-all cursor-pointer whitespace-nowrap shrink-0">
                                            Jadikan Aktif
                                        </button>
                                    @endif

                                    <!-- 3. Tombol Edit Periode -->
                                    <button type="button" 
                                        @click="openEditModal({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ addslashes($p->academic_year) }}', '{{ addslashes($p->description ?? '') }}')"
                                        title="Edit nama, tahun & keterangan periode"
                                        class="p-1.5 rounded-lg text-slate-600 hover:text-[#940C10] hover:bg-red-50 border border-slate-200 transition-all cursor-pointer whitespace-nowrap shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                    </button>

                                    <!-- 4. Tombol Hapus (Hanya draft bersih / tanpa suara) -->
                                    @if(!$isActive && !$hasBallots && ($p->voter_participations_count ?? 0) === 0)
                                        <button type="button" 
                                            @click="confirmDelete({{ $p->id }}, '{{ addslashes($p->name) }}')"
                                            title="Hapus periode pemilihan draft ini"
                                            class="p-1.5 rounded-lg text-red-600 hover:text-white hover:bg-red-600 border border-red-200 transition-all cursor-pointer whitespace-nowrap shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    @elseif($hasBallots)
                                        <span title="Dilindungi demi integritas pemilu (memiliki riwayat suara)" class="p-1.5 text-slate-300 cursor-not-allowed whitespace-nowrap shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                Belum ada riwayat periode pemilihan. Klik "Tambah Periode Baru" untuk memulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 1: TAMBAH PERIODE BARU                             -->
    <!-- ======================================================== -->
    <template x-teleport="body">
        <div x-show="modalCreateOpen" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div @click.away="modalCreateOpen = false"
                class="bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6 animate-scale-in">
                
                <!-- Header Modal Tanpa Kotak Border Sesuai Standar Minimalis -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-[#940C10] shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <line x1="12" y1="13" x2="12" y2="17"></line>
                            <line x1="10" y1="15" x2="14" y2="15"></line>
                        </svg>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#940C10]">Manajemen Pemilihan</span>
                            <h4 class="text-base sm:text-lg font-black text-slate-900 leading-tight">Tambah Periode Baru</h4>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Buat ruang lingkup periode pemilihan PEMIRA mandiri</p>
                        </div>
                    </div>
                    <button type="button" @click="modalCreateOpen = false" 
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('admin.periods.store') }}" method="POST" @submit="isSubmittingCreate = true" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Periode Pemilihan <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: PEMIRA BEM UBSI Kab.Karawang 2027/2028"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Periode / Akademik <span class="text-red-500">*</span></label>
                        <input type="text" name="academic_year" required placeholder="Contoh: 2027/2028"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi / Catatan (Opsional)</label>
                        <textarea name="description" rows="3" placeholder="Keterangan tambahan pelaksanaan PEMIRA..."
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all bg-slate-50/50 hover:bg-white focus:bg-white resize-none"></textarea>
                    </div>

                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/90 transition-all hover:bg-amber-50">
                        <label class="flex items-start gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="set_as_active" value="1" class="mt-0.5 rounded border-amber-400 text-[#940C10] focus:ring-[#940C10] w-4 h-4">
                            <div class="text-xs">
                                <span class="font-bold text-amber-950 block">Tetapkan langsung sebagai Periode Aktif</span>
                                <span class="text-amber-800 text-[11px] leading-relaxed block mt-0.5">
                                    Jika dicentang, periode ini akan menjadi periode aktif sistem dan periode aktif sebelumnya otomatis diarsipkan dengan bilik suara terkunci.
                                </span>
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="modalCreateOpen = false" :disabled="isSubmittingCreate"
                            class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors disabled:opacity-50">
                            Batal
                        </button>
                        <button type="submit" :disabled="isSubmittingCreate"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#940C10] hover:bg-[#7A0A0D] text-white text-xs font-bold shadow-md shadow-red-950/20 transition-all disabled:opacity-75 disabled:cursor-not-allowed min-w-[135px]">
                            <template x-if="isSubmittingCreate">
                                <svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <span x-text="isSubmittingCreate ? 'Menyimpan...' : 'Simpan Periode'">Simpan Periode</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- ======================================================== -->
    <!-- MODAL 2: EDIT PERIODE                                    -->
    <!-- ======================================================== -->
    <template x-teleport="body">
        <div x-show="modalEditOpen" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div @click.away="modalEditOpen = false"
                class="bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6 animate-scale-in">
                
                <!-- Header Modal Tanpa Kotak Border Sesuai Standar Minimalis -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-[#940C10] shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#940C10]">Manajemen Pemilihan</span>
                            <h4 class="text-base sm:text-lg font-black text-slate-900 leading-tight">Edit Informasi Periode</h4>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Perbarui nama, tahun akademik, atau deskripsi periode</p>
                        </div>
                    </div>
                    <button type="button" @click="modalEditOpen = false" 
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <form :action="'{{ url('admin/periods') }}/' + editForm.id" method="POST" @submit="isSubmittingEdit = true" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Periode Pemilihan <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="editForm.name" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Periode / Akademik <span class="text-red-500">*</span></label>
                        <input type="text" name="academic_year" x-model="editForm.academic_year" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi / Catatan (Opsional)</label>
                        <textarea name="description" x-model="editForm.description" rows="3"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all bg-slate-50/50 hover:bg-white focus:bg-white resize-none"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="modalEditOpen = false" :disabled="isSubmittingEdit"
                            class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors disabled:opacity-50">
                            Batal
                        </button>
                        <button type="submit" :disabled="isSubmittingEdit"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#940C10] hover:bg-[#7A0A0D] text-white text-xs font-bold shadow-md shadow-red-950/20 transition-all disabled:opacity-75 disabled:cursor-not-allowed min-w-[145px]">
                            <template x-if="isSubmittingEdit">
                                <svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <span x-text="isSubmittingEdit ? 'Menyimpan...' : 'Perbarui Periode'">Perbarui Periode</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- ======================================================== -->
    <!-- MODAL 3: KONFIRMASI BUKA / TUTUP BILIK SUARA             -->
    <!-- ======================================================== -->
    <template x-teleport="body">
        <div x-show="modalToggleVotingOpen" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div @click.away="modalToggleVotingOpen = false"
                class="bg-white rounded-3xl border border-slate-200 max-w-md w-full p-6 sm:p-7 shadow-2xl space-y-5 animate-scale-in">
                
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold"
                    :class="toggleVotingTarget.currentStatus === 'opened' ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600'">
                    <template x-if="toggleVotingTarget.currentStatus === 'opened'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </template>
                    <template x-if="toggleVotingTarget.currentStatus !== 'opened'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </template>
                </div>

                <div>
                    <h4 class="text-base font-extrabold text-slate-900" 
                        x-text="toggleVotingTarget.currentStatus === 'opened' ? 'Kunci & Tutup Bilik Suara?' : 'Buka Bilik Suara Mahasiswa?'">
                    </h4>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        <span x-show="toggleVotingTarget.currentStatus === 'opened'">
                            Bilik suara untuk <strong><span x-text="toggleVotingTarget.name"></span></strong> akan <strong>DITUTUP</strong>. Mahasiswa tidak dapat lagi mengakses form pemilihan suara.
                        </span>
                        <span x-show="toggleVotingTarget.currentStatus !== 'opened'">
                            Bilik suara untuk <strong><span x-text="toggleVotingTarget.name"></span></strong> akan <strong>DIBUKA</strong>. Mahasiswa DPT yang terverifikasi di TPS dapat langsung melakukan pencoblosan.
                        </span>
                    </p>
                </div>

                <form :action="'{{ url('admin/periods') }}/' + toggleVotingTarget.id + '/toggle-voting'" method="POST" class="flex items-center justify-end gap-3 pt-3">
                    @csrf
                    <button type="button" @click="modalToggleVotingOpen = false"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-white text-xs font-bold shadow-md transition-all"
                        :class="toggleVotingTarget.currentStatus === 'opened' ? 'bg-red-600 hover:bg-red-700 shadow-red-950/20' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-950/20'"
                        x-text="toggleVotingTarget.currentStatus === 'opened' ? 'Ya, Tutup Bilik Suara' : 'Ya, Buka Bilik Suara'">
                    </button>
                </form>
            </div>
        </div>
    </template>

    <!-- ======================================================== -->
    <!-- MODAL 4: KONFIRMASI TETAPKAN PERIODE AKTIF              -->
    <!-- ======================================================== -->
    <template x-teleport="body">
        <div x-show="modalSetActiveOpen" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div @click.away="modalSetActiveOpen = false"
                class="bg-white rounded-3xl border border-slate-200 max-w-md w-full p-6 sm:p-7 shadow-2xl space-y-5 animate-scale-in">
                
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                </div>

                <div>
                    <h4 class="text-base font-extrabold text-slate-900">Tetapkan sebagai Periode Aktif?</h4>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        Anda akan menetapkan <strong><span x-text="setActiveTarget.name"></span></strong> sebagai <strong>Periode Aktif Sistem</strong>.
                        Periode aktif lainnya akan otomatis diarsipkan dan bilik suaranya dikunci demi menjaga integritas data.
                    </p>
                </div>

                <form :action="'{{ url('admin/periods') }}/' + setActiveTarget.id + '/set-active'" method="POST" class="flex items-center justify-end gap-3 pt-3">
                    @csrf
                    <button type="button" @click="modalSetActiveOpen = false"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-950/20 transition-all">
                        Ya, Tetapkan Aktif
                    </button>
                </form>
            </div>
        </div>
    </template>

    <!-- ======================================================== -->
    <!-- MODAL 5: KONFIRMASI HAPUS PERIODE DRAFT                  -->
    <!-- ======================================================== -->
    <template x-teleport="body">
        <div x-show="modalDeleteOpen" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div @click.away="modalDeleteOpen = false"
                class="bg-white rounded-3xl border border-slate-200 max-w-md w-full p-6 sm:p-7 shadow-2xl space-y-5 animate-scale-in">
                
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </div>

                <div>
                    <h4 class="text-base font-extrabold text-slate-900">Hapus Periode Pemilihan?</h4>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        Apakah Anda yakin ingin menghapus <strong><span x-text="deleteTarget.name"></span></strong>?
                        Tindakan ini tidak dapat dibatalkan. Hanya periode yang belum memiliki suara sah yang diizinkan untuk dihapus.
                    </p>
                </div>

                <form :action="'{{ url('admin/periods') }}/' + deleteTarget.id" method="POST" class="flex items-center justify-end gap-3 pt-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="modalDeleteOpen = false"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-md shadow-red-950/20 transition-all">
                        Ya, Hapus Periode
                    </button>
                </form>
            </div>
        </div>
    </template>

</div>

<script>
    window.periodManagement = function periodManagement() {
        return {
            modalCreateOpen: false,
            modalEditOpen: false,
            modalToggleVotingOpen: false,
            modalSetActiveOpen: false,
            modalDeleteOpen: false,
            isSubmittingCreate: false,
            isSubmittingEdit: false,

            editForm: {
                id: null,
                name: '',
                academic_year: '',
                description: ''
            },

            toggleVotingTarget: {
                id: null,
                name: '',
                currentStatus: ''
            },

            setActiveTarget: {
                id: null,
                name: ''
            },

            deleteTarget: {
                id: null,
                name: ''
            },

            openCreateModal() {
                this.isSubmittingCreate = false;
                this.modalCreateOpen = true;
            },

            openEditModal(id, name, year, description) {
                this.editForm.id = id;
                this.editForm.name = name;
                this.editForm.academic_year = year;
                this.editForm.description = description;
                this.isSubmittingEdit = false;
                this.modalEditOpen = true;
            },

            confirmToggleVoting(id, name, currentStatus) {
                this.toggleVotingTarget.id = id;
                this.toggleVotingTarget.name = name;
                this.toggleVotingTarget.currentStatus = currentStatus;
                this.modalToggleVotingOpen = true;
            },

            confirmSetActive(id, name) {
                this.setActiveTarget.id = id;
                this.setActiveTarget.name = name;
                this.modalSetActiveOpen = true;
            },

            confirmDelete(id, name) {
                this.deleteTarget.id = id;
                this.deleteTarget.name = name;
                this.modalDeleteOpen = true;
            }
        };
    }
</script>
@endsection
