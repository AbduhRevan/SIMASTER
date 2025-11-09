@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark">Data Bidang</h5>
        <!-- Tombol Modal -->
        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#tambahBidangModal">
            + Tambah Bidang
        </button>
    </div>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>ID Bidang</th>
                <th>Nama Bidang</th>
                <th>Singkatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bidang as $item)
                <tr>
                    <td>{{ $item->bidang_id }}</td>
                    <td>{{ $item->nama_bidang }}</td>
                    <td>{{ $item->singkatan_bidang }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">Belum ada data bidang</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Bidang -->
<div class="modal fade" id="tambahBidangModal" tabindex="-1" aria-labelledby="tambahBidangLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="tambahBidangLabel">Tambah Bidang</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('bidang.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama_bidang" class="form-label">Nama Bidang</label>
            <input type="text" class="form-control" id="nama_bidang" name="nama_bidang" required>
          </div>
          <div class="mb-3">
            <label for="singkatan_bidang" class="form-label">Singkatan Bidang</label>
            <input type="text" class="form-control" id="singkatan_bidang" name="singkatan_bidang" required>
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
    font-weight: 500;
}
.btn-danger:hover {
    background-color: #7b0000;
    transform: scale(1.03);
    transition: all 0.2s ease-in-out;
}
</style>

<!-- Tambahkan JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
