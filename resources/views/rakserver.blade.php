@extends('layouts.app')

@section('title', 'Rak Server')

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

    {{-- ======= DATA RAK SERVER ======= --}}
    <div class="card-content">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="fa fa-server me-2"></i> Data Rak Server
            </h6>
            <button class="btn btn-maroon-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#tambahRakModal">
                <i class="fa fa-plus me-1"></i> Tambah Rak Server
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
                                placeholder="Cari Nomor Rak/Ruangan/Keterangan...">
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
                            <th width="12%">Nomor Rak</th>
                            <th width="15%">Ruangan</th>
                            <th width="12%" class="text-center">Kapasitas Total</th>
                            <th width="10%" class="text-center">Terpakai</th>
                            <th width="10%" class="text-center">Sisa</th>
                            <th width="26%">Keterangan</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="rakTableBody">
                        @forelse ($rak as $index => $item)
                            @php
                                $terpakai = 0;
                                foreach($item->servers as $server) {
                                    if($server->u_slot) {
                                        $slots = explode('-', $server->u_slot);
                                        if(count($slots) == 2) {
                                            $terpakai += (int)$slots[1] - (int)$slots[0] + 1;
                                        } else {
                                            $terpakai += 1;
                                        }
                                    }
                                }
                                $sisa = $item->kapasitas_u_slot - $terpakai;
                            @endphp
                            <tr class="rak-row">
                                <td>{{ $rak->firstItem() + $index }}</td>
                                <td class="rak-nomor"><strong>{{ $item->nomor_rak }}</strong></td>
                                <td class="rak-ruangan">{{ $item->ruangan }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $item->kapasitas_u_slot }}U</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $terpakai }}U</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $sisa > 0 ? 'bg-success' : 'bg-danger' }}">{{ $sisa }}U</span>
                                </td>
                                <td class="rak-keterangan">
                                    <small>{!! $item->keterangan ?? '-' !!}</small>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        {{-- Edit --}}
                                        <button class="btn btn-outline-warning btn-sm" 
                                            data-bs-toggle="modal"
                                            data-bs-target="#editRakModal{{ $item->rak_id }}"
                                            title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        {{-- Hapus --}}
                                        <button class="btn btn-outline-danger btn-sm btn-hapus"
                                            data-id="{{ $item->rak_id }}"
                                            data-nama="{{ $item->nomor_rak }}"
                                            title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Edit --}}
                            <div class="modal fade" id="editRakModal{{ $item->rak_id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header modal-header-gradient text-white border-0">
                                            <h5 class="modal-title fw-bold">
                                                <i class="fa fa-edit me-2"></i> Edit Rak Server
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('superadmin.rakserver.update', $item->rak_id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nomor Rak <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="nomor_rak" 
                                                        value="{{ $item->nomor_rak }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Ruangan <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="ruangan" 
                                                        value="{{ $item->ruangan }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Kapasitas U Slot <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="kapasitas_u_slot" 
                                                        value="{{ $item->kapasitas_u_slot }}" min="1" max="50" required>
                                                    <small class="text-muted">Maksimal 50U</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Keterangan</label>
                                                    <textarea class="form-control summernote" 
                                                        name="keterangan">{{ $item->keterangan }}</textarea>
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
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                Belum ada data rak server
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if(method_exists($rak, 'hasPages') && $rak->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3 flex-wrap gap-3" id="paginationWrapper">
            <p class="mb-0 text-secondary small">
                Menampilkan {{ $rak->firstItem() }}–{{ $rak->lastItem() }} dari {{ $rak->total() }} data
            </p>
            <div class="custom-pagination">
                {{-- Previous Button --}}
                @if ($rak->onFirstPage())
                    <span class="pagination-btn disabled">
                        <i class="fa fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $rak->previousPageUrl() }}" class="pagination-btn">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Page Numbers with sliding window --}}
                @php
                    $currentPage = $rak->currentPage();
                    $lastPage = $rak->lastPage();
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
                        <a href="{{ $rak->url($page) }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endfor

                {{-- Next Button --}}
                @if ($rak->hasMorePages())
                    <a href="{{ $rak->nextPageUrl() }}" class="pagination-btn">
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
<div class="modal fade" id="tambahRakModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-plus-circle me-2"></i> Tambah Rak Server
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.rakserver.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor Rak <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nomor_rak') is-invalid @enderror" 
                            name="nomor_rak" 
                            placeholder="Contoh: R16" 
                            value="{{ old('nomor_rak') }}"
                            required>
                        @error('nomor_rak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ruangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ruangan') is-invalid @enderror" 
                            name="ruangan" 
                            placeholder="Contoh: Pusdatin DC" 
                            value="{{ old('ruangan') }}"
                            required>
                        @error('ruangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kapasitas U Slot <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('kapasitas_u_slot') is-invalid @enderror" 
                            name="kapasitas_u_slot" 
                            placeholder="Contoh: 42" 
                            min="1"
                            max="50"
                            value="{{ old('kapasitas_u_slot') }}"
                            required>
                        <small class="text-muted">Maksimal 50U</small>
                        @error('kapasitas_u_slot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea class="form-control summernote @error('keterangan') is-invalid @enderror" 
                            name="keterangan"
                            placeholder="Opsional">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
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
{{-- Modal Hapus --}}
<div class="modal fade" id="hapusRakModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusRak" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4 text-center">
                    <i class="fa-solid fa-triangle-exclamation fa-3x text-warning mb-3"></i>
                    <p class="mb-3">Apakah Anda yakin ingin menghapus rak server <strong id="namaRakHapus"></strong>?</p>
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
    let searchTimeout;
    
    // ==========================================
    // INIT SUMMERNOTE
    // ==========================================
    $('.summernote').summernote({
        height: 120,
        tabsize: 2,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['color', ['color']],
            ['fontsize', ['fontsize']],
            ['fontname', ['fontname']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'table']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Refresh saat modal dibuka
    $('.modal').on('shown.bs.modal', function () {
        $(this).find('.summernote').summernote('refresh');
    });

    // ==========================================
    // FUNGSI DELETE - FIXED
    // ==========================================
    
    // Fungsi untuk bind event delete buttons
    function bindDeleteButtons() {
        // Hapus event listener lama untuk mencegah duplikasi
        $('.btn-hapus').off('click');
        
        // Bind event baru
        $('.btn-hapus').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            
            console.log('Delete button clicked:', { id: id, nama: nama });
            
            if (!id) {
                alert('ID rak tidak ditemukan!');
                return;
            }
            
            // Set nama rak di modal
            $('#namaRakHapus').text(nama || 'rak ini');
            
            // Set action URL untuk form delete - PENTING!
            const deleteRoute = '{{ route("superadmin.rakserver.delete", ":id") }}';
            const deleteUrl = deleteRoute.replace(':id', id);
            $('#formHapusRak').attr('action', deleteUrl);
            
            console.log('Delete URL set to:', deleteUrl);
            
            // Tampilkan modal
            const modalElement = document.getElementById('hapusRakModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            } else {
                console.error('Modal hapusRakModal tidak ditemukan!');
            }
        });
    }

    // Panggil fungsi bind saat halaman load pertama kali
    bindDeleteButtons();

    // ==========================================
    // LIVE SEARCH DENGAN AJAX
    // ==========================================
    
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        const searchValue = $(this).val();

        // Jika kosong, reload halaman untuk kembali ke pagination
        if (searchValue.length === 0) {
            window.location.href = '{{ route("superadmin.rakserver") }}';
            return;
        }

        // Debounce untuk mengurangi request
        searchTimeout = setTimeout(function() {
            if (searchValue.length >= 2) {
                performSearch(searchValue);
            }
        }, 300);
    });

    function performSearch(keyword) {
        // Tampilkan loading indicator jika ada
        if ($('#loadingIndicator').length) {
            $('#loadingIndicator').show();
        }

        $.ajax({
            url: '{{ route("superadmin.rakserver.search") }}',
            method: 'GET',
            data: { search: keyword },
            dataType: 'json',
            success: function(response) {
                if ($('#loadingIndicator').length) {
                    $('#loadingIndicator').hide();
                }
                
                renderSearchResults(response.data);
                
                // Update info hasil pencarian
                if ($('#searchResultInfo').length === 0) {
                    $('.table-responsive').after(
                        '<div id="searchResultInfo" class="mt-3 px-3 text-secondary small"></div>'
                    );
                }
                $('#searchResultInfo').html(
                    'Menampilkan ' + response.found + ' hasil pencarian dari ' + response.total + ' total data'
                );
                
                // Sembunyikan pagination saat search
                if ($('#paginationWrapper').length) {
                    $('#paginationWrapper').hide();
                }
            },
            error: function(xhr, status, error) {
                if ($('#loadingIndicator').length) {
                    $('#loadingIndicator').hide();
                }
                console.error('Search error:', error);
                alert('Terjadi kesalahan saat mencari data');
            }
        });
    }

    function renderSearchResults(data) {
        let html = '';
        
        if (data.length === 0) {
            html = '<tr>' +
                   '<td colspan="8" class="text-center text-muted py-4">' +
                   '<i class="fa fa-search fa-2x mb-2 d-block" style="opacity: 0.3;"></i>' +
                   'Tidak ada data yang sesuai dengan pencarian' +
                   '</td>' +
                   '</tr>';
        } else {
            data.forEach(function(item, index) {
                const sisa = item.kapasitas_u_slot - item.terpakai;
                const sisaBadge = sisa > 0 ? 'bg-success' : 'bg-danger';
                
                html += '<tr class="rak-row" data-id="' + item.rak_id + '">' +
                       '<td>' + (index + 1) + '</td>' +
                       '<td class="rak-nomor"><strong>' + escapeHtml(item.nomor_rak) + '</strong></td>' +
                       '<td class="rak-ruangan">' + escapeHtml(item.ruangan) + '</td>' +
                       '<td class="text-center">' +
                       '<span class="badge bg-secondary">' + item.kapasitas_u_slot + 'U</span>' +
                       '</td>' +
                       '<td class="text-center">' +
                       '<span class="badge bg-primary">' + item.terpakai + 'U</span>' +
                       '</td>' +
                       '<td class="text-center">' +
                       '<span class="badge ' + sisaBadge + '">' + sisa + 'U</span>' +
                       '</td>' +
                       '<td class="rak-keterangan">' +
                       '<small>' + (item.keterangan ? escapeHtml(item.keterangan) : '-') + '</small>' +
                       '</td>' +
                       '<td class="text-center">' +
                       '<div class="d-flex gap-2 justify-content-center">' +
                       '<button class="btn btn-outline-warning btn-sm btn-edit-search" ' +
                       'data-id="' + item.rak_id + '" title="Edit">' +
                       '<i class="fa fa-edit"></i>' +
                       '</button>' +
                       '<button class="btn btn-outline-danger btn-sm btn-hapus" ' +
                       'data-id="' + item.rak_id + '" ' +
                       'data-nama="' + escapeHtml(item.nomor_rak) + '" ' +
                       'title="Hapus">' +
                       '<i class="fa fa-trash"></i>' +
                       '</button>' +
                       '</div>' +
                       '</td>' +
                       '</tr>';
            });
        }

        $('#rakTableBody').html(html);
        
        // PENTING: Re-bind delete buttons setelah render hasil search
        bindDeleteButtons();
    }

    // ==========================================
    // HELPER FUNCTIONS
    // ==========================================
    
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

    // ==========================================
    // EVENT HANDLERS
    // ==========================================
    
    // Handler untuk tombol edit dari hasil search
    $(document).on('click', '.btn-edit-search', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const baseRoute = '{{ route("superadmin.rakserver") }}';
        window.location.href = baseRoute + '?edit=' + id;
    });

    // Clear search on ESC key
    $('#searchInput').on('keydown', function(e) {
        if (e.key === 'Escape') {
            $(this).val('');
            window.location.href = '{{ route("superadmin.rakserver") }}';
        }
    });

    // Konfirmasi saat submit form delete (opsional - sudah ada konfirmasi di modal)
    $('#formHapusRak').on('submit', function(e) {
        console.log('Form delete submitted to:', $(this).attr('action'));
        // Biarkan form submit secara normal
        return true;
    });

    // ==========================================
    // AUTO SHOW MODALS
    // ==========================================
    
    // Auto show modal tambah jika ada validation errors
    @if($errors->any())
        const tambahModal = new bootstrap.Modal(document.getElementById('tambahRakModal'));
        tambahModal.show();
    @endif

    // Auto open edit modal if ?edit=id in URL
    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('edit');
    if (editId) {
        const editModal = document.getElementById('editRakModal' + editId);
        if (editModal) {
            const modal = new bootstrap.Modal(editModal);
            modal.show();
        }
    }

    // ==========================================
    // SMOOTH SCROLL
    // ==========================================
    
    // Smooth scroll to top on pagination click
    $('.pagination-btn').on('click', function(e) {
        if ($(this).hasClass('disabled') || $(this).hasClass('active')) {
            e.preventDefault();
            return false;
        }
        
        $('html, body').animate({
            scrollTop: $('.card-content').offset().top - 100
        }, 400);
    });

    // ==========================================
    // DEBUG INFO
    // ==========================================
    console.log('Rak Server JavaScript initialized');
    console.log('Delete buttons found:', $('.btn-hapus').length);
    console.log('Delete modal exists:', $('#hapusRakModal').length > 0);
});
</script>
@endpush
@endsection