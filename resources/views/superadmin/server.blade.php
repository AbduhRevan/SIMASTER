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
</style>

<div class="container-fluid">
    <!-- Ringkasan Server -->
    <div class="row mb-4 text-center">
        <div class="col-md-3 mb-2">
            <div class="card-summary">
                <h5>Total</h5>
                <h2>{{ $total }}</h2>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card-summary">
                <h5>Aktif</h5>
                <h2>{{ $aktif }}</h2>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card-summary">
                <h5>Maintenance</h5>
                <h2>{{ $maintenance }}</h2>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card-summary">
                <h5>Tidak Aktif</h5>
                <h2>{{ $tidakAktif }}</h2>
            </div>
        </div>
    </div>

    <!-- Daftar Server -->
    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <input type="text" class="form-control me-2" placeholder="Cari nama/server/website/IP" style="width: 280px;">
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fa fa-plus"></i> Tambah Server
            </button>
        </div>

        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Server</th>
                    <th>Brand</th>
                    <th>Spesifikasi</th>
                    <th>Rak / Slot</th>
                    <th>Bidang</th>
                    <th>Satker</th>
                    <th>Website</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servers as $index => $server)
                <tr>
                    <td>{{ $index + 1 }}.</td>
                    <td>{{ $server->nama_server }}</td>
                    <td>{{ $server->brand ?? '-' }}</td>
                    <td>{{ $server->spesifikasi ?? '-' }}</td>
                    <td>{{ $server->rak ? $server->rak->nama_rak : '-' }} / {{ $server->u_slot ?? '-' }}</td>
                    <td>
                        @if($server->satker && $server->satker->nama_satker == 'Pusat Data dan Informasi Kemhan')
                            {{ $server->bidang->nama_bidang ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $server->satker ? $server->satker->nama_satker : '-' }}</td>
                    <td>{{ $server->website ? $server->website->nama_website : '-' }}</td>
                    <td>
                        @if($server->power_status==='ON')
                            <span class="badge bg-success">Aktif</span>
                        @elseif($server->power_status==='STANDBY')
                            <span class="badge bg-warning text-dark">Maintenance</span>
                        @else
                            <span class="badge bg-danger">Tidak Aktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('superadmin.server.detail', $server->server_id) }}" class="btn btn-light btn-sm">
                            <i class="fa fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Server -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content p-0">
            <div class="modal-header maroon-header">
                <h5 class="modal-title">Tambah Server Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="serverForm" action="{{ route('superadmin.server.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <!-- Step 1 -->
                    <div class="step">
                        <div class="mb-3">
                            <label class="form-label">Nama Server</label>
                            <input type="text" name="nama_server" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <input type="text" name="brand" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Spesifikasi</label>
                            <textarea name="spesifikasi" class="form-control"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Satker</label>
                                <select name="satker_id" id="satkerSelect" class="form-select" required>
                                    <option value="">Pilih Satker</option>
                                    @foreach($satkers as $satker)
                                        <option value="{{ $satker->satker_id }}" data-name="{{ $satker->nama_satker }}">{{ $satker->nama_satker }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="bidangWrapper" style="display:none;">
                                <label class="form-label">Bidang</label>
                                <select name="bidang_id" id="bidangSelect" class="form-select">
                                    <option value="">Pilih Bidang</option>
                                    @foreach($bidangs as $bidang)
                                        <option value="{{ $bidang->bidang_id }}">{{ $bidang->nama_bidang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rak</label>
                            <select name="rak_id" class="form-select">
                                <option value="">Pilih Rak</option>
                                @foreach($raks as $rak)
                                    <option value="{{ $rak->rak_id }}">{{ $rak->nomor_rak }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slot</label>
                            <input type="text" name="u_slot" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input type="text" name="website_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="summernote" class="form-control"></textarea>
                        </div>
                    </div>

                    <!-- Step 2: Konfirmasi -->
                    <div class="step" style="display:none;">
                        <h5>Konfirmasi Data Server</h5>
                        <ul class="list-group">
                            <li class="list-group-item">Nama Server: <span id="confirmNama"></span></li>
                            <li class="list-group-item">Brand: <span id="confirmBrand"></span></li>
                            <li class="list-group-item">Spesifikasi: <span id="confirmSpesifikasi"></span></li>
                            <li class="list-group-item">Satker: <span id="confirmSatker"></span></li>
                            <li class="list-group-item">Bidang: <span id="confirmBidang"></span></li>
                            <li class="list-group-item">Rak: <span id="confirmRak"></span></li>
                            <li class="list-group-item">Slot: <span id="confirmSlot"></span></li>
                            <li class="list-group-item">Website: <span id="confirmWebsite"></span></li>
                            <li class="list-group-item">Keterangan: <span id="confirmKeterangan"></span></li>
                        </ul>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" id="prevBtn" class="btn" style="display:none;">Sebelumnya</button>
                    <button type="button" id="nextBtn" class="btn btn-maroon">Selanjutnya</button>
                    <button type="submit" id="saveBtn" class="btn" style="display:none;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS Summernote & Stepper -->
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Summernote
    $('#summernote').summernote({
        height: 150,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

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
        $('#summernote').summernote('reset');
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
        // Update konfirmasi
        $('#confirmNama').text($('input[name="nama_server"]').val() || '-');
        $('#confirmBrand').text($('input[name="brand"]').val() || '-');
        $('#confirmSpesifikasi').text($('textarea[name="spesifikasi"]').val() || '-');
        $('#confirmSatker').text($('#satkerSelect option:selected').text() || '-');
        
        // Cek apakah bidang visible
        if($('#bidangWrapper').is(':visible')) {
            $('#confirmBidang').text($('#bidangSelect option:selected').text() || '-');
        } else {
            $('#confirmBidang').text('-');
        }
        
        $('#confirmRak').text($('select[name="rak_id"] option:selected').text() || '-');
        $('#confirmSlot').text($('input[name="u_slot"]').val() || '-');
        $('#confirmWebsite').text($('input[name="website_name"]').val() || '-');
        
        // Ambil konten dari Summernote
        const keteranganContent = $('#summernote').summernote('code');
        $('#confirmKeterangan').html(keteranganContent || '-');

        currentStep++;
        showStep(currentStep);
    });

    prevBtn.click(function(){
        currentStep--;
        showStep(currentStep);
    });

    // Bidang muncul HANYA jika nama Satker adalah "Pusat Data dan Informasi Kemhan"
    $('#satkerSelect').change(function(){
        const selectedName = $('#satkerSelect option:selected').data('name');
        
        // Cek apakah nama satker persis "Pusat Data dan Informasi Kemhan"
        if(selectedName === 'Pusat Data dan Informasi Kemhan'){
            $('#bidangWrapper').show();
        } else {
            $('#bidangWrapper').hide();
            $('#bidangSelect').val(''); // Reset pilihan bidang
        }
    });
});
</script>
@endpush
@endsection