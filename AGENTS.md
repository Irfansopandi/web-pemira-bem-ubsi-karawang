# PEMIRA BEM UBSI KARAWANG — AGENT DEVELOPMENT GUIDELINES

## 1. ARSITEKTUR UTAMA: FULL LARAVEL 13 MONOLITHIC

Aplikasi PEMIRA BEM UBSI Karawang dikembangkan murni menggunakan arsitektur **Full Laravel 13 Monolith**:
- **Backend & Routing**: Laravel 13 (PHP 8.3+)
- **Frontend / Templating**: Laravel Blade Components & Layouts
- **Styling**: Tailwind CSS v4 (Vite plugin `@tailwindcss/vite`)
- **Interaktivitas UI**: Alpine.js & Vanilla JavaScript
- **Ikon**: Lucide Icons
- **Database**: MySQL (relational multi-periode)
- **Asset Bundler**: Vite 8+

### ATURAN UTAMA & LARANGAN:
1. **DILARANG MENGGUNAKAN NEXT.JS ATAU REACT** sebagai frontend terpisah.
2. **DILARANG MEMBUAT FRONTEND TERPISAH**. Seluruh UI di-render menggunakan Laravel Blade.
3. **DILARANG MENGGUNAKAN REST API SEBAGAI ARSITEKTUR UTAMA** apabila Blade dan Laravel Controller sudah mencukupi.
4. Jangan menghapus atau memindahkan direktori `pemira-ubsi-frontend` tanpa persetujuan eksplisit dari pengguna.
5. Seluruh kode, logic, routing, autentikasi, dan view berada di dalam project `pemira-ubsi-backend`.

---

## 2. STANDAR CODING & ARSITEKTUR KODE

- **PHP 8.3+ Standard**: Gunakan typed properties, return types, match expressions, readonly properties, dan constructor promotion.
- **Controller Terstruktur**: Pisahkan controller berdasarkan konteks namespace:
  - `App\Http\Controllers\Admin\...`
  - `App\Http\Controllers\Committee\...`
  - `App\Http\Controllers\Student\...`
  - `App\Http\Controllers\Public\...`
- **Business Logic Layer**: Logika voting, kalkulasi perolehan suara, dan verifikasi dimasukkan ke dalam `App\Services\...`:
  - `VotingService` (menjamin atomisitas, anonymous ballot, token invalidation)
  - `ElectionService` (manajemen periode, status pemilihan, jadwal)
  - `CandidateService` (manajemen pasangan calon BEM)
- **Validation**: Gunakan dedicated `FormRequest` untuk validasi form, jangan menulis validasi panjang di dalam controller.
- **Authorization & Security**:
  - Gunakan Laravel Gates / Policies untuk otorisasi setiap aksi sensitif.
  - Setiap endpoint voting dan modifikasi data wajib diproteksi CSRF dan Rate Limiting.

---

## 3. STANDAR UI/UX: MODERN INSTITUTIONAL

- **Identitas Visual**: Modern institutional, formal, elegan, dan profesional mencerminkan kredibilitas pesta demokrasi kampus BEM UBSI Karawang.
- **Warna Identitas Resmi UBSI Karawang**:
  - Primary / Merah UBSI: `#940c10` (hover: `#7A0A0D`)
  - Accent / Kuning Emas UBSI: `#CE9501` (hover: `#B58200`)
  - Surface / Putih Bersih UBSI: `#FDFDFD`
  - Neutrals: Slate / Zinc untuk latar belakang yang bersih dan kontras tinggi
  - Status: Emerald `#059669` untuk sukses/sudah voting, Kuning UBSI `#CE9501` untuk peringatan/timer, Merah UBSI `#940c10` untuk error/darurat
- **Tipografi**: Bersih dan terbaca jelas (Instrument Sans / Inter), hierarki judul tegas, nyaman dibaca di layar monitor maupun ponsel pemilih.
- **Ikonografi**: Konsisten menggunakan Lucide Icons, hindari penggunaan emoji sebagai icon antarmuka.
- **Responsive & Spacing**: Desain wajib mobile-friendly dan nyaman digunakan di TPS (tablet/kios/smartphone/laptop).
- **Feedback & Guardrails**:
  - Dialog konfirmasi modal sebelum suara dikirim.
  - Halaman penyelesaian voting dengan timer hitung mundur 30 detik menuju auto-logout.
  - Tombol darurat "Selesai / Keluar Sekarang" untuk mempercepat antrean TPS.

