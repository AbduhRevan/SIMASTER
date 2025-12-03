@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="container-fluid py-4">

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Filter Card --}}
    <div class="card-content mb-3">
        <div class="card-body-custom">
            <form id="filterForm" action="{{ route('superadmin.pengguna.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Role</label>
                        <select name="role" id="filterRole" class="form-select">
                            <option value="">Semua</option>
                            <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                            <option value="banglola" {{ request('role') == 'banglola' ? 'selected' : '' }}>Admin Banglola</option>
                            <option value="pamsis" {{ request('role') == 'pamsis' ? 'selected' : '' }}>Admin Pamsis</option>
                            <option value="infratik" {{ request('role') == 'infratik' ? 'selected' : '' }}>Admin Infratik</option>
                            <option value="tatausaha" {{ request('role') == 'tatausaha' ? 'selected' : '' }}>Admin Tatausaha</option>
                            <option value="pimpinan" {{ request('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Status</label>
                        <select name="status" id="filterStatus" class="form-select">
                            <option value="">Semua</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>NonAktif</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="btnResetFilter" class="btn btn-outline-secondary w-100">
                            <i class="fa-solid fa-rotate-right me-2"></i>Reset Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Main Data Card --}}
    <div class="card-content">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="fa fa-users me-2"></i> Data Pengguna
            </h6>
            <button class="btn btn-maroon-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#tambahPenggunaModal">
                <i class="fa fa-plus me-1"></i> Tambah Pengguna
            </button>
        </div>

        <div class="card-body-custom">
            {{-- Search Bar --}}
            <div class="filter-bar mb-3">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" 
                                placeholder="Cari nama/username/email...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="18%">Nama Lengkap</th>
                            <th width="18%">Username/Email</th>
                            <th width="12%">Role</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="20%">Bidang</th>
                            <th width="17%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="penggunaTableBody">
                        @forelse($pengguna as $index => $user)
                            <tr class="pengguna-row" 
                                data-nama="{{ strtolower($user->nama_lengkap) }}"
                                data-username="{{ strtolower($user->username_email) }}"
                                data-role="{{ strtolower($user->role) }}"
                                data-status="{{ strtolower($user->status) }}">
                                <td class="row-number">{{ $pengguna->firstItem() + $index }}</td>
                                <td class="pengguna-nama">{{ $user->nama_lengkap }}</td>
                                <td class="pengguna-username">{{ $user->username_email }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td class="text-center">
                                    @if($user->status == 'active')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">NonAktif</span>
                                    @endif
                                </td>
                                <td>{{ $user->bidang ? $user->bidang->nama_bidang : '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        {{-- Edit --}}
                                        <button class="btn btn-outline-warning btn-sm" 
                                            data-bs-toggle="modal"
                                            data-bs-target="#editPenggunaModal{{ $user->user_id }}"
                                            title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        {{-- Toggle Status --}}
                                        <button class="btn btn-outline-secondary btn-sm btn-toggle-status"
                                            data-id="{{ $user->user_id }}"
                                            data-status="{{ $user->status }}"
                                            data-nama="{{ $user->nama_lengkap }}"
                                            title="Toggle Status">
                                            <i class="fa fa-ban"></i>
                                        </button>

                                        {{-- Hapus --}}
                                        <button class="btn btn-outline-danger btn-sm btn-hapus"
                                            data-id="{{ $user->user_id }}"
                                            data-nama="{{ $user->nama_lengkap }}"
                                            title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT --}}
                            <div class="modal fade" id="editPenggunaModal{{ $user->user_id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header modal-header-gradient text-white border-0">
                                            <h5 class="modal-title fw-bold">
                                                <i class="fa fa-edit me-2"></i> Edit Pengguna
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('superadmin.pengguna.update', $user->user_id) }}" method="POST" class="form-edit-pengguna">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="nama_lengkap" value="{{ $user->nama_lengkap }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Username/Email <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="username_email" value="{{ $user->username_email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Password</label>
                                                    <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
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
                                                        <label class="form-label fw-semibold">Bidang <span class="text-danger">*</span></label>
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
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                                        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>NonAktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fa fa-times me-1"></i> Batal
                                                </button>
                                                <button type="submit" class="btn btn-warning text-white">
                                                    <i class="fa fa-save me-1"></i> Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                        <tr id="emptyRow">
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                Belum ada data pengguna
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if(method_exists($pengguna, 'hasPages') && $pengguna->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3 flex-wrap gap-3" id="paginationWrapper">
            <p class="mb-0 text-secondary small">
                Menampilkan {{ $pengguna->firstItem() }}–{{ $pengguna->lastItem() }} dari {{ $pengguna->total() }} data
            </p>
            <div class="custom-pagination">
                {{-- Previous Button --}}
                @if ($pengguna->onFirstPage())
                    <span class="pagination-btn disabled">
                        <i class="fa fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $pengguna->previousPageUrl() }}" class="pagination-btn">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Page Numbers with sliding window --}}
                @php
                    $currentPage = $pengguna->currentPage();
                    $lastPage = $pengguna->lastPage();
                    $maxVisible = 2;
                    
                    if ($currentPage == 1) {
                        $start = 1;
                        $end = min($maxVisible, $lastPage);
                    } elseif ($currentPage == $lastPage) {
                        $start = max(1, $lastPage - $maxVisible + 1);
                        $end = $lastPage;
                    } else {
                        $start = $currentPage;
                        $end = min($currentPage + $maxVisible - 1, $lastPage);
                    }
                @endphp

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $currentPage)
                        <span class="pagination-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $pengguna->url($page) }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endfor

                {{-- Next Button --}}
                @if ($pengguna->hasMorePages())
                    <a href="{{ $pengguna->nextPageUrl() }}" class="pagination-btn">
                        <i class="fa fa-chevron-right"></i>
                    </a>
                @else
                    <span class="pagination-btn disabled">
                        <i class="fa fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="tambahPenggunaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-plus-circle me-2"></i> Tambah Pengguna
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.pengguna.store') }}" method="POST" id="formTambahPengguna">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_lengkap" placeholder="Contoh: Andi Saputra" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username/Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username_email" placeholder="Contoh: andisaputra@gmail.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
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
                            <label class="form-label fw-semibold">Bidang <span class="text-danger">*</span></label>
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
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" selected>Aktif</option>
                            <option value="inactive">NonAktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-maroon-gradient">
                        <i class="fa fa-save me-1"></i> Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL HAPUS (HARD DELETE) --}}
