@extends('layouts.app')

@section('title', 'Satuan Kerja')

@section('content')
<div class="container-fluid py-3">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Search -->
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari Nama/Singkatan Satker...">
            </div>
        </div>

        <!-- Tombol Tambah -->
        <button class="btn btn-maroon px-4 text-white" data-bs-toggle="modal" data-bs-target="#tambahSatkerModal">
            <i class="fa-solid fa-plus me-2"></i> Tambah Satuan Kerja
        </button>
    </div>

    <!-- WRAPPER TABLE -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Satuan Kerja</th>
                    <th>Singkatan</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="satkerTableBody">
                @forelse ($satker as $index => $item)
                    <tr class="satker-row">
                        <td>{{ $satker->firstItem() + $index }}</td>
                        <td class="satker-nama">{{ $item->nama_satker }}</td>
                        <td class="satker-singkatan">{{ $item->singkatan_satker }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <!-- Edit -->
                                <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal"
                                    data-bs-target="#editSatkerModal{{ $item->satker_id }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <!-- Hapus -->
                                <button class="btn btn-danger btn-sm btn-hapus"
                                    data-id="{{ $item->satker_id }}"
                                    data-nama="{{ $item->nama_satker }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- MODAL EDIT -->
                    <div class="modal fade" id="editSatkerModal{{ $item->satker_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 rounded-3 overflow-hidden">
                                <div class="modal-header bg-maroon text-white">
                                    <h5 class="modal-title">Edit Satuan Kerja</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('superadmin.satker.update', $item->satker_id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Nama Satuan Kerja <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nama_satker"
                                                   value="{{ $item->nama_satker }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Singkatan <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="singkatan_satker"
                                                   value="{{ $item->singkatan_satker }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-maroon text-white">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr id="emptyRow">
                        <td colspan="4" class="text-center text-muted">Belum ada data satuan kerja</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Info -->
    @if ($satker->count() > 0)
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
            <p class="mb-0 text-secondary small">
                Menampilkan {{ $satker->firstItem() }}–{{ $satker->lastItem() }} dari {{ $satker->total() }} data
            </p>
            <div class="pagination-custom">
                {{ $satker->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="tambahSatkerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 overflow-hidden">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Tambah Satuan Kerja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.satker.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Satuan Kerja <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_satker') is-invalid @enderror" 
                               name="nama_satker" 
                               placeholder="Contoh: Pusat Data dan Informasi"
                               value="{{ old('nama_satker') }}"
                               required>
                        @error('nama_satker')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Singkatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('singkatan_satker') is-invalid @enderror" 
                               name="singkatan_satker" 
                               placeholder="Contoh: PUSDATIN"
                               value="{{ old('singkatan_satker') }}"
                               required>
                        @error('singkatan_satker')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL HAPUS -->
<div class="modal fade" id="hapusSatkerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 overflow-hidden">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusSatker" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <i class="fa-solid fa-triangle-exclamation fa-3x text-warning mb-3"></i>
                    <p>Apakah Anda yakin ingin menghapus satuan kerja <strong id="namaSatkerHapus"></strong>?</p>
                    <div class="alert alert-warning small mb-0">
                        Data akan dipindahkan ke Arsip Sementara dan dapat dipulihkan dalam waktu 30 hari sebelum dihapus permanen.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.table-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    padding: 25px;
}
.bg-maroon, .btn-maroon {
    background-color: #7b0000 !important;
    border: none;
}
.btn-maroon:hover {
    background-color: #5a0000 !important;
}
.btn-warning {
    background-color: #ffc107;
    border: none;
}
.btn-warning:hover {
    background-color: #ffcd39;
}
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.pagination-custom nav {
    display: flex;
    gap: 6px;
}
.pagination .page-item.active .page-link {
    background-color: #7b0000;
    border-color: #7b0000;
}
.pagination .page-link {
    color: #7b0000;
    border-radius: 8px;
}
.pagination .page-link:hover {
    background-color: #7b0000;
    color: white;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // ✅ SEARCH FUNCTIONALITY
    $('#searchInput').on('keyup', function() {
        const searchValue = $(this).val().toLowerCase();
        let visibleRows = 0;

        $('.satker-row').each(function() {
            const nama = $(this).find('.satker-nama').text().toLowerCase();
            const singkatan = $(this).find('.satker-singkatan').text().toLowerCase();
            
            if (nama.includes(searchValue) || singkatan.includes(searchValue)) {
                $(this).show();
                visibleRows++;
            } else {
                $(this).hide();
            }
        });

        // Tampilkan pesan jika tidak ada hasil
        if (visibleRows === 0 && $('.satker-row').length > 0) {
            if ($('#noResultRow').length === 0) {
                $('#satkerTableBody').append(
                    '<tr id="noResultRow"><td colspan="4" class="text-center text-muted">Tidak ada data yang sesuai dengan pencarian</td></tr>'
                );
            }
        } else {
            $('#noResultRow').remove();
        }
    });

    // ✅ MODAL HAPUS HANDLER
    $('.btn-hapus').on('click', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        
        $('#namaSatkerHapus').text(nama);
        $('#formHapusSatker').attr('action', /satker/soft-delete/${id});
        
        const modal = new bootstrap.Modal(document.getElementById('hapusSatkerModal'));
        modal.show();
    });

    // ✅ AUTO SHOW MODAL JIKA ADA ERROR VALIDASI
    @if($errors->any())
        var tambahModal = new bootstrap.Modal(document.getElementById('tambahSatkerModal'));
        tambahModal.show();
    @endif
});
</script>
@endpush
@endsection