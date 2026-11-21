@forelse($participations as $part)
    @php
        $isPresent = (bool) !empty($part->verified_at);
        $verifierText = 'Belum Mengisi Buku Tamu';
        if ($isPresent) {
            if ($part->verifiedBy) {
                $verifierText = 'Buku Tamu TPS (' . $part->verifiedBy->name . ')';
            } elseif ($part->verified_at) {
                $verifierText = 'Buku Tamu Internal';
            }
        }
        $verifiedAtFormatted = $part->verified_at 
            ? $part->verified_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' 
            : '-';

        $votedAtFormatted = $part->voted_at 
            ? $part->voted_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' 
            : '-';

        $voterData = [
            'nim' => (string) $part->student_nim,
            'name' => (string) ($part->student->name ?? 'Mahasiswa'),
            'study_program' => (string) ($part->student->study_program ?? '-'),
            'class_name' => (string) ($part->student->class_name ?? '-'),
            'semester' => (string) ($part->student->semester ?? '-'),
            'asal_kampus' => (string) ($part->student->asal_kampus ?? $part->tps_location ?? '-'),
            'email_kampus' => (string) ($part->student->email_kampus ?? '-'),
            'phone' => (string) ($part->phone ?? '-'),
            'has_voted' => (bool) $part->has_voted,
            'voted_at' => (string) $votedAtFormatted,
            'is_present' => (bool) $isPresent,
            'verified_at' => (string) $verifiedAtFormatted,
            'verifier' => (string) $verifierText,
            'signature_url' => (string) ($part->signature_url ?? ''),
            'tps_location' => (string) ($part->student->asal_kampus ?? $part->tps_location ?? 'UBSI Karawang'),
        ];
    @endphp
    <tr class="hover:bg-slate-50/80 transition-colors">
        <td class="py-3.5 px-5">
            <div class="flex flex-col">
                <span class="font-bold text-slate-900 text-xs">{{ $part->student->name ?? 'Mahasiswa' }}</span>
                <span class="font-mono text-[11px] text-[#940C10] font-bold mt-0.5">{{ $part->student_nim }}</span>
                <span class="text-[11px] text-slate-500 font-medium">{{ $part->student->study_program ?? '-' }}</span>
            </div>
        </td>
        <td class="py-3.5 px-5">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold transition-colors {{ $part->has_voted ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $part->has_voted ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                {{ $part->has_voted ? 'Sudah Memilih' : 'Belum Memilih' }}
            </span>
        </td>
        <td class="py-3.5 px-5 text-slate-500 font-mono">
            {{ $part->voted_at ? $part->voted_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') : '-' }}
        </td>
        <td class="py-3.5 px-5">
            @if($isPresent)
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800" title="Tercatat hadir di Buku Tamu Digital">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        Hadir
                    </span>
                    @if($part->signature_url)
                        <img src="{{ $part->signature_url }}" alt="TTD" class="h-5 w-10 object-contain bg-white rounded border border-slate-200 p-0.5" title="Tanda tangan terverifikasi">
                    @endif
                </div>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600" title="Belum mengisi Buku Tamu Digital">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    Belum Hadir
                </span>
            @endif
        </td>
        <td class="py-3.5 px-5 text-center">
            <button type="button" 
                data-voter-btn="true"
                data-voter="{{ json_encode($voterData) }}"
                class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 hover:text-[#940C10] hover:bg-red-50 hover:border-red-200 transition-all shadow-2xs cursor-pointer"
                title="Lihat Detail Pemilih">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-12 text-slate-400">
            Tidak ada data pemilih yang sesuai dengan filter pencarian.
        </td>
    </tr>
@endforelse
