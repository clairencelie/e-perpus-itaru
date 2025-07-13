<!DOCTYPE html>
<html>

<head>
    <title>{{ $reportTitle }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h1 {
            font-size: 16pt;
            text-align: center;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 12pt;
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-info {
            font-size: 9pt;
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }

        .footer {
            font-size: 8pt;
            text-align: right;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <h1>Sistem Informasi Perpustakaan</h1>
    <h2>{{ $reportTitle }}</h2>
    <p class="filter-info">Filter: {{ $filterApplied }}</p>

    <table>
        <thead>
            <tr>
                <th>ID Peminjaman</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjamanData as $item)
            <tr>
                <td>{{ $item->id_peminjaman }}</td>
                <td>{{ $item->user->nama ?? 'N/A' }} ({{ $item->user->username ?? 'N/A' }})</td>
                <td>{{ $item->buku->judul ?? 'N/A' }}</td>
                <td>{{ $item->tanggal_pinjam->format('d M Y') }}</td>
                <td>{{ $item->tanggal_jatuh_tempo->format('d M Y') }}</td>
                <td>{{ $item->tanggal_pengembalian ? $item->tanggal_pengembalian->format('d M Y') : '-' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $item->status_peminjaman)) }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data peminjaman sesuai filter.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ Carbon\Carbon::now()->format('d M Y H:i:s') }}
    </div>
</body>

</html>