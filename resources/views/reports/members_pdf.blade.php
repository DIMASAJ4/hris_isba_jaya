<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Anggota ISBA JAYA</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1E3A5F; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1E3A5F; text-transform: uppercase; font-size: 18px; }
        .header p { margin: 2px 0; color: #666; }
        
        .meta { margin-bottom: 15px; width: 100%; }
        .meta td { vertical-align: top; }
        .title-box { background: #f4f6f9; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .title-box h2 { margin: 0; font-size: 14px; color: #1E3A5F; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #1E3A5F; color: white; text-align: left; padding: 8px; border: 1px solid #1E3A5F; }
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

    <div class="header">
        <h1>Ikatan Seluruh Mahasiswa Bangka (ISBA) JAYA</h1>
        <p>Sekretariat: Jl. Contoh No. 123, Yogyakarta. Email: info@isbajaya.org</p>
        <p>Sistem Informasi Manajemen Keanggotaan (HRIS)</p>
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
