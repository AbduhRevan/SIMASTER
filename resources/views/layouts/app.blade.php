<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIMASTER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f5f7;
            margin: 0;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 240px;
            background-color: #7b0000;
            color: #fff;
            padding: 20px 15px;
            display: flex;
            flex-direction: column;
        }
        .sidebar .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar .logo img {
            width: 70px;
        }
        .sidebar .logo h5 {
            font-weight: 600;
            margin-top: 10px;
            font-size: 16px;
        }
        .menu-link {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 10px;
            transition: 0.3s;
            font-size: 14px;
        }

        .menu-link i {
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .menu-link:hover,
        .menu-link.active {
            background-color: #ffffffff;
            color: #7b0000;
            transform: translateX(5px);
        }
        
        .menu-link:hover i,
        .menu-link.active i {
             color: #7b0000;
             transform: scale(1.2); /* icon sedikit membesar */
        }
        .menu-section p {
            font-size: 12px;
            text-transform: uppercase;
            color: #d1d1d1;
            margin: 20px 0 5px 10px;
        }
        .content {
            margin-left: 260px;
            padding: 25px 30px;
        }
        .card-summary {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            padding: 20px;
        }
        .table-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            padding: 20px;
        }
        .table th {
            background-color: #f9f9f9;
        }
        .sidebar hr {
            border-color: rgba(255,255,255,0.2);
        }

        .topbar {
             border-radius: 20px;
             margin: 15px;
             background-color: #fff;
        }

        .logout-btn {
             background: none;
             border: none;
             cursor: pointer;
             text-align: left;
             width: 100%;
             color: #fff;
             font-size: 14px;
             border-radius: 10px;
             transition: all 0.2s ease-in-out;
        }

        .logout-btn:hover {
             background-color: #ffffff;
             color: #7b0000;
             transform: translateX(5px);
}

    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="Logo">
            <h5>SIMASTER</h5>
            <small>SISTEM INFORMASI<br> MANAJEMEN ASET TERPADU</small>
        </div>

        <a href="{{ route('superadmin.dashboard') }}" class="menu-link {{ request()->is('superadmin/dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house me-2"></i> Dashboard
        </a>

        <div class="menu-section">
            <p>Data Master</p>
            <a href="{{ route('superadmin.bidang') }}" class="menu-link {{ request()->is('superadmin/bidang') ? 'active' : '' }}">
                <i class="fa-solid fa-briefcase me-2"></i> Bidang
            </a>
            <a href="{{ route('superadmin.satuankerja') }}" class="menu-link {{ request()->is('superadmin/satuankerja') ? 'active' : '' }}">
                <i class="fa-solid fa-building me-2"></i> Satuan Kerja
            </a>
            <a href="{{ route('superadmin.rakserver') }}" class="menu-link {{ request()->is('superadmin/rakserver') ? 'active' : '' }}">
                <i class="fa-solid fa-server me-2"></i> Rak Server
            </a>
        </div>

        <div class="menu-section">
            <p>Manajemen Aset</p>
            <a href="#" class="menu-link">
                <i class="fa-solid fa-computer me-2"></i> Server
            </a>
            <a href="#" class="menu-link">
                <i class="fa-solid fa-globe me-2"></i> Website
            </a>
            <a href="#" class="menu-link">
                <i class="fa-solid fa-screwdriver-wrench me-2"></i> Pemeliharaan
            </a>
        </div>

        <div class="menu-section">
            <p>Sistem</p>
            <a href="#" class="menu-link">
                <i class="fa-solid fa-user-gear me-2"></i> Pengguna
            </a>
            <a href="#" class="menu-link">
                <i class="fa-solid fa-list-check me-2"></i> Log Aktivitas
            </a>
             <a href="{{ route('pengaturan') }}" class="menu-link {{ request()->is('pengaturan') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i> Pengaturan
            </a>
            <form action="{{ route('logout') }}" method="POST">
             @csrf
            <button type="submit" class="menu-link logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
            </form>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>
</body>
</html>
