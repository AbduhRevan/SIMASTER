@extends('layouts.app')

@section('title', 'Bidang')

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

<!-- 🟥 HEADER -->
<div class="mb-4">
    <h4 class="fw-bold text-dark">Data Master Bidang</h4>
</div>

<div class="card table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Search -->
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari Nama/Singkatan Bidang...">
            </div>
        </div>

        <!-- Tombol Tambah -->
        <button class="btn btn-maroon px-4 text-white" data-bs-toggle="modal" data-bs-target="#tambahBidangModal">
            <i class="fa-solid fa-plus me-2"></i> Tambah Bidang
        </button>
    </div>

    <!-- TABLE -->
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Bidang</th>
                <th>Singkatan</th>
                <th class="text-center" style="width: 100px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="bidangTableBody">
            @forelse ($bidang as $index => $item)
                <tr class="bidang-row">
                    <td>{{ $index + 1 }}</td>
                    <td class="bidang-nama">{{ $item->nama_bidang }}</td>
                    <td class="bidang-singkatan">{{ $item->singkatan_bidang }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <!-- Edit -->
                            <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal"
                                data-bs-target="#editBidangModal{{ $item->bidang_id }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Hapus -->
                            <button class="btn btn-danger btn-sm btn-hapus"
                                data-id="{{ $item->bidang_id }}"
                                data-nama="{{ $item->nama_bidang }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- MODAL EDIT -->
                <div class="modal fade" id="editBidangModal{{ $item->bidang_id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-3 overflow-hidden">
                            <div class="modal-header bg-maroon text-white">
                                <h5 class="modal-title">Edit Bidang</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('superadmin.bidang.update', $item->bidang_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nama Bidang <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_bidang" value="{{ $item->nama_bidang }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Singkatan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="singkatan_bidang" value="{{ $item->singkatan_bidang }}" required>
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
                    <td colspan="4" class="text-center text-muted">Belum ada data bidang</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="tambahBidangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 overflow-hidden">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Tambah Bidang</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.bidang.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Bidang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_bidang') is-invalid @enderror" 
                               name="nama_bidang" 
                               placeholder="Contoh: Bidang Infrastruktur" 
                               value="{{ old('nama_bidang') }}"
                               required>
                        @error('nama_bidang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Singkatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('singkatan_bidang') is-invalid @enderror" 
                               name="singkatan_bidang" 
                               placeholder="Contoh: BI" 
                               value="{{ old('singkatan_bidang') }}"
                               required>
                        @error('singkatan_bidang')
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
<div class="modal fade" id="hapusBidangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 overflow-hidden">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusBidang" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <i class="fa-solid fa-triangle-exclamation fa-3x text-warning mb-3"></i>
                    <p>Apakah Anda yakin ingin menghapus bidang <strong id="namaBidangHapus"></strong>?</p>
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
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // ✅ SEARCH FUNCTIONALITY
    $('#searchInput').on('keyup', function() {
        const searchValue = $(this).val().toLowerCase();
        let visibleRows = 0;

        $('.bidang-row').each(function() {
            const nama = $(this).find('.bidang-nama').text().toLowerCase();
            const singkatan = $(this).find('.bidang-singkatan').text().toLowerCase();
            
            if (nama.includes(searchValue) || singkatan.includes(searchValue)) {
                $(this).show();
                visibleRows++;
            } else {
                $(this).hide();
            }
        });

        // Tampilkan pesan jika tidak ada hasil
        if (visibleRows === 0 && $('.bidang-row').length > 0) {
            if ($('#noResultRow').length === 0) {
                $('#bidangTableBody').append(
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
        
        $('#namaBidangHapus').text(nama);
        $('#formHapusBidang').attr('action', `/bidang/soft-delete/${id}`);
        
        const modal = new bootstrap.Modal(document.getElementById('hapusBidangModal'));
        modal.show();
    });

    // ✅ AUTO SHOW MODAL JIKA ADA ERROR VALIDASI
    @if($errors->any())
        var tambahModal = new bootstrap.Modal(document.getElementById('tambahBidangModal'));
        tambahModal.show();
    @endif
});
</script>
@endpush
@endsection