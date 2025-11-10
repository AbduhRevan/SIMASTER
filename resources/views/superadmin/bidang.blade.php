@extends('layouts.app')

@section('content')
{{-- FONT AWESOME (pastikan ini juga ada di layout head, tapi disini tetap biar aman) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark">Data Bidang</h5>
        <div class="d-flex gap-2">
            <!-- Search -->
            <form action="{{ route('superadmin.bidang') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari bidang..." value="{{ request('search') }}">
                <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <!-- Tombol Tambah -->
            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#tambahBidangModal">
                + Tambah Bidang
            </button>
        </div>
    </div>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>ID Bidang</th>
                <th>Nama Bidang</th>
                <th>Singkatan</th>
                <th style="width: 100px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bidang as $item)
                <tr>
                    <td>{{ $item->bidang_id }}</td>
                    <td>{{ $item->nama_bidang }}</td>
                    <td>{{ $item->singkatan_bidang }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <!-- Tombol Edit -->
                            <button class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editBidangModal{{ $item->bidang_id }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <button class="btn btn-danger btn-sm btn-hapus"
                                    data-id="{{ $item->bidang_id }}"
                                    data-nama="{{ $item->nama_bidang }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editBidangModal{{ $item->bidang_id }}" tabindex="-1" aria-labelledby="editBidangLabel{{ $item->bidang_id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title" id="editBidangLabel{{ $item->bidang_id }}">Edit Bidang</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('superadmin.bidang.update', $item->bidang_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Bidang</label>
                                        <input type="text" class="form-control" name="nama_bidang" value="{{ $item->nama_bidang }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Singkatan Bidang</label>
                                        <input type="text" class="form-control" name="singkatan_bidang" value="{{ $item->singkatan_bidang }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning text-dark">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada data bidang</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahBidangModal" tabindex="-1" aria-labelledby="tambahBidangLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="tambahBidangLabel">Tambah Bidang</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('superadmin.bidang.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Bidang</label>
            <input type="text" class="form-control" name="nama_bidang" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Singkatan Bidang</label>
            <input type="text" class="form-control" name="singkatan_bidang" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 🟢 Modal Hapus (1x aja, di luar foreach) -->
<div class="modal fade" id="hapusBidangModal" tabindex="-1" aria-labelledby="hapusBidangLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="hapusBidangLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="formHapusBidang" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus bidang <strong id="namaBidangHapus"></strong>?</p>
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
    transition: all 0.2s ease-in-out;
}
.table-card:hover { transform: translateY(-2px); }
.table thead th {
    background-color: #f8f9fa;
    color: #333;
    font-weight: 600;
    border-bottom: 2px solid #7b0000;
}
.table tbody tr:hover {
    background-color: rgba(123, 0, 0, 0.05);
    transition: background-color 0.2s ease;
}
.btn-danger {
    background-color: #7b0000;
    border: none;
}
.btn-danger:hover {
    background-color: #5a0000;
}
.btn-warning {
    background-color: #ffc107;
    border: none;
}
.btn-warning:hover {
    background-color: #ffcd39;
}
.btn-primary {
    background-color: #7b0000;
    border: none;
}
.btn-primary:hover {
    background-color: #5a0000;
}
.btn-hapus i {
    pointer-events: none;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hapusButtons = document.querySelectorAll('.btn-hapus');
    const modalElement = document.getElementById('hapusBidangModal');
    const modal = new bootstrap.Modal(modalElement);
    const formHapus = document.getElementById('formHapusBidang');
    const namaBidangHapus = document.getElementById('namaBidangHapus');

    hapusButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const nama = btn.dataset.nama;
            namaBidangHapus.textContent = nama;
            formHapus.action = `/bidang/delete/${id}`;
            modal.show();
        });
    });
});
</script>
@endsection
