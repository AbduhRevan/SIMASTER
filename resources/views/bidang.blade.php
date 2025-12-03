@extends('layouts.app')

@section('title', 'Bidang')

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

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i>
        <strong>Error!</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ======= DATA BIDANG ======= --}}
    <div class="card-content">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="fa fa-building me-2"></i> Data Bidang
            </h6>
            <button class="btn btn-maroon-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#tambahBidangModal">
                <i class="fa fa-plus me-1"></i> Tambah Bidang
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
                                placeholder="Cari Nama/Singkatan Bidang...">
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
                            <th width="45%">Nama Bidang</th>
                            <th width="30%">Singkatan</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bidangTableBody">
                        @forelse ($bidang as $index => $item)
                        <tr class="bidang-row">
                            <td>{{ $loop->iteration }}</td>
                            <td class="bidang-nama">{{ $item->nama_bidang }}</td>
                            <td class="bidang-singkatan">{{ $item->singkatan_bidang }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- Edit --}}
                                    <button class="btn btn-outline-warning btn-sm" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#editBidangModal{{ $item->bidang_id }}"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    {{-- Hapus --}}
                                    <button class="btn btn-outline-danger btn-sm btn-hapus"
                                        data-id="{{ $item->bidang_id }}"
                                        data-nama="{{ $item->nama_bidang }}"
                                        title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="editBidangModal{{ $item->bidang_id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header modal-header-gradient text-white border-0">
                                        <h5 class="modal-title fw-bold">
                                            <i class="fa fa-edit me-2"></i> Edit Bidang
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('superadmin.bidang.update', $item->bidang_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="current_page" value="{{ $bidang->currentPage() }}">
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Nama Bidang <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="nama_bidang" 
                                                    value="{{ $item->nama_bidang }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Singkatan <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="singkatan_bidang" 
                                                    value="{{ $item->singkatan_bidang }}" required>
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
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                Belum ada data bidang
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if(method_exists($bidang, 'hasPages') && $bidang->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3 flex-wrap gap-3" id="paginationWrapper">
            <p class="mb-0 text-secondary small">
                Menampilkan {{ $bidang->firstItem() }}–{{ $bidang->lastItem() }} dari {{ $bidang->total() }} data
            </p>
            <div class="custom-pagination">
                {{-- Previous Button --}}
                @if ($bidang->onFirstPage())
                    <span class="pagination-btn disabled">
                        <i class="fa fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $bidang->previousPageUrl() }}" class="pagination-btn">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Page Numbers with sliding window --}}
                @php
                    $currentPage = $bidang->currentPage();
                    $lastPage = $bidang->lastPage();
                    $maxVisible = 2; // Tampilkan 2 angka
                    
                    // Hitung range yang akan ditampilkan
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
                        <a href="{{ $bidang->url($page) }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endfor

                {{-- Next Button --}}
                @if ($bidang->hasMorePages())
                    <a href="{{ $bidang->nextPageUrl() }}" class="pagination-btn">
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

{{-- Modal Tambah --}}
<div class="modal fade" id="tambahBidangModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-plus-circle me-2"></i> Tambah Bidang
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.bidang.store') }}" method="POST">
                @csrf
                <input type="hidden" name="current_page" value="{{ $bidang->currentPage() }}">
                <div class="modal-body p-4">
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
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-maroon-gradient">
                        <i class="fa fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="hapusBidangModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusBidang" method="POST" action="">
                @csrf
                @method('DELETE')
                <input type="hidden" name="current_page" value="{{ $bidang->currentPage() }}">
                <div class="modal-body p-4 text-center">
                    <i class="fa-solid fa-triangle-exclamation fa-3x text-warning mb-3"></i>
                    <p class="mb-3">Apakah Anda yakin ingin menghapus bidang <strong id="namaBidangHapus"></strong>?</p>
                    <div class="alert alert-warning small mb-0">
                        <i class="fa fa-info-circle me-1"></i>
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

