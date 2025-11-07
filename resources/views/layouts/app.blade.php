<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIMASTER</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }
        
        /* Sidebar */
        .sidebar {
            background-color: #660708;
            color: #fff;
            min-height: 100vh;
            padding: 20px 10px;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 20px;
        }
        
        .sidebar-header img {
            width: 70px;
            margin-bottom: 10px;
        }
        
        .sidebar-header h5 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .sidebar-header p {
            font-size: 0.75rem;
            opacity: 0.8;
        }
        
        .sidebar h6 {
            padding: 0 15px;
            margin: 15px 0 10px;
            font-size: 0.85rem;
            text-transform: uppercase;
            opacity: 0.7;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 12px 15px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: #fff;
            color: #660708;
            font-weight: bold;
        }
        
        /* Main Content */
        .main-wrapper {
            margin-left: 250px;
            min-height: 100vh;
            width: calc(100% - 250px);
        }
        
        /* Top Navbar */
        .top-navbar {
            background: #fff;
            border-bottom: 1px solid #ddd;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .top-navbar h5 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
        }
        
        .top-navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .top-navbar-right .bell-icon {
            font-size: 1.2rem;
            color: #666;
            cursor: pointer;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Content */
        .content-wrapper {
            padding: 30px;
        }
        
        /* Cards */
        .card-summary {
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            background: #fff;
            height: 100%;
        }
        
        .card-summary h5 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .table-card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            background: #fff;
            padding: 25px;
        }
        
        .table-card h5 {
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }
        
        .badge {
            font-size: 0.8rem;
            padding: 6px 10px;
            border-radius: 12px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
        <h5>SIMASTER</h5>
        <p>Sistem Informasi Manajemen<br>Aset Terpadu</p>
    </div>
    
    @auth
        <h6>{{ ucfirst(auth()->user()->role) }}</h6>
        
        <nav class="nav flex-column">
            @if(auth()->user()->role === 'superadmin')
                <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('superadmin.dashboard') }}">
                   <i class="fa fa-home"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('superadmin.kelolaServer') ? 'active' : '' }}" 
                   href="{{ route('superadmin.kelolaServer') }}">
                   <i class="fa fa-server"></i> Kelola Server
                </a>
                <a class="nav-link {{ request()->routeIs('superadmin.kelolaWebsite') ? 'active' : '' }}" 
                   href="{{ route('superadmin.kelolaWebsite') }}">
                   <i class="fa fa-globe"></i> Kelola Website
                </a>
                <a class="nav-link {{ request()->routeIs('superadmin.kelolaLaporan') ? 'active' : '' }}" 
                   href="{{ route('superadmin.kelolaLaporan') }}">
                   <i class="fa fa-file-alt"></i> Kelola Laporan
                </a>
                <a class="nav-link {{ request()->routeIs('superadmin.kelolaPengguna') ? 'active' : '' }}" 
                   href="{{ route('superadmin.kelolaPengguna') }}">
                   <i class="fa fa-users"></i> Kelola Pengguna
                </a>
            @endif
            
            @if(auth()->user()->role === 'banglola')
                <a class="nav-link {{ request()->routeIs('banglola.dashboard') ? 'active' : '' }}" 
                   href="{{ route('banglola.dashboard') }}">
                   <i class="fa fa-home"></i> Dashboard
                </a>
                <a class="nav-link" href="#">
                   <i class="fa fa-globe"></i> Website Saya
                </a>
                <a class="nav-link" href="#">
                   <i class="fa fa-file-alt"></i> Laporan
                </a>
            @endif
            
            @if(auth()->user()->role === 'pamsis')
                <a class="nav-link {{ request()->routeIs('pamsis.dashboard') ? 'active' : '' }}" 
                   href="{{ route('pamsis.dashboard') }}">
                   <i class="fa fa-home"></i> Dashboard
                </a>
                <a class="nav-link" href="#">
                   <i class="fa fa-chart-line"></i> Monitoring
                </a>
            @endif
            
            @if(auth()->user()->role === 'infratik')
                <a class="nav-link {{ request()->routeIs('infratik.dashboard') ? 'active' : '' }}" 
                   href="{{ route('infratik.dashboard') }}">
                   <i class="fa fa-home"></i> Dashboard
                </a>
                <a class="nav-link" href="#">
                   <i class="fa fa-server"></i> Server
                </a>
            @endif
            
            @if(auth()->user()->role === 'tatausaha')
                <a class="nav-link {{ request()->routeIs('tatausaha.dashboard') ? 'active' : '' }}" 
                   href="{{ route('tatausaha.dashboard') }}">
                   <i class="fa fa-home"></i> Dashboard
                </a>
                <a class="nav-link" href="#">
                   <i class="fa fa-file-alt"></i> Dokumen
                </a>
            @endif
            
            @if(auth()->user()->role === 'pimpinan')
                <a class="nav-link {{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}" 
                   href="{{ route('pimpinan.dashboard') }}">
                   <i class="fa fa-home"></i> Dashboard
                </a>
                <a class="nav-link" href="#">
                   <i class="fa fa-chart-bar"></i> Laporan
                </a>
            @endif
        </nav>
    @endauth
</div>

<!-- Main Content -->
<div class="main-wrapper">
    <!-- Top Navbar -->
    <div class="top-navbar">
        <h5>@yield('title', 'Dashboard')</h5>
        
        <div class="top-navbar-right">
            <i class="fa fa-bell bell-icon"></i>
            
            <div class="dropdown">
                <a class="user-dropdown text-dark text-decoration-none" 
                   href="#" id="userDropdown" data-bs-toggle="dropdown">
                    <i class="fa fa-user-circle fa-2x"></i>
                    <span><strong>{{ auth()->user()->name ?? 'Guest' }}</strong></span>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="#"><i class="fa fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa fa-cog me-2"></i>Pengaturan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fa fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Page Content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>