<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Laporan Detail Website</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #000;
            margin: 15px;
        }
        
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #800000;
            position: relative;
        }
        
        .kop-surat .logo {
            position: absolute;
            left: 0;
            height: 60px;
            width: auto;
        }
        
        .kop-surat .header-text {
            text-align: center;
            flex: 1;
        }
        
        .kop-surat .header-text h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            color: #800000;
        }
        
        .kop-surat .header-text p {
            font-size: 10pt;
            margin: 2px 0;
            color: #000;
        }
        
        .kop-surat .header-text .tanggal {
            font-size: 9pt;
            font-style: italic;
            margin-top: 5px;
            color: #000;
        }
        
        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8pt;
        }
        
        .data-table thead tr {
            background-color: #800000;
            color: #fff;
        }
        
        .data-table th {
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #666;
            font-size: 9pt;
        }
        
        .data-table tbody tr {
            border-bottom: 1px solid #ddd;
        }
        
        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .data-table td {
            padding: 6px;
            border: 1px solid #ddd;
            vertical-align: top;
            word-wrap: break-word;
        }
        
        .data-table td.no {
            width: 3%;
            text-align: center;
        }
        
        .data-table td.nama {
            width: 12%;
        }
        
        .data-table td.url {
            width: 15%;
            font-size: 7pt;
        }
        
        .data-table td.status {
            width: 8%;
            text-align: center;
        }
        
        .data-table td.tahun {
            width: 8%;
            text-align: center;
        }
        
        .data-table td.server {
            width: 12%;
        }
        
        .data-table td.satker-bidang {
            width: 15%;
        }
        
        .data-table td.keterangan {
            width: 27%;
            font-size: 7pt;
        }
        
        /* Keterangan Styling */
        .keterangan-content {
            padding: 2px 0;
        }
        
        .keterangan-content p {
            margin: 3px 0;
            font-size: 7pt !important;
        }
        
        .keterangan-content ul, 
        .keterangan-content ol {
            margin: 3px 0;
            padding-left: 15px;
            font-size: 7pt !important;
        }
        
        .keterangan-content ul li,
        .keterangan-content ol li {
            margin-bottom: 2px;
        }
        
        .keterangan-content * {
            font-size: 7pt !important;
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
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <div class="kop-surat">
         <div class="header-text">
            <h1>Laporan Detail Website</h1>
            <p class="tanggal">Tanggal Cetak: {{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('DD MMMM YYYY, HH:mm') }} WIB</p>
        </div>
    </div>
    
    <!-- CONTENT -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="no">No</th>
                <th class="nama">Nama Website</th>
                <th class="url">URL</th>
                <th class="status">Status</th>
                <th class="tahun">Tahun Pengadaan</th>
                <th class="server">Server</th>
                <th class="satker-bidang">Satuan Kerja & Bidang</th>
                <th class="keterangan">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($websites as $index => $website)
            <tr>
                <td class="no">{{ $index + 1 }}</td>
                <td class="nama">{{ $website->nama_website }}</td>
                <td class="url">{{ $website->url }}</td>
                <td class="status">
                    @if($website->status === 'active')
                        <span class="status-badge status-aktif">AKTIF</span>
                    @elseif($website->status === 'maintenance')
                        <span class="status-badge status-maintenance">MAINTENANCE</span>
                    @else
                        <span class="status-badge status-tidak-aktif">TIDAK AKTIF</span>
                    @endif
                </td>
                <td class="tahun">{{ $website->tahun_pengadaan ?? '-' }}</td>
                <td class="server">{{ $website->server ? $website->server->nama_server : 'Belum terhubung' }}</td>
                <td class="satker-bidang">
                    <strong>Satker:</strong><br/>{{ $website->satker ? $website->satker->nama_satker : '-' }}<br/><br/>
                    <strong>Bidang:</strong><br/>{{ $website->bidang ? $website->bidang->nama_bidang : '-' }}
                </td>
                <td class="keterangan">
                    @if($website->keterangan)
                        <div class="keterangan-content">{!! $website->keterangan !!}</div>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 30px 0;">
                    <em>Tidak ada data website yang sesuai dengan filter</em>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- FOOTER -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari SIMASTER</p>
        <p>© {{ date('Y') }} Kementerian Pertahanan Republik Indonesia</p>
    </div>
</body>
</html>