.btn-outline-danger {
    border-color: #dc3545;
    color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

/* Button Group */
.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
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

@push('scripts')
<script>
$(document).ready(function() {
    // Search Functionality
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

        // Hide/show pagination when searching
        if (searchValue.length > 0) {
            $('#paginationWrapper').hide();
            
            if (visibleRows > 0) {
                if ($('#searchResultInfo').length === 0) {
                    $('.table-responsive').after(
                        `<div id="searchResultInfo" class="mt-3 px-3 text-secondary small">
                            Menampilkan ${visibleRows} hasil pencarian
                        </div>`
                    );
                } else {
                    $('#searchResultInfo').html(`Menampilkan ${visibleRows} hasil pencarian`);
                }
            }
        } else {
            $('#paginationWrapper').show();
            $('#searchResultInfo').remove();
        }

        // Show "no results" message
        if (visibleRows === 0 && $('.bidang-row').length > 0) {
            if ($('#noResultRow').length === 0) {
                $('#bidangTableBody').append(
                    `<tr id="noResultRow">
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="fa fa-search fa-2x mb-2 d-block" style="opacity: 0.3;"></i>
                            Tidak ada data yang sesuai dengan pencarian
                        </td>
                    </tr>`
                );
            }
        } else {
            $('#noResultRow').remove();
        }

        // Re-bind edit buttons untuk row yang visible
        bindEditButtons();
    });

    // Clear search on ESC
    $('#searchInput').on('keydown', function(e) {
        if (e.key === 'Escape') {
            $(this).val('');
            $(this).trigger('keyup');
        }
    });

    // PERBAIKAN: Function untuk bind delete buttons
    function bindDeleteButtons() {
        $('.btn-hapus').off('click').on('click', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            
            console.log('Delete ID:', id); // Debug
            console.log('Delete Name:', nama); // Debug
            
            $('#namaBidangHapus').text(nama);
            
            // PERBAIKAN: Gunakan route helper Laravel
            const deleteUrl = "{{ route('superadmin.bidang.delete', ':id') }}".replace(':id', id);
            console.log('Delete URL:', deleteUrl); // Debug
            
            $('#formHapusBidang').attr('action', deleteUrl);
            
            const modal = new bootstrap.Modal(document.getElementById('hapusBidangModal'));
            modal.show();
        });
    }

    // Handler untuk tombol edit
    function bindEditButtons() {
        // Bind untuk tombol edit yang ada di halaman (dari server)
        $('[data-bs-toggle="modal"][data-bs-target^="#editBidangModal"]').off('click').on('click', function() {
            // Default behavior - modal sudah ada di DOM
        });

        // Bind untuk tombol edit dari hasil search yang di-hide/show
        $('.bidang-row:visible .btn-outline-warning').off('click').on('click', function() {
            const modalTarget = $(this).data('bs-target');
            if (!modalTarget) {
                // Jika tidak ada modal target (mungkin dari dynamic content)
                const id = $(this).closest('tr').find('.btn-hapus').data('id');
                const nama = $(this).closest('tr').find('.bidang-nama').text();
                const singkatan = $(this).closest('tr').find('.bidang-singkatan').text();
                
                console.log('Edit ID:', id); // Debug
                
                // Tampilkan modal edit inline
                showInlineEditModal(id, nama, singkatan);
            }
        });
    }

    // Modal edit inline untuk hasil search atau data yang di-filter
    function showInlineEditModal(id, nama, singkatan) {
        const modalHtml = `
            <div class="modal fade" id="editBidangModalInline" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header modal-header-gradient text-white border-0">
                            <h5 class="modal-title fw-bold">
                                <i class="fa fa-edit me-2"></i> Edit Bidang
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="${"{{ route('superadmin.bidang.update', ':id') }}".replace(':id', id)}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="current_page" value="{{ $bidang->currentPage() }}">
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama Bidang <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama_bidang" value="${escapeHtml(nama)}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Singkatan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="singkatan_bidang" value="${escapeHtml(singkatan)}" required>
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
            </div>`;
        
        // Hapus modal inline lama jika ada
        $('#editBidangModalInline').remove();
        
        // Tambahkan modal baru ke body
        $('body').append(modalHtml);
        
        // Tampilkan modal
        const modal = new bootstrap.Modal(document.getElementById('editBidangModalInline'));
        modal.show();
        
        // Hapus modal setelah ditutup
        $('#editBidangModalInline').on('hidden.bs.modal', function() {
            $(this).remove();
        });
    }

    // Escape HTML untuk keamanan
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Initial bind untuk tombol edit dan hapus
    bindEditButtons();
    bindDeleteButtons();

    // Auto show modal if validation errors
    @if($errors->any())
        var tambahModal = new bootstrap.Modal(document.getElementById('tambahBidangModal'));
        tambahModal.show();
    @endif

    // Smooth scroll to top on pagination click
    $('.pagination-btn').on('click', function() {
        $('html, body').animate({
            scrollTop: $('.card-content').offset().top - 100
        }, 400);
    });
});
</script>
@endpush
@endsection