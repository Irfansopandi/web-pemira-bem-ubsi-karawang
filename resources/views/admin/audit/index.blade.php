@extends('layouts.admin', ['title' => 'Audit Trail & Log Aktivitas'])

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Header Halaman -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-[#940C10] to-[#7A0A0D] text-white flex items-center justify-center font-bold shadow-md shadow-red-950/20 shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    <path d="m9 12 2 2 4-4"></path>
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">Keamanan & Aktivitas</span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">Audit Trail & Log Aktivitas Sistem</h1>
                <p class="text-xs text-slate-500 font-medium">Rekaman Jejak Digital & Integritas Pengelolaan Sistem PEMIRA BEM UBSI Karawang</p>
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 transition-all shadow-2xs">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"></path></svg>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Filter & Table Card -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 shadow-xs overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Jejak Aktivitas Audit Trail</h3>
                <p class="text-xs text-slate-500">Mencatat seluruh aksi administratif, perubahan status, dan interaksi sensitif</p>
            </div>

            <!-- Form Pencarian & Filter -->
            <form action="{{ route('admin.audit.index') }}" method="GET" class="flex items-center gap-2 flex-wrap">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aksi, admin, IP..."
                        class="pl-9 pr-3.5 py-2 rounded-xl border border-slate-300 text-xs font-medium focus:ring-2 focus:ring-[#940c10] focus:border-[#940c10] bg-white w-56">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>

                <select name="action" onchange="this.form.submit()"
                    class="px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 cursor-pointer">
                    <option value="">Semua Tipe Aksi</option>
                    @foreach($actionTypes as $type)
                        <option value="{{ $type }}" {{ request('action') === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-all cursor-pointer">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'action']))
                    <a href="{{ route('admin.audit.index') }}" class="p-2 text-slate-400 hover:text-slate-600 text-xs font-bold" title="Reset Filter">
                        &times;
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel Log Audit -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-400 uppercase tracking-wider text-[10px] font-bold">
                        <th class="py-3.5 px-5">Waktu & Tanggal</th>
                        <th class="py-3.5 px-5">Administrator / Aktor</th>
                        <th class="py-3.5 px-5">Tipe Aksi</th>
                        <th class="py-3.5 px-5">Entitas / Modul</th>
                        <th class="py-3.5 px-5">IP Address</th>
                        <th class="py-3.5 px-5">Ringkasan Payload</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-5 font-mono text-slate-500">
                                <span class="font-bold text-slate-800 block">{{ $log->created_at ? $log->created_at->translatedFormat('d M Y, H:i:s') : '-' }}</span>
                                <span class="text-[10px] text-slate-400">{{ $log->created_at ? $log->created_at->diffForHumans() : '' }}</span>
                            </td>
                            <td class="py-3.5 px-5 font-bold text-slate-900">
                                {{ $log->admin->name ?? 'System Automated' }}
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-slate-600 font-mono text-[11px]">
                                {{ class_basename($log->entity_type) }} #{{ $log->entity_id }}
                            </td>
                            <td class="py-3.5 px-5 font-mono text-slate-500 text-[11px]">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                            <td class="py-3.5 px-5 font-mono text-[11px] text-slate-500 max-w-xs truncate">
                                @if($log->new_values)
                                    {{ json_encode($log->new_values) }}
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                Belum ada aktivitas audit yang tercatat pada sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginasi -->
        @if($logs->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $logs->links() }}
        </div>
        @endif

    </div>

</div>
@endsection
