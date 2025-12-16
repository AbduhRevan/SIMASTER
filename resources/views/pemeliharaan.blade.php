@extends('layouts.app')

@section('title', 'Pemeliharaan')

@section('content')
    <div class="container-fluid py-4">


        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ======= RINGKASAN PEMELIHARAAN ======= --}}
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card-stat text-center py-3">
                    <div class="stat-label text-uppercase small text-muted mb-2">
                        <i class="fa fa-list me-1"></i>Total Pemeliharaan
                    </div>
                    <h2 class="stat-value mb-0">{{ $totalPemeliharaan }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-stat text-center py-3">
                    <div class="stat-label text-uppercase small text-muted mb-2">
                        <i class="fa fa-plus-circle me-1 text-success"></i>Server
                    </div>
                    <h2 class="stat-value mb-0 text-success">{{ $totalServer }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-stat text-center py-3">
                    <div class="stat-label text-uppercase small text-muted mb-2">
                        <i class="fa fa-edit me-1 text-warning"></i>Website
                    </div>
                    <h2 class="stat-value mb-0 text-warning">{{ $totalWebsite }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-stat text-center py-3">
                    <div class="stat-label text-uppercase small text-muted mb-2">
                        <i class="fa fa-spinner me-1 text-danger"></i>Berlangsung
                    </div>
                    <h2 class="stat-value mb-0 text-danger">{{ $berlangsung }}</h2>
                </div>
            </div>
        </div>

        {{-- ======= RIWAYAT PEMELIHARAAN ======= --}}
        <div class="card-content">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="fa fa-history me-2"></i> Riwayat Pemeliharaan
                </h6>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-maroon-gradient btn-sm" data-bs-toggle="modal"
                        data-bs-target="#tambahPemeliharaanModal">
                        <i class="fa fa-plus me-1"></i> Tambah Jadwal
                    </button>
                </div>
            </div>

            <div class="card-body-custom">
                {{-- Filter --}}
                <form method="GET" class="filter-bar mb-3">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Cari aktivitas..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="jenis" class="form-select form-select-sm">
                                <option value="">Semua Jenis</option>
                                <option value="server" {{ request('jenis') == 'server' ? 'selected' : '' }}>Server</option>
                                <option value="website" {{ request('jenis') == 'website' ? 'selected' : '' }}>Website
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>
                                    Dijadwalkan</option>
                                <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>
                                    Berlangsung</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="tanggal" class="form-control form-control-sm"
                                value="{{ request('tanggal') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary btn-sm w-100">
                                <i class="fa fa-search me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Tabel --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Tanggal</th>
                                <th width="10%">Jenis</th>
                                <th width="18%">Asset</th>
                                <th width="12%">Status</th>
                                <th width="25%">Keterangan</th>
                                <th width="20%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pemeliharaan as $index => $item)
                                <tr>
                                    <td>{{ $pemeliharaan->firstItem() + $index }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pemeliharaan)->format('d M Y') }}</td>
                                    <td>
                                        @if ($item->server_id)
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
                                        <strong>{{ $item->server->nama_server ?? ($item->website->nama_website ?? '-') }}</strong>
                                    </td>
                                    <td>
                                        @if ($item->status_pemeliharaan === 'dijadwalkan')
                                            <span class="badge bg-secondary">
                                                <i class="fa fa-clock me-1"></i> Dijadwalkan
                                            </span>
                                        @elseif($item->status_pemeliharaan === 'berlangsung')
                                            <span class="badge bg-warning">
                                                <i class="fa fa-spinner fa-spin me-1"></i> Berlangsung
                                            </span>
                                        @elseif($item->status_pemeliharaan === 'selesai')
                                            <span class="badge bg-success">
                                                <i class="fa fa-check-circle me-1"></i> Selesai
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fa fa-times-circle me-1"></i> Dibatalkan
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($item->keterangan, 60) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1" role="group">
                                            {{-- Tombol Detail --}}
                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $item->pemeliharaan_id }}" title="Detail">
                                                <i class="fa fa-eye"></i>
                                            </button>

                                            {{-- Tombol Mulai (hanya jika dijadwalkan) --}}
                                            @if ($item->canStart())
                                                <form
                                                    action="{{ route('pemeliharaan.start', $item->pemeliharaan_id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Mulai pemeliharaan untuk {{ $item->asset_name }}?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success btn-sm"
                                                        title="Mulai Pemeliharaan">
                                                        <i class="fa fa-play"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Tombol Selesai (hanya jika berlangsung) --}}
                                            @if ($item->canFinish())
                                                <form
                                                    action="{{ route('pemeliharaan.finish', $item->pemeliharaan_id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Selesaikan pemeliharaan untuk {{ $item->asset_name }}?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-primary btn-sm"
                                                        title="Selesai Pemeliharaan">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Tombol Edit (hanya jika dijadwalkan atau dibatalkan) --}}
                                            @if (in_array($item->status_pemeliharaan, ['dijadwalkan', 'dibatalkan']))
                                                <button type="button" class="btn btn-outline-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $item->pemeliharaan_id }}"
                                                    title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endif

                                            {{-- Tombol Batal (jika dijadwalkan atau berlangsung) --}}
                                            @if ($item->canCancel())
                                                <form
                                                    action="{{ route('pemeliharaan.cancel', $item->pemeliharaan_id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Batalkan pemeliharaan ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm"
                                                        title="Batalkan">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Tombol Hapus (tidak bisa jika sedang berlangsung) --}}
                                            @if ($item->status_pemeliharaan !== 'berlangsung')
                                                <form
                                                    action="{{ route('pemeliharaan.destroy', $item->pemeliharaan_id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        title="Hapus">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Detail --}}
                                <div class="modal fade" id="detailModal{{ $item->pemeliharaan_id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header modal-header-gradient text-white border-0">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="fa fa-info-circle me-2"></i> Detail Pemeliharaan
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-secondary small">Tanggal
                                                            Pemeliharaan</label>
                                                        <p class="form-control-plaintext">
                                                            {{ \Carbon\Carbon::parse($item->tanggal_pemeliharaan)->format('d F Y') }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-label fw-semibold text-secondary small">Status</label>
                                                        <p class="form-control-plaintext">
                                                            @if ($item->status_pemeliharaan === 'dijadwalkan')
                                                                <span class="badge bg-secondary">Dijadwalkan</span>
                                                            @elseif($item->status_pemeliharaan === 'berlangsung')
                                                                <span class="badge bg-warning">Berlangsung</span>
                                                            @elseif($item->status_pemeliharaan === 'selesai')
                                                                <span class="badge bg-success">Selesai</span>
                                                            @else
                                                                <span class="badge bg-danger">Dibatalkan</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-secondary small">Jenis
                                                            Asset</label>
                                                        <p class="form-control-plaintext">
                                                            @if ($item->server_id)
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
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold text-secondary small">Nama
                                                            Asset</label>
                                                        <p class="form-control-plaintext">
                                                            {{ $item->server->nama_server ?? ($item->website->nama_website ?? '-') }}
                                                        </p>
                                                    </div>
                                                    @if ($item->status_sebelumnya)
                                                        <div class="col-md-6">
                                                            <label
                                                                class="form-label fw-semibold text-secondary small">Status
                                                                Sebelumnya</label>
                                                            <p class="form-control-plaintext">
                                                                <span
                                                                    class="badge bg-light text-dark">{{ strtoupper($item->status_sebelumnya) }}</span>
                                                            </p>
                                                        </div>
                                                    @endif
                                                    @if ($item->tanggal_selesai_aktual)
                                                        <div class="col-md-6">
                                                            <label
                                                                class="form-label fw-semibold text-secondary small">Tanggal
                                                                Selesai</label>
                                                            <p class="form-control-plaintext">
                                                                {{ \Carbon\Carbon::parse($item->tanggal_selesai_aktual)->format('d F Y H:i') }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-12">
                                                        <label
                                                            class="form-label fw-semibold text-secondary small">Keterangan</label>
                                                        <p class="form-control-plaintext bg-light p-3 rounded">
                                                            {{ $item->keterangan ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fa fa-times me-1"></i> Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Edit --}}
                                @if (in_array($item->status_pemeliharaan, ['dijadwalkan', 'dibatalkan']))
                                    <div class="modal fade" id="editModal{{ $item->pemeliharaan_id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header modal-header-gradient text-white border-0">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="fa fa-edit me-2"></i> Edit Pemeliharaan
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form
                                                    action="{{ route('pemeliharaan.update', $item->pemeliharaan_id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Tanggal Pemeliharaan
                                                                    <span class="text-danger">*</span></label>
                                                                <input type="date" name="tanggal_pemeliharaan"
                                                                    class="form-control"
                                                                    value="{{ $item->tanggal_pemeliharaan }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Jenis Asset <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="jenis_asset"
                                                                    class="form-select jenis-asset-edit" required
                                                                    data-modal-id="{{ $item->pemeliharaan_id }}">
                                                                    <option value="">-- Pilih Jenis --</option>
                                                                    <option value="server"
                                                                        {{ $item->server_id ? 'selected' : '' }}>Server
                                                                    </option>
                                                                    <option value="website"
                                                                        {{ $item->website_id ? 'selected' : '' }}>Website
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 server-select-edit-{{ $item->pemeliharaan_id }}"
                                                                style="display: {{ $item->server_id ? 'block' : 'none' }};">
                                                                <label class="form-label fw-semibold">Pilih Server</label>
                                                                <select name="server_id" class="form-select">
                                                                    <option value="">-- Pilih Server --</option>
                                                                    @foreach ($servers as $server)
                                                                        <option value="{{ $server->server_id }}"
                                                                            {{ $item->server_id == $server->server_id ? 'selected' : '' }}>
                                                                            {{ $server->nama_server }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 website-select-edit-{{ $item->pemeliharaan_id }}"
                                                                style="display: {{ $item->website_id ? 'block' : 'none' }};">
                                                                <label class="form-label fw-semibold">Pilih Website</label>
                                                                <select name="website_id" class="form-select">
                                                                    <option value="">-- Pilih Website --</option>
                                                                    @foreach ($websites as $website)
                                                                        <option value="{{ $website->website_id }}"
                                                                            {{ $item->website_id == $website->website_id ? 'selected' : '' }}>
                                                                            {{ $website->nama_website }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                           <div class="col-md-12">
                                                                <label class="form-label fw-semibold">
                                                                    Keterangan <span class="text-danger">*</span>
                                                                </label>
                                                                <textarea name="keterangan"
                                                                        class="form-control summernote"
                                                                        rows="4"
                                                                        required>{!! $item->keterangan ?? '' !!}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
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
                                @endif

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
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
            @if (isset($pemeliharaan) && $pemeliharaan->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $pemeliharaan->links() }}
                </div>
            @endif
        </div>

    </div>

    <style>
        /* Card Statistics */
        .card-stat {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
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

        /* Button Group */
        .btn-group-sm>.btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        tbody tr td.text-center.text-muted {
            background-color: #f8f9fa !important;
        }

        tbody tr td.text-muted i.fa-inbox {
            color: #6c757d !important;
            opacity: 0.6 !important;
        }

        /* Modal Gradient Header */
        .modal-header-gradient {
            background: linear-gradient(135deg, #7b0000 0%, #b91d1d 100%);
            border-radius: 8px 8px 0 0;
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
        /* Summernote Custom Styles */
        .note-editor {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }

        .note-editor.note-frame {
            border: 1px solid #dee2e6;
        }

        .note-editor.note-frame:focus-within {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .note-toolbar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 0.5rem;
        }

        .note-editable {
            background-color: #fff;
            padding: 1rem;
            min-height: 150px;
        }

        .note-editable p {
            margin-bottom: 0.5rem;
        }

        .modal .note-editor {
            margin-bottom: 0;
        }

        .note-btn-group {
            margin-right: 0.25rem;
        }

        .note-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        /* Fix untuk modal overlay */
        .modal.show .note-modal {
            z-index: 1060 !important;
        }

        .note-modal-backdrop {
            z-index: 1055 !important;
        }

    </style>

    {{-- ======= MODAL TAMBAH PEMELIHARAAN ======= --}}
    <div class="modal fade" id="tambahPemeliharaanModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header modal-header-gradient text-white border-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fa fa-plus-circle me-2"></i> Tambah Jadwal Pemeliharaan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pemeliharaan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Pemeliharaan <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pemeliharaan" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Asset <span
                                        class="text-danger">*</span></label>
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
                                    @foreach ($servers as $server)
                                        <option value="{{ $server->server_id }}">
                                            {{ $server->nama_server }} ({{ $server->power_status }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hanya menampilkan server yang tidak sedang maintenance</small>
                            </div>
                            <div class="col-md-12" id="websiteSelect" style="display:none;">
                                <label class="form-label fw-semibold">Pilih Website</label>
                                <select name="website_id" class="form-select">
                                    <option value="">-- Pilih Website --</option>
                                    @foreach ($websites as $website)
                                        <option value="{{ $website->website_id }}">
                                            {{ $website->nama_website }} ({{ $website->status }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hanya menampilkan website yang tidak sedang maintenance</small>
                            </div>
                           <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                Keterangan <span class="text-danger">*</span>
                            </label>

                            <textarea name="keterangan"
                                    class="form-control summernote"
                                    rows="4"
                                    required
                                    placeholder="Jelaskan detail pemeliharaan yang akan dilakukan..."></textarea>
                        </div>
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

    @push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Summernote CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    
    // ===========================
    // INISIALISASI SUMMERNOTE
    // ===========================
    function initSummernote() {
        $('.summernote').summernote({
            height: 150,
            minHeight: 150,
            maxHeight: 300,
            placeholder: 'Jelaskan detail pemeliharaan yang akan dilakukan...',
            ttoolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
             callbacks: {
                onInit: function() {
                    console.log('Summernote initialized');
                },
                onChange: function(contents, $editable) {
                    // Update textarea value saat konten berubah
                    $(this).val(contents);
                }
            }
        });
    }

    // Inisialisasi pertama kali
    initSummernote();

    // ===========================
    // REFRESH SAAT MODAL DIBUKA
    // ===========================
    $('.modal').on('shown.bs.modal', function () {
        const $modal = $(this);
        const $summernote = $modal.find('.summernote');
        
        // Destroy dulu jika sudah ada
        if ($summernote.next('.note-editor').length) {
            $summernote.summernote('destroy');
        }
        
        // Inisialisasi ulang
        $summernote.summernote({
            height: 150,
            minHeight: 150,
            maxHeight: 300,
            placeholder: 'Jelaskan detail pemeliharaan yang akan dilakukan...',
            toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
       });
    });

    // ===========================
    // CLEANUP SAAT MODAL DITUTUP
    // ===========================
    $('.modal').on('hidden.bs.modal', function () {
        const $summernote = $(this).find('.summernote');
        if ($summernote.next('.note-editor').length) {
            $summernote.summernote('destroy');
        }
    });

    // ===========================
    // HANDLE JENIS ASSET - TAMBAH MODAL
    // ===========================
    $('#jenisAsset').on('change', function() {
        const value = $(this).val();
        $('#serverSelect').hide();
        $('#websiteSelect').hide();

        if (value === 'server') {
            $('#serverSelect').show();
        } else if (value === 'website') {
            $('#websiteSelect').show();
        }
    });

    // ===========================
    // HANDLE JENIS ASSET - EDIT MODALS
    // ===========================
    $('.jenis-asset-edit').on('change', function() {
        const modalId = $(this).data('modal-id');
        $(`.server-select-edit-${modalId}`).hide();
        $(`.website-select-edit-${modalId}`).hide();

        if ($(this).val() === 'server') {
            $(`.server-select-edit-${modalId}`).show();
        } else if ($(this).val() === 'website') {
            $(`.website-select-edit-${modalId}`).show();
        }
    });

    // ===========================
    // VALIDASI SEBELUM SUBMIT
    // ===========================
    $('form').on('submit', function(e) {
        const $form = $(this);
        const $summernote = $form.find('.summernote');
        
        if ($summernote.length) {
            // Ambil konten dari Summernote
            const content = $summernote.summernote('code');
            
            // Cek apakah konten kosong (hanya tag HTML tanpa teks)
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = content;
            const textContent = tempDiv.textContent || tempDiv.innerText || '';
            
            if (!textContent.trim()) {
                e.preventDefault();
                alert('Keterangan tidak boleh kosong!');
                return false;
            }
            
            // Set value ke textarea
            $summernote.val(content);
        }
    });
});
</script>
@endpush
@endsection
