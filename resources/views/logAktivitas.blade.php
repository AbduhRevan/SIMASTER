@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="container-fluid py-3">

    {{-- Statistics Cards - Clean White Design --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-2 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Aktivitas</p>
                            <h2 class="mb-0 fw-bold" style="color: #2c3e50;">{{ $totalLog }}</h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #f8f9fa;">
                            <i class="fa fa-list fa-lg" style="color: #6c757d;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-2 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Create</p>
                            <h2 class="mb-0 fw-bold" style="color: #2c3e50;">{{ $logCreate }}</h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #f8f9fa;">
                            <i class="fa fa-plus-circle fa-lg" style="color: #28a745;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-2 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Update</p>
                            <h2 class="mb-0 fw-bold" style="color: #2c3e50;">{{ $logUpdate }}</h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #f8f9fa;">
                            <i class="fa fa-edit fa-lg" style="color: #ffc107;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-2 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Delete</p>
                            <h2 class="mb-0 fw-bold" style="color: #2c3e50;">{{ $logDelete }}</h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #f8f9fa;">
                            <i class="fa fa-trash fa-lg" style="color: #dc3545;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header p-3" style="background: linear-gradient(135deg, #8B0000 0%, #6B0000 100%); border-radius: 0.5rem 0.5rem 0 0;">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-white fw-semibold">
                    <i class="fa fa-clock-rotate-left me-2"></i> Riwayat Aktivitas
                </h6>
            </div>
        </div>

        <div class="card-body p-4">
            {{-- Filter Section --}}
            <form method="GET" action="{{ route('logAktivitas') }}" class="row g-3 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fa fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" 
                            placeholder="Cari aktivitas..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <select name="aksi" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Aksi</option>
                        <option value="CREATE" {{ request('aksi') == 'CREATE' ? 'selected' : '' }}>Create</option>
                        <option value="UPDATE" {{ request('aksi') == 'UPDATE' ? 'selected' : '' }}>Update</option>
                        <option value="DELETE" {{ request('aksi') == 'DELETE' ? 'selected' : '' }}>Delete</option>
                        <option value="LOGIN" {{ request('aksi') == 'LOGIN' ? 'selected' : '' }}>Login</option>
                        <option value="LOGOUT" {{ request('aksi') == 'LOGOUT' ? 'selected' : '' }}>Logout</option>
                        <option value="VIEW" {{ request('aksi') == 'VIEW' ? 'selected' : '' }}>View</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <select name="entitas" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Entitas</option>
                        <option value="pengguna" {{ request('entitas') == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                        <option value="server" {{ request('entitas') == 'server' ? 'selected' : '' }}>Server</option>
                        <option value="website" {{ request('entitas') == 'website' ? 'selected' : '' }}>Website</option>
                        <option value="pemeliharaan" {{ request('entitas') == 'pemeliharaan' ? 'selected' : '' }}>Pemeliharaan</option>
                        <option value="bidang" {{ request('entitas') == 'bidang' ? 'selected' : '' }}>Bidang</option>
                        <option value="satuan_kerja" {{ request('entitas') == 'satuan_kerja' ? 'selected' : '' }}>Satuan Kerja</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <input type="date" name="tanggal" class="form-control" 
                        value="{{ request('tanggal') }}" onchange="this.form.submit()">
                </div>

                <div class="col-lg-1">
                    <a href="{{ route('logAktivitas') }}" class="btn btn-outline-secondary w-100" title="Reset Filter">
                        <i class="fa fa-redo"></i>
                    </a>
                </div>
            </form>

            {{-- Timeline --}}
            <div class="timeline-container">
                @forelse($logs as $log)
                <div class="timeline-item">
                    <div class="timeline-marker">
                        @php
                            $iconData = match($log->aksi) {
                                'CREATE' => ['icon' => 'fa-plus-circle', 'color' => '#28a745'],
                                'UPDATE' => ['icon' => 'fa-edit', 'color' => '#17a2b8'],
                                'DELETE' => ['icon' => 'fa-trash', 'color' => '#dc3545'],
                                'LOGIN' => ['icon' => 'fa-sign-in-alt', 'color' => '#007bff'],
                                'LOGOUT' => ['icon' => 'fa-sign-out-alt', 'color' => '#6c757d'],
                                'VIEW' => ['icon' => 'fa-eye', 'color' => '#ffc107'],
                                default => ['icon' => 'fa-circle', 'color' => '#6c757d']
                            };
                        @endphp
                        <i class="fa {{ $iconData['icon'] }}" style="color: {{ $iconData['color'] }};"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-2 fw-semibold">
                                    @switch($log->aksi)
                                        @case('CREATE') <span style="color: #28a745;">Data Baru Ditambahkan</span> @break
                                        @case('UPDATE') <span style="color: #17a2b8;">Data Diperbarui</span> @break
                                        @case('DELETE') <span style="color: #dc3545;">Data Dihapus</span> @break
                                        @case('LOGIN') <span style="color: #007bff;">Login Sistem</span> @break
                                        @case('LOGOUT') <span style="color: #6c757d;">Logout Sistem</span> @break
                                        @case('VIEW') <span style="color: #ffc107;">Melihat Data</span> @break
                                        @default <span>Aktivitas</span>
                                    @endswitch
                                </h6>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="user-avatar-sm">
                                        {{ strtoupper(substr($log->pengguna->nama_lengkap ?? 'SYS', 0, 2)) }}
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <strong style="font-size: 14px; color: #2c3e50;">{{ $log->pengguna->nama_lengkap ?? 'System' }}</strong>
                                        @if($log->pengguna)
                                            @php
                                                $roleClass = match($log->pengguna->role) {
                                                    'superadmin' => 'bg-danger text-white',
                                                    'banglola' => 'bg-warning text-dark',
                                                    'pamsis' => 'bg-info text-white',
                                                    'infratik' => 'bg-primary text-white',
                                                    default => 'bg-secondary text-white'
                                                };
                                                $roleLabel = match($log->pengguna->role) {
                                                    'superadmin' => 'Super Admin',
                                                    'banglola' => 'Banglola',
                                                    'pamsis' => 'Pamsis',
                                                    'infratik' => 'Infratik',
                                                    default => ucfirst($log->pengguna->role)
                                                };
                                            @endphp
                                            <span class="badge {{ $roleClass }}" style="font-size: 10px; padding: 3px 8px; font-weight: 600;">
                                                {{ $roleLabel }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted" style="white-space: nowrap;">
                                <i class="fa fa-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($log->waktu_aksi)->timezone('Asia/Jakarta')->diffForHumans() }}
                            </small>
                        </div>
                        <p class="mb-3" style="font-size: 14px; color: #495057; line-height: 1.6;">{{ $log->deskripsi }}</p>
                        <div class="d-flex gap-3 text-muted" style="font-size: 12px;">
                            <span>
                                <i class="fa fa-layer-group me-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $log->entitas_diubah)) }}
                            </span>
                            <span>
                                <i class="fa fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($log->waktu_aksi)->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fa fa-inbox fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                    <p class="text-muted mb-0">Belum ada log aktivitas</p>
                </div>
                @endforelse
            </div>
            {{-- Pagination dengan Custom Style seperti Satuan Kerja --}}
            @if($logs->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3" id="paginationWrapper">
                <p class="mb-0 text-secondary small">
                    Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} data
                </p>
                <div class="custom-pagination">
                    {{-- Previous Button --}}
                    @if ($logs->onFirstPage())
                        <span class="pagination-btn disabled">
                            <i class="fa fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $logs->appends(request()->query())->previousPageUrl() }}" class="pagination-btn">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Page Numbers with sliding window --}}
                    @php
                        $currentPage = $logs->currentPage();
                        $lastPage = $logs->lastPage();
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
                            <a href="{{ $logs->appends(request()->query())->url($page) }}" class="pagination-btn">{{ $page }}</a>
                        @endif
                    @endfor

                    {{-- Next Button --}}
                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->appends(request()->query())->nextPageUrl() }}" class="pagination-btn">
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

</div>

<style>
/* Timeline Styles */
.timeline-container {
    position: relative;
    padding-left: 50px;
}

.timeline-container::before {
    content: '';
    position: absolute;
    left: 18px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-bottom: 35px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item:last-child .timeline-container::before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -32px;
    top: 0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: white;
    border: 3px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
    transition: all 0.3s ease;
}

.timeline-item:hover .timeline-marker {
    transform: scale(1.1);
    border-color: #8B0000;
    box-shadow: 0 0 0 4px rgba(139, 0, 0, 0.1);
}

.timeline-marker i {
    font-size: 16px;
}

.timeline-content {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.timeline-content:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border-color: #dee2e6;
    transform: translateY(-2px);
}

.user-avatar-sm {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #8B0000 0%, #6B0000 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
    flex-shrink: 0;
}

/* Custom Pagination Styling - Sama seperti Satuan Kerja */
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

/* Form Controls */
.form-control:focus,
.form-select:focus {
    border-color: #8B0000;
    box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.15);
}

/* Responsive */
@media (max-width: 768px) {
    .timeline-container {
        padding-left: 40px;
    }
    
    .timeline-container::before {
        left: 13px;
    }
    
    .timeline-marker {
        left: -27px;
        width: 30px;
        height: 30px;
    }
    
    .timeline-marker i {
        font-size: 14px;
    }
    
    .timeline-content {
        padding: 15px;
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
    // Smooth scroll to top on pagination click
    $('.pagination-btn').on('click', function() {
        $('html, body').animate({
            scrollTop: $('.card.shadow-sm').offset().top - 100
        }, 400);
    });
});

function exportLog() {
    alert('Fitur Export Log akan segera hadir!');
}

function confirmClearLog() {
    if (confirm('Apakah Anda yakin ingin menghapus semua log? Tindakan ini tidak dapat dibatalkan!')) {
        alert('Fitur Clear Log akan segera hadir!');
    }
}
</script>
@endpush

@endsection