<div class="modal fade" id="hapusPenggunaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusPengguna" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4 text-center">
                    <i class="fa-solid fa-triangle-exclamation fa-3x text-warning mb-3"></i>
                    <p class="mb-3">Apakah Anda yakin ingin menghapus user <strong id="namaPenggunaHapus"></strong>?</p>
                    <div class="alert alert-danger small mb-0">
                        <i class="fa fa-exclamation-circle me-1"></i>
                        Data akan dihapus secara permanen dan tidak dapat dipulihkan.
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash me-1"></i> Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TOGGLE STATUS --}}
<div class="modal fade" id="toggleStatusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-circle-info me-2"></i> Konfirmasi Ubah Status
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formToggleStatus" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <i class="fa-solid fa-circle-info fa-3x text-secondary mb-3"></i>
                    <p id="statusMessage"></p>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-maroon-gradient">
                        <i class="fa fa-check me-1"></i> Ya, Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Card Content */
.card-content {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    overflow: hidden;
}

.card-header-custom {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.card-body-custom {
    padding: 1.5rem;
}

/* Filter Bar */
.filter-bar {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 6px;
}

/* Table Styles */
.table-hover tbody tr {
    transition: background-color 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

/* Badge Styles */
.badge {
    padding: 0.35rem 0.65rem;
    font-weight: 500;
    font-size: 0.75rem;
}

/* Button Styles */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 4px;
}

.btn-outline-warning {
    border-color: #ffc107;
    color: #ffc107;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}

.btn-outline-danger {
    border-color: #dc3545;
    color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

/* Modal Gradient Header */
.modal-header-gradient {
    background: linear-gradient(135deg, #7b0000 0%, #b91d1d 100%);
}

/* Button Maroon Gradient */
.btn-maroon-gradient {
    background: linear-gradient(135deg, #7b0000 0%, #b91d1d 100%);
    border: none;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-maroon-gradient:hover {
    background: linear-gradient(135deg, #5e0000 0%, #8b1515 100%);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(123, 0, 0, 0.3);
}

/* Modal Shadow */
.modal-content {
    border-radius: 8px;
}

/* Input Group */
.input-group-text {
    border-right: 0;
}

/* Alert with Icon */
.alert i {
    font-size: 1rem;
}

/* Custom Pagination Styling */
.custom-pagination {
    display: flex;
    gap: 8px;
    align-items: center;
}

.pagination-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    background-color: white;
    color: #495057;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.pagination-btn:hover:not(.disabled):not(.active) {
    background-color: #f8f9fa;
    border-color: #adb5bd;
    color: #7b0000;
}

.pagination-btn.active {
    background: linear-gradient(135deg, #7b0000 0%, #b91d1d 100%);
    border-color: #7b0000;
    color: white;
    font-weight: 600;
    cursor: default;
}

.pagination-btn.disabled {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #adb5bd;
    cursor: not-allowed;
    opacity: 0.6;
}

.pagination-btn i {
    font-size: 12px;
}

/* Responsive */
@media (max-width: 768px) {
    .card-header-custom {
        flex-direction: column;
        gap: 1rem;
    }
    
    .filter-bar .col-md-6 {
        width: 100%;
    }

    #paginationWrapper {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    #paginationWrapper > div {
        width: 100%;
    }

    .custom-pagination {
        width: 100%;
        justify-content: center;
        flex-wrap: wrap;
    }

    .pagination-btn {
        min-width: 36px;
        height: 36px;
        font-size: 13px;
    }
}
</style>

<script>
/**
 * ====================================================================
 * KELOLA PENGGUNA - FULL JAVASCRIPT
 * ====================================================================
 * Features:
 * - Live Search (with debounce)
 * - Filter by Role & Status
 * - CRUD Operations (Create, Read, Update, Delete)
 * - Toggle Status
 * - Form Validation
 * - Error Handling
 * ====================================================================
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ====================================================================
    // CHECK JQUERY AVAILABILITY
    // ====================================================================
    if (typeof jQuery === 'undefined') {
        console.error('jQuery tidak ditemukan! Pastikan jQuery sudah dimuat.');
        alert('Terjadi kesalahan sistem. Silakan refresh halaman.');
        return;
    }

    const $ = jQuery;

    // ====================================================================
    // LIVE SEARCH FUNCTIONALITY (with Debounce)
    // ====================================================================
    $('#searchInput').on('keyup', debounce(function() {
        filterTable();
    }, 300)); // Delay 300ms untuk optimasi

    // Clear search on ESC key
    $('#searchInput').on('keydown', function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            $(this).val('');
            filterTable();
        }
    });

    // ====================================================================
    // FILTER FUNCTIONALITY
    // ====================================================================
    $('#filterRole, #filterStatus').on('change', function() {
        filterTable();
    });

    // ====================================================================
    // RESET FILTER
    // ====================================================================
    $('#btnResetFilter').on('click', function() {
        $('#filterRole').val('');
        $('#filterStatus').val('');
        $('#searchInput').val('');
        filterTable();
    });

    // ====================================================================
    // FILTER TABLE FUNCTION
    // ====================================================================
    function filterTable() {
        const searchValue = $('#searchInput').val().toLowerCase().trim();
        const roleFilter = $('#filterRole').val().toLowerCase();
        const statusFilter = $('#filterStatus').val().toLowerCase();
        let visibleRows = 0;

        $('.pengguna-row').each(function() {
            const nama = $(this).data('nama') || '';
            const username = $(this).data('username') || '';
            const role = $(this).data('role') || '';
            const status = $(this).data('status') || '';

            // Check search match
            const matchSearch = searchValue === '' || 
                               nama.includes(searchValue) || 
                               username.includes(searchValue);

            // Check role filter
            const matchRole = roleFilter === '' || role === roleFilter;

            // Check status filter
            const matchStatus = statusFilter === '' || status === statusFilter;

            // Show/hide row based on filters
            if (matchSearch && matchRole && matchStatus) {
                $(this).show();
                visibleRows++;
            } else {
                $(this).hide();
            }
        });

        // Update row numbers for visible rows
        let rowNumber = 1;
        $('.pengguna-row:visible').each(function() {
            $(this).find('.row-number').text(rowNumber);
            rowNumber++;
        });

        // Handle empty results
        handleEmptyResults(visibleRows);
    }

    // ====================================================================
    // HANDLE EMPTY RESULTS
    // ====================================================================
    function handleEmptyResults(visibleRows) {
        if (visibleRows === 0 && $('.pengguna-row').length > 0) {
            if ($('#noResultRow').length === 0) {
                $('#penggunaTableBody').append(
                    '<tr id="noResultRow">' +
                        '<td colspan="7" class="text-center text-muted py-4">' +
                            '<i class="fa fa-search fa-2x mb-2 d-block" style="opacity: 0.3;"></i>' +
                            'Tidak ada data yang sesuai dengan pencarian atau filter' +
                        '</td>' +
                    '</tr>'
                );
            }
            $('#emptyRow').hide();
        } else {
            $('#noResultRow').remove();
            if ($('.pengguna-row').length === 0) {
                $('#emptyRow').show();
            }
        }
    }

    // ====================================================================
    // DEBOUNCE FUNCTION (untuk optimasi search)
    // ====================================================================
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    // ====================================================================
    // FORM TAMBAH - VALIDATION
    // ====================================================================
    $('#formTambahPengguna').on('submit', function(e) {
        const password = $('#password').val();
        const passwordConfirm = $('#password_confirmation').val();
        
        // Check password match
        if (password !== passwordConfirm) {
            e.preventDefault();
            showAlert('error', 'Password dan Konfirmasi Password tidak cocok!');
            $('#password_confirmation').addClass('is-invalid');
            $('#password_confirmation').focus();
            return false;
        }
        
        // Check password length
        if (password.length < 6) {
            e.preventDefault();
            showAlert('error', 'Password minimal 6 karakter!');
            $('#password').addClass('is-invalid');
            $('#password').focus();
            return false;
        }

        // If validation passes, show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...');
    });

    // Remove invalid class on input
    $('#password, #password_confirmation').on('input', function() {
        $(this).removeClass('is-invalid');
    });

    // ====================================================================
    // FORM EDIT - VALIDATION
    // ====================================================================
    $('.form-edit-pengguna').on('submit', function(e) {
        const form = $(this);
        const namaLengkap = form.find('input[name="nama_lengkap"]').val().trim();
        const usernameEmail = form.find('input[name="username_email"]').val().trim();
        const role = form.find('select[name="role"]').val();
        const bidangId = form.find('select[name="bidang_id"]').val();
        const password = form.find('input[name="password"]').val();
        
        // Check required fields
        if (!namaLengkap || !usernameEmail || !role || !bidangId) {
            e.preventDefault();
            showAlert('error', 'Semua field wajib diisi!');
            return false;
        }
        
        // Validate password length if filled
        if (password && password.length < 6) {
            e.preventDefault();
            showAlert('error', 'Password minimal 6 karakter!');
            form.find('input[name="password"]').addClass('is-invalid').focus();
            return false;
        }

        // Show loading state
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...');
    });

    // ====================================================================
    // MODAL HAPUS - HANDLER
    // ====================================================================
    $(document).on('click', '.btn-hapus', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        
        // Validate data
        if (!id || !nama) {
            showAlert('error', 'Data tidak valid!');
            return;
        }
        
        // Set modal content
        $('#namaPenggunaHapus').text(nama);
        
        // Set form action URL
        const deleteUrl = '{{ route("superadmin.pengguna.destroy", ":id") }}'.replace(':id', id);
        $('#formHapusPengguna').attr('action', deleteUrl);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('hapusPenggunaModal'));
        modal.show();
    });

    // Hapus form submit handler
    $('#formHapusPengguna').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Menghapus...');
    });

    // ====================================================================
    // MODAL TOGGLE STATUS - HANDLER
    // ====================================================================
    $(document).on('click', '.btn-toggle-status', function() {
        const id = $(this).data('id');
        const currentStatus = $(this).data('status');
        const nama = $(this).data('nama');
        
        // Validate data
        if (!id || !nama) {
            showAlert('error', 'Data tidak valid!');
            return;
        }
        
        // Determine new status
        const newStatus = currentStatus === 'active' ? 'nonaktif' : 'aktif';
        const statusBadge = currentStatus === 'active' 
            ? '<span class="badge bg-secondary">Nonaktif</span>' 
            : '<span class="badge bg-success">Aktif</span>';
        
        // Set modal message
        $('#statusMessage').html(
            Apakah Anda yakin ingin mengubah status pengguna <strong>${nama}</strong> menjadi ${statusBadge}?
        );
        
        // Set form action URL using named route
        const toggleUrl = '{{ route("superadmin.pengguna.toggle-status", ":id") }}'.replace(':id', id);
        $('#formToggleStatus').attr('action', toggleUrl);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('toggleStatusModal'));
        modal.show();
    });

    // Toggle status form submit handler
    $('#formToggleStatus').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Mengubah...');
    });

    // ====================================================================
    // SHOW ALERT FUNCTION
    // ====================================================================
    function showAlert(type, message) {
        const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
        const icon = type === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fa-solid ${icon} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at the top
        $('.container-fluid').prepend(alertHtml);
        
        // Scroll to top
        $('html, body').animate({ scrollTop: 0 }, 300);
        
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    // ====================================================================
    // AUTO DISMISS ALERTS
    // ====================================================================
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);

    // ====================================================================
    // RESET MODAL STATE ON CLOSE
    // ====================================================================
    $('.modal').on('hidden.bs.modal', function() {
        // Reset form
        $(this).find('form')[0]?.reset();
        
        // Remove validation classes
        $(this).find('.is-invalid').removeClass('is-invalid');
        
        // Re-enable submit button
        $(this).find('button[type="submit"]')
            .prop('disabled', false)
            .html(function() {
                const modalId = $(this).closest('.modal').attr('id');
                if (modalId === 'tambahPenggunaModal') {
                    return '<i class="fa fa-save me-1"></i> Tambah';
                } else if (modalId === 'hapusPenggunaModal') {
                    return '<i class="fa fa-trash me-1"></i> Hapus';
                } else if (modalId === 'toggleStatusModal') {
                    return '<i class="fa fa-check me-1"></i> Ya, Ubah';
                } else {
                    return '<i class="fa fa-save me-1"></i> Simpan';
                }
            });
    });

    // ====================================================================
    // SMOOTH SCROLL ON PAGINATION CLICK
    // ====================================================================
    $('.pagination-btn').on('click', function(e) {
        if (!$(this).hasClass('disabled') && !$(this).hasClass('active')) {
            $('html, body').animate({
                scrollTop: $('.card-content').offset().top - 100
            }, 400);
        }
    });

    // ====================================================================
    // INITIAL FILTER CHECK
    // ====================================================================
    // Apply filter if there are existing filter values on page load
    if ($('#filterRole').val() || $('#filterStatus').val()) {
        filterTable();
    }

    // ====================================================================
    // PREVENT DOUBLE SUBMIT
    // ====================================================================
    $('form').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        
        // Re-enable after 3 seconds (in case of validation errors)
        setTimeout(function() {
            submitBtn.prop('disabled', false);
        }, 3000);
    });

    // ====================================================================
    // CONSOLE LOG - READY
    // ====================================================================
    console.log('%c✓ Kelola Pengguna Script Loaded', 'color: #28a745; font-weight: bold; font-size: 14px;');
    console.log('%cFeatures: Live Search, Filter, CRUD, Toggle Status', 'color: #6c757d; font-size: 12px;');

}); // END DOMContentLoaded
</script>
@endsection