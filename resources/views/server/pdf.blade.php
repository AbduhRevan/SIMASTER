<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <title>Laporan Detail Server</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #000;
            margin: 15px;
        }
        
        .kop-surat {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #800000; /* merah gelap */
            color: #800000; /* warna teks merah gelap */
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
            color: #000; /* merah gelap */
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
            width: 10%;
        }

        .data-table td.brand {
            width: 8%;
        }

        .data-table td.spek {
            width: 25%;
            font-size: 7pt;
        }

        .data-table td.lokasi {
            width: 10%;
        }

        .data-table td.bidang-satker {
            width: 12%;
        }

        .data-table td.website {
            width: 12%;
            font-size: 7pt;
        }

        .data-table td.status {
            width: 8%;
            text-align: center;
        }

        .data-table td.keterangan {
            width: 12%;
            font-size: 7pt;
        }  
        
        /* Spesifikasi Styling */
        .spec-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
            border: 1px solid #999;
            table-layout: fixed; 
        }

        .spec-content table td {
            padding: 3px 5px;
            border: 1px solid #999;
            font-size: 7pt !important;
            word-wrap: break-word; 
            overflow-wrap: break-word; 
        }

        .spec-content table td:first-child {
            background-color: #e8e8e8;
            font-weight: bold;
            width: 40%;
        }

        .spec-content p {
            margin: 3px 0;
            font-size: 7pt !important;
        }

        .spec-content ul, .spec-content ol {
            margin: 3px 0;
            padding-left: 15px;
            font-size: 7pt !important;
        }

        .spec-content ul li, .spec-content ol li {
            margin-bottom: 2px;
        }

        /* Tambahan untuk semua elemen di dalam spec-content */
        .spec-content * {
            font-size: 7pt !important;
        }
        
        
        /* Website List */
        .website-list {
            margin: 0;
            padding-left: 15px;
            font-size: 7pt;
        }

        .website-list li {
            margin-bottom: 2px;
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
        <h1>Laporan Detail Server</h1>
        <p class="tanggal">Tanggal Cetak: {{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('DD MMMM YYYY, HH:mm') }} WIB</p>
    </div>
    
    <!-- CONTENT -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="no">No</th>
                <th class="nama">Nama Server</th>
                <th class="brand">Brand</th>
                <th class="spek">Spesifikasi</th>
                <th class="lokasi">Lokasi<br/>(Rak/Slot)</th>
                <th class="bidang-satker">Bidang & Satker</th>
                <th class="website">Website Terhubung</th>
                <th class="status">Status</th>
                <th class="keterangan">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($servers as $index => $server)
            <tr>
                <td class="no">{{ $index + 1 }}</td>
                <td class="nama">{{ $server->nama_server }}</td>
                <td class="brand">{{ $server->brand ?? '-' }}</td>
                <td class="spek">
                    @if($server->spesifikasi)
                        <div class="spec-content">{!! $server->spesifikasi !!}</div>
                    @else
                        -
                    @endif
                </td>
                <td class="lokasi">
                    <strong>Rak:</strong> {{ $server->rak ? $server->rak->nomor_rak : '-' }}<br/>
                    <strong>Slot:</strong> {{ $server->u_slot ?? '-' }}
                </td>
                <td class="bidang-satker">
                    <strong>Bidang:</strong><br/>{{ $server->bidang ? $server->bidang->nama_bidang : '-' }}<br/><br/>
                    <strong>Satker:</strong><br/>{{ $server->satker ? $server->satker->nama_satker : '-' }}
                </td>
                <td class="website">
                    @if($server->websites && $server->websites->count() > 0)
                        <ul class="website-list">
                            @foreach($server->websites as $website)
                                <li>{{ $website->nama_website }}<br/>({{ $website->url }})</li>
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
                </td>
                <td class="status">
                    @if($server->power_status === 'ON')
                        <span class="status-badge status-aktif">AKTIF</span>
                    @elseif($server->power_status === 'STANDBY')
                        <span class="status-badge status-maintenance">MAINTENANCE</span>
                    @else
                        <span class="status-badge status-tidak-aktif">TIDAK AKTIF</span>
                    @endif
                </td>
                <td class="keterangan">
                    @if($server->keterangan)
                        <div class="spec-content">{!! $server->keterangan !!}</div>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 30px 0;">
                    <em>Tidak ada data server yang sesuai dengan filter</em>
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