@extends('layouts.app')

@section('title', 'Kelola Server')

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

    {{-- ======= RINGKASAN SERVER ======= --}}
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card-stat text-center py-3">
                <div class="stat-label text-uppercase small text-muted mb-2">
                    <i class="fa fa-server me-1"></i>Total Server
                </div>
                <h2 class="stat-value mb-0">{{ $total }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-stat text-center py-3">
                <div class="stat-label text-uppercase small text-muted mb-2">
                    <i class="fa fa-check-circle me-1 text-success"></i>Aktif
                </div>
                <h2 class="stat-value mb-0 text-success">{{ $aktif }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-stat text-center py-3">
                <div class="stat-label text-uppercase small text-muted mb-2">
                    <i class="fa fa-wrench me-1 text-warning"></i>Maintenance
                </div>
                <h2 class="stat-value mb-0 text-warning">{{ $maintenance }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-stat text-center py-3">
                <div class="stat-label text-uppercase small text-muted mb-2">
                    <i class="fa fa-times-circle me-1 text-danger"></i>Tidak Aktif
                </div>
                <h2 class="stat-value mb-0 text-danger">{{ $tidakAktif }}</h2>
            </div>
        </div>
    </div>

    {{-- ======= DAFTAR SERVER ======= --}}
    <div class="card-header-custom d-flex justify-content-between align-items-center">
    <h6 class="mb-0 fw-semibold">
        <i class="fa fa-list me-2"></i> Daftar Server
    </h6>
    <div class="d-flex gap-2">
        <div class="btn-group">
            <button type="button" class="btn btn-danger btn-sm" onclick="exportServerPDF()">
                <i class="fa fa-file-pdf me-1"></i> Export PDF
            </button>
        </div>
        <button class="btn btn-maroon-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
            <i class="fa fa-plus me-1"></i> Tambah Server
        </button>
    </div>
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
                                placeholder="Cari nama/server/rak/bidang/satker...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter untuk Export --}}
            <form id="filterServerForm" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="rak" class="form-select form-select-sm" id="filterRak">
                            <option value="">Filter Rak</option>
                            @foreach($raks as $rak)
                                <option value="{{ $rak->nomor_rak }}">{{ $rak->nomor_rak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="bidang" class="form-select form-select-sm" id="filterBidang">
                            <option value="">Filter Bidang</option>
                            @foreach($bidangs as $bidang)
                                <option value="{{ $bidang->nama_bidang }}">{{ $bidang->nama_bidang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="satker" class="form-select form-select-sm" id="filterSatker">
                            <option value="">Filter Satker</option>
                            @foreach($satkers as $satker)
                                <option value="{{ $satker->nama_satker }}">{{ $satker->nama_satker }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select form-select-sm" id="filterStatus">
                            <option value="">Filter Status</option>
                            <option value="ON">Aktif</option>
                            <option value="STANDBY">Maintenance</option>
                            <option value="OFF">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary btn-sm" onclick="applyFilter()">
                            <i class="fa fa-filter me-1"></i> Terapkan Filter
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="resetFilter()">
                            <i class="fa fa-redo me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Nama Server</th>
                            <th width="12%">Rak / Slot</th>
                            <th width="15%">Bidang</th>
                            <th width="15%">Satker</th>
                            <th width="10%" class="text-center">Website</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="18%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($servers as $index => $server)
                        <tr class="server-row">
                            <td>{{ $index + 1 }}</td>
                            <td class="server-nama"><strong>{{ $server->nama_server }}</strong></td>
                            <td class="server-rak">{{ $server->rak ? $server->rak->nomor_rak : '-' }} / {{ $server->u_slot ?? '-' }}</td>
                            <td class="server-bidang">
                                {{ $server->bidang ? $server->bidang->nama_bidang : '-' }}
                            </td>
                            <td class="server-satker">{{ $server->satker ? $server->satker->nama_satker : '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $server->websites->count() }}</span>
                            </td>
                            <td class="text-center">
                                @if($server->power_status==='ON')
                                    <span class="badge bg-success">
                                        <i class="fa fa-check-circle me-1"></i>Aktif
                                    </span>
                                @elseif($server->power_status==='STANDBY')
                                    <span class="badge bg-warning">
                                        <i class="fa fa-wrench me-1"></i>Maintenance
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fa fa-times-circle me-1"></i>Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- Detail --}}
                                    <button class="btn btn-outline-info btn-sm btn-detail-server" 
                                        data-id="{{ $server->server_id }}" 
                                        title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    
                                    {{-- Edit --}}
                                    <button class="btn btn-outline-warning btn-sm btn-edit-server" 
                                        data-id="{{ $server->server_id }}" 
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    
                                    {{-- Delete --}}
                                    <button class="btn btn-outline-danger btn-sm btn-hapus" 
                                        data-id="{{ $server->server_id }}" 
                                        data-nama="{{ $server->nama_server }}"
                                        title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
<tr>
    <td colspan="8" class="text-center text-muted py-4">
        <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
        Belum ada data server
    </td>
</tr>
@endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail Server --}}
<div class="modal fade" id="modalDetailServer" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-info-circle me-2"></i> Detail Server
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="200">Nama Server</th>
                        <td id="detailNamaServer"></td>
                    </tr>
                    <tr>
                        <th>Brand</th>
                        <td id="detailBrand"></td>
                    </tr>
                    <tr>
                        <th>Spesifikasi</th>
                        <td id="detailSpesifikasi"></td>
                    </tr>
                    <tr>
                        <th>Rak</th>
                        <td id="detailRak"></td>
                    </tr>
                    <tr>
                        <th>Slot</th>
                        <td id="detailUSlot"></td>
                    </tr>
                    <tr>
                        <th>Bidang</th>
                        <td id="detailBidang"></td>
                    </tr>
                    <tr>
                        <th>Satker</th>
                        <td id="detailSatker"></td>
                    </tr>
                    <tr>
                        <th>Website Terhubung</th>
                        <td id="detailWebsites"></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td id="detailStatus"></td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td id="detailKeterangan"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Server --}}
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-plus-circle me-2"></i> Tambah Server Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="serverForm" action="{{ route('superadmin.server.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Server <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_server" placeholder="Contoh: Server-01" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Brand</label>
                            <input type="text" class="form-control" name="brand" placeholder="Contoh: DELL, HP, IBM">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="power_status" required>
                                <option value="">Pilih Status</option>
                                <option value="ON" selected>Aktif</option>
                                <option value="STANDBY">Maintenance</option>
                                <option value="OFF">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Spesifikasi</label>
                        <textarea class="form-control summernote" name="spesifikasi" rows="2" placeholder="Masukkan spesifikasi server"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Rak Server</label>
                            <select class="form-select" name="rak_id" id="rakSelect">
                                <option value="">Pilih Rak</option>
                                @foreach($raks as $rak)
                                    <option value="{{ $rak->rak_id }}" 
                                            data-kapasitas="{{ $rak->kapasitas_u_slot }}"
                                            data-terpakai="{{ $rak->terpakai_u }}"
                                            data-sisa="{{ $rak->sisa_u }}">
                                        {{ $rak->nomor_rak }} ({{ $rak->ruangan }}) - Sisa: {{ $rak->sisa_u }}U / {{ $rak->kapasitas_u_slot }}U
                                    </option>
                                @endforeach
                            </select>
                            <div id="rakInfo" class="slot-info" style="display:none;">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Kapasitas: <span id="rakKapasitas"></span>U | 
                                    Terpakai: <span id="rakTerpakai"></span>U | 
                                    <span class="text-success fw-semibold">Tersedia: <span id="rakSisa"></span>U</span>
                                </small>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3" id="slotWrapper" style="display:none;">
                            <label class="form-label fw-semibold">U-Slot <span class="text-danger">*</span></label>
                            
                            <div class="loading-slots text-center py-2" id="loadingSlots" style="display:none;">
                                <i class="fas fa-spinner fa-spin"></i> Memuat slot...
                            </div>
                            
                            <div id="slotSelectWrapper" style="display:none;">
                                <div class="mb-2">
                                    <label class="form-check-label small me-3">
                                        <input type="radio" name="slot_type" value="single" class="form-check-input" checked> Single Slot
                                    </label>
                                    <label class="form-check-label small">
                                        <input type="radio" name="slot_type" value="range" class="form-check-input"> Range Slot
                                    </label>
                                </div>

                                <div id="singleSlotDiv">
                                    <select class="form-select" name="u_slot_single" id="singleSlotSelect">
                                        <option value="">Pilih Slot</option>
                                    </select>
                                </div>

                                <div id="rangeSlotDiv" style="display:none;">
                                    <div class="row">
                                        <div class="col-6">
                                            <select class="form-select" id="slotStart">
                                                <option value="">Slot Awal</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select class="form-select" id="slotEnd">
                                                <option value="">Slot Akhir</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="u_slot_range" id="slotRangeValue">
                                </div>

                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-lightbulb"></i> Hanya slot yang tersedia yang ditampilkan
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Satuan Kerja</label>
                            <select class="form-select" id="satkerSelect" name="satker_id">
                                <option value="">Pilih Satker</option>
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->satker_id }}" data-name="{{ $satker->nama_satker }}">
                                        {{ $satker->nama_satker }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3" id="bidangWrapper" style="display: none;">
                            <label class="form-label fw-semibold">Bidang</label>
                            <select class="form-select" id="bidangSelect" name="bidang_id">
                                <option value="">Pilih Bidang</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->bidang_id }}">{{ $bidang->nama_bidang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea class="form-control summernote" 
                                name="keterangan"
                                rows="3"
                                placeholder="Tulis keterangan di sini..."></textarea>
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

{{-- Modal Edit Server --}}
<div class="modal fade" id="modalEditServer" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-edit me-2"></i> Edit Server
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editServerForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <input type="hidden" id="editServerId" name="server_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Server <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editNamaServer" name="nama_server" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Brand</label>
                            <input type="text" class="form-control" id="editBrand" name="brand">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="editPowerStatus" name="power_status" required>
                                <option value="ON">Aktif</option>
                                <option value="STANDBY">Maintenance</option>
                                <option value="OFF">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    
                     <div class="mb-3">
                        <label class="form-label fw-semibold">Spesifikasi</label>
                        <textarea class="form-control summernote" name="spesifikasi" rows="2" placeholder="Masukkan spesifikasi server"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Rak Server</label>
                            <select class="form-select" id="editRakId" name="rak_id">
                                <option value="">Pilih Rak</option>
                                @foreach($raks as $rak)
                                    <option value="{{ $rak->rak_id }}" 
                                            data-kapasitas="{{ $rak->kapasitas_u_slot }}"
                                            data-terpakai="{{ $rak->terpakai_u }}"
                                            data-sisa="{{ $rak->sisa_u }}">
                                        {{ $rak->nomor_rak }} ({{ $rak->ruangan }}) - Sisa: {{ $rak->sisa_u }}U
                                    </option>
                                @endforeach
                            </select>
                            <div id="editRakInfo" class="slot-info" style="display:none;">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Kapasitas: <span id="editRakKapasitas"></span>U | 
                                    Terpakai: <span id="editRakTerpakai"></span>U | 
                                    <span class="text-success fw-semibold">Tersedia: <span id="editRakSisa"></span>U</span>
                                </small>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3" id="editSlotWrapper" style="display:none;">
                            <label class="form-label fw-semibold">U-Slot</label>
                            
                            <div class="loading-slots text-center py-2" id="editLoadingSlots" style="display:none;">
                                <i class="fas fa-spinner fa-spin"></i> Memuat slot...
                            </div>
                            
                            <div id="editSlotSelectWrapper" style="display:none;">
                                <div class="mb-2">
                                    <label class="form-check-label small me-3">
                                        <input type="radio" name="edit_slot_type" value="single" class="form-check-input" checked> Single Slot
                                    </label>
                                    <label class="form-check-label small">
                                        <input type="radio" name="edit_slot_type" value="range" class="form-check-input"> Range Slot
                                    </label>
                                </div>

                                <div id="editSingleSlotDiv">
                                    <select class="form-select" name="u_slot_single" id="editSingleSlotSelect">
                                        <option value="">Pilih Slot</option>
                                    </select>
                                </div>

                                <div id="editRangeSlotDiv" style="display:none;">
                                    <div class="row">
                                        <div class="col-6">
                                            <select class="form-select" id="editSlotStart">
                                                <option value="">Slot Awal</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select class="form-select" id="editSlotEnd">
                                                <option value="">Slot Akhir</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="u_slot_range" id="editSlotRangeValue">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Satuan Kerja</label>
                            <select class="form-select" id="editSatkerId" name="satker_id">
                                <option value="">Pilih Satker</option>
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->satker_id }}" data-name="{{ $satker->nama_satker }}">
                                        {{ $satker->nama_satker }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3" id="editBidangWrapper" style="display: none;">
                            <label class="form-label fw-semibold">Bidang</label>
                            <select class="form-select" id="editBidangId" name="bidang_id">
                                <option value="">Pilih Bidang</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->bidang_id }}">{{ $bidang->nama_bidang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea class="form-control summernote" 
                                name="keterangan"
                                rows="3"
                                placeholder="Tulis keterangan di sini..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="fa fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="hapusServerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusServer" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4 text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p class="mb-3">Apakah Anda yakin ingin menghapus server <strong id="namaServerHapus"></strong>?</p>
                    <div class="alert alert-warning small mb-0">
                        <i class="fa fa-info-circle me-1"></i>
                        Data akan dihapus permanen dan tidak dapat dipulihkan.
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
/* Card Statistics */
.card-stat {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.card-stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.12);
}

.stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
}

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

/* Empty State Styling */
tbody tr td.text-center.text-muted {
    background-color: #f8f9fa !important;
}

tbody tr td.text-muted i.fa-inbox {
    color: #6c757d !important;
    opacity: 0.6 !important;
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

.btn-outline-info {
    border-color: #0dcaf0;
    color: #0dcaf0;
}

.btn-outline-info:hover {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #fff;
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

/* Slot Info */
.slot-info {
    margin-top: 8px;
    padding: 8px 12px;
    border-radius: 5px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

/* Responsive */
@media (max-width: 768px) {
    .stat-value {
        font-size: 1.5rem;
    }
    
    .card-header-custom {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    let availableSlots = [];
    let currentServerId = null;

// === EXPORT FUNCTIONS ===
function exportServerPDF() {
    const form = document.getElementById('filterServerForm');
    const url = new URL('{{ route("superadmin.server.export.pdf") }}', window.location.origin);
    
    const formData = new FormData(form);
    for (let [key, value] of formData.entries()) {
        if (value) url.searchParams.append(key, value);
    }
    
    window.open(url, '_blank');
}

// === EXPORT FUNCTIONS ===
window.exportServerPDF = function() {
    const form = document.getElementById('filterServerForm');
    const url = new URL('{{ route("superadmin.server.export.pdf") }}', window.location.origin);
    
    const formData = new FormData(form);
    for (let [key, value] of formData.entries()) {
        if (value) url.searchParams.append(key, value);
    }
    
    window.open(url, '_blank');
};


// === FILTER FUNCTIONS ===
window.applyFilter = function() {
    const rak = $('#filterRak').val();
    const bidang = $('#filterBidang').val();
    const satker = $('#filterSatker').val();
    const status = $('#filterStatus').val();
    
    let visibleRows = 0;
    
    $('.server-row').each(function() {
        let show = true;
        
        // Filter Rak
        if (rak && !$(this).find('.server-rak').text().includes(rak)) {
            show = false;
        }
        
        // Filter Bidang
        if (bidang && !$(this).find('.server-bidang').text().includes(bidang)) {
            show = false;
        }
        
        // Filter Satker
        if (satker && !$(this).find('.server-satker').text().includes(satker)) {
            show = false;
        }
        
        // Filter Status
        if (status) {
            const statusCell = $(this).find('td:nth-child(7)');
            let matchStatus = false;
            
            if (status === 'ON' && statusCell.find('.badge.bg-success').length > 0) {
                matchStatus = true;
            } else if (status === 'STANDBY' && statusCell.find('.badge.bg-warning').length > 0) {
                matchStatus = true;
            } else if (status === 'OFF' && statusCell.find('.badge.bg-danger').length > 0) {
                matchStatus = true;
            }
            
            if (!matchStatus) {
                show = false;
            }
        }
        
        if (show) {
            $(this).show();
            visibleRows++;
        } else {
            $(this).hide();
        }
    });
    
    // Show no results message
    if (visibleRows === 0 && $('.server-row').length > 0) {
        if ($('#noFilterResultRow').length === 0) {
            $('table tbody').append(
                '<tr id="noFilterResultRow"><td colspan="8" class="text-center text-muted py-4"><i class="fa fa-filter fa-2x mb-2 d-block" style="opacity: 0.3;"></i>Tidak ada data yang sesuai dengan filter</td></tr>'
            );
        }
    } else {
        $('#noFilterResultRow').remove();
    }
};

window.resetFilter = function() {
    $('#filterRak, #filterBidang, #filterSatker, #filterStatus').val('');
    $('.server-row').show();
    $('#noFilterResultRow').remove();
};

    // === SUMMERNOTE INITIALIZATION ===
    $('.summernote').summernote({
        height: 120,
        tabsize: 2,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['font', ['fontsize', 'fontname']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'table']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Roboto', 'Times New Roman'],
        fontSizes: ['8','9','10','11','12','14','16','18','20','24','28','32','36']
    });

     // Refresh saat modal dibuka
    $('.modal').on('shown.bs.modal', function () {
        $(this).find('.summernote').summernote('refresh');
    });

    // === SLOT TYPE TOGGLE ===
    $('input[name="slot_type"]').change(function() {
        if($(this).val() === 'single') {
            $('#singleSlotDiv').show();
            $('#rangeSlotDiv').hide();
        } else {
            $('#singleSlotDiv').hide();
            $('#rangeSlotDiv').show();
        }
    });

    $('input[name="edit_slot_type"]').change(function() {
        if($(this).val() === 'single') {
            $('#editSingleSlotDiv').show();
            $('#editRangeSlotDiv').hide();
        } else {
            $('#editSingleSlotDiv').hide();
            $('#editRangeSlotDiv').show();
        }
    });

    // === RAK SELECTED ===
    $('#rakSelect').change(function() {
        const rakId = $(this).val();
        
        if(rakId) {
            const selectedOption = $(this).find('option:selected');
            $('#rakKapasitas').text(selectedOption.data('kapasitas'));
            $('#rakTerpakai').text(selectedOption.data('terpakai'));
            $('#rakSisa').text(selectedOption.data('sisa'));
            $('#rakInfo').show();
            loadAvailableSlots(rakId);
        } else {
            $('#rakInfo').hide();
            $('#slotWrapper').hide();
        }
    });

    $('#editRakId').change(function() {
        const rakId = $(this).val();
        
        if(rakId) {
            const selectedOption = $(this).find('option:selected');
            $('#editRakKapasitas').text(selectedOption.data('kapasitas'));
            $('#editRakTerpakai').text(selectedOption.data('terpakai'));
            $('#editRakSisa').text(selectedOption.data('sisa'));
            $('#editRakInfo').show();
            loadAvailableSlotsForEdit(rakId, currentServerId);
        } else {
            $('#editRakInfo').hide();
            $('#editSlotWrapper').hide();
        }
    });

    // === LOAD AVAILABLE SLOTS ===
    function loadAvailableSlots(rakId) {
        $('#loadingSlots').show();
        $('#slotSelectWrapper').hide();
        $('#slotWrapper').show();

        $.ajax({
            url: `/superadmin/server/rak/${rakId}/available-slots`,
            type: 'GET',
            success: function(response) {
                availableSlots = response.available_slots;
                populateSlotDropdowns(availableSlots);
                $('#loadingSlots').hide();
                $('#slotSelectWrapper').show();
            },
            error: function() {
                alert('Gagal memuat data slot');
                $('#loadingSlots').hide();
            }
        });
    }

    function loadAvailableSlotsForEdit(rakId, serverId) {
        $('#editLoadingSlots').show();
        $('#editSlotSelectWrapper').hide();
        $('#editSlotWrapper').show();

        $.ajax({
            url: `/superadmin/server/rak/${rakId}/available-slots`,
            type: 'GET',
            success: function(response) {
                availableSlots = response.available_slots;
                populateEditSlotDropdowns(availableSlots);
                $('#editLoadingSlots').hide();
                $('#editSlotSelectWrapper').show();
            },
            error: function() {
                alert('Gagal memuat data slot');
                $('#editLoadingSlots').hide();
            }
        });
    }

    function populateSlotDropdowns(slots) {
        $('#singleSlotSelect, #slotStart, #slotEnd').empty().append('<option value="">Pilih Slot</option>');
        slots.forEach(slot => {
            $('#singleSlotSelect').append(`<option value="${slot}">Slot ${slot}U</option>`);
            $('#slotStart, #slotEnd').append(`<option value="${slot}">${slot}U</option>`);
        });
    }

    function populateEditSlotDropdowns(slots) {
        $('#editSingleSlotSelect, #editSlotStart, #editSlotEnd').empty().append('<option value="">Pilih Slot</option>');
        slots.forEach(slot => {
            $('#editSingleSlotSelect').append(`<option value="${slot}">Slot ${slot}U</option>`);
            $('#editSlotStart, #editSlotEnd').append(`<option value="${slot}">${slot}U</option>`);
        });
    }

    // === RANGE SLOT CHANGE ===
    $('#slotStart, #slotEnd').change(function() {
        const start = $('#slotStart').val();
        const end = $('#slotEnd').val();
        if(start && end) {
            $('#slotRangeValue').val(`${start}-${end}`);
        }
    });

    $('#editSlotStart, #editSlotEnd').change(function() {
        const start = $('#editSlotStart').val();
        const end = $('#editSlotEnd').val();
        if(start && end) {
            $('#editSlotRangeValue').val(`${start}-${end}`);
        }
    });

    // === FORM SUBMIT ===
    $('#serverForm').submit(function(e) {
        const slotType = $('input[name="slot_type"]:checked').val();
        if($('#rakSelect').val()) {
            let slotValue = slotType === 'single' ? $('#singleSlotSelect').val() : $('#slotRangeValue').val();
            $('input[name="u_slot"]').remove();
            $(this).append(`<input type="hidden" name="u_slot" value="${slotValue}">`);
        }
    });

    $('#editServerForm').submit(function(e) {
        const slotType = $('input[name="edit_slot_type"]:checked').val();
        if($('#editRakId').val()) {
            let slotValue = slotType === 'single' ? $('#editSingleSlotSelect').val() : $('#editSlotRangeValue').val();
            $('input[name="u_slot"]').remove();
            $(this).append(`<input type="hidden" name="u_slot" value="${slotValue}">`);
        }
    });

    // === DETAIL SERVER ===
    $(document).on('click', '.btn-detail-server', function () {
        let id = $(this).data('id');
        $.ajax({
            url: `/superadmin/server/${id}/detail`,
            type: "GET",
            success: function (response) {
                let s = response.data;
                $('#detailNamaServer').text(s.nama_server ?? '-');
                $('#detailBrand').text(s.brand ?? '-');
                $('#detailSpesifikasi').html(s.spesifikasi ?? '-');
                $('#detailRak').text(s.rak ? s.rak.nomor_rak : '-');
                $('#detailUSlot').text(s.u_slot ?? '-');
                $('#detailBidang').text(s.bidang ? s.bidang.nama_bidang : '-');
                $('#detailSatker').text(s.satker ? s.satker.nama_satker : '-');
                
                let websitesHtml = '-';
                if(s.websites && s.websites.length > 0) {
                    websitesHtml = '<ul class="mb-0">';
                    s.websites.forEach(w => {
                        websitesHtml += `<li><a href="${w.url}" target="_blank">${w.nama_website}</a></li>`;
                    });
                    websitesHtml += '</ul>';
                }
                $('#detailWebsites').html(websitesHtml);
                
                let statusBadge = s.power_status === 'ON' ? '<span class="badge bg-success">Aktif</span>' :
                                  s.power_status === 'STANDBY' ? '<span class="badge bg-warning">Maintenance</span>' :
                                  '<span class="badge bg-danger">Tidak Aktif</span>';
                $('#detailStatus').html(statusBadge);
                $('#detailKeterangan').html(s.keterangan ?? '-');
                $('#modalDetailServer').modal('show');
            }
        });
    });

    // === EDIT SERVER ===
        $(document).on('click', '.btn-edit-server', function () {
        let id = $(this).data('id');
        currentServerId = id;
        
        $.ajax({
            url: `/superadmin/server/${id}/edit`,
            type: "GET",
            success: function (response) {
                let s = response.data;
                
                // Set basic data
                $('#editServerId').val(s.server_id);
                $('#editNamaServer').val(s.nama_server);
                $('#editBrand').val(s.brand);
                $('#editPowerStatus').val(s.power_status);
                $('#editRakId').val(s.rak_id);
                $('#editSatkerId').val(s.satker_id);
                $('#editBidangId').val(s.bidang_id);
                
                // Set Summernote untuk Spesifikasi
                $('#editServerForm').find('[name="spesifikasi"]').summernote('code', s.spesifikasi || '');
                
                // Set Summernote untuk Keterangan
                $('#editServerForm').find('[name="keterangan"]').summernote('code', s.keterangan || '');
                
                // Check dan tampilkan bidang jika satker = Pusat Data dan Informasi
                const selectedSatker = $('#editSatkerId option:selected').data('name');
                if (selectedSatker && selectedSatker.includes('Pusat Data dan Informasi')) {
                    $('#editBidangWrapper').show();
                } else {
                    $('#editBidangWrapper').hide();
                }
                
                // Handle Rak dan Slot
                if(s.rak_id) {
                    $('#editRakId').trigger('change');
                    
                    // Tunggu slot selesai load, baru set value
                    setTimeout(() => {
                        if(s.u_slot) {
                            // Check apakah range atau single
                            if(s.u_slot.includes('-')) {
                                // Range slot
                                $('input[name="edit_slot_type"][value="range"]').prop('checked', true).trigger('change');
                                const parts = s.u_slot.split('-');
                                $('#editSlotStart').val(parts[0]);
                                $('#editSlotEnd').val(parts[1]);
                                $('#editSlotRangeValue').val(s.u_slot);
                            } else {
                                // Single slot
                                $('input[name="edit_slot_type"][value="single"]').prop('checked', true).trigger('change');
                                $('#editSingleSlotSelect').val(s.u_slot);
                            }
                        }
                    }, 500);
                }
                
                // Set form action
                $('#editServerForm').attr('action', `/superadmin/server/update/${s.server_id}`);
                
                // Show modal
                $('#modalEditServer').modal('show');
            },
            error: function() {
                alert('Gagal memuat data server');
            }
        });
    });

    // === BIDANG VISIBILITY ===
    $('#satkerSelect, #editSatkerId').change(function(){
        const selectedName = $(this).find('option:selected').data('name');
        const isEdit = $(this).attr('id') === 'editSatkerId';
        const bidangWrapper = isEdit ? '#editBidangWrapper' : '#bidangWrapper';
        const bidangSelect = isEdit ? '#editBidangId' : '#bidangSelect';
        
        if(selectedName && selectedName.includes('Pusat Data dan Informasi')){
            $(bidangWrapper).show();
        } else {
            $(bidangWrapper).hide();
            $(bidangSelect).val('');
        }
    });

    // === SEARCH ===
    $('#searchInput').on('keyup', function() {
        const searchValue = $(this).val().toLowerCase();
        let visibleRows = 0;

        $('.server-row').each(function() {
            const nama = $(this).find('.server-nama').text().toLowerCase();
            const rak = $(this).find('.server-rak').text().toLowerCase();
            const bidang = $(this).find('.server-bidang').text().toLowerCase();
            const satker = $(this).find('.server-satker').text().toLowerCase();
            
            if (nama.includes(searchValue) || rak.includes(searchValue) ||
                bidang.includes(searchValue) || satker.includes(searchValue)) {
                $(this).show();
                visibleRows++;
            } else {
                $(this).hide();
            }
        });

        if (visibleRows === 0 && $('.server-row').length > 0) {
            if ($('#noResultRow').length === 0) {
                $('table tbody').append(
                    '<tr id="noResultRow"><td colspan="8" class="text-center text-muted py-4"><i class="fa fa-search fa-2x mb-2 d-block" style="opacity: 0.3;"></i>Tidak ada data yang sesuai dengan pencarian</td></tr>'
                );
            }
        } else {
            $('#noResultRow').remove();
        }
    });

    // === MODAL HAPUS ===
    $('.btn-hapus').on('click', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        $('#namaServerHapus').text(nama);
        $('#formHapusServer').attr('action', `/superadmin/server/${id}`);
        const modal = new bootstrap.Modal(document.getElementById('hapusServerModal'));
        modal.show();
    });
});
</script>
@endpush
@endsection