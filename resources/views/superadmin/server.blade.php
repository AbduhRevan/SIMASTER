@extends('layouts.app')

@section('title', 'Kelola Server')

@section('content')
<style>
/* Modal Header Maroon */
.modal-header.maroon-header {
    background-color: #800000;
    color: white;
}

/* Tombol Stepper */
.btn-maroon {
    background-color: #800000;
    color: white;
    border: none;
}

.btn-maroon:hover {
    background-color: #660000;
    color: white;
}

#prevBtn {
    background-color: #6c757d;
    color: white;
    border: none;
}

#prevBtn:hover {
    background-color: #5a6268;
}

#saveBtn {
    background-color: #0d6efd;
    color: white;
    border: none;
}

#saveBtn:hover {
    background-color: #0b5ed7;
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

/* Tombol Maroon */
.btn-maroon {
    background-color: #7b0000 !important;
    border: none;
    color: white;
}

.btn-maroon:hover {
    background-color: #5a0000 !important;
    color: white;
}

/* Tombol Aksi (Detail, Edit, Delete) - Sama seperti di Kelola Pengguna */
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

/* Tombol Detail - Info/Cyan */
.btn-detail {
    background-color: #17a2b8;
    color: white;
}

.btn-detail:hover {
    background-color: #138496;
    color: white;
}

/* Tombol Edit - Warning/Yellow */
.btn-edit {
    background-color: #ffc107;
    color: white;
}

.btn-edit:hover {
    background-color: #e0a800;
    color: white;
}

/* Tombol Delete - Danger/Red */
.btn-delete {
    background-color: #dc3545;
    color: white;
}

.btn-delete:hover {
    background-color: #c82333;
    color: white;
}

/* Modal Konfirmasi Hapus - Style seperti Bidang */
.bg-maroon {
    background-color: #7b0000 !important;
}

.btn-maroon {
    background-color: #7b0000 !important;
    border: none;
}

.btn-maroon:hover {
    background-color: #5a0000 !important;
}
</style>

