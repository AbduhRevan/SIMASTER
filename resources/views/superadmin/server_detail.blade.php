@extends('layouts.app')

@section('title', 'Detail Server')

@section('content')
<style>
.detail-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 30px;
}

.detail-header {
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 20px;
    margin-bottom: 25px;
}

.detail-row {
    display: flex;
    padding: 15px 0;
    border-bottom: 1px solid #f5f5f5;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #555;
    width: 200px;
    flex-shrink: 0;
}

.detail-value {
    color: #333;
    flex: 1;
}

.status-badge {
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 600;
    display: inline-block;
}

.btn-maroon {
    background-color: #7A1313;
    color: white;
}

.btn-maroon:hover {
    background-color: #5e0e0e;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}
</style>

<div class="container-fluid px-4 py-4">
    <div class="detail-card">
        <!-- Header -->
        <div class="detail-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3 class="fw-bold mb-2">{{ $server->nama_server }}</h3>
                    <p class="text-muted mb-0">
                        <i class="fa-solid fa-server me-2"></i>Server ID: {{ $server->server_id }}
                    </p>
                </div>
                <div>
                    @if($server->power_status === 'ON')
                        <span class="status-badge bg-success text-white">
                            <i class="fa-solid fa-circle-check me-1"></i>Aktif
                        </span>
                    @elseif($server->power_status === 'STANDBY')
                        <span class="status-badge bg-warning text-dark">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>Maintenance
                        </span>
                    @else
                        <span class="status-badge bg-danger text-white">
                            <i class="fa-solid fa-circle-xmark me-1"></i>Tidak Aktif
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informasi Umum -->
        <div class="mb-4">
            <h5 class="fw-bold mb-3">
                <i class="fa-solid fa-circle-info me-2 text-primary"></i>Informasi Umum
            </h5>
            
            <div class="detail-row">
                <div class="detail-label">Nama Server</div>
                <div class="detail-value">{{ $server->nama_server }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    @if($server->power_status === 'ON')
                        <span class="badge bg-success">Aktif</span>
                    @elseif($server->power_status === 'STANDBY')
                        <span class="badge bg-warning text-dark">Maintenance</span>
                    @else
                        <span class="badge bg-danger">Tidak Aktif</span>
                    @endif
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Brand</div>
                <div class="detail-value">{{ $server->brand ?? '-' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Spesifikasi</div>
                <div class="detail-value">{{ $server->spesifikasi ?? '-' }}</div>
            </div>
        </div>

        <!-- Lokasi & Penempatan -->
        <div class="mb-4">
            <h5 class="fw-bold mb-3">
                <i class="fa-solid fa-location-dot me-2 text-danger"></i>Lokasi & Penempatan
            </h5>
            
            <div class="detail-row">
                <div class="detail-label">Rak</div>
                <div class="detail-value">{{ $server->rak ? $server->rak->nama_rak : '-' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">U Slot</div>
                <div class="detail-value">{{ $server->u_slot ?? '-' }}</div>
            </div>
        </div>

        <!-- Organisasi -->
        <div class="mb-4">
            <h5 class="fw-bold mb-3">
                <i class="fa-solid fa-building me-2 text-success"></i>Organisasi
            </h5>
            
            <div class="detail-row">
                <div class="detail-label">Satuan Kerja</div>
                <div class="detail-value">{{ $server->satker ? $server->satker->nama_satker : '-' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Bidang</div>
                <div class="detail-value">
                    @if($server->satker && $server->satker->nama_satker == 'Pusat Data dan Informasi Kemhan')
                        {{ $server->bidang ? $server->bidang->nama_bidang : '-' }}
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>

        <!-- Aplikasi & Website -->
        <div class="mb-4">
            <h5 class="fw-bold mb-3">
                <i class="fa-solid fa-globe me-2 text-info"></i>Aplikasi & Website
            </h5>
            
            <div class="detail-row">
                <div class="detail-label">Website Terhubung</div>
                <div class="detail-value">
                    @if($server->website)
                        <a href="{{ $server->website->url ?? '#' }}" target="_blank" class="text-decoration-none">
                            {{ $server->website->nama_website }}
                            <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                        </a>
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="mb-4">
            <h5 class="fw-bold mb-3">
                <i class="fa-solid fa-note-sticky me-2 text-warning"></i>Keterangan
            </h5>
            
            <div class="detail-row">
                <div class="detail-label">Deskripsi</div>
                <div class="detail-value">
                    @if($server->keterangan)
                        {!! $server->keterangan !!}
                    @else
                        <span class="text-muted">Tidak ada keterangan</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('superadmin.server.index') }}" class="btn btn-light px-4">
                <i class="fa-solid fa-arrow-left me-2"></i>Kembali
            </a>
            
            <button class="btn btn-maroon px-4" data-bs-toggle="modal" data-bs-target="#editModal">
                <i class="fa-solid fa-pen-to-square me-2"></i>Edit Server
            </button>
            
            <button class="btn btn-danger px-4 ms-auto" data-bs-toggle="modal" data-bs-target="#hapusModal">
                <i class="fa-solid fa-trash me-2"></i>Hapus Server
            </button>
        </div>
    </div>
</div>

<!-- Modal Edit Server -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-maroon text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold">Edit Server</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('superadmin.server.update', $server->server_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Server <span class="text-danger">*</span></label>
                        <input type="text" name="nama_server" class="form-control" value="{{ $server->nama_server }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ $server->brand }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Spesifikasi</label>
                        <textarea name="spesifikasi" class="form-control" rows="2">{{ $server->spesifikasi }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Satuan Kerja</label>
                            <select name="satker_id" id="editSatkerSelect" class="form-select">
                                <option value="">Pilih Satker</option>
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->satker_id }}" 
                                        data-name="{{ $satker->nama_satker }}"
                                        {{ $server->satker_id == $satker->satker_id ? 'selected' : '' }}>
                                        {{ $satker->nama_satker }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3" id="editBidangWrapper" 
                            style="display: {{ $server->satker && $server->satker->nama_satker == 'Pusat Data dan Informasi Kemhan' ? 'block' : 'none' }};">
                            <label class="form-label fw-semibold">Bidang</label>
                            <select name="bidang_id" id="editBidangSelect" class="form-select">
                                <option value="">Pilih Bidang</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->bidang_id }}" 
                                        {{ $server->bidang_id == $bidang->bidang_id ? 'selected' : '' }}>
                                        {{ $bidang->nama_bidang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rak</label>
                        <select name="rak_id" class="form-select">
                            <option value="">Pilih Rak</option>
                            @foreach($raks as $rak)
                                <option value="{{ $rak->rak_id }}" {{ $server->rak_id == $rak->rak_id ? 'selected' : '' }}>
                                    {{ $rak->nama_rak }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">U Slot</label>
                        <input type="text" name="u_slot" class="form-control" value="{{ $server->u_slot }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="power_status" class="form-select" required>
                            <option value="ON" {{ $server->power_status == 'ON' ? 'selected' : '' }}>Aktif</option>
                            <option value="STANDBY" {{ $server->power_status == 'STANDBY' ? 'selected' : '' }}>Maintenance</option>
                            <option value="OFF" {{ $server->power_status == 'OFF' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="summernote">{{ $server->keterangan }}</textarea>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon text-white">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="hapusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-danger text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center p-4">
                <i class="fa-solid fa-triangle-exclamation text-danger mb-3" style="font-size: 3rem;"></i>
                <p class="mb-4 fs-6">
                    Apakah Anda yakin ingin menghapus server<br>
                    <span class="fw-bold">"{{ $server->nama_server }}"</span>?
                </p>
                <p class="text-muted small">Data yang dihapus tidak dapat dikembalikan.</p>
            </div>

            <form action="{{ route('superadmin.server.destroy', $server->server_id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Bidang visibility untuk Edit Modal
    $('#editSatkerSelect').change(function() {
        const selectedName = $('#editSatkerSelect option:selected').data('name');
        if(selectedName === 'Pusat Data dan Informasi Kemhan') {
            $('#editBidangWrapper').show();
        } else {
            $('#editBidangWrapper').hide();
            $('#editBidangSelect').val('');
        }
    });
});
</script>
@endpush

@endsection