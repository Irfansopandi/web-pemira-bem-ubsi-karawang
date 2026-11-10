@extends('layouts.app', ['title' => 'Verifikasi TPS Onsite — PEMIRA BEM UBSI'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Top TPS Header -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#940c10] text-[#CE9501] flex items-center justify-center font-bold shadow-xs">
                <i data-lucide="check-square" class="w-7 h-7"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#CE9501]">Verifikator TPS Onsite</span>
                <h1 class="text-2xl font-black text-gray-900">Pusat Verifikasi Pemilih</h1>
                <p class="text-xs text-gray-500">Petugas: {{ $user->name }} &bull; Lokasi: {{ $user->tps_location ?? 'Gedung Utama UBSI' }}</p>
            </div>
        </div>

        <!-- Quick Search Form -->
        <form action="{{ route('committee.dashboard') }}" method="GET" class="w-full md:w-auto flex items-center gap-2">
            <div class="relative w-full md:w-72">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari NIM atau Nama..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#940c10] shadow-2xs">
            </div>
            <button type="submit" class="px-4 py-2.5 rounded-xl bg-gray-900 text-white text-xs font-bold hover:bg-black transition-colors">
                Cari
            </button>
            @if($search)
                <a href="{{ route('committee.dashboard') }}" class="p-2.5 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 text-xs">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Voter Verification Table -->
    <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-xs space-y-4 p-6 sm:p-8">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-black text-gray-900">Daftar Pemilih Terdaftar TPS</h2>
                <p class="text-xs text-gray-500">Verifikasi fisik kehadiran mahasiswa sebelum masuk bilik suara</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-red-50 text-[#940c10] border border-red-200 text-xs font-bold">
                Periode {{ $activePeriod->academic_year ?? 'Aktif' }}
            </span>
        </div>

        @if($participations && $participations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-400 uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4">NIM</th>
                            <th class="py-3 px-4">Nama Mahasiswa</th>
                            <th class="py-3 px-4">Program Studi</th>
                            <th class="py-3 px-4">Status Hak Suara</th>
                            <th class="py-3 px-4">Verifikasi Fisik</th>
                            <th class="py-3 px-4 text-right">Aksi Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($participations as $item)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-gray-900">{{ $item->student->nim }}</td>
                                <td class="py-3.5 px-4 font-medium text-gray-900">{{ $item->student->name }}</td>
                                <td class="py-3.5 px-4">{{ $item->student->study_program }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ $item->has_voted ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $item->has_voted ? 'Sudah Memilih' : 'Belum Memilih' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($item->verified_at)
                                        <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold text-[11px]">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                            Terverifikasi ({{ $item->verified_at->format('H:i') }})
                                        </span>
                                    @else
                                        <span class="text-gray-400">Belum diverifikasi</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    @if(! $item->verified_at)
                                        <form action="{{ route('committee.tps.verify', $item) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-3.5 py-1.5 rounded-lg bg-[#940c10] text-white font-bold hover:bg-[#7A0A0D] transition-colors shadow-2xs">
                                                Verifikasi Onsite
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-gray-400 italic">Selesai Check-in</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-gray-100">
                {{ $participations->links() }}
            </div>
        @else
            <div class="text-center py-12 text-gray-400 text-xs">
                Tidak ada data pemilih yang sesuai dengan pencarian.
            </div>
        @endif
    </div>

</div>
@endsection

