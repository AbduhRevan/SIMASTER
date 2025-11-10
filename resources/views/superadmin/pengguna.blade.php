@extends('layouts.app')

@section('content')
    <style>
        .content-wrapper {
            width: 100%;
            max-width: 100%;
        }

        .filter-section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .filter-row {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            display: block;
        }

        .filter-select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }

        .btn-generate {
            background: #6B1515;
            color: white;
            border: none;
            padding: 10px 40px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            height: 46px;
            white-space: nowrap;
        }

        .btn-generate:hover {
            background: #4A0E0E;
        }

        .action-bar {
            background: white;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            margin-bottom: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            max-width: 400px;
            flex: 1;
            min-width: 250px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .search-input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-size: 14px;
        }

        .search-input::placeholder {
            color: #999;
        }

        .btn-add {
            background: #6B1515;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .btn-add:hover {
            background: #4A0E0E;
        }

        .table-container {
            background: white;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .data-table thead {
            background: #f8f9fa;
        }

        .data-table th {
            padding: 15px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e9ecef;
            white-space: nowrap;
        }

        .data-table td {
            padding: 15px;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
        }

        .data-table tbody tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            white-space: nowrap;
        }

        .status-aktif {
            background: #d4edda;
            color: #155724;
        }

        .status-nonaktif {
            background: #e2e3e5;
            color: #6c757d;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .btn-edit {
            background: #fff3cd;
            color: #856404;
        }

        .btn-edit:hover {
            background: #ffc107;
        }

        .btn-disable {
            background: #f8d7da;
            color: #721c24;
        }

        .btn-disable:hover {
            background: #dc3545;
            color: white;
        }

        .btn-delete {
            background: #f8d7da;
            color: #721c24;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .btn-generate {
                width: 100%;
            }
            
            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                max-width: 100%;
            }
            
            .btn-add {
                width: 100%;
            }
        }
    </style>

    <div class="content-wrapper">
        <!-- Filter Section -->
        <div class="filter-section">
            <form id="filterForm">
                <div class="filter-row">
                    <div class="filter-group">
                        <div class="filter-label">Role</div>
                        <select name="role" class="filter-select">
                            <option>Semua</option>
                            <option>User</option>
                            <option>Admin Bidang</option>
                            <option>Teknisi</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">Status</div>
                        <select name="status" class="filter-select">
                            <option>Semua</option>
                            <option>Aktif</option>
                            <option>NonAktif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-generate">Generate</button>
                </div>
            </form>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Cari nama/username/email">
            </div>
            <button class="btn-add" data-toggle="modal" data-target="#penggunaModal" onclick="openModal('add')">Tambah Pengguna</button>
        </div>

        <!-- Data Table -->
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Username/email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Bidang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="penggunaTableBody">
                    @foreach($pengguna as $index => $user)
                    <tr>
                        <td>{{ $pengguna->firstItem() + $index }}</td>
                        <td>{{ $user->nama_lengkap }}</td>
                        <td>{{ $user->username_email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td><span class="status-badge {{ $user->status == 'active' ? 'status-aktif' : 'status-nonaktif' }}">{{ $user->status == 'active' ? 'Aktif' : 'NonAktif' }}</span></td>
                        <td>{{ $user->bidang ? $user->bidang->nama_bidang : '-' }}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-edit" onclick="editPengguna({{ $user->user_id }})"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn-action btn-disable" onclick="toggleStatus({{ $user->user_id }})"><i class="fas fa-ban"></i></button>
                                <button class="btn-action btn-delete" onclick="deletePengguna({{ $user->user_id }})"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding: 20px;">
                {{ $pengguna->links() }}
            </div>
        </div>
    </div>

    <!-- Modal untuk Tambah/Edit -->
    <div class="modal fade" id="penggunaModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="penggunaForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="userId" name="user_id">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Username/Email</label>
                            <input type="text" name="username_email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" id="passwordField" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="superadmin">Superadmin</option>
                                <option value="banglola">Banglola</option>
                                <option value="pamsis">Pamsis</option>
                                <option value="infratik">Infratik</option>
                                <option value="tatausaha">Tatausaha</option>
                                <option value="pimpinan">Pimpinan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bidang</label>
                            <select name="bidang_id" class="form-control">
                                <option value="">Pilih Bidang</option>
                                <!-- Options akan diisi via JS -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">NonAktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Filter dan Search
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        loadPengguna();
    });

    $('#searchInput').on('keyup', function() {
        loadPengguna();
    });

    function loadPengguna() {
        $.ajax({
            url: '{{ route("superadmin.pengguna.index") }}',
            data: {
                role: $('select[name=role]').val(),
                status: $('select[name=status]').val(),
                search: $('#searchInput').val()
            },
            success: function(response) {
                $('#penggunaTableBody').html(response.data.map((user, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${user.nama_lengkap}</td>
                        <td>${user.username_email}</td>
                        <td>${user.role}</td>
                        <td><span class="status-badge ${user.status == 'active' ? 'status-aktif' : 'status-nonaktif'}">
                            ${user.status == 'active' ? 'Aktif' : 'NonAktif'}
                        </span></td>
                        <td>${user.bidang ? user.bidang.nama_bidang : '-'}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-edit" onclick="editPengguna(${user.user_id})">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="btn-action btn-disable" onclick="toggleStatus(${user.user_id})">
                                    <i class="fas fa-ban"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="deletePengguna(${user.user_id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join(''));
            }
        });
    }

    // Modal Functions
    window.openModal = function(type, id = null) {
        $('#modalTitle').text(type === 'add' ? 'Tambah Pengguna' : 'Edit Pengguna');
        $('#userId').val('');
        $('#penggunaForm')[0].reset();
        $('#passwordField').attr('required', type === 'add');
        
        if (type === 'edit' && id) {
            editPengguna(id);
        } else {
            $.get('{{ route("superadmin.pengguna.bidang") }}', function(data) {
                populateBidang(data.bidang);
            });
            $('#penggunaModal').modal('show');
        }
    };

    window.editPengguna = function(id) {
        let editUrl = `{{ url('superadmin/pengguna') }}/${id}/edit`;
        $.get(editUrl, function(data) {
            $('#userId').val(data.pengguna.user_id);
            $('input[name=nama_lengkap]').val(data.pengguna.nama_lengkap);
            $('input[name=username_email]').val(data.pengguna.username_email);
            $('select[name=role]').val(data.pengguna.role);
            $('select[name=status]').val(data.pengguna.status);
            populateBidang(data.bidang, data.pengguna.bidang_id);
            $('#passwordField').removeAttr('required');
            $('#penggunaModal').modal('show');
        });
    };

    function populateBidang(bidang, selected = '') {
        let options = '<option value="">Pilih Bidang</option>';
        bidang.forEach(b => {
            options += `<option value="${b.bidang_id}" ${b.bidang_id == selected ? 'selected' : ''}>${b.nama_bidang}</option>`;
        });
        $('select[name=bidang_id]').html(options);
    }

    // Submit Form Add/Edit
    $('#penggunaForm').on('submit', function(e) {
        e.preventDefault();

        let userId = $('#userId').val();
        let url = userId 
            ? `{{ url('superadmin/pengguna/update') }}/${userId}`
            : '{{ route("superadmin.pengguna.store") }}';
        let method = userId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function() {
                $('#penggunaModal').modal('hide');
                loadPengguna();
                alert('Berhasil disimpan!');
            },
            error: function(xhr) {
                if(xhr.responseJSON && xhr.responseJSON.errors){
                    let messages = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    alert(messages);
                } else {
                    alert('Error: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan'));
                }
            }
        });
    });

    window.toggleStatus = function(id) {
        if(confirm('Yakin ingin mengubah status pengguna ini?')) {
            $.ajax({
                url: `{{ url('superadmin/pengguna/toggle-status') }}/${id}`,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    loadPengguna();
                    alert('Status berhasil diubah!');
                },
                error: function() {
                    alert('Gagal mengubah status!');
                }
            });
        }
    };

    window.deletePengguna = function(id) {
        if(confirm('Yakin ingin menghapus pengguna ini?')) {
            $.ajax({
                url: `{{ url('superadmin/pengguna') }}/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    loadPengguna();
                    alert('Pengguna berhasil dihapus!');
                },
                error: function() {
                    alert('Gagal menghapus pengguna!');
                }
            });
        }
    };
});
</script>
@endpush