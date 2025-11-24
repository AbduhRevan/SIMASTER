@extends('layouts.app')

@section('title', 'Pemeliharaan')

@section('content')
<div class="container-fluid py-3">

    {{-- ======= RINGKASAN PEMELIHARAAN ======= --}}
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3 rounded-4 border-start border-primary border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold text-secondary small mb-1">Total Pemeliharaan</div>
                        <h3 class="text-primary mb-0">{{ $totalPemeliharaan }}</h3>
                    </div>
                    <div class="bg-primary-subtle rounded-3 p-3">
                        <i class="fa fa-tools fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3 rounded-4 border-start border-success border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold text-secondary small mb-1">Pemeliharaan Server</div>
                        <h3 class="text-success mb-0">{{ $totalServer }}</h3>
                    </div>
                    <div class="bg-success-subtle rounded-3 p-3">
                        <i class="fa fa-server fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3 rounded-4 border-start border-info border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold text-secondary small mb-1">Pemeliharaan Website</div>
                        <h3 class="text-info mb-0">{{ $totalWebsite }}</h3>
                    </div>
                    <div class="bg-info-subtle rounded-3 p-3">
                        <i class="fa fa-globe fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======= JADWAL PEMELIHARAAN ======= --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center p-3">
            <h6 class="fw-semibold mb-0">
                <i class="fa fa-wrench me-2 text-secondary"></i> Jadwal Pemeliharaan
            </h6>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahPemeliharaanModal">
                    <i class="fa fa-plus me-1"></i> Tambah Jadwal
                </button>
            </div>
        </div>

        <div class="card-body p-3">
            {{-- Filter --}}
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" 
                        placeholder="Cari pemeliharaan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="jenis" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Jenis</option>
                        <option value="server" {{ request('jenis') == 'server' ? 'selected' : '' }}>Server</option>
                        <option value="website" {{ request('jenis') == 'website' ? 'selected' : '' }}>Website</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="tanggal" class="form-control form-control-sm" 
                        value="{{ request('tanggal') }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary btn-sm w-100">
                        <i class="fa fa-search"></i> Filter
                    </button>
                </div>
            </form>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">Tanggal</th>
                            <th width="12%">Jenis</th>
                            <th width="23%">Asset</th>
                            <th width="38%">Keterangan</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemeliharaan as $index => $item)
                        <tr>
                            <td>{{ $pemeliharaan->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pemeliharaan)->format('d M Y') }}</td>
                            <td>
                                @if($item->server_id)
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="fa fa-server me-1"></i> Server
                                    </span>
                                @elseif($item->website_id)
                                    <span class="badge bg-info-subtle text-info">
                                        <i class="fa fa-globe me-1"></i> Website
                                    </span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item->server->nama_server ?? $item->website->nama_website ?? '-' }}</strong>
                            </td>
                            <td>
                                <small>{{ Str::limit($item->keterangan, 80) }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-info" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailModal{{ $item->pemeliharaan_id }}"
                                        title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-warning"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal{{ $item->pemeliharaan_id }}"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form action="{{ route('superadmin.pemeliharaan.destroy', $item->pemeliharaan_id) }}" 
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Detail --}}
                        <div class="modal fade" id="detailModal{{ $item->pemeliharaan_id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title">
                                            <i class="fa fa-info-circle me-2"></i> Detail Pemeliharaan
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-secondary small">Tanggal Pemeliharaan</label>
                                                <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($item->tanggal_pemeliharaan)->format('d F Y') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-secondary small">Jenis Asset</label>
                                                <p class="form-control-plaintext">
                                                    @if($item->server_id)
                                                        <span class="badge bg-success-subtle text-success">
                                                            <i class="fa fa-server me-1"></i> Server
                                                        </span>
                                                    @elseif($item->website_id)
                                                        <span class="badge bg-info-subtle text-info">
                                                            <i class="fa fa-globe me-1"></i> Website
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold text-secondary small">Nama Asset</label>
                                                <p class="form-control-plaintext">{{ $item->server->nama_server ?? $item->website->nama_website ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold text-secondary small">Keterangan</label>
                                                <p class="form-control-plaintext bg-light p-3 rounded">{{ $item->keterangan ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fa fa-times me-1"></i> Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="editModal{{ $item->pemeliharaan_id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-white">
                                        <h5 class="modal-title">
                                            <i class="fa fa-edit me-2"></i> Edit Pemeliharaan
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('superadmin.pemeliharaan.update', $item->pemeliharaan_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Tanggal Pemeliharaan <span class="text-danger">*</span></label>
                                                    <input type="date" name="tanggal_pemeliharaan" class="form-control" 
                                                        value="{{ $item->tanggal_pemeliharaan }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Jenis Asset <span class="text-danger">*</span></label>
                                                    <select name="jenis_asset" class="form-select jenis-asset-edit" required data-modal-id="{{ $item->pemeliharaan_id }}">
                                                        <option value="">-- Pilih Jenis --</option>
                                                        <option value="server" {{ $item->server_id ? 'selected' : '' }}>Server</option>
                                                        <option value="website" {{ $item->website_id ? 'selected' : '' }}>Website</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 server-select-edit-{{ $item->pemeliharaan_id }}" style="display: {{ $item->server_id ? 'block' : 'none' }};">
                                                    <label class="form-label fw-semibold">Pilih Server</label>
                                                    <select name="server_id" class="form-select">
                                                        <option value="">-- Pilih Server --</option>
                                                        @foreach($servers as $server)
                                                            <option value="{{ $server->server_id }}" {{ $item->server_id == $server->server_id ? 'selected' : '' }}>
                                                                {{ $server->nama_server }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-12 website-select-edit-{{ $item->pemeliharaan_id }}" style="display: {{ $item->website_id ? 'block' : 'none' }};">
                                                    <label class="form-label fw-semibold">Pilih Website</label>
                                                    <select name="website_id" class="form-select">
                                                        <option value="">-- Pilih Website --</option>
                                                        @foreach($websites as $website)
                                                            <option value="{{ $website->website_id }}" {{ $item->website_id == $website->website_id ? 'selected' : '' }}>
                                                                {{ $website->nama_website }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label fw-semibold">Keterangan <span class="text-danger">*</span></label>
                                                    <textarea name="keterangan" class="form-control" rows="4" required 
                                                        placeholder="Jelaskan detail pemeliharaan...">{{ $item->keterangan }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fa fa-times me-1"></i> Batal
                                            </button>
                                            <button type="submit" class="btn btn-warning text-white">
                                                <i class="fa fa-save me-1"></i> Update
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                Belum ada data pemeliharaan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if(isset($pemeliharaan) && $pemeliharaan->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $pemeliharaan->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ======= MODAL TAMBAH PEMELIHARAAN ======= --}}
<div class="modal fade" id="tambahPemeliharaanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fa fa-plus-circle me-2"></i> Tambah Jadwal Pemeliharaan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.pemeliharaan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Pemeliharaan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pemeliharaan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Asset <span class="text-danger">*</span></label>
                            <select name="jenis_asset" id="jenisAsset" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="server">Server</option>
                                <option value="website">Website</option>
                            </select>
                        </div>
                        <div class="col-md-12" id="serverSelect" style="display:none;">
                            <label class="form-label fw-semibold">Pilih Server</label>
                            <select name="server_id" class="form-select">
                                <option value="">-- Pilih Server --</option>
                                @foreach($servers as $server)
                                    <option value="{{ $server->server_id }}">{{ $server->nama_server }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12" id="websiteSelect" style="display:none;">
                            <label class="form-label fw-semibold">Pilih Website</label>
                            <select name="website_id" class="form-select">
                                <option value="">-- Pilih Website --</option>
                                @foreach($websites as $website)
                                    <option value="{{ $website->website_id }}">{{ $website->nama_website }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Keterangan <span class="text-danger">*</span></label>
                            <textarea name="keterangan" class="form-control" rows="4" required 
                                placeholder="Jelaskan detail pemeliharaan yang akan dilakukan..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======= JAVASCRIPT ======= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle Tambah Modal
    const jenisAsset = document.getElementById('jenisAsset');
    const serverSelect = document.getElementById('serverSelect');
    const websiteSelect = document.getElementById('websiteSelect');

    if (jenisAsset) {
        jenisAsset.addEventListener('change', function() {
            serverSelect.style.display = 'none';
            websiteSelect.style.display = 'none';
            
            if (this.value === 'server') {
                serverSelect.style.display = 'block';
            } else if (this.value === 'website') {
                websiteSelect.style.display = 'block';
            }
        });
    }

    // Handle Edit Modals
    const editSelects = document.querySelectorAll('.jenis-asset-edit');
    editSelects.forEach(function(select) {
        select.addEventListener('change', function() {
            const modalId = this.getAttribute('data-modal-id');
            const serverDiv = document.querySelector('.server-select-edit-' + modalId);
            const websiteDiv = document.querySelector('.website-select-edit-' + modalId);
            
            if (serverDiv && websiteDiv) {
                serverDiv.style.display = 'none';
                websiteDiv.style.display = 'none';
                
                if (this.value === 'server') {
                    serverDiv.style.display = 'block';
                } else if (this.value === 'website') {
                    websiteDiv.style.display = 'block';
                }
            }
        });
    });
});
</script>

<style>
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>

@endsection