---

## 4. ATURAN KEAMANAN VOTING & PRIVASI SUARA (ANONYMOUS BALLOT)

1. **Prinsip Kerahasiaan (Luber Jurdil)**:
   - **Identitas mahasiswa TIDAK BOLEH dapat dihubungkan kembali dengan pilihan kandidatnya**.
   - DILARANG menyimpan `student_id` dan `candidate_id` dalam satu baris tabel yang sama di database.
2. **Pemisahan Partisipasi dan Kotak Suara**:
   - Tabel **Voter Participation** (`election_voters`): Mencatat status mahasiswa bahwa hak suaranya telah digunakan pada periode tersebut (`has_voted = true`, `voted_at = timestamp`), IP/lokasi TPS jika diperlukan untuk audit.
   - Tabel **Anonymous Ballots** (`ballots`): Menyimpan surat suara murni (`election_id`, `candidate_id`, `ballot_hash/serial`, `created_at`). Tidak ada foreign key ke `students` atau `users`.
3. **Mekanisme Blind Token / One-Time Single-Use Credential**:
   - Saat mahasiswa menekan tombol konfirmasi voting, transaksi database (`DB::transaction`) secara atomik:
     1. Memeriksa apakah mahasiswa sudah memilih (`lockForUpdate`).
     2. Menandai mahasiswa telah menggunakan hak suaranya.
     3. Memasukkan surat suara anonim ke tabel `ballots`.
     4. Menghanguskan sesi voting untuk mencegah double-spending/race condition.
4. **Proteksi Akses Mahasiswa Pasca Voting**:
   - Jika mahasiswa login kembali setelah memilih, sistem hanya menampilkan status bahwa hak suara telah digunakan dan ringkasan pasangan calon yang dipilih (disimpan dalam sesi terenkripsi atau token receipt terisolasi, tanpa menghubungkan tabel identitas dan ballot secara publik).
   - Mahasiswa tidak dapat mengganti pilihan atau melakukan voting ulang.
5. **Audit Logging**:
   - Seluruh aktivitas admin dan panitia (tambah kandidat, ganti status periode, buka/tutup voting) wajib tercatat di `audit_logs`.

---

## 5. ATURAN DATABASE & ERD

1. **Multi-Period Archiving**:
   - Sistem wajib mendukung banyak periode PEMIRA tanpa menghapus data historis masa lalu.
   - Setiap entitas kandidat, pemilih, dan ballot harus berelasi dengan `election_period_id`.
2. **Persetujuan ERD**:
   - JANGAN membuat migration baru sebelum rancangan ERD, primary key, foreign key, unique constraint, dan strategi anonymous ballot disetujui pengguna.

---

## 6. WORKFLOW PENGEMBANGAN BERTAHAP (9 FASE)

- **Fase 1**: Project Inspection (Analisis dependensi & verifikasi environment).
- **Fase 2**: Architecture Preparation (Dokumentasi arsitektur & panduan agen).
- **Fase 3**: UI/UX Design System (Penyusunan panduan desain di `docs/DESIGN_SYSTEM.md`).
- **Fase 4**: Database Design (Perancangan ERD lengkap untuk disetujui).
- **Fase 5**: Authentication & Multi-Role Guardrails (Admin, Panitia, Mahasiswa).
- **Fase 6**: Election Management (Periode, Kandidat Ketua-Wakil, Jadwal, Pengumuman).
- **Fase 7**: Secure & Anonymous Voting Engine (One-time token, atomicity, 30s countdown).
- **Fase 8**: Result Management & Real-time/Public Tally (Validasi & publikasi resmi).
- **Fase 9**: Quality Assurance & Security Hardening (Pest/PHPUnit, race condition audit).

---

## 7. STANDAR TESTING & QA

- Setiap alur kritis wajib memiliki unit & feature test:
  - Uji otentikasi peran (Admin, Panitia, Mahasiswa, Tamu).
  - Uji integritas suara (uji coba race condition / pengiriman suara ganda secara simultan).
  - Uji kerahasiaan suara (verifikasi ketiadaan foreign key student pada tabel ballot).
  - Uji siklus hidup periode (voting ditutup di luar jadwal).
