<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .kop-surat { position: relative; border-bottom: 3px solid #980D0D; padding-bottom: 10px; margin-bottom: 15px; text-align: center; }
        .logo-img { position: absolute; left: 10px; top: 0px; width: 65px; height: auto; }
        .instansi-box { margin: 0 75px; text-align: center; }
        .instansi-name { font-size: 14px; font-weight: bold; color: #980D0D; margin: 0; line-height: 1.3; }
        .instansi-sub { font-size: 8.5px; color: #666; margin: 2px 0 0 0; }
        .doc-title { text-align: center; text-transform: uppercase; font-size: 14px; font-weight: bold; margin: 20px 0 5px 0; text-decoration: underline; }
        .doc-number { text-align: center; margin-bottom: 20px; font-family: 'Courier', monospace; font-size: 10px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 4px 0; vertical-align: top; }
        .label { font-weight: bold; width: 130px; }
        .stats-box { border: 1px solid #ddd; padding: 10px; background-color: #fcfcfc; margin-bottom: 20px; }
        .stats-table { width: 100%; text-align: center; }
        .stats-table th { font-weight: bold; font-size: 9px; text-transform: uppercase; color: #666; }
        .stats-table td { font-size: 16px; font-weight: bold; color: #980D0D; }
        .main-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .main-table th, .main-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .main-table th { background-color: #f5f5f5; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .main-table tr:nth-child(even) { background-color: #fafafa; }
        .badge-hadir { color: green; font-weight: bold; }
        .badge-izin { color: orange; font-weight: bold; }
        .badge-sakit { color: blue; font-weight: bold; }
        .badge-tidak-hadir { color: red; font-weight: bold; }
        .signature-table { width: 100%; margin-top: 40px; }
        .signature-table td { width: 50%; text-align: center; }
        .signature-space { height: 60px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #999; border-top: 1px solid #eee; padding-top: 4px; }
        .clear { clear: both; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <img src="{{ public_path('images/logowo.png') }}" class="logo-img">
        <div class="instansi-box">
            <h1 class="instansi-name">IKATAN PELAJAR DAN MAHASISWA BANGKA JAKARTA RAYA</h1>
            <h2 style="font-size: 12px; font-weight: bold; color: #980D0D; margin: 2px 0;">(ISBA JAYA)</h2>
            <p class="instansi-sub">Jl. Langga Raya No 77 A, Lenteng Agung, Jagakarsa, Jakarta Selatan</p>
            <p class="instansi-sub">Email: sekretariat@isbajaya.org | Website: www.isbajaya.org</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="doc-title">LAPORAN KEHADIRAN / ABSENSI ANGGOTA</div>
    <div class="doc-number">Acara: {{ $event->title }}</div>

    <table class="info-table">
        <tr>
            <td class="label">Kode Acara</td>
            <td>: {{ $event->event_code }}</td>
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
            <td class="label">Laporan Dibuat</td>
            <td>: {{ date('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Oleh</td>
            <td>: {{ auth()->user()->name }}</td>
        </tr>
    </table>

    <div class="stats-box">
        <table class="stats-table">
            <tr>
                <th>Total Anggota</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Tidak Hadir (Alpa)</th>
            </tr>
            <tr>
                <td>{{ $attendances->count() }}</td>
                <td>{{ $event->totalHadir() }}</td>
                <td>{{ $event->totalSakit() }}</td>
                <td>{{ $event->totalIzin() }}</td>
                <td>{{ $event->totalTidakHadir() }}</td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No</th>
                <th>Nama Anggota</th>
                <th>NIM</th>
                <th>Departemen</th>
                <th style="width: 70px; text-align: center;">Status</th>
                <th>Waktu</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $attendance)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $attendance->member->full_name }}</strong></td>
                <td>{{ $attendance->member->nim }}</td>
                <td>{{ $attendance->member->department->name ?? '-' }}</td>
                <td style="text-align: center;">
                    @if($attendance->status === 'Hadir')
                        <span class="badge-hadir">Hadir</span>
                    @elseif($attendance->status === 'Izin')
                        <span class="badge-izin">Izin</span>
                    @elseif($attendance->status === 'Sakit')
                        <span class="badge-sakit">Sakit</span>
                    @else
                        <span class="badge-tidak-hadir">Alpa</span>
                    @endif
                </td>
                <td>
                    {{ $attendance->checked_in_at ? $attendance->checked_in_at->format('H:i') : '-' }}
                </td>
                <td>{{ $attendance->note ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>Mengetahui,</td>
            <td>Tertanda,</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Ketua Umum ISBA JAYA</td>
            <td style="font-weight: bold;">Admin HRD / Sekretaris</td>
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
        Dokumen ini dihasilkan secara otomatis oleh Sistem HRIS ISBA JAYA. Halaman 1 dari 1.
    </div>

</body>
</html>
