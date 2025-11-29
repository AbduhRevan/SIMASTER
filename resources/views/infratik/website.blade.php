@extends('layouts.infratik')

@section('title', 'Kelola Website')

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

  {{-- ======= RINGKASAN WEBSITE ======= --}}
  <div class="row mb-4 g-3">
    <div class="col-md-3">
      <div class="card-stat text-center py-3">
        <div class="stat-label text-uppercase small text-muted mb-2">
          <i class="fa fa-globe me-1"></i>Total Website
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

  {{-- ======= PENCARIAN DAN TAMBAH ======= --}}
  <div class="card-content mb-4">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text bg-white">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <input type="text" id="searchInput" class="form-control" placeholder="Cari nama website atau URL...">
        </div>
      </div>
      <button class="btn btn-maroon-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
        <i class="fa-solid fa-plus me-1"></i>Tambah Website
      </button>
    </div>
  </div>

  {{-- ======= DAFTAR WEBSITE (CARD LAYOUT) ======= --}}
  <div class="row g-4" id="websiteContainer">
    @forelse($websites as $website)
    <div class="col-12 col-sm-6 col-lg-4 website-card">
      <div class="card-website h-100">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <h5 class="fw-bold mb-0 text-dark">{{ $website->nama_website }}</h5>
          @if($website->status === 'active')
            <span class="badge bg-success">
              <i class="fa fa-check-circle me-1"></i>Aktif
            </span>
          @elseif($website->status === 'maintenance')
            <span class="badge bg-warning">
              <i class="fa fa-wrench me-1"></i>Maintenance
            </span>
          @else
            <span class="badge bg-danger">
              <i class="fa fa-times-circle me-1"></i>Tidak Aktif
            </span>
          @endif
        </div>

        <p class="mb-2">
          <a href="{{ $website->url }}" target="_blank" class="text-decoration-none text-primary fw-medium">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> {{ Str::limit($website->url, 40) }}
          </a>
        </p>

        <div class="website-info mb-3">
          <p class="mb-1 text-muted small">
            <i class="fa-solid fa-server me-1"></i> 
            <strong>Server:</strong> {{ $website->server ? $website->server->nama_server : 'Belum terhubung' }}
          </p>

          <p class="mb-1 text-muted small">
            <i class="fa-solid fa-briefcase me-1"></i> 
            <strong>Bidang:</strong> {{ $website->bidang ? $website->bidang->nama_bidang : '-' }}
          </p>
          
          <p class="mb-0 text-muted small">
            <i class="fa-solid fa-building me-1"></i> 
            <strong>Satker:</strong> {{ $website->satker ? $website->satker->nama_satker : '-' }}
          </p>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-auto">
          <button class="btn btn-outline-info btn-sm btn-detail" data-id="{{ $website->website_id }}" title="Detail">
            <i class="fa-solid fa-eye"></i>
          </button>
          <button class="btn btn-outline-warning btn-sm btn-edit" 
            data-id="{{ $website->website_id }}"
            data-nama="{{ $website->nama_website }}"
            data-url="{{ $website->url }}"
            data-bidang="{{ $website->bidang_id }}"
            data-satker="{{ $website->satker_id }}"
            data-server="{{ $website->server_id }}"
            data-status="{{ $website->status }}"
            data-tahun="{{ $website->tahun_pengadaan }}"
            data-keterangan="{{ $website->keterangan }}"
            title="Edit">
            <i class="fa-solid fa-pen-to-square"></i>
          </button>
          <button class="btn btn-outline-danger btn-sm btn-hapus" 
            data-id="{{ $website->website_id }}"
            data-nama="{{ $website->nama_website }}"
            title="Hapus">
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>
      </div>
    </div>
    @empty
<div class="col-12">
  <div class="alert alert-light text-center border-0" style="background-color: #f8f9fa;">
    <i class="fa-solid fa-inbox fa-3x mb-3 d-block" style="color: #6c757d; opacity: 0.6;"></i>
    <p class="mb-0" style="color: #6c757d;">Belum ada data website</p>
  </div>
