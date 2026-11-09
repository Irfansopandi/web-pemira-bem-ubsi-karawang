# PEMIRA BEM UBSI KARAWANG — GEMINI INSTRUCTION GUIDELINES

Dokumen panduan eksekusi untuk model AI Gemini dalam mengembangkan sistem PEMIRA BEM UBSI Karawang.

## 1. CORE MISSION & ARCHITECTURE POLICY

- **Arsitektur Wajib**: Full Laravel 13 Monolith (Routing, Controller, Models, Blade Views, Services).
- **Frontend Engine**: Laravel Blade Templating didukung Tailwind CSS v4 dan Alpine.js.
- **Warna Resmi UBSI**: Merah `#940c10`, Kuning `#CE9501`, Putih `#FDFDFD`.
- **Ikonografi**: Lucide Icons.
- **Larangan Keras**:
  - JANGAN membuat atau mengaktifkan frontend terpisah (Next.js / React / Vue SPA).
  - JANGAN menggunakan REST API sebagai alur kerja utama jika Blade + Controller dapat menyelesaikannya.
  - JANGAN memindahkan atau menghapus folder `pemira-ubsi-frontend` tanpa izin pengguna.
  - JANGAN membuat migration database sebelum dokumen ERD dipresentasikan dan disetujui.

---

## 2. PANDUAN IMPLEMENTASI PERAN & FITUR

1. **Admin**:
   - Master Periode PEMIRA (aktifkan/nonaktifkan, jadwal mulai & selesai).
   - Manajemen Mahasiswa (import NIM, generate kredensial).
   - Manajemen Akun Panitia TPS.
   - Manajemen Kandidat (Paslon Ketua & Wakil Ketua BEM, visi, misi, program kerja, foto).
   - Pengumuman & Monitoring Partisipasi.
   - Validasi & Publikasi Rekapitulasi Suara.

2. **Panitia (Committee)**:
   - Verifikasi mahasiswa di lokasi TPS (onsite check-in).
   - Monitoring antrean dan kehadiran per sesi/lokasi voting.

3. **Mahasiswa**:
   - Login menggunakan NIM dan Password.
   - Akses profil paslon, visi-misi, program kerja.
   - Menggunakan hak suara sekali per periode pemilihan secara anonim.
   - Dialog konfirmasi sebelum submit suara.
   - Halaman penyelesaian dengan hitung mundur 30 detik menuju logout otomatis, atau tombol kembali langsung.
   - Login ulang pasca voting menampilkan status bahwa hak pilih telah digunakan dan hanya menampilkan paslon yang telah dipilih.

4. **Publik**:
   - Portal informasi PEMIRA, jadwal, profil kandidat.
   - Tampilan perolehan suara resmi hanya setelah status hasil dipublikasikan oleh Admin.

---

## 3. PRINSIP KERAHASIAAN SUARA (ANONYMOUS BALLOT ARCHITECTURE)

- **Pemisahan Entitas**:
  - Data pemilih yang mencatat siapa yang sudah hadir/memilih (`voter_participations`) terpisah total dari surat suara (`ballots`).
  - Tidak ada kolom `user_id` atau `student_id` pada tabel `ballots`.
- **Atomisitas Transaksi**:
  - Eksekusi voting dilakukan di dalam `DB::transaction` dengan `lockForUpdate` pada record partisipasi pemilih.
  - Suara yang dikirimkan tidak dapat dibatalkan atau diedit.

---

## 4. STANDAR PENGEMBANGAN SISTEM

- Mengikuti PSR-12 dan Clean Architecture di Laravel 13.
- Menggunakan Form Requests untuk validasi input.
- Menggunakan Laravel Service Layer (`App\Services`) untuk memisahkan business logic dari controller.
- Menggunakan Blade Components untuk elemen visual berulang (Navbar, Sidebar, Card Kandidat, Modal, Badge, Timer).
- Dokumentasi UI/UX System tersimpan di `docs/DESIGN_SYSTEM.md`.

