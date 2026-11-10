@extends('layouts.app', ['title' => 'Daftarkan Paslon Baru — Admin PEMIRA UBSI'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-md">
        <!-- Header -->
        <div class="p-6 sm:p-8 bg-gradient-to-r from-[#940c10] to-[#7A0A0D] text-white flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-xs flex items-center justify-center font-bold text-[#CE9501] ring-2 ring-[#CE9501]/40">
                    <i data-lucide="user-plus" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-[#CE9501]">Registrasi Kontestan</span>
                    <h1 class="text-xl sm:text-2xl font-black">Tambah Pasangan Calon BEM</h1>
                    <p class="text-xs text-red-100">Periode Pemilihan: {{ $period->name }}</p>
                </div>
            </div>

            <a href="{{ route('admin.candidates.index') }}"
                class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-bold transition-colors">
                Kembali
            </a>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-10 space-y-8">
            @csrf
            <input type="hidden" name="election_period_id" value="{{ $period->id }}">

            <!-- Section 1: Nomor Urut -->
            <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Nomor Urut Undian</h3>
                <div class="max-w-xs">
                    <label for="candidate_number" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Nomor Urut Paslon <span class="text-red-500">*</span>
                    </label>
                    <input type="number" min="1" max="99" name="candidate_number" id="candidate_number"
                        value="{{ old('candidate_number') }}" required
                        placeholder="Contoh: 1 atau 2"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-[#940c10] shadow-2xs">
                    @error('candidate_number')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section 2: Data Ketua & Wakil -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Calon Ketua BEM -->
                <div class="p-5 rounded-2xl bg-red-50/40 border border-red-100 space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#940c10]">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        <span>Calon Ketua BEM</span>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label for="chairman_name" class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap Ketua <span class="text-red-500">*</span></label>
                            <input type="text" name="chairman_name" id="chairman_name" value="{{ old('chairman_name') }}" required
                                placeholder="Contoh: Fajar Nugraha"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs text-gray-900 focus:ring-2 focus:ring-[#940c10]">
                            @error('chairman_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="chairman_nim" class="block text-xs font-bold text-gray-700 mb-1">NIM Ketua <span class="text-red-500">*</span></label>
                            <input type="text" name="chairman_nim" id="chairman_nim" value="{{ old('chairman_nim') }}" required
                                placeholder="Contoh: 12220101"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-mono text-gray-900 focus:ring-2 focus:ring-[#940c10]">
                            @error('chairman_nim') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="chairman_study_program" class="block text-xs font-bold text-gray-700 mb-1">Program Studi <span class="text-red-500">*</span></label>
                            <input type="text" name="chairman_study_program" id="chairman_study_program" value="{{ old('chairman_study_program') }}" required
                                placeholder="Contoh: Sistem Informasi"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs text-gray-900 focus:ring-2 focus:ring-[#940c10]">
                            @error('chairman_study_program') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Calon Wakil Ketua BEM -->
                <div class="p-5 rounded-2xl bg-amber-50/40 border border-amber-100 space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#CE9501]">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                        <span>Calon Wakil Ketua BEM</span>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label for="vice_chairman_name" class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap Wakil <span class="text-red-500">*</span></label>
                            <input type="text" name="vice_chairman_name" id="vice_chairman_name" value="{{ old('vice_chairman_name') }}" required
                                placeholder="Contoh: Siti Aisyah Rahmawati"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs text-gray-900 focus:ring-2 focus:ring-[#940c10]">
                            @error('vice_chairman_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="vice_chairman_nim" class="block text-xs font-bold text-gray-700 mb-1">NIM Wakil <span class="text-red-500">*</span></label>
                            <input type="text" name="vice_chairman_nim" id="vice_chairman_nim" value="{{ old('vice_chairman_nim') }}" required
                                placeholder="Contoh: 12220145"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-mono text-gray-900 focus:ring-2 focus:ring-[#940c10]">
                            @error('vice_chairman_nim') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="vice_chairman_study_program" class="block text-xs font-bold text-gray-700 mb-1">Program Studi <span class="text-red-500">*</span></label>
                            <input type="text" name="vice_chairman_study_program" id="vice_chairman_study_program" value="{{ old('vice_chairman_study_program') }}" required
                                placeholder="Contoh: Teknologi Informasi"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs text-gray-900 focus:ring-2 focus:ring-[#940c10]">
                            @error('vice_chairman_study_program') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Visi & Misi Paslon -->
            <div class="space-y-6">
                <!-- Visi -->
                <div>
                    <label for="vision" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Visi Pasangan Calon <span class="text-red-500">*</span>
                    </label>
                    <textarea name="vision" id="vision" rows="3" required
                        placeholder="Tuliskan gagasan dan visi besar kepemimpinan paslon..."
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 text-xs sm:text-sm text-gray-900 focus:ring-2 focus:ring-[#940c10] shadow-2xs leading-relaxed">{{ old('vision') }}</textarea>
                    @error('vision') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Misi (Textarea: 1 baris per butir) -->
                <div>
                    <label for="mission_raw" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Misi Paslon <span class="text-red-500">*</span>
                    </label>
                    <textarea name="mission_raw" id="mission_raw" rows="4" required
                        placeholder="Tuliskan butir-butir misi. Tekan ENTER untuk setiap butir baru:&#10;Membangun ruang aspirasi mahasiswa yang inklusif dan terbuka.&#10;Mengoptimalkan sinergi antar organisasi dan himpunan program studi.&#10;Mendorong literasi digital dalam kegiatan kampus."
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 text-xs sm:text-sm text-gray-900 focus:ring-2 focus:ring-[#940c10] shadow-2xs leading-relaxed font-mono">{{ old('mission_raw') }}</textarea>
                    <p class="text-[11px] text-gray-500 mt-1">Petunjuk: Tuliskan satu butir misi per baris (tekan Enter untuk butir berikutnya).</p>
                    @error('mission_raw') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Program Kerja Unggulan -->
                <div>
                    <label for="work_programs_raw" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Program Kerja Unggulan (Opsional)
                    </label>
                    <textarea name="work_programs_raw" id="work_programs_raw" rows="3"
                        placeholder="Contoh program kerja unggulan (satu per baris):&#10;UBSI Youth & Tech Festival&#10;Portal Terpadu Aspirasi Digital&#10;BEM Menyapa Civitas"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 text-xs sm:text-sm text-gray-900 focus:ring-2 focus:ring-[#940c10] shadow-2xs leading-relaxed font-mono">{{ old('work_programs_raw') }}</textarea>
                    <p class="text-[11px] text-gray-500 mt-1">Petunjuk: Tuliskan satu nama program kerja per baris.</p>
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.candidates.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#940c10] text-white text-xs font-extrabold hover:bg-[#7A0A0D] shadow-sm transition-all">
                    <i data-lucide="check" class="w-4 h-4 text-[#CE9501]"></i>
                    <span>Simpan & Daftarkan Paslon</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

