@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-3">

    {{-- ======= RINGKASAN WEBSITE DAN SERVER ======= --}}
<div class="row mb-4 g-3">
    {{-- Ringkasan Website --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 p-3 rounded-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h6 class="fw-semibold mb-0">
                    <i class="fa fa-globe me-2 text-primary"></i> Ringkasan Website
                </h6>
                <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2">
                    Total: {{ $totalWebsite }}
                </span>
            </div>
            <div class="d-flex justify-content-around text-center">
                <div>
                    <div class="fw-semibold text-secondary small">Aktif</div>
                    <a href="{{ route('website.index', ['status' => 'active']) }}" class="text-decoration-none">
                        <h4 class="text-success mb-0">{{ $aktifWebsite }}</h4>
                    </a>
                </div>
                <div>
                    <div class="fw-semibold text-secondary small">Maintenance</div>
                    <a href="{{ route('website.index', ['status' => 'maintenance']) }}" class="text-decoration-none">
                        <h4 class="text-warning mb-0">{{ $maintenanceWebsite }}</h4>
                    </a>
                </div>
                <div>
                    <div class="fw-semibold text-secondary small">Tidak Aktif</div>
                    <a href="{{ route('website.index', ['status' => 'inactive']) }}" class="text-decoration-none">
                        <h4 class="text-danger mb-0">{{ $tidakAktifWebsite }}</h4>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Server --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 p-3 rounded-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h6 class="fw-semibold mb-0">
                    <i class="fa fa-server me-2 text-info"></i> Ringkasan Server
                </h6>
                <span class="badge bg-info-subtle text-info fw-semibold px-3 py-2">
                    Total: {{ $totalServer }}
                </span>
            </div>
            <div class="d-flex justify-content-around text-center">
                <div>
                    <div class="fw-semibold text-secondary small">Aktif</div>
                    <a href="{{ route('server.index', ['status' => 'ON']) }}" class="text-decoration-none">
                        <h4 class="text-success mb-0">{{ $aktifServer }}</h4>
                    </a>
                </div>
                <div>
                    <div class="fw-semibold text-secondary small">Maintenance</div>
                    <a href="{{ route('server.index', ['status' => 'STANDBY']) }}" class="text-decoration-none">
                        <h4 class="text-warning mb-0">{{ $maintenanceServer }}</h4>
                    </a>
                </div>
                <div>
                    <div class="fw-semibold text-secondary small">Tidak Aktif</div>
                    <a href="{{ route('server.index', ['status' => 'OFF']) }}" class="text-decoration-none">
                        <h4 class="text-danger mb-0">{{ $tidakAktifServer }}</h4>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ======= DATA WEBSITE & SERVER (10 TERBARU) ======= --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center p-3">
        <div>
            <h6 class="fw-semibold mb-0">
                <i class="fa fa-table me-2 text-secondary"></i> Data Website & Server Terbaru
            </h6>
            <small class="text-muted">Menampilkan 10 data terbaru</small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('website.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-globe me-1"></i> Lihat Semua Website
            </a>
            <a href="{{ route('server.index') }}" class="btn btn-sm btn-outline-info">
                <i class="fa fa-server me-1"></i> Lihat Semua Server
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tipe</th>
                    <th>Nama</th>
                    <th>URL / Alamat</th>
                    <th>Status</th>
                    <th>PIC</th>
                    <th>Update Terakhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gabunganData->take(10) as $item)
                <tr>
                    <td>
                        <span class="badge {{ $item['tipe'] == 'Website' ? 'bg-primary-subtle text-primary' : 'bg-info-subtle text-info' }}">
                            {{ $item['tipe'] }}
                        </span>
                    </td>
                    <td><strong>{{ $item['nama'] }}</strong></td>
                    <td>
                        @if($item['url'] && $item['url'] != '-')
                            <a href="{{ $item['url'] }}" target="_blank" class="text-decoration-none">
                                {{ Str::limit($item['url'], 40) }}
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($item['status'] == 'active')
                            <span class="badge bg-success-subtle text-success">Aktif</span>
                        @elseif($item['status'] == 'maintenance')
                            <span class="badge bg-warning-subtle text-warning">Maintenance</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger">Tidak Aktif</span>
                        @endif
                    </td>
                    <td>{{ $item['pic'] }}</td>
                    <td>
                        <small>{{ $item['updated_at'] ? $item['updated_at']->format('d M Y, H:i') : '-' }}</small>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                        Belum ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($gabunganData->count() > 10)
    <div class="card-footer bg-light text-center py-2">
        <small class="text-muted">
            Menampilkan 10 dari {{ $gabunganData->count() }} total data.
        </small>
    </div>
    @endif
</div>


    {{-- ======= GRAFIK STATUS WEBSITE & SERVER ======= --}}
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3"><i class="fa fa-chart-bar me-2 text-primary"></i>Grafik Status Website</h6>
                    <canvas id="websiteChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3"><i class="fa fa-chart-bar me-2 text-info"></i>Grafik Status Server</h6>
                    <canvas id="serverChart" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ======= CHART.JS SCRIPT ======= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Website chart
    const websiteCtx = document.getElementById('websiteChart');
    new Chart(websiteCtx, {
        type: 'bar',
        data: {
            labels: ['Aktif', 'Maintenance', 'Tidak Aktif'],
            datasets: [{
                label: 'Jumlah Website',
                data: [{{ $aktifWebsite }}, {{ $maintenanceWebsite }}, {{ $tidakAktifWebsite }}],
                backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                borderRadius: 8
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Server chart
    const serverCtx = document.getElementById('serverChart');
    new Chart(serverCtx, {
        type: 'bar',
        data: {
            labels: ['Aktif', 'Maintenance', 'Tidak Aktif'],
            datasets: [{
                label: 'Jumlah Server',
                data: [{{ $aktifServer }}, {{ $maintenanceServer }}, {{ $tidakAktifServer }}],
                backgroundColor: ['#0dcaf0', '#ffc107', '#dc3545'],
                borderRadius: 8
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>

<style>
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
</style>

@endsection
