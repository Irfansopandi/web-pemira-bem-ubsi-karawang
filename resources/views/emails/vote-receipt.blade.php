<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tanda Terima Pemungutan Suara - PEMIRA BEM UBSI Kab.Karawang</title>
    <style>
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 16px 10px !important;
            }
            .email-header {
                padding: 18px 16px 16px 16px !important;
            }
            .email-logo-cell {
                width: 36px !important;
                padding-right: 10px !important;
            }
            .email-logo-img {
                width: 36px !important;
                height: 36px !important;
            }
            .email-title {
                font-size: 11.5px !important;
                letter-spacing: -0.01em !important;
                white-space: nowrap !important;
            }
            .email-body {
                padding: 22px 20px !important;
            }
            .email-footer {
                padding: 16px 20px !important;
            }
            .receipt-card-header,
            .receipt-card-body {
                padding: 14px 16px !important;
            }
            .receipt-label,
            .receipt-value {
                display: block !important;
                width: 100% !important;
                text-align: left !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            .receipt-label {
                padding-top: 10px !important;
                padding-bottom: 2px !important;
                font-size: 12px !important;
            }
            .receipt-value {
                padding-top: 0 !important;
                padding-bottom: 10px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #0f172a; -webkit-font-smoothing: antialiased;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1f5f9;">
        <tr>
            <td class="email-wrapper" align="center" style="padding: 32px 16px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 580px; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden;">
                    <!-- Garis Aksen Tipis Institusional di Paling Atas -->
                    <tr>
                        <td style="height: 4px; background-color: #940c10; font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    <!-- Header Bersih & Formal dengan Logo -->
                    @php
                        $logoPath = public_path('images/logo-bem-ubsi.png');
                        $logoSrc = (isset($message) && file_exists($logoPath))
                            ? $message->embed($logoPath)
                            : asset('images/logo-bem-ubsi.png');

                        $watermarkPath = public_path('images/logo-bem-ubsi-watermark.png');
                        $watermarkSrc = (isset($message) && file_exists($watermarkPath))
                            ? $message->embed($watermarkPath)
                            : asset('images/logo-bem-ubsi-watermark.png');
                    @endphp
                    <tr>
                        <td class="email-header" style="padding: 24px 32px 20px 32px; border-bottom: 1px solid #e2e8f0;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td class="email-logo-cell" width="46" style="width: 46px; padding-right: 14px; vertical-align: middle;">
                                        <img class="email-logo-img" src="{{ $logoSrc }}" alt="Logo PEMIRA BEM UBSI" width="44" height="44" style="display: block; width: 44px; height: 44px; object-fit: contain; border: 0;">
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <p style="margin: 0 0 4px 0; font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #940c10;">
                                            Tanda Terima Pemungutan Suara
                                        </p>
                                        <h1 class="email-title" style="margin: 0; font-size: 17px; font-weight: 700; color: #0f172a; line-height: 1.3;">
                                            PEMIRA BEM UBSI KABUPATEN KARAWANG
                                        </h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Konten Utama dengan Watermark Logo di Tengah -->
                    <tr>
                        <td class="email-body" background="{{ $watermarkSrc }}" style="padding: 26px 32px 28px 32px; background-color: #ffffff; background-image: url('{{ $watermarkSrc }}'); background-repeat: no-repeat; background-position: center center; background-size: 240px 240px;">
                            <p style="margin: 0 0 14px 0; font-size: 14px; line-height: 1.65; color: #334155;">
                                Terima kasih telah menggunakan hak suara Anda dalam Pemilihan Raya Badan Eksekutif Mahasiswa Universitas Bina Sarana Informatika Kabupaten Karawang.
                            </p>

                            <p style="margin: 0 0 22px 0; font-size: 14px; line-height: 1.65; color: #334155;">
                                Kami mengonfirmasi bahwa pemungutan suara Anda telah berhasil direkam oleh sistem.
                            </p>

                            <!-- Tabel Informasi Tanda Terima -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: transparent; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 22px;">
                                <tr>
                                    <td class="receipt-card-header" style="padding: 12px 18px; border-bottom: 1px solid #e2e8f0;">
                                        <p style="margin: 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569;">
                                            Informasi Tanda Terima
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="receipt-card-body" style="padding: 8px 18px 10px 18px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size: 13.5px; line-height: 1.55;">
                                            <tr>
                                                <td class="receipt-label" width="42%" style="padding: 8px 12px 8px 0; color: #64748b; border-bottom: 1px solid #e2e8f0; vertical-align: top;">
                                                    Nama Mahasiswa
                                                </td>
                                                <td class="receipt-value" width="58%" style="padding: 8px 0; color: #0f172a; font-weight: 600; border-bottom: 1px solid #e2e8f0; vertical-align: top; word-break: break-word;">
                                                    {{ $studentName }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="receipt-label" width="42%" style="padding: 8px 12px 8px 0; color: #64748b; border-bottom: 1px solid #e2e8f0; vertical-align: top;">
                                                    NIM
                                                </td>
                                                <td class="receipt-value" width="58%" style="padding: 8px 0; color: #0f172a; font-weight: 700; letter-spacing: 0.03em; border-bottom: 1px solid #e2e8f0; vertical-align: top;">
                                                    <strong style="font-weight: 700; color: #0f172a;">{{ $studentNim }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="receipt-label" width="42%" style="padding: 8px 12px 8px 0; color: #64748b; border-bottom: 1px solid #e2e8f0; vertical-align: top;">
                                                    Periode Pemilihan
                                                </td>
                                                <td class="receipt-value" width="58%" style="padding: 8px 0; color: #0f172a; font-weight: 600; border-bottom: 1px solid #e2e8f0; vertical-align: top; word-break: break-word;">
                                                    {{ $periodName }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="receipt-label" width="42%" style="padding: 8px 12px 8px 0; color: #64748b; border-bottom: 1px solid #e2e8f0; vertical-align: top;">
                                                    Waktu Pemungutan Suara
                                                </td>
                                                <td class="receipt-value" width="58%" style="padding: 8px 0; color: #0f172a; font-weight: 600; border-bottom: 1px solid #e2e8f0; vertical-align: top;">
                                                    {{ $votedAtFormatted }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="receipt-label" width="42%" style="padding: 10px 12px 6px 0; color: #64748b; vertical-align: top;">
                                                    Status
                                                </td>
                                                <td class="receipt-value" width="58%" style="padding: 10px 0 6px 0; vertical-align: top;">
                                                    <span style="display: inline-block; padding: 2px 10px; background-color: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; border-radius: 4px; font-size: 12px; font-weight: 700;">
                                                        Berhasil direkam
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Pesan Penutup -->
                            <p style="margin: 0 0 22px 0; padding: 12px 14px; border-left: 3px solid #ce9501; background-color: #f8fafc; font-size: 13.5px; line-height: 1.6; color: #475569;">
                                "Terima kasih telah berpartisipasi dalam PEMIRA BEM UBSI Kab.Karawang. Suara Anda merupakan bagian dari proses demokrasi mahasiswa."
                            </p>

                            <p style="margin: 0 0 2px 0; font-size: 14px; color: #334155;">Salam,</p>
                            <p style="margin: 0; font-size: 14px; font-weight: 700; color: #0f172a;">Panitia PEMIRA BEM UBSI Kab.Karawang.</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="email-footer" style="background-color: #f8fafc; padding: 16px 32px; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; font-size: 11.5px; line-height: 1.55; color: #64748b;">
                                Email ini dikirim secara otomatis oleh sistem E-Voting PEMIRA BEM UBSI Kab.Karawang. Demi menjaga kerahasiaan suara (LUBER JURDIL), pilihan kandidat tidak dicantumkan dalam tanda terima ini.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
