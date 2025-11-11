@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="mb-4">
    <h4 class="fw-bold text-dark">Arsip Sementara Bidang</h4>
    <p class="text-muted small mb-0">Data berikut telah dihapus sementara dan dapat dipulihkan dalam waktu 30 hari.</p>
</div>

<div class="card table-card">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Bidang</th>
                <th>Singkatan</th>
                <th>Tanggal Dihapus</th>
                <th class="text-center" style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bidangTerhapus as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_bidang }}</td>
                    <td>{{ $item->singkatan_bidang }}</td>
                    <td>{{ $item->deleted_at->format('d M Y H:i') }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <!-- Pulihkan -->
                            <form action="{{ route('superadmin.bidang.restore', $item->bidang_id) }}" method="POST">
                                @csrf
                                <button class="btn btn-success btn-sm" title="Pulihkan">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </button>
                            </form>

                            <!-- Hapus Permanen -->
                            <form action="{{ route('superadmin.bidang.forceDelete', $item->bidang_id) }}" method="POST" onsubmit="return confirm('Yakin hapus permanen data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Hapus Permanen">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Tidak ada data di arsip sementara.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
.table-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    padding: 25px;
}
.btn-success {
    background-color: #198754;
    border: none;
}
.btn-success:hover {
    background-color: #157347;
}
.btn-danger {
    background-color: #7b0000;
    border: none;
}
.btn-danger:hover {
    background-color: #5a0000;
}
</style>
@endsection