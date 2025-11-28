@extends(App\Helpers\LayoutHelper::getUserLayout())

@section('title', 'Kelola Item Panduan')

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
        border: none;
        cursor: pointer;
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

    .table tbody td:last-child {
        white-space: nowrap;
        text-align: center;
    }

    .btn-action {
        margin: 0 2px;
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: .2s;
        display: inline-block;
        vertical-align: middle;
    }

    .btn-edit {
        background: #ffc107;
        color: white;
    }

    .btn-edit:hover {
        background: #e0a800;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    .btn-toggle {
        background: #17a2b8;
        color: white;
    }

    .btn-toggle:hover {
        background: #138496;
    }

    td form {
        display: inline-block;
        margin: 0;
        padding: 0;
    }

    .modal-header {
        background: #7b0000;
        color: white;
        border-bottom: none;
    }

    .modal-lg {
        max-width: 900px;
    }

    .modal-body {
        padding: 30px;
    }

    .modal-body .mb-3 {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
    }

    .form-control, .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all .3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #7b0000;
        box-shadow: 0 0 0 0.2rem rgba(123, 0, 0, 0.15);
        outline: none;
    }

    .form-control::placeholder {
        color: #999;
        font-style: italic;
    }

    /* Textarea khusus untuk konten */
 textarea.form-control {
    min-height: 200px;
    resize: vertical;
    line-height: 1.6;
}

    .text-muted {
        color: #6c757d;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .modal-footer {
        border-top: 1px solid #e0e0e0;
        padding: 15px 30px;
    }

    .modal-footer .btn {
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .btn-primary {
        background: #7b0000;
        border: none;
    }

    .btn-primary:hover {
        background: #5a0000;
    }

    .kategori-badge {
        background: #6c757d;
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
    }

    /* Character counter */
    .char-counter {
        text-align: right;
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }

    .char-counter.warning {
        color: #ffc107;
    }

    .char-counter.danger {
        color: #dc3545;
    }
</style>

<div class="page-header">
    <h2><i class="fas fa-list-ul me-2"></i> Kelola Item Panduan</h2>
    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-2"></i> Tambah Item
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
                <th width="18%">Kategori</th>
                <th width="22%">Judul</th>
                <th width="28%">Konten</th>
                <th width="7%">Urutan</th>
                <th width="10%">Status</th>
                <th width="10%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <span class="kategori-badge">
                            {{ $item->kategoriPanduan->nama_kategori }}
                        </span>
                    </td>
                    <td><strong>{{ $item->judul }}</strong></td>
                    <td>{{ Str::limit(strip_tags($item->konten), 60) }}</td>
                    <td><span class="badge bg-secondary">{{ $item->urutan }}</span></td>
                    <td>
                        <span class="badge-{{ $item->is_active ? 'active' : 'inactive' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <button class="btn-action btn-edit" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEdit{{ $item->id }}"
                            type="button">
                            <i class="fas fa-edit"></i>
                        </button>
                        
                        <form action="{{ route('superadmin.panduan.item.toggle', $item->id) }}" 
                            method="POST" style="display:inline;">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn-action btn-toggle">
                                <i class="fas fa-toggle-{{ $item->is_active ? 'on' : 'off' }}"></i>
                            </button>
                        </form>

                        <form action="{{ route('superadmin.panduan.item.delete', $item->id) }}" 
                            method="POST" 
                            style="display:inline;"
                            onsubmit="return confirm('Yakin ingin menghapus item ini?')">
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
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Item Panduan</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('superadmin.panduan.item.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-folder me-1"></i> Kategori <span style="color: red;">*</span></label>
                                        <select name="kategori_panduan_id" class="form-select" required>
                                            @foreach($kategori as $kat)
                                                <option value="{{ $kat->id }}" 
                                                    {{ $item->kategori_panduan_id == $kat->id ? 'selected' : '' }}>
                                                    {{ $kat->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-heading me-1"></i> Judul <span style="color: red;">*</span></label>
                                        <input type="text" name="judul" class="form-control" 
                                            value="{{ $item->judul }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-align-left me-1"></i> Konten <span style="color: red;">*</span></label>
                                        <textarea name="konten" 
                                            class="form-control konten-textarea" 
                                            placeholder="Masukkan konten panduan di sini..."
                                            required>{{ $item->konten }}</textarea>
                                        <small class="text-muted">Tulis konten panduan dengan jelas dan detail</small>
                                        <div class="char-counter" data-counter-for="konten">0 karakter</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-sort-numeric-up me-1"></i> Urutan <span style="color: red;">*</span></label>
                                        <input type="number" name="urutan" class="form-control" 
                                            value="{{ $item->urutan }}" required min="0">
                                        <small class="text-muted">Urutan tampilan dalam kategori (angka lebih kecil tampil lebih dulu)</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i> Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada item panduan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Tambah Item Panduan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.panduan.item.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-folder me-1"></i> Kategori <span style="color: red;">*</span></label>
                        <select name="kategori_panduan_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-heading me-1"></i> Judul <span style="color: red;">*</span></label>
                        <input type="text" name="judul" class="form-control" 
                            placeholder="Contoh: Apa itu SIMASTER?" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-align-left me-1"></i> Konten <span style="color: red;">*</span></label>
                        <textarea name="konten" 
                            id="kontenTambah"
                            class="form-control konten-textarea" 
                            placeholder="Masukkan konten panduan di sini..."
                            required></textarea>
                        <small class="text-muted">Tulis konten panduan dengan jelas dan detail</small>
                        <div class="char-counter" data-counter-for="kontenTambah">0 karakter</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-sort-numeric-up me-1"></i> Urutan <span style="color: red;">*</span></label>
                        <input type="number" name="urutan" class="form-control" 
                            value="0" required min="0">
                        <small class="text-muted">Urutan tampilan dalam kategori (angka lebih kecil tampil lebih dulu)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    
    // Character counter function
    function updateCharCounter(textarea) {
        var $textarea = $(textarea);
        var length = $textarea.val().length;
        var $counter = $textarea.closest('.mb-3').find('.char-counter');
        
        $counter.text(length + ' karakter');
        
        // Color coding
        if (length > 5000) {
            $counter.removeClass('warning').addClass('danger');
        } else if (length > 3000) {
            $counter.removeClass('danger').addClass('warning');
        } else {
            $counter.removeClass('warning danger');
        }
    }
    
    // Initialize character counter for all textareas
    $('.konten-textarea').each(function() {
        updateCharCounter(this);
    });
    
    // Update counter on input
    $(document).on('input', '.konten-textarea', function() {
        updateCharCounter(this);
    });
    
    // Update counter when modal opens (for edit)
    $('[id^="modalEdit"]').on('shown.bs.modal', function() {
        $(this).find('.konten-textarea').each(function() {
            updateCharCounter(this);
        });
    });
    
    // Reset form when modal Tambah closes
    $('#modalTambah').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $('#kontenTambah').val('');
        updateCharCounter($('#kontenTambah'));
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        var $form = $(this);
        var $konten = $form.find('textarea[name="konten"]');
        
        if ($konten.length) {
            var content = $konten.val().trim();
            
            if (content.length === 0) {
                e.preventDefault();
                alert('Konten tidak boleh kosong!');
                $konten.focus();
                return false;
            }
            
            if (content.length < 10) {
                e.preventDefault();
                alert('Konten terlalu pendek! Minimal 10 karakter.');
                $konten.focus();
                return false;
            }
        }
    });
    
    // Auto-resize textarea
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }
    
    // Apply auto-resize on input
    $(document).on('input', '.konten-textarea', function() {
        autoResize(this);
    });
    
    // Initialize auto-resize on modal open
    $('[id^="modalEdit"], #modalTambah').on('shown.bs.modal', function() {
        $(this).find('.konten-textarea').each(function() {
            autoResize(this);
        });
    });
});
</script>

@endsection