</div>
@endforelse
  </div>
</div>

{{-- Modal Detail Website --}}
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header modal-header-gradient text-white border-0">
        <h5 class="modal-title fw-bold" id="detailNamaWebsite">
          <i class="fa fa-info-circle me-2"></i>Detail Website
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-4">
        <div class="row">
          <div class="col-md-6 mb-4">
            <h6 class="text-muted mb-3 fw-semibold text-uppercase">
              <i class="fa fa-info-circle me-1"></i>Informasi Dasar
            </h6>
            
            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary small">Nama Website</label>
              <p class="form-control-plaintext" id="detailNama">-</p>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary small">URL</label>
              <p class="form-control-plaintext" id="detailUrlContainer">-</p>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary small">Status</label>
              <div id="detailStatus">-</div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary small">Tahun Pengadaan</label>
              <p class="form-control-plaintext" id="detailTahun">-</p>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <h6 class="text-muted mb-3 fw-semibold text-uppercase">
              <i class="fa fa-building me-1"></i>Organisasi & Infrastruktur
            </h6>
            
            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary small">Server</label>
              <p class="form-control-plaintext" id="detailServer">
                <i class="fa-solid fa-server me-1"></i> -
              </p>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary small">Satuan Kerja</label>
              <p class="form-control-plaintext" id="detailSatker">
                <i class="fa-solid fa-building me-1"></i> -
              </p>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary small">Bidang</label>
              <p class="form-control-plaintext" id="detailBidang">
                <i class="fa-solid fa-briefcase me-1"></i> -
              </p>
            </div>
          </div>

          <div class="col-12">
            <h6 class="text-muted mb-3 fw-semibold text-uppercase">
              <i class="fa fa-file-alt me-1"></i>Keterangan
            </h6>
            <div class="bg-light p-3 rounded" id="detailKeterangan">
              <p class="text-muted mb-0 fst-italic">Tidak ada keterangan</p>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer border-0 bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fa fa-times me-1"></i>Tutup
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Modal Tambah Website --}}
<div class="modal fade" id="tambahModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header modal-header-gradient text-white border-0">
        <h5 class="modal-title fw-bold">
          <i class="fa fa-plus-circle me-2"></i>Tambah Website Baru
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('infratik.website.store') }}" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Website <span class="text-danger">*</span></label>
            <input type="text" name="nama_website" class="form-control" placeholder="Contoh: Portal Kemhan RI" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">URL <span class="text-danger">*</span></label>
            <input type="url" name="url" class="form-control" placeholder="https://example.com" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Server</label>
            <select name="server_id" class="form-select">
              <option value="">Pilih Server (Opsional)</option>
              @foreach($servers as $server)
                <option value="{{ $server->server_id }}">
                  {{ $server->nama_server }} 
                  @if($server->rak)
                    ({{ $server->rak->nomor_rak }})
                  @endif
                </option>
              @endforeach
            </select>
            <small class="text-muted">Website ini akan terhubung ke server yang dipilih</small>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Satuan Kerja</label>
              <select name="satker_id" id="satkerSelect" class="form-select">
                <option value="">Pilih Satker</option>
                @foreach($satkers as $satker)
                  <option value="{{ $satker->satker_id }}" data-name="{{ $satker->nama_satker }}">
                    {{ $satker->nama_satker }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6 mb-3" id="bidangWrapper" style="display:none;">
              <label class="form-label fw-semibold">Bidang</label>
              <select name="bidang_id" id="bidangSelect" class="form-select">
                <option value="">Pilih Bidang</option>
                @foreach($bidangs as $bidang)
                  <option value="{{ $bidang->bidang_id }}">{{ $bidang->nama_bidang }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
              <select name="status" class="form-select" required>
                <option value="">Pilih Status</option>
                <option value="active">Aktif</option>
                <option value="maintenance">Maintenance</option>
                <option value="inactive">Tidak Aktif</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Tahun Pengadaan</label>
              <input type="number" name="tahun_pengadaan" class="form-control" placeholder="2024" min="1900" max="{{ date('Y') + 1 }}">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan..."></textarea>
          </div>
        </div>

        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times me-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-maroon-gradient">
            <i class="fa fa-save me-1"></i>Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Edit Website --}}
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header modal-header-gradient text-white border-0">
        <h5 class="modal-title fw-bold">
          <i class="fa fa-edit me-2"></i>Edit Website
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Website <span class="text-danger">*</span></label>
            <input type="text" name="nama_website" id="editNama" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">URL <span class="text-danger">*</span></label>
            <input type="url" name="url" id="editUrl" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Server</label>
            <select name="server_id" id="editServer" class="form-select">
              <option value="">Pilih Server (Opsional)</option>
              @foreach($servers as $server)
                <option value="{{ $server->server_id }}">
                  {{ $server->nama_server }}
                  @if($server->rak)
                    ({{ $server->rak->nomor_rak }})
                  @endif
                </option>
              @endforeach
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Satuan Kerja</label>
              <select name="satker_id" id="editSatker" class="form-select">
                <option value="">Pilih Satker</option>
                @foreach($satkers as $satker)
                  <option value="{{ $satker->satker_id }}" data-name="{{ $satker->nama_satker }}">
                    {{ $satker->nama_satker }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6 mb-3" id="editBidangWrapper" style="display:none;">
              <label class="form-label fw-semibold">Bidang</label>
              <select name="bidang_id" id="editBidang" class="form-select">
                <option value="">Pilih Bidang</option>
                @foreach($bidangs as $bidang)
                  <option value="{{ $bidang->bidang_id }}">{{ $bidang->nama_bidang }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
              <select name="status" id="editStatus" class="form-select" required>
                <option value="active">Aktif</option>
                <option value="maintenance">Maintenance</option>
                <option value="inactive">Tidak Aktif</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Tahun Pengadaan</label>
              <input type="number" name="tahun_pengadaan" id="editTahun" class="form-control" min="1900" max="{{ date('Y') + 1 }}">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Keterangan</label>
            <textarea name="keterangan" id="editKeterangan" class="form-control" rows="3"></textarea>
          </div>
        </div>

        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times me-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-warning text-white">
            <i class="fa fa-save me-1"></i>Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="hapusModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header modal-header-gradient text-white border-0">
        <h5 class="modal-title fw-bold">
          <i class="fa fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center p-4">
        <i class="fa-solid fa-triangle-exclamation fa-3x text-warning mb-3"></i>
        <p class="mb-3">
          Apakah Anda yakin ingin menghapus website<br>
          <strong id="hapusNama">""</strong>?
        </p>
        <div class="alert alert-warning small mb-0">
          <i class="fa fa-info-circle me-1"></i>
          Data yang dihapus tidak dapat dikembalikan.
        </div>
      </div>

      <form id="hapusForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times me-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-danger">
            <i class="fa fa-trash me-1"></i>Ya, Hapus
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

/* Card Content (Search & Button Container) */
.card-content {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    overflow: hidden;
}

.card-header-custom {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
}

/* Website Card */
.card-website {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    padding: 1.5rem;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.card-website:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.website-info {
    flex-grow: 1;
}

/* Badge Styles */
.badge {
    padding: 0.35rem 0.65rem;
    font-weight: 500;
    font-size: 0.75rem;
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

/* Input Group */
.input-group-text {
    border-right: 0;
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
    
    .card-header-custom .col-md-4 {
        width: 100%;
    }
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
  // Search functionality
  $('#searchInput').on('keyup', function() {
    const value = $(this).val().toLowerCase();
    $('.website-card').filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });

  // Clear search on ESC
  $('#searchInput').on('keydown', function(e) {
    if (e.key === 'Escape') {
      $(this).val('');
      $(this).trigger('keyup');
    }
  });

  // Detail button handler dengan AJAX
  $('.btn-detail').click(function() {
    const id = $(this).data('id');
    
    $.ajax({
      url: `/infratik/website/${id}/detail`,
      type: 'GET',
      success: function(data) {
        $('#detailNamaWebsite').html('<i class="fa fa-info-circle me-2"></i>' + data.nama_website);
        $('#detailNama').text(data.nama_website);
        
        $('#detailUrlContainer').html(
          `<a href="${data.url}" target="_blank" class="text-decoration-none">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> ${data.url}
          </a>`
        );
        
        let statusBadge = '';
        if(data.status === 'active') {
          statusBadge = '<span class="badge bg-success"><i class="fa fa-check-circle me-1"></i>Aktif</span>';
        } else if(data.status === 'maintenance') {
          statusBadge = '<span class="badge bg-warning"><i class="fa fa-wrench me-1"></i>Maintenance</span>';
        } else {
          statusBadge = '<span class="badge bg-danger"><i class="fa fa-times-circle me-1"></i>Tidak Aktif</span>';
        }
        $('#detailStatus').html(statusBadge);
        
        $('#detailTahun').text(data.tahun_pengadaan || '-');
        
        $('#detailServer').html(
          '<i class="fa-solid fa-server me-1"></i> ' + 
          (data.server ? data.server.nama_server : 'Belum terhubung')
        );
        
        $('#detailSatker').html(
          '<i class="fa-solid fa-building me-1"></i> ' + 
          (data.satker ? data.satker.nama_satker : '-')
        );
        
        $('#detailBidang').html(
          '<i class="fa-solid fa-briefcase me-1"></i> ' + 
          (data.bidang ? data.bidang.nama_bidang : '-')
        );
        
        if(data.keterangan) {
          $('#detailKeterangan').html(data.keterangan.replace(/\n/g, '<br>'));
        } else {
          $('#detailKeterangan').html('<p class="text-muted mb-0 fst-italic">Tidak ada keterangan</p>');
        }
        
        $('#detailModal').modal('show');
      },
      error: function() {
        alert('Gagal memuat detail website');
      }
    });
  });

  // Bidang visibility untuk Modal Tambah
  $('#satkerSelect').change(function() {
    const selectedName = $('#satkerSelect option:selected').data('name')?.toLowerCase() || '';
    if (selectedName.includes('pusat data dan informasi')) {
      $('#bidangWrapper').show();
    } else {
      $('#bidangWrapper').hide();
      $('#bidangSelect').val('');
    }
  });

  // Bidang visibility untuk Modal Edit
  $('#editSatker').change(function() {
    const selectedName = $('#editSatker option:selected').data('name')?.toLowerCase() || '';
    if(selectedName.includes('pusat data dan informasi')) {
      $('#editBidangWrapper').show();
    } else {
      $('#editBidangWrapper').hide();
      $('#editBidang').val('');
    }
  });

   // Edit button handler
  $('.btn-edit').click(function() {
    const id = $(this).data('id');
    const nama = $(this).data('nama');
    const url = $(this).data('url');
    const bidang = $(this).data('bidang');
    const satker = $(this).data('satker');
    const server = $(this).data('server');
    const status = $(this).data('status');
    const tahun = $(this).data('tahun');
    const keterangan = $(this).data('keterangan');

    $('#editNama').val(nama);
    $('#editUrl').val(url);
    $('#editSatker').val(satker || '');
    $('#editBidang').val(bidang || '');
    $('#editServer').val(server || '');
    $('#editStatus').val(status);
    $('#editTahun').val(tahun || '');
    $('#editKeterangan').val(keterangan || '');

    const selectedName = $('#editSatker option:selected').data('name')?.toLowerCase() || '';
    if(selectedName.includes('pusat data dan informasi')) {
      $('#editBidangWrapper').show();
    }

    $('#editForm').attr('action', `/infratik/website/update/${id}`);
    $('#editModal').modal('show');
  });

  // Delete button handler
  $('.btn-hapus').click(function() {
    const id = $(this).data('id');
    const nama = $(this).data('nama');

    $('#hapusNama').text(`"${nama}"`);
    $('#hapusForm').attr('action', `/infratik/website/delete/${id}`);
    $('#hapusModal').modal('show');
  });
});
</script>
@endpush
@endsection