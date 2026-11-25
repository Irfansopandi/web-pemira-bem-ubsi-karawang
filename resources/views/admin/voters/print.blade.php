<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Daftar Pemilih Tetap (DPT) - PEMIRA BEM UBSI Kab.Karawang</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 24px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #0f172a;
            background-color: #f8fafc;
            font-size: 12px;
            line-height: 1.45;
        }
        .toolbar {
            max-width: 1000px;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .toolbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .select-status {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            font-size: 12px;
            font-weight: 600;
            color: #1e293b;
            cursor: pointer;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #1e293b;
        }
        .btn-primary {
            background: #940c10;
            border-color: #940c10;
            color: #ffffff;
        }
        .sheet {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            padding: 28px 36px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
        }
        /* Kop Surat Resmi */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 32px;
            padding-bottom: 12px;
            border-bottom: 3px double #000000;
            margin-bottom: 18px;
            font-family: 'Times New Roman', Times, serif;
            color: #000000;
        }
        .kop-logo-left,
        .kop-logo-right {
            width: 84px;
            height: 84px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .kop-center {
            text-align: center;
            line-height: 1.3;
        }
        .kop-org {
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0;
        }
        .kop-address {
            font-size: 12.5px;
            margin: 3px 0 0 0;
        }
        .kop-email {
            color: #0563c1;
            text-decoration: underline;
        }
        /* Judul Laporan */
        .report-title-wrap {
            text-align: center;
            margin-bottom: 14px;
        }
        .report-title {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #0f172a;
            margin: 0;
        }
        .report-meta {
            font-size: 11px;
            color: #475569;
            margin: 4px 0 0 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 7px 9px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            color: #334155;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }
        .badge-ok {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        .badge-info {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }
        .badge-muted {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .toolbar {
                display: none !important;
            }
            .sheet {
                border: none;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    @php
        $statusLabel = match(request('status')) {
            'present' => 'Sudah Hadir (TPS)',
            'not_present' => 'Belum Hadir',
            'voted' => 'Sudah Memilih',
            'not_voted' => 'Belum Memilih',
            default => 'Semua Mahasiswa (DPT)',
        };
    @endphp

    <div class="toolbar">
        <a href="{{ route('admin.voters.index', request()->query()) }}" class="btn">&larr; Kembali ke Kelola Mahasiswa</a>
        <div class="toolbar-right">
            <form action="{{ route('admin.voters.print') }}" method="GET" style="margin: 0;">
                @foreach(request()->except('status', 'page') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <select name="status" onchange="this.form.submit()" class="select-status">
                    <option value="" {{ !request('status') ? 'selected' : '' }}>Status: Semua Mahasiswa (DPT)</option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Status: Sudah Hadir (TPS)</option>
                    <option value="not_present" {{ request('status') === 'not_present' ? 'selected' : '' }}>Status: Belum Hadir</option>
                    <option value="voted" {{ request('status') === 'voted' ? 'selected' : '' }}>Status: Sudah Memilih</option>
                    <option value="not_voted" {{ request('status') === 'not_voted' ? 'selected' : '' }}>Status: Belum Memilih</option>
                </select>
            </form>
            <button type="button" onclick="window.print()" class="btn btn-primary">Cetak / Simpan PDF</button>
        </div>
    </div>

    <div class="sheet">
        <!-- Kop Surat Resmi BEM UBSI Kampus Kabupaten Karawang -->
        <div class="kop-surat">
            <img src="{{ asset('images/logo-ubsi.png') }}" alt="Logo Universitas BSI" class="kop-logo-left">
            <div class="kop-center">
                <p class="kop-org">BADAN EKSEKUTIF MAHASISWA</p>
                <p class="kop-org">UNIVERSITAS BINA SARANA INFORMATIKA</p>
                <p class="kop-org">KAMPUS KABUPATEN KARAWANG</p>
                <p class="kop-address">
                    Jl. Banten No. 1 Karangpawitan, Kec. Karawang Barat,<br>
                    Kab. Karawang, Prov. Jawa Barat &ndash; 41351 Telp. (0267) 8454893<br>
                    Contact Person : 0812 5229 1838 &ndash; e-mail : <span class="kop-email">bem.krw@bsi.ac.id</span>
                </p>
            </div>
            <img src="{{ asset('images/logo-bem-ubsi.png') }}" alt="Logo BEM UBSI Karawang" class="kop-logo-right">
        </div>

        <!-- Judul Laporan Tanpa Card Summary -->
        <div class="report-title-wrap">
            <h1 class="report-title">Daftar Pemilih Tetap (DPT) &amp; Kehadiran Mahasiswa</h1>
            <p class="report-meta">
                Periode: <strong>{{ $period ? $period->academic_year : '-' }}</strong>
                &bull; Status: <strong>{{ $statusLabel }}</strong>
                @if(request('study_program'))
                    &bull; Prodi: <strong>{{ request('study_program') }}</strong>
                @endif
                @if(request('class_name'))
                    &bull; Kelas: <strong>{{ request('class_name') }}</strong>
                @endif
                &bull; Dicetak: {{ now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB
                &bull; Total: <strong>{{ $participations->count() }}</strong> mahasiswa
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 36px;">No</th>
                    <th style="width: 85px;">NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Program Studi</th>
                    <th class="text-center" style="width: 72px;">Kelas</th>
                    <th class="text-center" style="width: 66px;">Semester</th>
                    <th>Asal Kampus</th>
                    <th class="text-center" style="width: 98px;">Kehadiran TPS</th>
                    <th class="text-center" style="width: 105px;">Status Hak Suara</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participations as $index => $part)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td style="font-family: monospace; font-weight: 700;">{{ $part->student_nim }}</td>
                        <td><strong>{{ $part->student->name ?? '-' }}</strong></td>
                        <td>{{ $part->student->study_program ?? '-' }}</td>
                        <td class="text-center">{{ $part->student->class_name ?? '-' }}</td>
                        <td class="text-center">{{ $part->student->semester ?? '-' }}</td>
                        <td>{{ $part->student->asal_kampus ?? ($part->tps_location ?? '-') }}</td>
                        <td class="text-center">
                            @if($part->verified_at)
                                <span class="badge badge-info">Hadir</span>
                            @else
                                <span class="badge badge-muted">Belum Hadir</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($part->has_voted)
                                <span class="badge badge-ok">Sudah Memilih</span>
                            @else
                                <span class="badge badge-muted">Belum Memilih</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 18px; color: #64748b;">
                            Tidak ada data mahasiswa yang sesuai dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
