<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keluhan - SanitaCheck</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1a5c38;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #1a5c38;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0;
            font-size: 14px;
        }
        .info {
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #1a5c38;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-menunggu { background-color: #fff3cd; color: #856404; }
        .status-proses { background-color: #e8f0fe; color: #1a73e8; }
        .status-selesai { background-color: #d4edda; color: #155724; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keluhan Fasilitas</h1>
        <p>Sistem Sanitasi Kampus Terintegrasi - SanitaCheck</p>
    </div>

    <div class="info">
        <p><strong>Tanggal Cetak:</strong> {{ date('d M Y H:i') }}</p>
        <p><strong>Total Laporan:</strong> {{ count($data) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">ID</th>
                <th style="width: 18%;">Fasilitas</th>
                <th style="width: 15%;">Lokasi</th>
                <th style="width: 12%;">Pelapor</th>
                <th style="width: 25%;">Catatan</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 8%;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            <tr>
                <td>{{ $item['ID'] }}</td>
                <td>{{ $item['Fasilitas'] }}</td>
                <td>{{ $item['Lokasi'] }}</td>
                <td>{{ $item['Pelapor'] }}</td>
                <td>{{ $item['Catatan'] }}</td>
                <td>
                    <span class="status-badge status-{{ strtolower($item['Status']) }}">
                        {{ $item['Status'] }}
                    </span>
                </td>
                <td>{{ $item['Tanggal'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">Belum ada data laporan keluhan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #1a5c38; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="bi bi-printer"></i> Cetak
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>
</body>
</html>
