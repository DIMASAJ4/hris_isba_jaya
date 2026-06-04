<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .kop-surat { border-bottom: 3px solid #980D0D; padding-bottom: 10px; margin-bottom: 20px; }
        .logo-img { width: 70px; height: auto; float: left; margin-top: 5px; }
        .instansi-box { margin-left: 90px; text-align: center; margin-right: 20px; }
        .instansi-name { font-size: 15px; font-weight: bold; color: #980D0D; margin: 0; line-height: 1.3; }
        .instansi-sub { font-size: 9px; color: #666; margin: 2px 0 0 0; }
        .doc-title { text-align: center; text-transform: uppercase; font-size: 16px; font-weight: bold; margin: 30px 0 5px 0; text-decoration: underline; }
        .doc-number { text-align: center; margin-bottom: 30px; font-family: 'Courier', monospace; font-size: 11px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 5px 0; vertical-align: top; }
        .label { font-weight: bold; width: 150px; }
        .content-box { border: 1px solid #eee; padding: 20px; background-color: #fcfcfc; margin-top: 10px; min-height: 200px; }
        .section-label { font-weight: bold; text-transform: uppercase; font-size: 10px; color: #999; margin-top: 20px; }
        .signature-table { width: 100%; margin-top: 50px; }
        .signature-table td { width: 50%; text-align: center; }
        .signature-space { height: 80px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
        .clear { clear: both; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <img src="{{ public_path('images/logoasli.png') }}" class="logo-img">
        <div class="instansi-box">
            <h1 class="instansi-name">IKATAN PELAJAR DAN MAHASISWA BANGKA JAKARTA RAYA</h1>
            <h2 style="font-size: 14px; font-weight: bold; color: #980D0D; margin: 2px 0;">(ISBA JAYA)</h2>
            <p class="instansi-sub">Jl. Langga Raya No 77 A, Lenteng Agung, Jagakarsa, Jakarta Selatan</p>
            <p class="instansi-sub">Email: sekretariat@isbajaya.org | Website: www.isbajaya.org</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="doc-title">BERITA ACARA KEGIATAN</div>
    <div class="doc-number">Nomor: {{ $event->event_code }}/ISBA-JAYA/{{ date('Y') }}</div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Acara / Kegiatan</td>
            <td>: {{ $event->title }}</td>
        </tr>
        <tr>
            <td class="label">Hari / Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Tempat / Lokasi</td>
            <td>: {{ $event->location }}</td>
        </tr>
        <tr>
            <td class="label">Status Pelaksanaan</td>
            <td>: {{ $event->status }}</td>
        </tr>
        <tr>
            <td class="label">Dokumentator</td>
            <td>: {{ $event->creator->name }}</td>
        </tr>
        <tr>
            <td class="label">Dicetak Oleh</td>
            <td>: {{ auth()->user()->name }}</td>
        </tr>
    </table>

    <div class="section-label">AGENDA & DESKRIPSI JALANNYA ACARA:</div>
    <div class="content-box">
        {!! nl2br(e($event->description)) !!}
    </div>

    @if($event->notes)
    <div class="section-label">CATATAN TAMBAHAN / TINDAK LANJUT:</div>
    <div style="font-style: italic; margin-top: 5px; color: #555;">
        - {{ $event->notes }}
    </div>
    @endif

    <table class="signature-table">
        <tr>
            <td>Mengetahui,</td>
            <td>Tertanda,</td>
        </tr>
        <tr>
            <td class="font-weight: bold;">Ketua Umum ISBA JAYA</td>
            <td class="font-weight: bold;">Admin HRD / Sekretaris</td>
        </tr>
        <tr>
            <td class="signature-space"></td>
            <td class="signature-space"></td>
        </tr>
        <tr>
            <td>( ........................................... )</td>
            <td>( {{ auth()->user()->name }} )</td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh Sistem HRIS ISBA JAYA pada {{ date('d/m/Y H:i') }}.
    </div>

</body>
</html>
