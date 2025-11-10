@extends('layouts.app')

@section('title', 'Kelola Website')

@section('content')
<div class="container-fluid px-4 py-4">

  @if(isset($website))
    {{-- TAMPILAN DETAIL WEBSITE --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold">Detail Website</h3>
      <a href="{{ route('superadmin.website.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
      </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
      <div class="card-header bg-maroon text-white py-3" style="border-radius: 15px 15px 0 0;">
        <h5 class="mb-0 fw-bold">{{ $website->nama_website }}</h5>
      </div>

      <div class="card-body p-4">
        <div class="row">
          <div class="col-md-6 mb-4">
            <h6 class="text-muted mb-3 fw-semibold">INFORMASI DASAR</h6>
            
            <div class="mb-3">
              <label class="text-muted small mb-1">Nama Website</label>
              <p class="fw-semibold mb-0">{{ $website->nama_website }}</p>
            </div>

            <div class="mb-3">
              <label class="text-muted small mb-1">URL</label>
              <p class="mb-0">
                <a href="{{ $website->url }}" target="_blank" class="text-decoration-none">
                  <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> {{ $website->url }}
                </a>
              </p>
            </div>

            <div class="mb-3">
              <label class="text-muted small mb-1">Status</label>
              <div>
                @if($website->status === 'active')
                  <span class="badge bg-success px-3 py-2" style="border-radius: 8px;">Aktif</span>
                @elseif($website->status === 'maintenance')
                  <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 8px;">Maintenance</span>
                @else
                  <span class="badge bg-danger px-3 py-2" style="border-radius: 8px;">Tidak Aktif</span>
                @endif
              </div>
            </div>

            <div class="mb-3">
              <label class="text-muted small mb-1">Tahun Pengadaan</label>
              <p class="fw-semibold mb-0">{{ $website->tahun_pengadaan ?? '-' }}</p>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <h6 class="text-muted mb-3 fw-semibold">ORGANISASI</h6>
            
            <div class="mb-3">
              <label class="text-muted small mb-1">Satuan Kerja</label>
              <p class="fw-semibold mb-0">
                <i class="fa-solid fa-building me-1"></i>
                {{ $website->satker->nama_satker ?? '-' }}
              </p>
            </div>

            <div class="mb-3">
              <label class="text-muted small mb-1">Bidang</label>
              <p class="fw-semibold mb-0">
                <i class="fa-solid fa-briefcase me-1"></i>
                {{ $website->bidang->nama_bidang ?? '-' }}
              </p>
            </div>

            <div class="mb-3">
              <label class="text-muted small mb-1">Server</label>
              <p class="fw-semibold mb-0">
                <i class="fa-solid fa-server me-1"></i>
                {{ $website->server->nama_server ?? '-' }}
              </p>
            </div>
          </div>

          <div class="col-12">
            <h6 class="text-muted mb-3 fw-semibold">KETERANGAN</h6>
            <div class="p-3 bg-light rounded">
              @if($website->keterangan)
                {!! nl2br(e($website->keterangan)) !!}
              @else
                <p class="text-muted mb-0 fst-italic">Tidak ada keterangan</p>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="card-footer bg-white border-top py-3 d-flex justify-content-end gap-2" style="border-radius: 0 0 15px 15px;">
        <button class="btn btn-warning text-white btn-edit" 
          data-id="{{ $website->website_id }}"
          data-nama="{{ $website->nama_website }}"
          data-url="{{ $website->url }}"
          data-bidang="{{ $website->bidang_id }}"
          data-satker="{{ $website->satker_id }}"
          data-status="{{ $website->status }}"
          data-tahun="{{ $website->tahun_pengadaan }}"
          data-keterangan="{{ $website->keterangan }}">
          <i class="fa-solid fa-pen-to-square me-2"></i>Edit
        </button>
        <button class="btn btn-danger btn-hapus" 
          data-id="{{ $website->website_id }}"
          data-nama="{{ $website->nama_website }}">
          <i class="fa-solid fa-trash me-2"></i>Hapus
        </button>
      </div>
    </div>

  @else
    {{-- TAMPILAN LIST WEBSITE --}}
    <h3 class="fw-bold mb-4">Kelola Website</h3>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Statistik --}}
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-0 bg-white py-3">
          <h5 class="fw-bold mb-1">Total</h5>
          <h4>{{ $total }}</h4>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-0 bg-white py-3">
          <h5 class="fw-bold mb-1">Aktif</h5>
          <h4>{{ $aktif }}</h4>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-0 bg-white py-3">
          <h5 class="fw-bold mb-1">Maintenance</h5>
          <h4>{{ $maintenance }}</h4>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-0 bg-white py-3">
          <h5 class="fw-bold mb-1">Tidak Aktif</h5>
          <h4>{{ $tidakAktif }}</h4>
        </div>
      </div>
    </div>

    {{-- Pencarian dan Tambah --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari nama website atau URL...">
        </div>
      </div>
      <button class="btn btn-maroon text-white px-4" data-bs-toggle="modal" data-bs-target="#tambahModal">
        <i class="fa-solid fa-plus me-2"></i>Tambah Website
      </button>
    </div>

    {{-- Daftar Website --}}
    <div class="row g-4 mt-2" id="websiteContainer">
      @forelse($websites as $web)
      <div class="col-md-4 website-card">
        <div class="card shadow-sm border-0 p-3 h-100" style="border-radius: 15px;">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <h5 class="fw-bold mb-0">{{ $web->nama_website }}</h5>
            @if($web->status === 'active')
              <span class="badge bg-success px-3 py-2" style="border-radius: 8px;">Aktif</span>
            @elseif($web->status === 'maintenance')
              <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 8px;">Maintenance</span>
            @else
              <span class="badge bg-danger px-3 py-2" style="border-radius: 8px;">Tidak Aktif</span>
            @endif
          </div>

          <p class="mb-1">
            <a href="{{ $web->url }}" target="_blank" class="text-decoration-none fw-medium">
              <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> {{ Str::limit($web->url, 40) }}
            </a>
          </p>

          <p class="mb-1 text-muted small">
            <i class="fa-solid fa-briefcase me-1"></i> 
            {{ $web->bidang ? $web->bidang->nama_bidang : '-' }}
          </p>
          
          <p class="mb-2 text-muted small">
            <i class="fa-solid fa-building me-1"></i> 
            {{ $web->satker ? $web->satker->nama_satker : '-' }}
          </p>

          <div class="d-flex justify-content-end gap-2 mt-auto">
            <a href="{{ route('superadmin.website.detail', $web->website_id) }}" class="btn btn-light btn-sm border">
              <i class="fa-solid fa-eye"></i> Detail
            </a>
            <button class="btn btn-light btn-sm border btn-edit" 
              data-id="{{ $web->website_id }}"
              data-nama="{{ $web->nama_website }}"
              data-url="{{ $web->url }}"
              data-bidang="{{ $web->bidang_id }}"
              data-satker="{{ $web->satker_id }}"
              data-status="{{ $web->status }}"
              data-tahun="{{ $web->tahun_pengadaan }}"
              data-keterangan="{{ $web->keterangan }}">
              <i class="fa-solid fa-pen-to-square"></i> Edit
            </button>
            <button class="btn btn-danger btn-sm btn-hapus" 
              data-id="{{ $web->website_id }}"
              data-nama="{{ $web->nama_website }}">
              <i class="fa-solid fa-trash"></i> Hapus
            </button>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12">
        <div class="alert alert-info text-center">
          <i class="fa-solid fa-circle-info me-2"></i>Belum ada data website
        </div>
      </div>
      @endforelse
    </div>
  @endif
</div>

{{-- Modal Tambah Website --}}
<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header bg-maroon text-white border-0 rounded-top-4">
        <h5 class="modal-title fw-bold">Tambah Website Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('superadmin.website.store') }}" method="POST">
        @csrf
        <div class="modal-body px-4 pb-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Website <span class="text-danger">*</span></label>
            <input type="text" name="nama_website" class="form-control" placeholder="Contoh: Portal Kemhan RI" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">URL <span class="text-danger">*</span></label>
            <input type="url" name="url" class="form-control" placeholder="https://example.com" required>
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
            <textarea name="keterangan" id="summernote" class="form-control summernote-editor"></textarea>
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

