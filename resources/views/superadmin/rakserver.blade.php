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
        <h5 class="fw-bold text-dark">Data Rak Server</h5>
        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#tambahRakModal">
            + Tambah Rak Server
        </button>
    </div>

    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>ID Rak</th>
                <th>Nomor Rak</th>
                <th>Ruangan</th>
                <th>Kapasitas (U Slot)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rak as $item)
                <tr>
                    <td>{{ $item->rak_id }}</td>
                    <td><span class="fw-semibold text-danger">{{ $item->nomor_rak }}</span></td>
                    <td>{{ $item->ruangan }}</td>
                    <td>{{ $item->kapasitas_u_slot }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada data rak server</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Rak -->
<div class="modal fade" id="tambahRakModal" tabindex="-1" aria-labelledby="tambahRakLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="tambahRakLabel">Tambah Rak Server</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('rak.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="nomor_rak" class="form-label">Nomor Rak</label>
            <input type="text" class="form-control" id="nomor_rak" name="nomor_rak" required>
          </div>
          <div class="mb-3">
            <label for="ruangan" class="form-label">Ruangan</label>
            <input type="text" class="form-control" id="ruangan" name="ruangan" required>
          </div>
          <div class="mb-3">
            <label for="kapasitas_u_slot" class="form-label">Kapasitas (U Slot)</label>
            <input type="number" class="form-control" id="kapasitas_u_slot" name="kapasitas_u_slot" required>
          </div>
          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
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
    background-color: #a40000;
    transform: scale(1.03);
    transition: all 0.2s ease-in-out;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
