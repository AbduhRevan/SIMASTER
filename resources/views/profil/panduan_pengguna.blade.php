@extends(App\Helpers\LayoutHelper::getUserLayout())

@section('title', 'Panduan Pengguna')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>
    .content {
        padding: 100px 40px 30px 40px !important;
    }

    .page-header {
        background: linear-gradient(135deg, #7b0000 0%, #a00000 100%);
        padding: 25px 35px;
        border-radius: 15px;
        color: white;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 15px rgba(123, 0, 0, 0.3);
    }

    .page-header h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn-manage {
        background: white;
        color: #7b0000;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all .3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .btn-manage:hover {
        background: #f8f9fa;
        color: #7b0000;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .panduan-container {
        display: flex;
        gap: 25px;
        align-items: flex-start;
    }

    .sidebar-kategori {
        width: 300px;
        background: white;
        border-radius: 15px;
        padding: 0;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        height: fit-content;
        position: sticky;
        top: 120px;
    }

    .sidebar-header {
        background: linear-gradient(135deg, #7b0000 0%, #a00000 100%);
        color: white;
        padding: 20px;
        border-radius: 15px 15px 0 0;
        font-weight: 700;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-content {
        padding: 15px;
    }

    .kategori-item {
        padding: 14px 18px;
        margin-bottom: 8px;
        border-radius: 10px;
        cursor: pointer;
        transition: all .3s;
        text-decoration: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #333;
        border: 2px solid transparent;
    }

    .kategori-item:hover {
        background: #f8f9fa;
        color: #7b0000;
        border-color: #7b0000;
        transform: translateX(5px);
    }

    .kategori-item.active {
        background: #7b0000;
        color: white;
        border-color: #7b0000;
        font-weight: 600;
    }

    .kategori-item .badge {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .kategori-item.active .badge {
        background: rgba(255,255,255,0.3);
    }

    .konten-panduan {
        flex: 1;
        background: white;
        border-radius: 15px;
        padding: 35px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        min-height: 500px;
    }

    .konten-header {
        border-bottom: 3px solid #7b0000;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .konten-header h3 {
        color: #7b0000;
        margin: 0 0 10px 0;
        font-size: 26px;
        font-weight: 700;
    }

    .konten-header p {
        color: #6c757d;
        margin: 0;
        font-size: 15px;
    }

    .item-panduan {
        margin-bottom: 15px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        transition: all .3s;
    }

    .item-panduan:hover {
        border-color: #7b0000;
        box-shadow: 0 3px 12px rgba(123, 0, 0, 0.1);
    }

    .item-header {
        background: #f8f9fa;
        padding: 18px 24px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all .3s;
    }

    .item-header:hover {
        background: #e9ecef;
    }

    .item-header h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 600;
        color: #333;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .item-header .chevron {
        color: #7b0000;
        transition: transform .3s;
        font-size: 18px;
    }

    .item-header .chevron.rotate {
        transform: rotate(90deg);
    }

    .item-body {
        padding: 25px;
        display: none;
        background: white;
        line-height: 1.8;
    }

    .item-body.show {
        display: block;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .item-body ul, .item-body ol {
        padding-left: 25px;
        margin: 15px 0;
    }

    .item-body li {
        margin-bottom: 10px;
    }

    .item-body p {
        margin-bottom: 15px;
    }

    .item-body strong {
        color: #7b0000;
    }

    .alert-empty {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .alert-empty i {
        font-size: 64px;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .alert-empty p {
        font-size: 16px;
        margin: 0;
    }

    .alert-info {
        background: #e7f3ff;
        border: 2px solid #2196F3;
        color: #0d47a1;
        padding: 20px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .alert-info i {
        font-size: 24px;
    }

    /* ==================== KELOLA ITEM TABLE STYLES ==================== */
    
    /* Container Tabel */
    .table-responsive {
        background: white;
        border-radius: 15px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        overflow-x: auto;
    }

    /* Tabel */
    .table {
        margin-bottom: 0;
        width: 100%;
    }

    .table thead th {
        background: linear-gradient(135deg, #7b0000 0%, #a00000 100%);
        color: white;
        font-weight: 600;
        padding: 16px 12px;
        border: none;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 16px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Kolom No - Lebih kecil */
    .table thead th:nth-child(1),
    .table tbody td:nth-child(1) {
        width: 50px;
        text-align: center;
    }

    /* Kolom Kategori */
    .table thead th:nth-child(2),
    .table tbody td:nth-child(2) {
        width: 150px;
    }

    /* Kolom Judul */
    .table thead th:nth-child(3),
    .table tbody td:nth-child(3) {
        width: 200px;
    }

    /* Kolom Konten */
    .table thead th:nth-child(4),
    .table tbody td:nth-child(4) {
        min-width: 250px;
        max-width: 350px;
    }

    /* Kolom Urutan */
    .table thead th:nth-child(5),
    .table tbody td:nth-child(5) {
        width: 80px;
        text-align: center;
    }

    /* Kolom Status */
    .table thead th:nth-child(6),
    .table tbody td:nth-child(6) {
        width: 100px;
        text-align: center;
    }

    /* Kolom Aksi - Lebih lebar untuk menampung tombol vertikal */
    .table thead th:nth-child(7),
    .table tbody td:nth-child(7) {
        width: 130px;
        text-align: center;
        padding: 12px 8px;
    }

    /* Badge Kategori */
    .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    /* Badge Status */
    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }

    /* Badge Urutan */
    .badge-info {
        background-color: #17a2b8;
        color: white;
        font-size: 14px;
        padding: 4px 10px;
        border-radius: 50%;
        min-width: 28px;
        display: inline-block;
    }

    /* Konten yang terpotong */
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.4;
    }

    /* Action Buttons Container - DIPERBAIKI */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: stretch;
        justify-content: center;
    }

    /* Button Styling - DIPERBAIKI */
    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        font-weight: 500;
        text-decoration: none;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
        color: #000;
    }

    .btn-info {
        background-color: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background-color: #138496;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        color: white;
    }

    .btn i {
        font-size: 12px;
    }

    .text-center {
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 1400px) {
        .table thead th:nth-child(4),
        .table tbody td:nth-child(4) {
            max-width: 250px;
        }
    }

    @media (max-width: 1200px) {
        .action-buttons {
            flex-direction: row;
            flex-wrap: wrap;
            gap: 4px;
        }
        
        .btn-sm {
            min-width: auto;
            padding: 5px 8px;
            width: auto;
        }
        
        .btn-sm span {
            display: none;
        }
        
        .btn-sm i {
            margin: 0;
        }
    }

    @media (max-width: 992px) {
        .panduan-container {
            flex-direction: column;
        }

        .sidebar-kategori {
            width: 100%;
            position: relative;
            top: 0;
        }
        
        .table {
            font-size: 13px;
        }
        
        .table thead th,
        .table tbody td {
            padding: 12px 8px;
        }
    }
</style>

<div class="page-header">
    <h2>
        <i class="fas fa-book"></i>
        Panduan Pengguna SIMASTER
    </h2>
    @if(auth()->user()->role === 'superadmin')
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('superadmin.panduan.kategori.index') }}" class="btn-manage">
                <i class="fas fa-folder"></i>
                Kelola Kategori
            </a>
            <a href="{{ route('superadmin.panduan.item.index') }}" class="btn-manage">
                <i class="fas fa-edit"></i>
                Kelola Item
            </a>
        </div>
    @endif
</div>

@if($kategoriList->isEmpty() || !$kategoriAktif)
    <div class="alert-info">
        <i class="fas fa-info-circle"></i>
        <div>
            <strong>Belum ada panduan yang tersedia.</strong>
            @if(auth()->user()->role === 'superadmin')
                <br>Silakan tambahkan kategori dan item panduan terlebih dahulu.
            @else
                <br>Silakan hubungi administrator untuk menambahkan panduan.
            @endif
        </div>
    </div>
@else
    <div class="panduan-container">
        <!-- Sidebar Kategori -->
        <div class="sidebar-kategori">
            <div class="sidebar-header">
                <i class="fas fa-list"></i>
                Kategori Panduan
            </div>
            <div class="sidebar-content">
                @foreach($kategoriList as $kategori)
                    <a href="{{ route('panduan.pengguna', $kategori->slug) }}" 
                       class="kategori-item {{ $kategoriAktif->id == $kategori->id ? 'active' : '' }}">
                        <span>{{ $kategori->nama_kategori }}</span>
                        <span class="badge">
                            {{ $kategori->items()->where('is_active', true)->count() }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Konten Panduan -->
        <div class="konten-panduan">
            <div class="konten-header">
                <h3>{{ $kategoriAktif->nama_kategori }}</h3>
                <p>{{ $kategoriAktif->deskripsi }}</p>
            </div>

            @if($items->isEmpty())
                <div class="alert-empty">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada panduan untuk kategori ini.</p>
                    @if(auth()->user()->role === 'superadmin')
                        <a href="{{ route('superadmin.panduan.item.index') }}" class="btn-manage mt-3">
                            <i class="fas fa-plus"></i>
                            Tambah Item Panduan
                        </a>
                    @endif
                </div>
            @else
                @foreach($items as $index => $item)
                    <div class="item-panduan">
                        <div class="item-header" onclick="toggleItem({{ $item->id }})">
                            <h5>
                                <i class="fas fa-chevron-right chevron" id="icon-{{ $item->id }}"></i>
                                {{ $item->judul }}
                            </h5>
                        </div>
                        <div class="item-body {{ $index == 0 ? 'show' : '' }}" id="body-{{ $item->id }}">
                            {!! $item->konten !!}
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endif

<script>
    function toggleItem(id) {
        const body = document.getElementById('body-' + id);
        const icon = document.getElementById('icon-' + id);
        
        // Toggle show class
        body.classList.toggle('show');
        
        // Toggle icon rotation
        icon.classList.toggle('rotate');
    }

    // Auto-expand dan rotate icon untuk item pertama
    document.addEventListener('DOMContentLoaded', function() {
        const firstIcon = document.querySelector('.item-panduan:first-child .chevron');
        if (firstIcon) {
            firstIcon.classList.add('rotate');
        }
    });
</script>

@endsection