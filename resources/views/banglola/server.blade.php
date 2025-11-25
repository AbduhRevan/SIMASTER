@extends('layouts.banglola')
@section('title', 'Kelola Server')

@section('content')
<style>
/* Modal Header Maroon */
.modal-header.maroon-header {
    background-color: #800000;
    color: white;
}

/* Tombol Maroon */
.btn-maroon {
    background-color: #800000;
    color: white;
    border: none;
}

.btn-maroon:hover {
    background-color: #660000;
    color: white;
}

/* Card Summary Style */
.card-summary {
    background-color: #fff;
    border-radius: 0.5rem;
    padding: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.table-card {
    background-color: #fff;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Search Box dengan Icon */
.search-wrapper {
    position: relative;
    width: 280px;
}

.search-wrapper .search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    pointer-events: none;
}

.search-wrapper input {
    padding-left: 38px;
}

/* Tombol Aksi */
.action-buttons {
    display: inline-flex;
    gap: 5px;
}

.btn-action {
    width: 32px;
    height: 32px;
    padding: 0;
    border: none;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.btn-action i {
    font-size: 14px;
}

.btn-detail {
    background-color: #17a2b8;
    color: white;
}

.btn-detail:hover {
    background-color: #138496;
    color: white;
}

.btn-edit {
    background-color: #ffc107;
    color: white;
}

.btn-edit:hover {
    background-color: #e0a800;
    color: white;
}

.btn-delete {
    background-color: #dc3545;
    color: white;
}

.btn-delete:hover {
    background-color: #c82333;
    color: white;
}

/* Slot Info Badge */
.slot-info {
    font-size: 0.85rem;
    margin-top: 5px;
    padding: 5px 10px;
    border-radius: 5px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.slot-available {
    color: #28a745;
    font-weight: 600;
}

.slot-occupied {
    color: #dc3545;
    font-weight: 600;
}

/* Loading Indicator */
.loading-slots {
    display: none;
    text-align: center;
    padding: 10px;
    color: #6c757d;
}
</style>

<div class="container-fluid">
    <!-- Alert Messages -->
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

    <!-- Ringkasan Server -->
    <div class="row mb-4 text-center">
        <div class="col-6 col-md-3 mb-3">
            <div class="card-summary">
                <h5>Total</h5>
                <h2>{{ $total }}</h2>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card-summary">
                <h5>Aktif</h5>
                <h2>{{ $aktif }}</h2>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card-summary">
                <h5>Maintenance</h5>
                <h2>{{ $maintenance }}</h2>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card-summary">
                <h5>Tidak Aktif</h5>
                <h2>{{ $tidakAktif }}</h2>
            </div>
        </div>
    </div>

    <!-- Daftar Server -->
    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Search dengan Icon -->
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari nama/server/rak">
            </div>
            
            <!-- Tombol Tambah Maroon -->
            <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fa fa-plus"></i> Tambah Server
            </button>
        </div>

        <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Nama Server</th>
                    <th class="text-center">Rak / Slot</th>
                    <th class="text-center">Bidang</th>
                    <th class="text-center">Satker</th>
                    <th class="text-center">Jumlah Website</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servers as $index => $server)
                <tr class="server-row">
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td class="text-center server-nama">{{ $server->nama_server }}</td>
                    <td class="text-center server-rak">{{ $server->rak ? $server->rak->nomor_rak : '-' }} / {{ $server->u_slot ?? '-' }}</td>
                    <td class="text-center server-bidang">
                        {{ $server->bidang ? $server->bidang->nama_bidang : '-' }}
                    </td>
                    <td class="text-center server-satker">{{ $server->satker ? $server->satker->nama_satker : '-' }}</td>
                    <td class="text-center">
                        <span class="badge bg-info">{{ $server->websites->count() }} Website</span>
                    </td>
                    <td class="text-center">
                        @if($server->power_status==='ON')
                            <span class="badge bg-success">Aktif</span>
                        @elseif($server->power_status==='STANDBY')
                            <span class="badge bg-warning text-dark">Maintenance</span>
                        @else
                            <span class="badge bg-danger">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="action-buttons">
                            <!-- Tombol Detail -->
                            <button class="btn-action btn-detail btn-detail-server" 
                                    data-id="{{ $server->server_id }}" 
                                    title="Detail">
                                <i class="fa fa-eye"></i>
                            </button>
                            
                            <!-- Tombol Edit -->
                            <button class="btn-action btn-edit btn-edit-server" 
                                    data-id="{{ $server->server_id }}" 
                                    title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            
                            <!-- Tombol Delete -->
                            <button class="btn-action btn-delete btn-hapus" 
                                    data-id="{{ $server->server_id }}" 
                                    data-nama="{{ $server->nama_server }}"
                                    title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>

<!-- Modal Detail Server -->
<div class="modal fade" id="modalDetailServer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header maroon-header">
                <h5 class="modal-title">Detail Server</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
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
        </div>
    </div>
</div>

<!-- Modal Tambah Server -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content border-0 rounded-4 shadow">
            
            <div class="modal-header bg-maroon text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold">Tambah Server Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="serverForm" action="{{ route('banglola.server.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 pb-4">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Server <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_server" placeholder="Contoh: Server-01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Brand</label>
                        <input type="text" class="form-control" name="brand" placeholder="Contoh: DELL, HP, IBM">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Spesifikasi</label>
                        <textarea class="form-control" name="spesifikasi" rows="2" placeholder="Masukkan spesifikasi server"></textarea>
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
                                <small>
                                    <i class="fas fa-info-circle"></i> 
                                    Kapasitas: <span id="rakKapasitas"></span>U | 
                                    Terpakai: <span id="rakTerpakai"></span>U | 
                                    <span class="slot-available">Tersedia: <span id="rakSisa"></span>U</span>
                                </small>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3" id="slotWrapper" style="display:none;">
                            <label class="form-label fw-semibold">U-Slot <span class="text-danger">*</span></label>
                            
                            <div class="loading-slots" id="loadingSlots">
                                <i class="fas fa-spinner fa-spin"></i> Memuat slot...
                            </div>
                            
                            <div id="slotSelectWrapper" style="display:none;">
                                <div class="mb-2">
                                    <label class="form-check-label small">
                                        <input type="radio" name="slot_type" value="single" class="form-check-input" checked> Single Slot
                                    </label>
                                    <label class="form-check-label small ms-3">
                                        <input type="radio" name="slot_type" value="range" class="form-check-input"> Range Slot
                                    </label>
                                </div>

                                <!-- Single Slot -->
                                <div id="singleSlotDiv">
                                    <select class="form-select" name="u_slot_single" id="singleSlotSelect">
                                        <option value="">Pilih Slot</option>
                                    </select>
                                </div>

                                <!-- Range Slot -->
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
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="power_status" required>
                            <option value="">Pilih Status</option>
                            <option value="ON" selected>Aktif</option>
                            <option value="STANDBY">Maintenance</option>
                            <option value="OFF">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3" placeholder="Tulis keterangan di sini..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Server -->
<div class="modal fade" id="modalEditServer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header maroon-header">
                <h5 class="modal-title">Edit Server</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="editServerForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editServerId" name="server_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Server <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editNamaServer" name="nama_server" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" class="form-control" id="editBrand" name="brand">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Spesifikasi</label>
                        <textarea class="form-control" id="editSpesifikasi" name="spesifikasi" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Rak Server</label>
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
                                <small>
                                    <i class="fas fa-info-circle"></i> 
                                    Kapasitas: <span id="editRakKapasitas"></span>U | 
                                    Terpakai: <span id="editRakTerpakai"></span>U | 
                                    <span class="slot-available">Tersedia: <span id="editRakSisa"></span>U</span>
                                </small>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3" id="editSlotWrapper" style="display:none;">
                            <label class="form-label">U-Slot</label>
                            
                            <div class="loading-slots" id="editLoadingSlots">
                                <i class="fas fa-spinner fa-spin"></i> Memuat slot...
                            </div>
                            
                            <div id="editSlotSelectWrapper" style="display:none;">
                                <div class="mb-2">
                                    <label class="form-check-label small">
                                        <input type="radio" name="edit_slot_type" value="single" class="form-check-input" checked> Single Slot
                                    </label>
                                    <label class="form-check-label small ms-3">
                                        <input type="radio" name="edit_slot_type" value="range" class="form-check-input"> Range Slot
                                    </label>
                                </div>

                                <!-- Single Slot -->
                                <div id="editSingleSlotDiv">
                                    <select class="form-select" name="u_slot_single" id="editSingleSlotSelect">
                                        <option value="">Pilih Slot</option>
                                    </select>
                                </div>

                                <!-- Range Slot -->
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

                    <div class="mb-3">
                        <label class="form-label">Satuan Kerja</label>
                        <select class="form-select" id="editSatkerId" name="satker_id">
                            <option value="">Pilih Satker</option>
                            @foreach($satkers as $satker)
                                <option value="{{ $satker->satker_id }}" data-name="{{ $satker->nama_satker }}">
                                    {{ $satker->nama_satker }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="editBidangWrapper" style="display: none;">
                        <label class="form-label">Bidang</label>
                        <select class="form-select" id="editBidangId" name="bidang_id">
                            <option value="">Pilih Bidang</option>
                            @foreach($bidangs as $bidang)
                                <option value="{{ $bidang->bidang_id }}">{{ $bidang->nama_bidang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="editPowerStatus" name="power_status" required>
                            <option value="ON">Aktif</option>
                            <option value="STANDBY">Maintenance</option>
                            <option value="OFF">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" id="editKeterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="hapusServerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 overflow-hidden">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapusServer" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    </div>
                    
                    <p class="mb-3">Apakah Anda yakin ingin menghapus server <strong id="namaServerHapus"></strong>?</p>
                    
                    <div class="alert alert-warning small mb-0">
                        Data akan dihapus permanen dan tidak dapat dipulihkan.
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let availableSlots = [];
    let occupiedSlots = [];
    let currentServerId = null; // untuk edit

    // === FORM TAMBAH: Slot Type Toggle ===
    $('input[name="slot_type"]').change(function() {
        if($(this).val() === 'single') {
            $('#singleSlotDiv').show();
            $('#rangeSlotDiv').hide();
        } else {
            $('#singleSlotDiv').hide();
            $('#rangeSlotDiv').show();
        }
    });

    // === FORM EDIT: Slot Type Toggle ===
    $('input[name="edit_slot_type"]').change(function() {
        if($(this).val() === 'single') {
            $('#editSingleSlotDiv').show();
            $('#editRangeSlotDiv').hide();
        } else {
            $('#editSingleSlotDiv').hide();
            $('#editRangeSlotDiv').show();
        }
    });

    // === FORM TAMBAH: Rak Selected ===
    $('#rakSelect').change(function() {
        const rakId = $(this).val();
        
        if(rakId) {
            const selectedOption = $(this).find('option:selected');
            const kapasitas = selectedOption.data('kapasitas');
            const terpakai = selectedOption.data('terpakai');
            const sisa = selectedOption.data('sisa');

            // Show info
            $('#rakKapasitas').text(kapasitas);
            $('#rakTerpakai').text(terpakai);
            $('#rakSisa').text(sisa);
            $('#rakInfo').show();

            // Load available slots
            loadAvailableSlots(rakId);
        } else {
            $('#rakInfo').hide();
            $('#slotWrapper').hide();
        }
    });

    // === FORM EDIT: Rak Selected ===
    $('#editRakId').change(function() {
        const rakId = $(this).val();
        
        if(rakId) {
            const selectedOption = $(this).find('option:selected');
            const kapasitas = selectedOption.data('kapasitas');
            const terpakai = selectedOption.data('terpakai');
            const sisa = selectedOption.data('sisa');

            $('#editRakKapasitas').text(kapasitas);
            $('#editRakTerpakai').text(terpakai);
            $('#editRakSisa').text(sisa);
            $('#editRakInfo').show();

            loadAvailableSlotsForEdit(rakId, currentServerId);
        } else {
            $('#editRakInfo').hide();
            $('#editSlotWrapper').hide();
        }
    });

    // === Function: Load Available Slots (TAMBAH) ===
    function loadAvailableSlots(rakId) {
        console.log('🔍 Loading slots for rak_id:', rakId); // Debug
        
        $('#loadingSlots').show();
        $('#slotSelectWrapper').hide();
        $('#slotWrapper').show();

        $.ajax({
            url: `/banglola/server/rak/${rakId}/available-slots`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('✅ Response received:', response); // Debug
                
                if(response.success && response.available_slots) {
                    availableSlots = response.available_slots;
                    occupiedSlots = response.occupied_slots || [];
                    
                    console.log('Available slots:', availableSlots); // Debug
                    console.log('Occupied slots:', occupiedSlots); // Debug
                    
                    populateSlotDropdowns(availableSlots);
                    
                    $('#loadingSlots').hide();
                    $('#slotSelectWrapper').show();
                } else {
                    console.error('❌ Invalid response format:', response);
                    alert('Format response tidak valid');
                    $('#loadingSlots').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ AJAX Error:');
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response:', xhr.responseText);
                console.error('Status Code:', xhr.status);
                
                let errorMsg = 'Gagal memuat data slot';
                
                if(xhr.status === 404) {
                    errorMsg = 'Route tidak ditemukan (404). Periksa URL.';
                } else if(xhr.status === 500) {
                    errorMsg = 'Server error (500). Cek log Laravel.';
                    try {
                        const errorData = JSON.parse(xhr.responseText);
                        if(errorData.message) {
                            errorMsg += '\n' + errorData.message;
                        }
                    } catch(e) {}
                } else if(xhr.status === 0) {
                    errorMsg = 'Tidak dapat terhubung ke server. Periksa koneksi.';
                }
                
                alert(errorMsg);
                $('#loadingSlots').hide();
            }
        });
    }

    // === Function: Load Available Slots (EDIT) ===
    function loadAvailableSlotsForEdit(rakId, serverId) {
        console.log('🔍 Loading slots for edit - rak_id:', rakId, 'server_id:', serverId); // Debug
        
        $('#editLoadingSlots').show();
        $('#editSlotSelectWrapper').hide();
        $('#editSlotWrapper').show();

        $.ajax({
            url: `/banglola/server/rak/${rakId}/available-slots`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('✅ Edit Response received:', response); // Debug
                
                if(response.success && response.available_slots) {
                    availableSlots = response.available_slots;
                    occupiedSlots = response.occupied_slots || [];
                    
                    populateEditSlotDropdowns(availableSlots);
                    
                    $('#editLoadingSlots').hide();
                    $('#editSlotSelectWrapper').show();
                } else {
                    console.error('❌ Invalid response format:', response);
                    alert('Format response tidak valid');
                    $('#editLoadingSlots').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Edit AJAX Error:');
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response:', xhr.responseText);
                console.error('Status Code:', xhr.status);
                
                let errorMsg = 'Gagal memuat data slot';
                
                if(xhr.status === 404) {
                    errorMsg = 'Route tidak ditemukan (404)';
                } else if(xhr.status === 500) {
                    errorMsg = 'Server error (500). Cek log Laravel.';
                }
                
                alert(errorMsg);
                $('#editLoadingSlots').hide();
            }
        });
    }

    // === Function: Populate Slot Dropdowns (TAMBAH) ===
    function populateSlotDropdowns(slots) {
        console.log('📋 Populating dropdowns with slots:', slots); // Debug
        
        // Single slot
        $('#singleSlotSelect').empty().append('<option value="">Pilih Slot</option>');
        slots.forEach(slot => {
            $('#singleSlotSelect').append(`<option value="${slot}">Slot ${slot}U</option>`);
        });

        // Range slot (start & end)
        $('#slotStart, #slotEnd').empty().append('<option value="">Pilih</option>');
        slots.forEach(slot => {
            $('#slotStart').append(`<option value="${slot}">${slot}U</option>`);
            $('#slotEnd').append(`<option value="${slot}">${slot}U</option>`);
        });
        
        console.log('✅ Dropdowns populated'); // Debug
    }

    // === Function: Populate Slot Dropdowns (EDIT) ===
    function populateEditSlotDropdowns(slots) {
        console.log('📋 Populating edit dropdowns with slots:', slots); // Debug
        
        // Single slot
        $('#editSingleSlotSelect').empty().append('<option value="">Pilih Slot</option>');
        slots.forEach(slot => {
            $('#editSingleSlotSelect').append(`<option value="${slot}">Slot ${slot}U</option>`);
        });

        // Range slot
        $('#editSlotStart, #editSlotEnd').empty().append('<option value="">Pilih</option>');
        slots.forEach(slot => {
            $('#editSlotStart').append(`<option value="${slot}">${slot}U</option>`);
            $('#editSlotEnd').append(`<option value="${slot}">${slot}U</option>`);
        });
        
        console.log('✅ Edit dropdowns populated'); // Debug
    }

    // === Handle Range Slot Change (TAMBAH) ===
    $('#slotStart, #slotEnd').change(function() {
        const start = $('#slotStart').val();
        const end = $('#slotEnd').val();
        
        if(start && end) {
            $('#slotRangeValue').val(`${start}-${end}`);
        } else {
            $('#slotRangeValue').val('');
        }
    });

    // === Handle Range Slot Change (EDIT) ===
    $('#editSlotStart, #editSlotEnd').change(function() {
        const start = $('#editSlotStart').val();
        const end = $('#editSlotEnd').val();
        
        if(start && end) {
            $('#editSlotRangeValue').val(`${start}-${end}`);
        } else {
            $('#editSlotRangeValue').val('');
        }
    });

    // === Form Submit: Set correct u_slot value ===
    $('#serverForm').submit(function(e) {
        const slotType = $('input[name="slot_type"]:checked').val();
        
        if($('#rakSelect').val()) {
            let slotValue = '';
            
            if(slotType === 'single') {
                slotValue = $('#singleSlotSelect').val();
            } else {
                slotValue = $('#slotRangeValue').val();
            }
            
            // Remove existing hidden input
            $('input[name="u_slot"]').remove();
            
            // Add hidden input with correct value
            $(this).append(`<input type="hidden" name="u_slot" value="${slotValue}">`);
        }
    });

    // === Edit Form Submit ===
    $('#editServerForm').submit(function(e) {
        const slotType = $('input[name="edit_slot_type"]:checked').val();
        
        if($('#editRakId').val()) {
            let slotValue = '';
            
            if(slotType === 'single') {
                slotValue = $('#editSingleSlotSelect').val();
            } else {
                slotValue = $('#editSlotRangeValue').val();
            }
            
            $('input[name="u_slot"]').remove();
            $(this).append(`<input type="hidden" name="u_slot" value="${slotValue}">`);
        }
    });

    // === Detail Server AJAX ===
    $(document).on('click', '.btn-detail-server', function () {
        let id = $(this).data('id');

        $.ajax({
            url: `/banglola/server/${id}/detail`,
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
                
                // Tampilkan daftar website
                let websitesHtml = '-';
                if(s.websites && s.websites.length > 0) {
                    websitesHtml = '<ul class="mb-0">';
                    s.websites.forEach(w => {
                        websitesHtml += `<li><a href="${w.url}" target="_blank">${w.nama_website}</a></li>`;
                    });
                    websitesHtml += '</ul>';
                }
                $('#detailWebsites').html(websitesHtml);
                
                // Status
                let statusBadge = '';
                if(s.power_status === 'ON') {
                    statusBadge = '<span class="badge bg-success">Aktif</span>';
                } else if(s.power_status === 'STANDBY') {
                    statusBadge = '<span class="badge bg-warning text-dark">Maintenance</span>';
                } else {
                    statusBadge = '<span class="badge bg-danger">Tidak Aktif</span>';
                }
                $('#detailStatus').html(statusBadge);
                
                $('#detailKeterangan').html(s.keterangan ?? '-');
                $('#modalDetailServer').modal('show');
            },
            error: function(xhr) {
                alert('Gagal memuat data server');
                console.error(xhr);
            }
        });
    });

    // === Edit Server - Load Data ===
    $(document).on('click', '.btn-edit-server', function () {
        let id = $(this).data('id');
        currentServerId = id;

        $.ajax({
            url: `/banglola/server/${id}/edit`,
            type: "GET",
            success: function (response) {
                let s = response.data;

                $('#editServerId').val(s.server_id);
                $('#editNamaServer').val(s.nama_server);
                $('#editBrand').val(s.brand);
                $('#editSpesifikasi').val(s.spesifikasi);
                $('#editRakId').val(s.rak_id);
                $('#editSatkerId').val(s.satker_id);
                $('#editBidangId').val(s.bidang_id);
                $('#editPowerStatus').val(s.power_status);
                $('#editKeterangan').val(s.keterangan || '');

                // Cek bidang visibility
                const selectedSatker = $('#editSatkerId option:selected').data('name');
                if (selectedSatker === 'Pusat Data dan Informasi Kemhan') {
                    $('#editBidangWrapper').show();
                } else {
                    $('#editBidangWrapper').hide();
                }

                // Trigger rak change untuk load slots
                if(s.rak_id) {
                    $('#editRakId').trigger('change');
                    
                    // Set slot value after slots loaded
                    setTimeout(() => {
                        if(s.u_slot) {
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

                $('#editServerForm').attr('action', `/banglola/server/update/${s.server_id}`);
                $('#modalEditServer').modal('show');
            },
            error: function(xhr) {
                alert('Gagal memuat data server');
                console.error(xhr);
            }
        });
    });

    // Show/hide bidang saat satker berubah di form edit
    $('#editSatkerId').change(function(){
        const selectedName = $('#editSatkerId option:selected').data('name');
        
        if(selectedName === 'Pusat Data dan Informasi Kemhan'){
            $('#editBidangWrapper').show();
        } else {
            $('#editBidangWrapper').hide();
            $('#editBidangId').val('');
        }
    });

    // === Search Functionality ===
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
                    '<tr id="noResultRow"><td colspan="8" class="text-center text-muted">Tidak ada data yang sesuai dengan pencarian</td></tr>'
                );
            }
        } else {
            $('#noResultRow').remove();
        }
    });

    // === Modal Hapus Handler ===
    $('.btn-hapus').on('click', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        
        $('#namaServerHapus').text(nama);
        $('#formHapusServer').attr('action', `/banglola/server/${id}`);
        
        const modal = new bootstrap.Modal(document.getElementById('hapusServerModal'));
        modal.show();
    });

    // Bidang visibility untuk form tambah
    $('#satkerSelect').change(function(){
        const selectedName = $('#satkerSelect option:selected').data('name');
        
        if(selectedName && selectedName.includes('Pusat Data dan Informasi')) {
            $('#bidangWrapper').show();
        } else {
            $('#bidangWrapper').hide();
            $('#bidangSelect').val('');
        }
    });
});
</script>
@endpush
@endsection