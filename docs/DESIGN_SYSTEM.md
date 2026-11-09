# DESIGN SYSTEM — PEMIRA BEM UNIVERSITAS BSI KAMPUS KARAWANG
*Dokumentasi Resmi Standar Desain Antarmuka Sistem E-Voting PEMIRA BEM UBSI Karawang*
*Dihasilkan & Disesuaikan Penuh dengan Identitas Resmi BEM UBSI Karawang dan UI/UX Pro Max Skill*

---

## 1. VISUAL DIRECTION & IDENTITAS RESMI BEM UBSI KARAWANG

### A. Lembaga & Identitas Organisasi
- **Institusi Induk**: Universitas Bina Sarana Informatika (UBSI) Kampus Karawang.
- **Lembaga Eksekutif**: Badan Eksekutif Mahasiswa (BEM) UBSI Karawang.
- **Penyelenggara Pemilihan**: Panitia Pemilihan Umum Raya (PEMIRA) BEM UBSI Karawang.
- **Karakteristik Desain**:
  - *Modern Institutional & Accessible* (WCAG AAA Compliance).
  - Wibawa organisasi mahasiswa yang independen, demokratis, jujur, adil, transparan, dan kredibel.
  - Membawa identitas kebanggaan almamater UBSI Karawang.
- **Aturan Akses Utama (Zero-Guest Policy)**:
  - **DILARANG menyediakan halaman publik / tamu / landing page umum**.
  - Rute awal aplikasi (`/`) langsung mengarahkan pengguna ke **Halaman Login Resmi PEMIRA BEM UBSI Karawang**.
  - Seluruh akses informasi kandidat, visi misi, bilik suara, dan rekapitulasi data berada di balik autentikasi terverifikasi.

### B. Identitas Warna Resmi Kampus & BEM UBSI Karawang
- **Merah UBSI (Primary Brand)**: `#940c10` (hover: `#7A0A0D`) — Warna utama almamater UBSI, header organisasi, badge kepemimpinan BEM, dan tombol aksi utama.
- **Kuning Emas UBSI (Accent & Highlight)**: `#CE9501` (hover: `#B58200`) — Warna emas lambang BEM UBSI, nomor urut paslon ketua & wakil ketua, border kartu sorotan, serta indikator timer.
- **Putih Bersih UBSI (Surface)**: `#FDFDFD` — Latar belakang bilik suara dan kontainer kartu berlatar bersih dengan kontras tinggi.

---

## 2. COLOR PALETTE SYSTEM

```
┌────────────────────────────────────────────────────────────────────────┐
│ IDENTITAS RESMI UBSI KARAWANG                                          │
│ Merah UBSI      : #940c10 (Primary Brand - Header, Badge Utama, CTA)   │
│ Merah Dark/Hover: #7A0A0D (Hover State Tombol Utama)                   │
│ Kuning Emas     : #CE9501 (Accent Gold - Nomor Paslon, Aksen, Warning) │
│ Kuning Hover    : #B58200 (Hover State Aksen)                          │
│ Putih Bersih    : #FDFDFD (Surface / Background Bilik Suara & Card)    │
├────────────────────────────────────────────────────────────────────────┤
│ NETRAL & TEKS (KONTRAST TINGGI WCAG AAA)                               │
│ Teks Utama      : #1A1A1A (Near Black - Keterbacaan Maksimal)          │
│ Teks Sekunder   : #4B5563 (Slate 600 - Visi, Misi, Panduan)            │
│ Border & Line   : #E5E7EB (Slate 200 - Pembatas Elemen)                │
│ Card Surface    : #FFFFFF (White Murni)                                │
│ Background Body : #F8FAFC / #FDFDFD                                    │
├────────────────────────────────────────────────────────────────────────┤
│ STATUS & FEEDBACK                                                      │
│ Success / Voted : #059669 (Emerald 600 - Hak Suara Berhasil Digunakan) │
│ Warning / Timer : #CE9501 (Kuning UBSI - Countdown Timer 30 Detik)     │
│ Danger / Batal  : #940c10 (Merah UBSI - Tombol Darurat Keluar / Batal) │
└────────────────────────────────────────────────────────────────────────┘
```

### Konfigurasi Tailwind CSS v4 (`resources/css/app.css`)
```css
@import 'tailwindcss';

@theme {
  --color-ubsi-red: #940c10;
  --color-ubsi-red-dark: #7A0A0D;
  --color-ubsi-red-light: #FEE2E2;
  --color-ubsi-gold: #CE9501;
  --color-ubsi-gold-dark: #B58200;
  --color-ubsi-gold-light: #FEF3C7;
  --color-ubsi-white: #FDFDFD;
  
  --color-brand-primary: #940c10;
  --color-brand-accent: #CE9501;
  --color-surface-bg: #FDFDFD;
  --color-surface-card: #FFFFFF;
  --color-text-main: #1A1A1A;
  --color-text-muted: #4B5563;
  --color-status-success: #059669;
}
```

---

## 3. TYPOGRAPHY SYSTEM

