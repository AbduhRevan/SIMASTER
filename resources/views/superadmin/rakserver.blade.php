@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- HEADER -->
<div class="mb-4">
    <h4 class="fw-bold text-dark">Data Master Rak Server</h4>
</div>

<div class="card table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Search -->
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari Nomor Rak/Ruangan/Keterangan...">
            </div>
        </div>

        <!-- Tombol Tambah -->
        <button class="btn btn-maroon px-4 text-white" data-bs-toggle="modal" data-bs-target="#tambahRakModal">
            <i class="fa-solid fa-plus me-2"></i> Tambah Rak Server
        </button>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nomor Rak</th>
                <th>Ruangan</th>
                <th>Kapasitas Total (U)</th>
                <th>Terpakai (U)</th>
                <th>Sisa (U)</th>
                <th>Keterangan</th>
                <th class="text-center" style="width: 100px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="rakTableBody">
            @forelse ($rak as $index => $item)
                @php
                    // Hitung total U terpakai dari semua server di rak ini
                    $terpakai = 0;
                    foreach($item->servers as $server) {
                        // u_slot bisa berisi nilai seperti "1-4" atau "5-8" 
                        if($server->u_slot) {
                            $slots = explode('-', $server->u_slot);
                            if(count($slots) == 2) {
                                $terpakai += (int)$slots[1] - (int)$slots[0] + 1;
                            } else {
                                $terpakai += 1; // jika format tidak sesuai, hitung 1U
                            }
                        }
                    }
                    $sisa = $item->kapasitas_u_slot - $terpakai;
                @endphp
                <tr class="rak-row" data-index="{{ $index }}">
                    <td class="row-number">{{ $index + 1 }}</td>
                    <td class="rak-nomor">{{ $item->nomor_rak }}</td>
                    <td class="rak-ruangan">{{ $item->ruangan }}</td>
                    <td class="text-center">{{ $item->kapasitas_u_slot }}U</td>
                    <td class="text-center">
                        <span class="badge bg-primary">{{ $terpakai }}U</span>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $sisa > 0 ? 'bg-success' : 'bg-danger' }}">{{ $sisa }}U</span>
                    </td>
                    <td class="rak-keterangan">{{ $item->keterangan ?? '-' }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <!-- Edit -->
                            <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal"
                                data-bs-target="#editRakModal{{ $item->rak_id }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Hapus -->
                            <button class="btn btn-danger btn-sm btn-hapus"
                                data-id="{{ $item->rak_id }}"
                                data-nama="{{ $item->nomor_rak }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- MODAL EDIT -->
                <div class="modal fade" id="editRakModal{{ $item->rak_id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-3 overflow-hidden">
                            <div class="modal-header bg-maroon text-white">
                                <h5 class="modal-title">Edit Rak Server</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('superadmin.rakserver.update', $item->rak_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nomor Rak <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nomor_rak" value="{{ $item->nomor_rak }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Ruangan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="ruangan" value="{{ $item->ruangan }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Kapasitas U Slot <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="kapasitas_u_slot" value="{{ $item->kapasitas_u_slot }}" min="1" max="50" required>
                                        <small class="text-muted">Maksimal 50U</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Keterangan</label>
                                        <textarea class="form-control" name="keterangan" rows="3">{{ $item->keterangan }}</textarea>
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
                <tr id="emptyRow">
                    <td colspan="8" class="text-center text-muted">Belum ada data rak server</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted small" id="paginationInfo">
            Menampilkan <span id="startItem">1</span> - <span id="endItem">6</span> dari <span id="totalItems">{{ $rak->count() }}</span> data
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm" id="prevBtn" disabled>
                <i class="fa-solid fa-chevron-left"></i> Previous
            </button>
            <div class="d-flex gap-1" id="pageNumbers"></div>
            <button class="btn btn-outline-secondary btn-sm" id="nextBtn">
                Next <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="tambahRakModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 overflow-hidden">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Tambah Rak Server</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.rakserver.store') }}" method="POST">
                @csrf
                <div class="modal-body">
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
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  name="keterangan" 
                                  rows="3" 
                                  placeholder="Opsional">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
<div class="modal fade" id="hapusRakModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 overflow-hidden">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusRak" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <i class="fa-solid fa-triangle-exclamation fa-3x text-warning mb-3"></i>
                    <p>Apakah Anda yakin ingin menghapus rak server <strong id="namaRakHapus"></strong>?</p>
                    <div class="alert alert-warning small mb-0">
                        Data akan dihapus secara permanen dan tidak dapat dipulihkan.
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
.page-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dee2e6;
    background: white;
    cursor: pointer;
    border-radius: 4px;
    font-size: 14px;
}
.page-btn:hover {
    background: #f8f9fa;
}
.page-btn.active {
    background: #7b0000;
    color: white;
    border-color: #7b0000;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    const itemsPerPage = 6;
    let currentPage = 1;
    let allRows = $('.rak-row');
    let filteredRows = allRows;
    
    function updateRowNumbers() {
        filteredRows.each(function(index) {
            const rowPage = Math.floor(index / itemsPerPage) + 1;
            if (rowPage === currentPage) {
                $(this).find('.row-number').text(index + 1);
            }
        });
    }
    
    function updatePagination() {
        const totalItems = filteredRows.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        
        // Update info
        const startItem = totalItems === 0 ? 0 : (currentPage - 1) * itemsPerPage + 1;
        const endItem = Math.min(currentPage * itemsPerPage, totalItems);
        
        $('#startItem').text(startItem);
        $('#endItem').text(endItem);
        $('#totalItems').text(totalItems);
        
        // Show/hide rows
        allRows.hide();
        filteredRows.each(function(index) {
            const rowPage = Math.floor(index / itemsPerPage) + 1;
            if (rowPage === currentPage) {
                $(this).show();
            }
        });
        
        // Update row numbers
        updateRowNumbers();
        
        // Update buttons
        $('#prevBtn').prop('disabled', currentPage === 1 || totalPages === 0);
        $('#nextBtn').prop('disabled', currentPage === totalPages || totalPages === 0);
        
        // Update page numbers
        renderPageNumbers(totalPages);
        
        // Hide empty/no result rows
        $('#emptyRow, #noResultRow').remove();
        if (totalItems === 0) {
            const message = allRows.length === 0 
                ? 'Belum ada data rak server' 
                : 'Tidak ada data yang sesuai dengan pencarian';
            $('#rakTableBody').append(
                `<tr id="noResultRow"><td colspan="8" class="text-center text-muted">${message}</td></tr>`
            );
        }
    }
    
    function renderPageNumbers(totalPages) {
        const pageNumbersContainer = $('#pageNumbers');
        pageNumbersContainer.empty();
        
        if (totalPages <= 1) return;
        
        let startPage = Math.max(1, currentPage - 1);
        let endPage = Math.min(totalPages, currentPage + 1);
        
        if (currentPage === 1) {
            endPage = Math.min(3, totalPages);
        } else if (currentPage === totalPages) {
            startPage = Math.max(1, totalPages - 2);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = $('<button>')
                .addClass('page-btn')
                .text(i)
                .toggleClass('active', i === currentPage)
                .on('click', function() {
                    currentPage = i;
                    updatePagination();
                });
            pageNumbersContainer.append(pageBtn);
        }
    }
    
    // Initialize
    updatePagination();
    
    // Search functionality
    $('#searchInput').on('keyup', function() {
        const searchValue = $(this).val().toLowerCase();
        
        if (searchValue === '') {
            filteredRows = allRows;
        } else {
            filteredRows = allRows.filter(function() {
                const nomor = $(this).find('.rak-nomor').text().toLowerCase();
                const ruangan = $(this).find('.rak-ruangan').text().toLowerCase();
                const keterangan = $(this).find('.rak-keterangan').text().toLowerCase();
                
                return nomor.includes(searchValue) || 
                       ruangan.includes(searchValue) || 
                       keterangan.includes(searchValue);
            });
        }
        
        currentPage = 1;
        updatePagination();
    });
    
    // Pagination buttons
    $('#prevBtn').on('click', function() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    });
    
    $('#nextBtn').on('click', function() {
        const totalPages = Math.ceil(filteredRows.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
        }
    });
    
    // Modal hapus handler
    $(document).on('click', '.btn-hapus', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        
        $('#namaRakHapus').text(nama);
        $('#formHapusRak').attr('action', `/superadmin/rakserver/delete/${id}`);
        
        const modal = new bootstrap.Modal(document.getElementById('hapusRakModal'));
        modal.show();
    });
    
    // Auto show modal if validation errors
    @if($errors->any())
        var tambahModal = new bootstrap.Modal(document.getElementById('tambahRakModal'));
        tambahModal.show();
    @endif
});
</script>
@endpush
@endsection