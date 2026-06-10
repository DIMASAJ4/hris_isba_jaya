<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Anggota ISBA JAYA</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        
        .kop-surat { position: relative; border-bottom: 3px solid #980D0D; padding-bottom: 10px; margin-bottom: 20px; text-align: center; }
        .logo-img { position: absolute; left: 10px; top: 0px; width: 70px; height: auto; }
        .instansi-box { margin: 0 80px; text-align: center; }
        .instansi-name { font-size: 15px; font-weight: bold; color: #980D0D; margin: 0; line-height: 1.3; }
        .instansi-sub { font-size: 9px; color: #666; margin: 2px 0 0 0; }
        .clear { clear: both; }

        .meta { margin-bottom: 15px; width: 100%; }
        .meta td { vertical-align: top; }
        .title-box { background: #f4f6f9; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .title-box h2 { margin: 0; font-size: 14px; color: #980D0D; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #980D0D; color: white; text-align: left; padding: 8px; border: 1px solid #980D0D; }
        td { padding: 8px; border: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
        .badge { padding: 2px 6px; border-radius: 3px; font-weight: bold; font-size: 9px; }
        .badge-aktif { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-alumni { background: #dbeafe; color: #1e40af; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <img src="{{ public_path('images/logoasli.jpeg') }}" class="logo-img">
        <div class="instansi-box">
            <h1 class="instansi-name">IKATAN PELAJAR DAN MAHASISWA BANGKA JAKARTA RAYA</h1>
            <h2 style="font-size: 14px; font-weight: bold; color: #980D0D; margin: 2px 0;">(ISBA JAYA)</h2>
            <p class="instansi-sub">Jl. Langga Raya No 77 A, Lenteng Agung, Jagakarsa, Jakarta Selatan</p>
            <p class="instansi-sub">Email: sekretariat@isbajaya.org | Website: www.isbajaya.org</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="title-box">
        <h2>LAPORAN DATA ANGGOTA</h2>
        <p style="margin: 5px 0 0 0">Dicetak pada: {{ now()->format('d F Y, H:i') }} | Oleh: {{ auth()->user()->name }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px">No</th>
                <th>Nama Lengkap</th>
                <th>NIM</th>
                <th>Departemen</th>
                <th>Jabatan</th>
                <th style="width: 70px">Angkatan</th>
                <th style="width: 60px">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $index => $member)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td><strong>{{ $member->full_name }}</strong></td>
                <td>{{ $member->nim }}</td>
                <td>{{ $member->department->name ?? '-' }}</td>
                <td>{{ $member->position->name ?? '-' }}</td>
                <td style="text-align: center">{{ $member->batch_year ?? '-' }}</td>
                <td style="text-align: center">
                    <span class="badge {{ $member->status == 'Aktif' ? 'badge-aktif' : ($member->status == 'Pending' ? 'badge-pending' : 'badge-alumni') }}">
                        {{ strtoupper($member->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Halaman 1 dari 1 — HRIS ISBA JAYA &copy; {{ date('Y') }}
    </div>

</body>
</html>