<div class="container-fluid">
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
                <input type="text" id="searchInput" class="form-control" placeholder="Cari nama/server/website/IP">
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
                    <th class="text-center">Website</th>
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
                    <td class="text-center server-website">{{ $server->website ? $server->website->nama_website : '-' }}</td>
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
                        <th>Nama Server</th>
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
                        <th>Website</th>
                        <td id="detailWebsite"></td>
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

            <form id="serverForm" action="{{ route('superadmin.server.store') }}" method="POST">
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Rak Server</label>
                            <select class="form-select" name="rak_id">
                                <option value="">Pilih Rak</option>
                                @foreach($raks as $rak)
                                    <option value="{{ $rak->rak_id }}">{{ $rak->nomor_rak }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">U-Slot</label>
                            <input type="text" class="form-control" name="u_slot" placeholder="Contoh: 1-3">
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
                        <label class="form-label fw-semibold">Website</label>
                        <input type="text" class="form-control" name="website_name" placeholder="Nama website (opsional)">
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
                        <textarea class="form-control summernote-editor" name="keterangan" rows="3" placeholder="Tulis keterangan di sini..."></textarea>
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rak Server</label>
                            <select class="form-select" id="editRakId" name="rak_id">
                                <option value="">Pilih Rak</option>
                                @foreach($raks as $rak)
                                    <option value="{{ $rak->rak_id }}">{{ $rak->nomor_rak }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">U-Slot</label>
                            <input type="text" class="form-control" id="editUSlot" name="u_slot" placeholder="Contoh: 1-3">
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
                        <textarea class="form-control summernote-edit" id="editKeterangan" name="keterangan" rows="3"></textarea>
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
                    <!-- Icon Warning -->
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    </div>
                    
                    <!-- Teks Konfirmasi -->
                    <p class="mb-3">Apakah Anda yakin ingin menghapus server <strong id="namaServerHapus"></strong>?</p>
                    
                    <!-- Alert Box -->
                    <div class="alert alert-warning small mb-0">
                        Data akan dipindahkan ke Arsip Sementara dan dapat dipulihkan dalam waktu 30 hari sebelum dihapus permanen.
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

<!-- JS Stepper dan Logic Halaman -->
@push('scripts')
<script>
$(document).ready(function() {
    let currentStep = 0;
    const steps = $('.step');
    const nextBtn = $('#nextBtn');
    const prevBtn = $('#prevBtn');
    const saveBtn = $('#saveBtn');

    function showStep(index){
        steps.hide();
        $(steps[index]).show();
        prevBtn.toggle(index > 0);
        nextBtn.toggle(index < steps.length - 1);
        saveBtn.toggle(index === steps.length - 1);
    }

    // Reset form ketika modal ditutup
    $('#tambahModal').on('hidden.bs.modal', function () {
        $('#serverForm')[0].reset();
        $('#bidangWrapper').hide();
        $('#bidangSelect').val('');
        currentStep = 0;
        showStep(currentStep);
    });

    // Jalankan setelah modal muncul
    $('#tambahModal').on('shown.bs.modal', function () {
        showStep(currentStep);
    });

    nextBtn.click(function(){
        $('#confirmNama').text($('input[name="nama_server"]').val() || '-');
        $('#confirmBrand').text($('input[name="brand"]').val() || '-');
        $('#confirmSpesifikasi').text($('textarea[name="spesifikasi"]').val() || '-');
        $('#confirmSatker').text($('#satkerSelect option:selected').text() || '-');
        
        if($('#bidangWrapper').is(':visible')) {
            $('#confirmBidang').text($('#bidangSelect option:selected').text() || '-');
        } else {
            $('#confirmBidang').text('-');
        }
        
        $('#confirmRak').text($('select[name="rak_id"] option:selected').text() || '-');
        $('#confirmSlot').text($('input[name="u_slot"]').val() || '-');
        $('#confirmWebsite').text($('input[name="website_name"]').val() || '-');
        
        const keteranganContent = $('.summernote').summernote('code');
        $('#confirmKeterangan').html(keteranganContent || '-');

        currentStep++;
        showStep(currentStep);
    });

    prevBtn.click(function(){
        currentStep--;
        showStep(currentStep);
    });

    // 🔥 DETAIL SERVER AJAX
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
                $('#detailWebsite').text(s.website ? s.website.nama_website : '-');
                $('#detailKeterangan').html(s.keterangan ?? '-');
                $('#modalDetailServer').modal('show');
            },
            error: function(xhr) {
                alert('Gagal memuat data server');
                console.error(xhr);
            }
        });
    });

    // 🔥 EDIT SERVER - LOAD DATA
    $(document).on('click', '.btn-edit-server', function () {
        let id = $(this).data('id');

        $.ajax({
            url: `/superadmin/server/${id}/edit`,
            type: "GET",
            success: function (response) {
                let s = response.data;

                $('#editServerId').val(s.server_id);
                $('#editNamaServer').val(s.nama_server);
                $('#editBrand').val(s.brand);
                $('#editSpesifikasi').val(s.spesifikasi);
                $('#editRakId').val(s.rak_id);
                $('#editUSlot').val(s.u_slot);
                $('#editSatkerId').val(s.satker_id);
                $('#editBidangId').val(s.bidang_id);
                $('#editPowerStatus').val(s.power_status);
                
                // Set keterangan ke summernote
                if (typeof $('.summernote-edit').summernote === 'function') {
                    $('.summernote-edit').summernote('code', s.keterangan || '');
                } else {
                    $('#editKeterangan').val(s.keterangan || '');
                }

                // Cek apakah harus menampilkan bidang
                const selectedSatker = $('#editSatkerId option:selected').data('name');
                if (selectedSatker === 'Pusat Data dan Informasi Kemhan') {
                    $('#editBidangWrapper').show();
                } else {
                    $('#editBidangWrapper').hide();
                }

                // Set URL form action
                $('#editServerForm').attr('action', `/superadmin/server/update/${s.server_id}`);
                
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

    // 🔍 SEARCH FUNCTIONALITY - Sama seperti di Bidang
    $('#searchInput').on('keyup', function() {
        const searchValue = $(this).val().toLowerCase();
        let visibleRows = 0;

        $('.server-row').each(function() {
            const nama = $(this).find('.server-nama').text().toLowerCase();
            const brand = $(this).find('.server-brand').text().toLowerCase();
            const spesifikasi = $(this).find('.server-spesifikasi').text().toLowerCase();
            const rak = $(this).find('.server-rak').text().toLowerCase();
            const bidang = $(this).find('.server-bidang').text().toLowerCase();
            const satker = $(this).find('.server-satker').text().toLowerCase();
            const website = $(this).find('.server-website').text().toLowerCase();
            
            if (nama.includes(searchValue) || brand.includes(searchValue) || 
                spesifikasi.includes(searchValue) || rak.includes(searchValue) ||
                bidang.includes(searchValue) || satker.includes(searchValue) || 
                website.includes(searchValue)) {
                $(this).show();
                visibleRows++;
            } else {
                $(this).hide();
            }
        });

        // Tampilkan pesan jika tidak ada hasil
        if (visibleRows === 0 && $('.server-row').length > 0) {
            if ($('#noResultRow').length === 0) {
                $('table tbody').append(
                    '<tr id="noResultRow"><td colspan="10" class="text-center text-muted">Tidak ada data yang sesuai dengan pencarian</td></tr>'
                );
            }
        } else {
            $('#noResultRow').remove();
        }
    });

    // 🔥 MODAL HAPUS HANDLER - Sama seperti di Bidang
    $('.btn-hapus').on('click', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        
        $('#namaServerHapus').text(nama);
        $('#formHapusServer').attr('action', `/superadmin/server/${id}`);
        
        const modal = new bootstrap.Modal(document.getElementById('hapusServerModal'));
        modal.show();
    });

    // Handle form submit untuk hapus
    $('#formHapusServer').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const actionUrl = form.attr('action');
        
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#hapusServerModal').modal('hide');
                alert('Server berhasil dihapus');
                location.reload();
            },
            error: function(xhr) {
                $('#hapusServerModal').modal('hide');
                alert('Gagal menghapus server');
                console.error(xhr);
            }
        });
    });

    // Bidang muncul HANYA jika nama Satker adalah "Pusat Data dan Informasi Kemhan"
    $('#satkerSelect').change(function(){
        const selectedName = $('#satkerSelect option:selected').data('name');
        
        console.log('Satker dipilih:', selectedName); // Debug
        
        // Cek apakah nama satker mengandung "Pusat Data dan Informasi"
        if(selectedName && selectedName.includes('Pusat Data dan Informasi')) {
            $('#bidangWrapper').show();
            console.log('Bidang ditampilkan'); // Debug
        } else {
            $('#bidangWrapper').hide();
            $('#bidangSelect').val('');
            console.log('Bidang disembunyikan'); // Debug
        }
    });

    // Trigger change saat modal dibuka untuk cek nilai awal
    $('#tambahModal').on('shown.bs.modal', function () {
        $('#satkerSelect').trigger('change');
    });
});
</script>
@endpush
@endsection