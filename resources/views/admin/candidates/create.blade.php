@extends('layouts.admin', ['title' => 'Tambah Paslon BEM'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-6" x-data="{
    isSubmitting: false,
    candidateNumber: '{{ old('candidate_number', $suggestedNumber ?? '') }}',
    existingNumbers: {{ json_encode($existingNumbers ?? []) }},
    isNumberDuplicate() {
        const num = parseInt(this.candidateNumber);
        return !isNaN(num) && this.existingNumbers.includes(num);
    },
    photoPreview: null,
    handlePhoto(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (ev) => {
                this.photoPreview = ev.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}">

    <!-- Tombol Kembali di Luar Card Sesuai Arahan -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.candidates.index') }}"
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 transition-colors shadow-2xs group/back">
            <svg class="w-4 h-4 text-slate-500 transform group-hover/back:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="m15 18-6-6 6-6"></path>
            </svg>
            <span>Kembali ke Kelola Paslon</span>
        </a>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl xl:rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs">
        
        <!-- Header Bersih Minimalis (Tanpa Border / AI Slop) -->
        <div class="p-6 sm:p-8 border-b border-slate-100 flex items-start sm:items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3.5 sm:gap-4">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-[#940C10] shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <line x1="19" y1="8" x2="19" y2="14"></line>
                    <line x1="22" y1="11" x2="16" y2="11"></line>
                </svg>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">Manajemen Pemilihan</span>
                        <span class="text-slate-300">•</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-300">
                            Periode: {{ $period->name }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">
                        Tambah Pasangan Calon BEM
                    </h1>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                        Daftarkan kandidat Calon Ketua & Wakil Ketua BEM, foto resmi, visi, misi, dan program kerja unggulan
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data" @submit="isSubmitting = true" class="p-6 sm:p-8 space-y-8">
            @csrf
            <input type="hidden" name="election_period_id" value="{{ $period->id }}">

            <!-- Section 1: Nomor Urut & Foto Paslon -->
            <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-5">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                    
                    <!-- Nomor Urut dengan Pencegahan Nomor Ganda -->
                    <div>
                        <label for="candidate_number" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Nomor Urut Paslon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" inputmode="numeric" pattern="[0-9]*" name="candidate_number" id="candidate_number"
                            x-model="candidateNumber"
                            @input="candidateNumber = $event.target.value.replace(/[^0-9]/g, '')"
                            required
                            placeholder="Contoh: 1, 2, atau 3"
                            class="w-full px-4 py-2.5 rounded-xl border text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 transition-all"
                            :class="isNumberDuplicate() ? 'border-red-400 bg-red-50/40 focus:ring-red-200 focus:border-red-500' : 'border-slate-300 bg-white focus:ring-[#940C10]/20 focus:border-[#940C10]'">
                        
                        <p class="text-[11px] text-slate-500 mt-1.5">Nomor urut resmi yang akan dicantumkan pada kartu surat suara bilik e-voting.</p>

                        <!-- Indikator Nomor yang Sudah Digunakan -->
                        @if(!empty($existingNumbers) && count($existingNumbers) > 0)
                            <div class="flex items-center gap-1.5 flex-wrap mt-2 text-[11px] text-slate-500">
                                <span>Nomor terdaftar saat ini:</span>
                                @foreach($existingNumbers as $num)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-slate-200 text-slate-800 font-bold font-mono text-[10px]">0{{ $num }}</span>
                                @endforeach
                            </div>
                        @endif

                        <!-- Validasi Nomor Ganda Realtime -->
                        <template x-if="isNumberDuplicate()">
                            <p class="text-xs text-red-600 mt-2 flex items-center gap-1.5 font-bold">
                                <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <span>Nomor urut <span x-text="'0' + candidateNumber"></span> sudah terdaftar! Mohon pilih nomor urut lain agar tidak ganda.</span>
                            </p>
                        </template>

                        @error('candidate_number')
                            <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1 font-semibold">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Upload Foto Paslon Lengkap dengan Informasi Ukuran -->
                    <div>
                        <label for="photo" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Foto Resmi Pasangan Calon (Portrait)
                        </label>
                        
                        <!-- File Input -->
                        <div class="flex items-start gap-4">
                            <!-- Preview Box -->
                            <div class="w-20 h-26 rounded-xl overflow-hidden border-2 border-dashed border-slate-300 bg-white flex items-center justify-center shrink-0 relative shadow-2xs">
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" alt="Preview Foto Paslon" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!photoPreview">
                                    <div class="text-center p-2 text-slate-400">
                                        <svg class="w-6 h-6 mx-auto text-slate-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                        <span class="text-[9px] font-bold block mt-1">Pratinjau</span>
                                    </div>
                                </template>
                            </div>

                            <div class="flex-1 space-y-2">
                                <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg,image/webp"
                                    @change="handlePhoto"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-700 bg-white file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#940C10] file:text-white hover:file:bg-[#7A0A0D] file:cursor-pointer transition-all">
                                
                                <!-- Spesifikasi Ukuran Upload Sesuai Instruksi -->
                                <div class="p-2.5 rounded-xl bg-amber-50/70 border border-amber-200/80 text-[11px] text-amber-900 space-y-1">
                                    <p class="font-bold flex items-center gap-1 text-[#940C10]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                        <span>Ketentuan Berkas Foto:</span>
                                    </p>
                                    <ul class="list-disc list-inside space-y-0.5 text-slate-600 pl-1">
                                        <li><strong>Format:</strong> JPG, JPEG, PNG, atau WEBP</li>
                                        <li><strong>Ukuran Berkas:</strong> Maksimal <strong>2 MB</strong> (2.048 KB)</li>
                                        <li><strong>Rasio Disarankan:</strong> Portrait <strong>3:4</strong> atau <strong>4:5</strong> (contoh: 600×800 px / 900×1200 px)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        @error('photo')
                            <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1 font-semibold">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Section 2: Data Ketua & Calon Wakil Ketua BEM (Validasi Khusus Angka pada NIM) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Calon Ketua BEM -->
                <div class="p-5 sm:p-6 rounded-2xl bg-white border border-slate-200/90 shadow-2xs space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <svg class="w-5 h-5 text-[#940C10]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#940C10]">Calon Ketua BEM</span>
                    </div>

                    <div class="space-y-3.5">
                        <div>
                            <label for="chairman_name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Ketua <span class="text-red-500">*</span></label>
                            <input type="text" name="chairman_name" id="chairman_name" value="{{ old('chairman_name') }}" required
                                placeholder="Contoh: Fajar Nugraha"
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('chairman_name') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all">
                            @error('chairman_name') <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="chairman_nim" class="block text-xs font-bold text-slate-700 mb-1">NIM Ketua <span class="text-red-500">*</span></label>
                            <!-- Hanya Angka Saja yang Bisa Diinput -->
                            <input type="text" inputmode="numeric" pattern="[0-9]*" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                name="chairman_nim" id="chairman_nim" value="{{ old('chairman_nim') }}" required
                                placeholder="Contoh: 12220101"
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('chairman_nim') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-xs font-mono text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all">
                            <p class="text-[10px] text-slate-400 mt-1">Hanya berupa angka (huruf & simbol otomatis diblokir).</p>
                            @error('chairman_nim') <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="chairman_study_program" class="block text-xs font-bold text-slate-700 mb-1">Program Studi <span class="text-red-500">*</span></label>
                            <input type="text" name="chairman_study_program" id="chairman_study_program" value="{{ old('chairman_study_program') }}" required
                                placeholder="Contoh: Sistem Informasi"
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('chairman_study_program') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all">
                            @error('chairman_study_program') <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Calon Wakil Ketua BEM -->
                <div class="p-5 sm:p-6 rounded-2xl bg-white border border-slate-200/90 shadow-2xs space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <svg class="w-5 h-5 text-[#CE9501]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <polyline points="16 11 18 13 22 9"></polyline>
                        </svg>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#CE9501]">Calon Wakil Ketua BEM</span>
                    </div>

                    <div class="space-y-3.5">
                        <div>
                            <label for="vice_chairman_name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Wakil <span class="text-red-500">*</span></label>
                            <input type="text" name="vice_chairman_name" id="vice_chairman_name" value="{{ old('vice_chairman_name') }}" required
                                placeholder="Contoh: Siti Aisyah Rahmawati"
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('vice_chairman_name') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all">
                            @error('vice_chairman_name') <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="vice_chairman_nim" class="block text-xs font-bold text-slate-700 mb-1">NIM Wakil <span class="text-red-500">*</span></label>
                            <!-- Hanya Angka Saja yang Bisa Diinput -->
                            <input type="text" inputmode="numeric" pattern="[0-9]*" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                name="vice_chairman_nim" id="vice_chairman_nim" value="{{ old('vice_chairman_nim') }}" required
                                placeholder="Contoh: 12220145"
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('vice_chairman_nim') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-xs font-mono text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all">
                            <p class="text-[10px] text-slate-400 mt-1">Hanya berupa angka (huruf & simbol otomatis diblokir).</p>
                            @error('vice_chairman_nim') <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="vice_chairman_study_program" class="block text-xs font-bold text-slate-700 mb-1">Program Studi <span class="text-red-500">*</span></label>
                            <input type="text" name="vice_chairman_study_program" id="vice_chairman_study_program" value="{{ old('vice_chairman_study_program') }}" required
                                placeholder="Contoh: Teknologi Informasi"
                                class="w-full px-3.5 py-2.5 rounded-xl border @error('vice_chairman_study_program') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all">
                            @error('vice_chairman_study_program') <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Visi, Misi & Program Kerja Unggulan -->
            <div class="space-y-6">
                <!-- Visi -->
                <div>
                    <label for="vision" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                        Visi Pasangan Calon <span class="text-red-500">*</span>
                    </label>
                    <textarea name="vision" id="vision" rows="3" required
                        placeholder="Tuliskan gagasan dan visi besar kepemimpinan paslon..."
                        class="w-full px-4 py-3 rounded-xl border @error('vision') border-red-400 bg-red-50/20 @else border-slate-300 bg-white @enderror text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all leading-relaxed">{{ old('vision') }}</textarea>
                    @error('vision') <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Misi (Textarea: 1 baris per butir) -->
                <div>
                    <label for="mission_raw" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                        Misi Paslon (Minimal 1 Butir) <span class="text-red-500">*</span>
                    </label>
                    <textarea name="mission_raw" id="mission_raw" rows="5" required
                        placeholder="Tuliskan butir-butir misi. Tekan ENTER untuk setiap butir baru:&#10;Membangun ruang aspirasi mahasiswa yang inklusif dan solutif.&#10;Mengoptimalkan sinergi antar organisasi mahasiswa dan himpunan prodi.&#10;Mendorong literasi teknologi dan daya saing civitas akademika."
                        class="w-full px-4 py-3 rounded-xl border @error('mission_raw') border-red-400 bg-red-50/20 @else border-slate-300 bg-white @enderror text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all leading-relaxed font-mono">{{ old('mission_raw') }}</textarea>
                    <p class="text-[11px] text-slate-500 mt-1">Petunjuk: Tuliskan satu butir misi per baris (tekan Enter untuk butir berikutnya).</p>
                    @error('mission_raw') <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Program Kerja Unggulan -->
                <div>
                    <label for="work_programs_raw" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                        Program Kerja Unggulan (Opsional)
                    </label>
                    <textarea name="work_programs_raw" id="work_programs_raw" rows="4"
                        placeholder="Contoh program kerja unggulan (satu per baris):&#10;UBSI Youth & Tech Festival&#10;Portal Aspirasi Mahasiswa 24 Jam&#10;Pekan Olahraga & Seni Mahasiswa"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#940C10]/20 focus:border-[#940C10] transition-all leading-relaxed font-mono">{{ old('work_programs_raw') }}</textarea>
                    <p class="text-[11px] text-slate-500 mt-1">Petunjuk: Tuliskan satu program kerja unggulan per baris.</p>
                </div>
            </div>

            <!-- Submit Actions dengan Loading Spinner -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.candidates.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    :disabled="isSubmitting || isNumberDuplicate()"
                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-[#940C10] hover:bg-[#7A0A0D] text-white text-xs font-bold shadow-md shadow-red-950/20 transition-all disabled:opacity-60 disabled:cursor-not-allowed min-w-[185px]">
                    <!-- Spinner Saat Menyimpan -->
                    <template x-if="isSubmitting">
                        <svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan & Daftarkan Paslon'">Simpan & Daftarkan Paslon</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
