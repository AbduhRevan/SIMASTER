<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Detail Server</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            margin: 20px;
        }
        
        .kop-surat {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #000;
        }
        
        .kop-surat h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        
        .kop-surat p {
            font-size: 10pt;
            margin: 2px 0;
        }
        
        .kop-surat .tanggal {
            font-size: 9pt;
            font-style: italic;
            margin-top: 5px;
        }
        
        /* Server Section */
        .server-section {
            margin-bottom: 20px;
        }
        
        .server-number {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 8px;
            padding: 5px 10px;
            background-color: #f0f0f0;
            border-left: 4px solid #000;
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
        
        /* Spesifikasi Styling */
        .spec-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            border: 1px solid #666;
        }
        
        .spec-content table td {
            padding: 6px 10px;
            border: 1px solid #666;
            font-size: 10pt;
        }
        
        .spec-content table td:first-child {
            background-color: #e8e8e8;
            font-weight: bold;
            width: 40%;
        }
        
        .spec-content p {
            margin: 5px 0;
        }
        
        .spec-content ul, .spec-content ol {
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
        
        /* Website List */
        .website-list {
            margin: 0;
            padding-left: 25px;
        }
        
        .website-list li {
            margin-bottom: 4px;
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
        <h1>Laporan Detail Server</h1>
        <p><strong>SIMASTER</strong></p>
        <p>Sistem Informasi Manajemen Aset Terpadu</p>
        <p class="tanggal">Tanggal Cetak: {{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('DD MMMM YYYY, HH:mm') }} WIB</p>
    </div>
    
    <!-- CONTENT -->
    @forelse($servers as $index => $server)
    <div class="server-section">
        <div class="server-number">{{ $index + 1 }}. {{ strtoupper($server->nama_server) }}</div>
        
        <table class="info-table">
            <tr>
                <td class="label">Nama Server</td>
                <td class="value">{{ $server->nama_server }}</td>
            </tr>
            <tr>
                <td class="label">Brand</td>
                <td class="value">{{ $server->brand ?? '-' }}</td>
            </tr>
            @if($server->spesifikasi)
            <tr>
                <td class="label">Spesifikasi</td>
                <td class="value">
                    <div class="spec-content">{!! $server->spesifikasi !!}</div>
                </td>
            </tr>
            @endif
            <tr>
                <td class="label">Rak</td>
                <td class="value">{{ $server->rak ? $server->rak->nomor_rak : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Slot</td>
                <td class="value">{{ $server->u_slot ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Bidang</td>
                <td class="value">{{ $server->bidang ? $server->bidang->nama_bidang : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Satuan Kerja</td>
                <td class="value">{{ $server->satker ? $server->satker->nama_satker : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Website Terhubung</td>
                <td class="value">
                    @if($server->websites && $server->websites->count() > 0)
                        <ul class="website-list">
                            @foreach($server->websites as $website)
                                <li>{{ $website->nama_website }} ({{ $website->url }})</li>
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td class="value">
                    @if($server->power_status === 'ON')
                        <span class="status-badge status-aktif">AKTIF</span>
                    @elseif($server->power_status === 'STANDBY')
                        <span class="status-badge status-maintenance">MAINTENANCE</span>
                    @else
                        <span class="status-badge status-tidak-aktif">TIDAK AKTIF</span>
                    @endif
                </td>
            </tr>
            @if($server->keterangan)
            <tr>
                <td class="label">Keterangan</td>
                <td class="value">
                    <div class="spec-content">{!! $server->keterangan !!}</div>
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
        <p><em>Tidak ada data server yang sesuai dengan filter</em></p>
    </div>
    @endforelse
    
    <!-- FOOTER -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari SIMASTER - Sistem Informasi Manajemen Aset Terpadu</p>
        <p>© {{ date('Y') }} Kementerian Pertahanan Republik Indonesia</p>
    </div>
</body>
</html>