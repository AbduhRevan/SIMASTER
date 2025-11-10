@extends('layouts.app')

@section('content')
    <style>
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
            max-width: 900px;
        }

        .filter-group {
            flex: 1;
        }

        .filter-label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
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
        }

        .search-box {
            position: relative;
            max-width: 400px;
            flex: 1;
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
        }

        .btn-add:hover {
            background: #4A0E0E;
        }

        .table-container {
            background: white;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
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
    </style>

    <div class="content-wrapper">
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-row">
                <div class="filter-group">
                    <div class="filter-label">Role</div>
                    <select class="filter-select">
                        <option>Semua</option>
                        <option>User</option>
                        <option>Admin Bidang</option>
                        <option>Teknisi</option>
                    </select>
                </div>
                <div class="filter-group">
                    <div class="filter-label">Status</div>
                    <select class="filter-select">
                        <option>Semua</option>
                        <option>Aktif</option>
                        <option>NonAktif</option>
                    </select>
                </div>
                <button class="btn-generate">Generate</button>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" class="search-input" placeholder="Cari nama/username/email">
            </div>
            <button class="btn-add">Tambah Pengguna</button>
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
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Andi Saputra</td>
                        <td>andi01@gmail.com</td>
                        <td>User</td>
                        <td><span class="status-badge status-aktif">Aktif</span></td>
                        <td>Bangloa</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn-action btn-disable"><i class="fas fa-ban"></i></button>
                                <button class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Budi Pratama</td>
                        <td>budipra@gmail.com</td>
                        <td>Admin Bidang</td>
                        <td><span class="status-badge status-nonaktif">NonAktif</span></td>
                        <td>Bangloa</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn-action btn-disable"><i class="fas fa-ban"></i></button>
                                <button class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Abduh Revan</td>
                        <td>vann4@gmail.com</td>
                        <td>Admin Bidang</td>
                        <td><span class="status-badge status-aktif">Aktif</span></td>
                        <td>Pamsis</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn-action btn-disable"><i class="fas fa-ban"></i></button>
                                <button class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Nayla Zharif</td>
                        <td>naynay@gmail.com</td>
                        <td>User</td>
                        <td><span class="status-badge status-nonaktif">NonAktif</span></td>
                        <td>Pamsis</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn-action btn-disable"><i class="fas fa-ban"></i></button>
                                <button class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Bela Adelia</td>
                        <td>adeliabel@gmail.com</td>
                        <td>Teknisi</td>
                        <td><span class="status-badge status-aktif">Aktif</span></td>
                        <td>Infratik</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn-action btn-disable"><i class="fas fa-ban"></i></button>
                                <button class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
