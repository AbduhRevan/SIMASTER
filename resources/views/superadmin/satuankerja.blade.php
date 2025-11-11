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
        <h5 class="fw-bold text-dark">Data Satker</h5>
        <!-- Tombol Modal -->
        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#tambahSatkerModal">
            + Tambah Satker
        </button>
    </div>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Satker</th>
                <th>Singkatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($satker as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td> <!-- nomor urut -->
                    <td>{{ $item->nama_satker }}</td>
                    <td>{{ $item->singkatan_satker }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">Belum ada data satker</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Satker -->
<div class="modal fade" id="tambahSatkerModal" tabindex="-1" aria-labelledby="tambahSatkerLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="tambahSatkerLabel">Tambah Satker</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('superadmin.satker.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama_satker" class="form-label">Nama Satker</label>
            <input type="text" class="form-control" id="nama_satker" name="nama_satker" required>
          </div>
          <div class="mb-3">
            <label for="singkatan_satker" class="form-label">Singkatan Satker</label>
            <input type="text" class="form-control" id="singkatan_satker" name="singkatan_satker" required>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