{{-- Modal Edit Website --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header bg-maroon text-white border-0 rounded-top-4">
        <h5 class="modal-title fw-bold">Edit Website</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body px-4 pb-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Website <span class="text-danger">*</span></label>
            <input type="text" name="nama_website" id="editNama" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">URL <span class="text-danger">*</span></label>
            <input type="url" name="url" id="editUrl" class="form-control" required>
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

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-maroon text-white">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
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
          Apakah Anda yakin ingin menghapus website<br>
          <span class="fw-bold" id="hapusNama">""</span>?
        </p>
        <p class="text-muted small">Data yang dihapus tidak dapat dikembalikan.</p>
      </div>

      <form id="hapusForm" method="POST">
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

{{-- Style --}}
<style>
  .bg-maroon {
    background-color: #7A1313 !important;
  }

  .btn-maroon {
    background-color: #7A1313;
  }

  .btn-maroon:hover {
    background-color: #5e0e0e;
  }

  .card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
  }
</style>

{{-- Scripts --}}
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

  // Bidang visibility untuk Modal Tambah
  $('#satkerSelect').change(function() {
    const selectedName = $('#satkerSelect option:selected').data('name');
    if(selectedName === 'Pusat Data dan Informasi Kemhan') {
      $('#bidangWrapper').show();
    } else {
      $('#bidangWrapper').hide();
      $('#bidangSelect').val('');
    }
  });

  // Bidang visibility untuk Modal Edit
  $('#editSatker').change(function() {
    const selectedName = $('#editSatker option:selected').data('name');
    if(selectedName === 'Pusat Data dan Informasi Kemhan') {
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
    const status = $(this).data('status');
    const tahun = $(this).data('tahun');
    const keterangan = $(this).data('keterangan');

    $('#editNama').val(nama);
    $('#editUrl').val(url);
    $('#editSatker').val(satker || '');
    $('#editBidang').val(bidang || '');
    $('#editStatus').val(status);
    $('#editTahun').val(tahun || '');
    $('#editKeterangan').val(keterangan || '');

    // Check if bidang should be shown
    const selectedName = $('#editSatker option:selected').data('name');
    if(selectedName === 'Pusat Data dan Informasi Kemhan') {
      $('#editBidangWrapper').show();
    }

    $('#editForm').attr('action', `/superadmin/website/update/${id}`);
    $('#editModal').modal('show');
  });

  // Delete button handler
  $('.btn-hapus').click(function() {
    const id = $(this).data('id');
    const nama = $(this).data('nama');

    $('#hapusNama').text(`"${nama}"`);
    $('#hapusForm').attr('action', `/superadmin/website/delete/${id}`);
    $('#hapusModal').modal('show');
  });
});
</script>
@endpush
@endsection