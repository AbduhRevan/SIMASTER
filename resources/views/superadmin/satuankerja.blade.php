@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- 🟥 HEADER -->
<div class="mb-4">
    <h4 class="fw-bold text-dark">Data Master Satuan Kerja</h4>
</div>

<div class="card table-card">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <!-- Search -->
        <form action="{{ route('superadmin.satuankerja') }}" method="GET" class="d-flex w-50 min-w-300">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="fa-solid fa-magnifying-glass text-secondary"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0"
                       placeholder="Cari ID/Nama/Singkatan Satker..." value="{{ request('search') }}">
            </div>
        </form>

        <!-- Tombol Tambah -->
        <button class="btn btn-maroon px-4 text-white mt-2 mt-sm-0" data-bs-toggle="modal" data-bs-target="#tambahSatkerModal">
            <i class="fa-solid fa-plus me-2"></i> Tambah Satuan Kerja
        </button>
    </div>

    <!-- WRAPPER TABLE -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="white-space: nowrap;">No</th>
                    <th style="white-space: nowrap;">Nama Satuan Kerja</th>
                    <th style="white-space: nowrap;">Singkatan</th>
                    <th class="text-center" style="white-space: nowrap;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($satker as $index => $item)
                    <tr>
                        <td>{{ $satker->firstItem() + $index }}</td>
                        <td>{{ $item->nama_satker }}</td>
                        <td>{{ $item->singkatan_satker }}</td>
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
                        <div class="modal-dialog">
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
                                            <label class="form-label fw-semibold">Nama Satuan Kerja</label>
                                            <input type="text" class="form-control" name="nama_satker"
                                                   value="{{ $item->nama_satker }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Singkatan</label>
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
                    <tr>
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
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-3 overflow-hidden">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Tambah Satuan Kerja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.satker.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Satuan Kerja</label>
                        <input type="text" class="form-control" name="nama_satker" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Singkatan</label>
                        <input type="text" class="form-control" name="singkatan_satker" required>
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
.table th, .table td {
    white-space: nowrap;
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
.min-w-300 {
    min-width: 300px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const hapusButtons = document.querySelectorAll('.btn-hapus');
    const modalElement = document.getElementById('hapusSatkerModal');
    const modal = new bootstrap.Modal(modalElement);
    const formHapus = document.getElementById('formHapusSatker');
    const namaSatkerHapus = document.getElementById('namaSatkerHapus');

    hapusButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const nama = btn.dataset.nama;
            namaSatkerHapus.textContent = nama;
            formHapus.action = `/satker/soft-delete/${id}`;
            modal.show();
        });
    });
});
</script>
@endsection
