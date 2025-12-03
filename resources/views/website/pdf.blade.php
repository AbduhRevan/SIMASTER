<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Laporan Detail Website</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            margin: 20px;
        }
        
        .kop-surat {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #800000;
        }
        
        .kop-surat h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            color: #800000;
        }
        
        .kop-surat p {
            font-size: 10pt;
            margin: 2px 0;
            color: #000;
        }
        
        .kop-surat .tanggal {
            font-size: 9pt;
            font-style: italic;
            margin-top: 5px;
            color: #000;
        }
        
        /* Website Section */
        .website-section {
            margin-bottom: 20px;
        }
        
        .website-number {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 8px;
            padding: 6px 12px;
            background-color: #800000;
            color: #fff;
            border-radius: 3px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .info-table tr {
            border-bottom: 1px solid #ddd;
        }
        
        .info-table td {
            padding: 6px 10px;
            vertical-align: top;
        }
        
        .info-table td.label {
            width: 180px;
            font-weight: bold;
            color: #333;
        }
        
        .info-table td.value {
            color: #000;
        }
        
        /* Keterangan Styling */
        .keterangan-content {
            padding: 5px 0;
        }
        
        .keterangan-content p {
            margin: 5px 0;
        }
        
        .keterangan-content ul, 
        .keterangan-content ol {
            margin: 5px 0;
            padding-left: 25px;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10pt;
        }
        
        .status-aktif {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #155724;
        }
        
        .status-maintenance {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #856404;
        }
        
        .status-tidak-aktif {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #721c24;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9pt;
            color: #666;
            padding-top: 15px;
            border-top: 1px solid #ccc;
        }
        
        /* Separator Line */
        .separator {
            border-top: 2px solid #ccc;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <div class="kop-surat">
        <h1>Laporan Detail Website</h1>
        <p>Sistem Informasi Manajemen Aset Terpadu</p>
        <p class="tanggal">Tanggal Cetak: {{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('DD MMMM YYYY, HH:mm') }} WIB</p>
    </div>
    
    <!-- CONTENT -->
    @forelse($websites as $index => $website)
    <div class="website-section">
        <div class="website-number">{{ $index + 1 }}. {{ strtoupper($website->nama_website) }}</div>
        
        <table class="info-table">
            <tr>
                <td class="label">Nama Website</td>
                <td class="value">{{ $website->nama_website }}</td>
            </tr>
            <tr>
                <td class="label">URL</td>
                <td class="value">{{ $website->url }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td class="value">
                    @if($website->status === 'active')
                        <span class="status-badge status-aktif">AKTIF</span>
                    @elseif($website->status === 'maintenance')
                        <span class="status-badge status-maintenance">MAINTENANCE</span>
                    @else
                        <span class="status-badge status-tidak-aktif">TIDAK AKTIF</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Tahun Pengadaan</td>
                <td class="value">{{ $website->tahun_pengadaan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Server</td>
                <td class="value">{{ $website->server ? $website->server->nama_server : 'Belum terhubung' }}</td>
            </tr>
            <tr>
                <td class="label">Satuan Kerja</td>
                <td class="value">{{ $website->satker ? $website->satker->nama_satker : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Bidang</td>
                <td class="value">{{ $website->bidang ? $website->bidang->nama_bidang : '-' }}</td>
            </tr>
            @if($website->keterangan)
            <tr>
                <td class="label">Keterangan</td>
                <td class="value">
                    <div class="keterangan-content">{!! $website->keterangan !!}</div>
                </td>
            </tr>
            @endif
        </table>
    </div>
    
    @if(!$loop->last)
        <div class="separator"></div>
    @endif
    @empty
    <div style="text-align: center; padding: 50px 0;">
        <p><em>Tidak ada data website yang sesuai dengan filter</em></p>
    </div>
    @endforelse
    
    <!-- FOOTER -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari SIMASTER - Sistem Informasi Manajemen Aset Terpadu</p>
        <p>© {{ date('Y') }} Kementerian Pertahanan Republik Indonesia</p>
    </div>
</body>
</html>