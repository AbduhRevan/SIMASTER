<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <title>Laporan Detail Server</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 10px;
        }
        
         .page-number {
            position: fixed;
            bottom: 10mm;
            right: 10mm;
            font-size: 10pt;
            color: #666;
        }
        
        .page-number:after {
            content: counter(page);
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #800000;
            color: #800000;
            page-break-after: avoid;
        }

        .kop-surat h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            color: #800000; 
        }

        .kop-surat p {
            font-size: 9pt;
            margin: 2px 0;
            color: #000; 
        }

        .kop-surat .tanggal {
            font-size: 8pt;
            font-style: italic;
            margin-top: 4px;
            color: #000;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 7pt;
            page-break-inside: auto;
        }

        .data-table thead {
            display: table-header-group;
        }

        .data-table thead tr {
            background-color: #800000;
            color: #fff;
            page-break-inside: avoid;
            page-break-after: avoid;
        }

        .data-table th {
            padding: 5px 3px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #666;
            font-size: 7.5pt;
            line-height: 1.2;
        }

        .data-table tbody tr {
            border-bottom: 1px solid #ddd;
            page-break-inside: avoid;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data-table td {
            padding: 4px 3px;
            border: 1px solid #ddd;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        .data-table td.no {
            width: 3%;
            text-align: center;
            font-size: 8pt;
        }

        .data-table td.nama {
            width: 10%;
            font-size: 7pt;
        }

        .data-table td.brand {
            width: 6%;
            font-size: 7pt;
        }

        .data-table td.spek {
            width: 28%;
            font-size: 6pt;
            line-height: 1.2;
        }

        .data-table td.lokasi {
            width: 8%;
            font-size: 7pt;
        }

        .data-table td.bidang-satker {
            width: 12%;
            font-size: 6.5pt;
        }

        .data-table td.website {
            width: 12%;
            font-size: 6pt;
        }

        .data-table td.status {
            width: 8%;
            text-align: center;
            font-size: 7pt;
        }

        .data-table td.keterangan {
            width: 13%;
            font-size: 6pt;
        }  
        
        /* Spesifikasi Styling */
        .spec-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 2px 0;
            border: 1px solid #aaa;
            table-layout: fixed; 
        }

        .spec-content table td {
            padding: 2px 4px;
            border: 1px solid #aaa;
            font-size: 6pt !important;
            word-wrap: break-word; 
            overflow-wrap: break-word;
            line-height: 1.2;
        }

        .spec-content table td:first-child {
            background-color: #e8e8e8;
            font-weight: bold;
            width: 35%;
        }

        .spec-content p {
            margin: 2px 0;
            font-size: 6pt !important;
            line-height: 1.2;
        }

        .spec-content ul, .spec-content ol {
            margin: 2px 0;
            padding-left: 12px;
            font-size: 6pt !important;
            line-height: 1.2;
        }

        .spec-content ul li, .spec-content ol li {
            margin-bottom: 1px;
        }

        /* Tambahan untuk semua elemen di dalam spec-content */
        .spec-content * {
            font-size: 6pt !important;
            line-height: 1.2;
        }
        
        
        /* Website List */
        .website-list {
            margin: 0;
            padding-left: 12px;
            font-size: 6pt;
            line-height: 1.2;
        }

        .website-list li {
            margin-bottom: 1px;
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

    <!-- PAGE NUMBER -->
    <div class="page-number"></div>

</body>
</html>