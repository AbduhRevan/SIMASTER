@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card-summary">
            <h5>
                <i class="fa fa-globe me-2 text-primary"></i>
                Ringkasan Website 
                <span class="badge bg-primary">Total: 33</span>
            </h5>
            <div class="d-flex justify-content-around mt-4">
                <div class="text-center">
                    <div class="text-muted small">Aktif</div>
                    <h3 class="text-success mb-0">20</h3>
                </div>
                <div class="text-center">
                    <div class="text-muted small">Maintenance</div>
                    <h3 class="text-warning mb-0">3</h3>
                </div>
                <div class="text-center">
                    <div class="text-muted small">Tidak Aktif</div>
                    <h3 class="text-danger mb-0">10</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3">
        <div class="card-summary">
            <h5>
                <i class="fa fa-server me-2 text-info"></i>
                Ringkasan Server 
                <span class="badge bg-info">Total: 10</span>
            </h5>
            <div class="d-flex justify-content-around mt-4">
                <div class="text-center">
                    <div class="text-muted small">Aktif</div>
                    <h3 class="text-success mb-0">7</h3>
                </div>
                <div class="text-center">
                    <div class="text-muted small">Maintenance</div>
                    <h3 class="text-warning mb-0">2</h3>
                </div>
                <div class="text-center">
                    <div class="text-muted small">Tidak Aktif</div>
                    <h3 class="text-danger mb-0">1</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Data -->
<div class="table-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            <i class="fa fa-table me-2"></i>
            Data Website & Server
        </h5>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" style="width: auto;">
                <option>Semua Status</option>
                <option>Aktif</option>
                <option>Maintenance</option>
                <option>Tidak Aktif</option>
            </select>
            <input type="text" class="form-control form-control-sm" 
                   placeholder="🔍 Cari nama/URL/PIC" style="width: 250px;">
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th>PIC</th>
                    <th>Update Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Portal Kemhan RI</strong></td>
                    <td><a href="https://portal.kemhan.go.id" target="_blank">portal.kemhan.go.id</a></td>
                    <td><span class="badge bg-success">Aktif</span></td>
                    <td>Banglola</td>
                    <td>2025-08-20</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><strong>PPID Kemhan</strong></td>
                    <td><a href="https://ppid.kemhan.go.id" target="_blank">ppid.kemhan.go.id</a></td>
                    <td><span class="badge bg-danger">Tidak Aktif</span></td>
                    <td>Banglola</td>
                    <td>2023-08-01</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><strong>SVR-02-A</strong></td>
                    <td><a href="#">192.168.1.2</a></td>
                    <td><span class="badge bg-warning text-dark">Maintenance</span></td>
                    <td>Infratik</td>
                    <td>2025-07-19</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Grafik -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fa fa-chart-bar me-2 text-primary"></i>
                    Grafik Status Website
                </h5>
                <canvas id="websiteChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fa fa-chart-bar me-2 text-info"></i>
                    Grafik Status Server
                </h5>
                <canvas id="serverChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Website Chart
    const websiteCtx = document.getElementById('websiteChart').getContext('2d');
    new Chart(websiteCtx, {
        type: 'bar',
        data: {
            labels: ['Aktif', 'Maintenance', 'Tidak Aktif'],
            datasets: [{
                label: 'Jumlah Website',
                data: [20, 3, 10],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgb(40, 167, 69)',
                    'rgb(255, 193, 7)',
                    'rgb(220, 53, 69)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });

    // Server Chart
    const serverCtx = document.getElementById('serverChart').getContext('2d');
    new Chart(serverCtx, {
        type: 'bar',
        data: {
            labels: ['Aktif', 'Maintenance', 'Tidak Aktif'],
            datasets: [{
                label: 'Jumlah Server',
                data: [7, 2, 1],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgb(40, 167, 69)',
                    'rgb(255, 193, 7)',
                    'rgb(220, 53, 69)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 2
                    }
                }
            }
        }
    });
</script>
@endsection