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
    <h4 class="fw-bold text-dark">Kelola Pengguna</h4>
</div>

<!-- Filter Section -->
<div class="card table-card mb-3">
    <form id="filterForm" action="{{ route('superadmin.pengguna.index') }}" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Role</label>
                <select name="role" class="form-select">
                    <option value="">Semua</option>
                    <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                    <option value="admin banglola" {{ request('role') == 'banglola' ? 'selected' : '' }}>Admin Banglola</option>
                    <option value="admin pamsis" {{ request('role') == 'pamsis' ? 'selected' : '' }}>Admin Pamsis</option>
                    <option value="admin infratik" {{ request('role') == 'infratik' ? 'selected' : '' }}>Admin Infratik</option>
                    <option value="admin tatausaha" {{ request('role') == 'tatausaha' ? 'selected' : '' }}>Admin Tatausaha</option>
                    <option value="pimpinan" {{ request('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>NonAktif</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-maroon text-white w-100">
                    <i class="fa-solid fa-filter me-2"></i>Generate
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Search & Add Button -->
<div class="card table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Search -->
        <form action="{{ route('superadmin.pengguna.index') }}" method="GET" class="d-flex w-50">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-secondary"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama/username/email..." value="{{ request('search') }}">
            </div>
        </form>

        <!-- Tombol Tambah -->
        <button class="btn btn-maroon px-4 text-white" data-bs-toggle="modal" data-bs-target="#tambahPenggunaModal">
            <i class="fa-solid fa-plus me-2"></i> Tambah Pengguna
        </button>
    </div>

    <!-- TABLE -->
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Username/Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Bidang</th>
                <th class="text-center" style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengguna as $index => $user)
                <tr>
                    <td>{{ $pengguna->firstItem() + $index }}</td>
                    <td>{{ $user->nama_lengkap }}</td>
                    <td>{{ $user->username_email }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($user->role) }}</span></td>
                    <td>
                        @if($user->status == 'active')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">NonAktif</span>
                        @endif
                    </td>
                    <td>{{ $user->bidang ? $user->bidang->nama_bidang : '-' }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <!-- Edit -->
                            <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal"
                                data-bs-target="#editPenggunaModal{{ $user->user_id }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Toggle Status -->
                            <button class="btn btn-info btn-sm text-white btn-toggle-status"
                                data-id="{{ $user->user_id }}"
                                data-status="{{ $user->status }}">
                                <i class="fa-solid fa-ban"></i>
                            </button>

                            <!-- Hapus -->
                            <button class="btn btn-danger btn-sm btn-hapus"
                                data-id="{{ $user->user_id }}"
                                data-nama="{{ $user->nama_lengkap }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- MODAL EDIT -->
                <div class="modal fade" id="editPenggunaModal{{ $user->user_id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content border-0 rounded-3 overflow-hidden">
                            <div class="modal-header bg-maroon text-white">
                                <h5 class="modal-title">Edit Pengguna</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('superadmin.pengguna.update', $user->user_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nama Lengkap<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_lengkap" value="{{ $user->nama_lengkap }}" placeholder="Contoh: Andi Saputra" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Username/Email<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="username_email" value="{{ $user->username_email }}" placeholder="Contoh: andisaputra@gmail.com" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Role<span class="text-danger">*</span></label>
                                            <select name="role" class="form-select" required>
                                                <option value="">Pilih Role</option>
                                                <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                                <option value="banglola" {{ $user->role == 'banglola' ? 'selected' : '' }}>Banglola</option>
                                                <option value="pamsis" {{ $user->role == 'pamsis' ? 'selected' : '' }}>Pamsis</option>
                                                <option value="infratik" {{ $user->role == 'infratik' ? 'selected' : '' }}>Infratik</option>
                                                <option value="tatausaha" {{ $user->role == 'tatausaha' ? 'selected' : '' }}>Tatausaha</option>
                                                <option value="pimpinan" {{ $user->role == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Bidang<span class="text-danger">*</span></label>
                                            <select name="bidang_id" class="form-select" required>
                                                <option value="">Pilih Bidang</option>
                                                @php
                                                    $allBidang = \App\Models\Bidang::all();
                                                @endphp
                                                @foreach($allBidang as $b)
                                                    <option value="{{ $b->bidang_id }}" {{ $user->bidang_id == $b->bidang_id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
                    <td colspan="7" class="text-center text-muted">Belum ada data pengguna</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $pengguna->links() }}
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="tambahPenggunaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-3 overflow-hidden">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Tambah Pengguna</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.pengguna.store') }}" method="POST" id="formTambahPengguna">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_lengkap" placeholder="Contoh: Andi Saputra" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username/Email<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username_email" placeholder="Contoh: andisaputra@gmail.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Konfirmasi Password<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Role<span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="">Pilih Role</option>
                                <option value="superadmin">Superadmin</option>
                                <option value="banglola">Admin Banglola</option>
                                <option value="pamsis">Admin Pamsis</option>
                                <option value="infratik">Admin Infratik</option>
                                <option value="tatausaha">Admin Tatausaha</option>
                                <option value="pimpinan">Pimpinan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Bidang<span class="text-danger">*</span></label>
                            <select name="bidang_id" class="form-select" required>
                                <option value="">Pilih Bidang</option>
                                @php
                                    $allBidang = \App\Models\Bidang::all();
                                @endphp
                                @foreach($allBidang as $b)
                                    <option value="{{ $b->bidang_id }}">{{ $b->nama_bidang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status<span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" selected>Aktif</option>
                            <option value="inactive">NonAktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon text-white">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL HAPUS -->
<div class="modal fade" id="hapusPenggunaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 overflow-hidden">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusPengguna" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <i class="fa-solid fa-triangle-exclamation fa-3x text-warning mb-3"></i>
                    <p>Apakah Anda yakin ingin menghapus user <strong id="namaPenggunaHapus"></strong>?</p>
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

<!-- MODAL TOGGLE STATUS -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 overflow-hidden">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Konfirmasi Ubah Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formToggleStatus" method="POST">
                @csrf
                <div class="modal-body text-center">
                    <i class="fa-solid fa-circle-info fa-3x text-info mb-3"></i>
                    <p id="statusMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon text-white">Ya, Ubah</button>
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
.btn-info {
    background-color: #0dcaf0;
    border: none;
}
.btn-info:hover {
    background-color: #31d2f2;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Validasi Password Confirmation
    const formTambah = document.getElementById('formTambahPengguna');
    if(formTambah) {
        formTambah.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            
            if(password !== passwordConfirm) {
                e.preventDefault();
                alert('Password dan Konfirmasi Password tidak cocok!');
                return false;
            }
        });
    }

    // Handle Hapus Button
    const hapusButtons = document.querySelectorAll('.btn-hapus');
    const modalElement = document.getElementById('hapusPenggunaModal');
    const modal = new bootstrap.Modal(modalElement);
    const formHapus = document.getElementById('formHapusPengguna');
    const namaPenggunaHapus = document.getElementById('namaPenggunaHapus');

    hapusButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const nama = btn.dataset.nama;
            namaPenggunaHapus.textContent = nama;
            formHapus.action = `/superadmin/pengguna/${id}`;
            modal.show();
        });
    });

    // Handle Toggle Status Button
    const toggleButtons = document.querySelectorAll('.btn-toggle-status');
    const toggleModal = new bootstrap.Modal(document.getElementById('toggleStatusModal'));
    const formToggle = document.getElementById('formToggleStatus');
    const statusMessage = document.getElementById('statusMessage');

    toggleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const currentStatus = btn.dataset.status;
            const newStatus = currentStatus === 'active' ? 'nonaktif' : 'aktif';
            
            statusMessage.textContent = `Apakah Anda yakin ingin mengubah status pengguna ini menjadi ${newStatus}?`;
            formToggle.action = `/superadmin/pengguna/toggle-status/${id}`;
            toggleModal.show();
        });
    });
});
</script>
@endsection