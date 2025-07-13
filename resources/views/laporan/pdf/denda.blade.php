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
                <th>ID Denda</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Nominal</th>
                <th>Status Pembayaran</th>
                <th>Tgl Bayar</th>
                <th>Terlambat</th>
                <th>Rusak/Hilang</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dendaData as $denda)
            <tr>
                <td>{{ $denda->id_denda }}</td>
                <td>{{ $denda->peminjaman->user->nama ?? 'N/A' }}</td>
                <td>{{ $denda->peminjaman->buku->judul ?? 'N/A' }}</td>
                <td>Rp. {{ number_format($denda->nominal_denda, 0, ',', '.') }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $denda->status_pembayaran)) }}</td>
                <td>{{ $denda->tanggal_bayar ? $denda->tanggal_bayar->format('d M Y') : '-' }}</td>
                <td>{{ $denda->is_terlambat ? 'Ya' : 'Tidak' }}</td>
                <td>{{ $denda->is_rusak ? 'Ya' : 'Tidak' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data denda sesuai filter.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ Carbon\Carbon::now()->format('d M Y H:i:s') }}
    </div>
</body>

</html>