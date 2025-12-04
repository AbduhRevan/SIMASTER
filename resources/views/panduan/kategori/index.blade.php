@extends(App\Helpers\LayoutHelper::getUserLayout())

@section('title', 'Kelola Kategori Panduan')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>
    .content {
        padding: 100px 40px 30px 40px !important;
    }

    .page-header {
        background: #7b0000;
        padding: 20px 30px;
        border-radius: 15px;
        color: white;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .btn-add {
        background: white;
        color: #7b0000;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: .2s;
    }

    .btn-add:hover {
        background: #f0f0f0;
        color: #7b0000;
    }

    .table-container {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.07);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead {
        background: #f8f9fa;
    }

    .badge-active {
        background: #28a745;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
    }

    .badge-inactive {
        background: #6c757d;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
    }

    .btn-action {
        margin: 0 3px;
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: .2s;
    }

    .btn-edit {
        background: #ffc107;
        color: white;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-toggle {
        background: #17a2b8;
        color: white;
    }

    .modal-header {
        background: #7b0000;
        color: white;
    }
</style>

<div class="page-header">
    <h2><i class="fas fa-folder-open me-2"></i> Kelola Kategori Panduan</h2>
    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-2"></i> Tambah Kategori
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="table-container">
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Kategori</th>
                <th width="15%">Slug</th>
                <th width="30%">Deskripsi</th>
                <th width="8%">Urutan</th>
                <th width="10%">Status</th>
                <th width="12%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $item->nama_kategori }}</strong></td>
                    <td><code>{{ $item->slug }}</code></td>
                    <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                    <td><span class="badge bg-secondary">{{ $item->urutan }}</span></td>
                    <td>
                        <span class="badge-{{ $item->is_active ? 'active' : 'inactive' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <button class="btn-action btn-edit" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEdit{{ $item->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        
                        <form action="{{ route('superadmin.panduan.kategori.toggle', $item->id) }}" 
                            method="POST" style="display:inline;">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn-action btn-toggle">
                                <i class="fas fa-toggle-{{ $item->is_active ? 'on' : 'off' }}"></i>
                            </button>
                        </form>

                        <form action="{{ route('superadmin.panduan.kategori.delete', $item->id) }}" 
                            method="POST" 
                            style="display:inline;"
                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Kategori Panduan</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('superadmin.panduan.kategori.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Kategori</label>
                                        <input type="text" name="nama_kategori" class="form-control" 
                                            value="{{ $item->nama_kategori }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control summernote" rows="3">{!! $item->deskripsi !!}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Urutan</label>
                                        <input type="number" name="urutan" class="form-control" 
                                            value="{{ $item->urutan }}" required min="0">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada kategori panduan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori Panduan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.panduan.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" 
                            placeholder="Contoh: Informasi Umum" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control summernote" rows="3" 
                            placeholder="Deskripsi kategori (opsional)"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="urutan" class="form-control" 
                            value="0" required min="0">
                        <small class="text-muted">Urutan tampilan kategori (angka lebih kecil tampil lebih dulu)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Summernote CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

<script>
$(document).ready(function () {

    // Inisialisasi Summernote
    $('.summernote').summernote({
        height: 150,
        tabsize: 2,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
    });

    // Refresh ketika modal dibuka
    $('.modal').on('shown.bs.modal', function () {
        $(this).find('.summernote').summernote('refresh');
    });

});
</script>

@endsection