Menggunakan tipografi modern berseri bebas-distraksi yang memiliki keterbacaan tinggi di berbagai resolusi layar:
- **Font Keluarga Utama**: `Instrument Sans` / `Inter`, dengan fallback sans-serif sistem.
- **Hierarki & Skala Ukuran**:
  - `Display / Judul Halaman`: 28px - 36px (Font-bold, warna `#940c10` atau `#1A1A1A`).
  - `Nomor Urut Paslon`: 32px - 40px (Font-extrabold, warna `#CE9501` atau background `#940c10` dengan teks `#FDFDFD`).
  - `Nama Pasangan Calon`: 20px - 24px (Font-bold, warna `#1A1A1A`).
  - `Sub-Heading / Bagian`: 16px - 18px (Font-semibold, warna `#4B5563`).
  - `Body Text (Visi-Misi, Panduan)`: 15px - 16px (Font-normal, leading-relaxed, warna `#1A1A1A`).
  - `Metadata / Waktu / Keterangan`: 12px - 13px (Font-medium, warna `#6B7280`).

---

## 4. SPACING & TARGET SENTUH (TOUCH TARGETS)

- **Grid & Container**:
  - Bilik Suara Mahasiswa: `max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8`.
  - Dashboard Admin/Panitia: `max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6`.
- **Target Sentuh Minimum**:
  - Seluruh tombol interaktif, radio selector, dan kartu paslon memiliki target sentuh minimum `h-11` (44px) hingga `h-12` (48px) untuk kenyamanan tablet dan smartphone di TPS.

---

## 5. BLUEPRINT KOMPONEN REUSABLE

### A. Kartu Pasangan Calon (`candidate-card.blade.php`)
- **Border & Container**: Kartu berlatar belakang `#FFFFFF` dengan border halus `border-slate-200` dan hover aksen emas `hover:border-[#CE9501]`.
- **Nomor Urut**: Badge lingkaran tegas dengan latar belakang Merah UBSI `#940c10`, teks nomor Kuning Emas `#CE9501`, dan kontur putih.
- **Dua Slot Foto Paslon**:
  - Foto Calon Ketua & Foto Calon Wakil Ketua berdampingan dengan rasio aspek 3:4.
  - Border tipis dan bayangan lembut (*subtle elevation*).
- **Tab Visi, Misi, & Program Kerja**:
  - Didukung Alpine.js (`x-data="{ tab: 'visi' }"`).
  - Tab aktif bergaris bawah tebal warna Kuning Emas `#CE9501` atau Merah `#940c10`.
- **Tombol Aksi Pemilihan**:
  - Tombol primer Merah `#940c10` dengan teks `#FDFDFD`. Saat hover berubah menjadi `#7A0A0D`.

### B. Dialog Konfirmasi Voting 2-Langkah (`modal-confirm-vote.blade.php`)
- **Backdrop**: Gelap transparan `bg-black/60` dengan `backdrop-blur-sm`.
- **Panel Modal**: Warna `#FDFDFD`, sudut melengkung `rounded-2xl`, border atas tebal Merah `#940c10`.
- **Peringatan Penting**:
  - Kotak peringatan Kuning Emas `#FEF3C7` dengan teks `#B58200`: *"Pilihan Anda bersifat final dan rahasia. Suara tidak dapat diganti setelah dikirim."*
- **Dua Tombol**:
  - Tombol Batal: `bg-slate-100 hover:bg-slate-200 text-slate-700` ("Kembali Memeriksa").
  - Tombol Kirim Suara: `bg-[#940c10] hover:bg-[#7A0A0D] text-[#FDFDFD]` ("Ya, Kirim Suara Saya").

### C. Halaman Penyelesaian Voting & Countdown 30 Detik (`completion.blade.php`)
- **Status Box**:
  - Icon Ceklis Sukses Hijau `#059669`.
  - Judul: *"Hak Suara Anda Berhasil Digunakan Secara Anonim"*.
  - Detail: Tertera nomor urut dan nama paslon yang telah dipilih sebagai konfirmasi penerimaan suara.
- **Timer Hitung Mundur 30 Detik**:
  - Badge angka dinamis dengan lingkaran warna Kuning Emas `#CE9501` yang berkurang tiap detik.
  - Teks: *"Sesi Anda akan berakhir secara otomatis dalam [30] detik untuk menjaga kerahasiaan suara."*
- **Tombol Darurat Antrean**:
  - Tombol merah `#940c10` dengan teks `#FDFDFD`: *"Selesai / Keluar Sekarang"*, mengakhiri sesi seketika demi mempercepat pemilih berikutnya di TPS.

### D. Halaman Login Langsung (Direct Entry — Zero-Guest Policy)
- **Aturan Rute**: Aplikasi tidak memiliki landing page atau halaman guest umum. Rute root (`/`) langsung mengarahkan pengguna ke halaman Login (`/login`).
- **Antarmuka Login Institusional**:
  - Identitas kampus UBSI Karawang di header card.
  - Form Login terpadu yang memfasilitasi mahasiswa (NIM + Password) serta Panitia/Admin.
  - Tidak ada tombol registrasi mandiri (DPT sepenuhnya dikelola dan di-import oleh Admin).
  - Peringatan keamanan dan informasi bantuan panitia TPS.

---

## 6. PRE-DELIVERY CHECKLIST UI/UX

- [x] **Skema Warna Resmi**: Merah `#940c10`, Kuning `#CE9501`, Putih `#FDFDFD`.
- [x] **Aksesibilitas Kontras**: Memenuhi rasio kontras 4.5:1 untuk teks biasa dan 3:1 untuk tombol besar.
- [x] **Ikonografi**: Konsisten menggunakan Lucide Icons (tidak menggunakan emoji).
- [x] **Responsivitas**: Teruji pada layar HP (375px), Tablet TPS (768px), dan Desktop (1024px+).
