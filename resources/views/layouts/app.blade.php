<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIMASTER</title>

   <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Summernote CSS is already loaded here -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f5f7;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(270deg, #800000 0%, #660708 100%);
            color: #fff;
            padding: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Logo Section */
        .sidebar .logo {
            text-align: center;
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .logo img {
            width: 65px;
            height: 65px;
            object-fit: contain;
            margin-bottom: 12px;
        }

        .sidebar .logo h5 {
            font-weight: 700;
            font-size: 18px;
            margin: 0;
            letter-spacing: 1px;
            color: #fff;
        }

        .sidebar .logo small {
            display: block;
            font-size: 10px;
            font-weight: 400;
            line-height: 1.4;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 5px;
            letter-spacing: 0.5px;
        }

        /* Menu Container */
        .menu-container {
            padding: 15px 12px;
            flex: 1;
        }

        /* Menu Section */
        .menu-section {
            margin-top: 15px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .menu-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .menu-section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            margin: 0 0 10px 12px;
            letter-spacing: 1px;
        }

        /* Menu Link */
        .menu-link {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
            position: relative;
        }

        .menu-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .menu-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateX(3px);
        }

        .menu-link.active {
            background-color: #fff;
            color: #660708;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            padding: 12px 16px;
            border-radius: 10px;
        }

        .menu-link.active i {
            color: #660708;
        }

        /* Logout Button */
        .logout-section {
            padding: 15px 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            font-weight: 500;
            padding: 12px 16px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .logout-btn i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateX(3px);
        }

        /* Content Area */
        .content {
            margin-left: 300px;
            padding: 100px 40px 30px 40px;
            min-height: 100vh;
        }

        /* Cards */
        .card-summary {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-summary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .table-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 20px;
        }

        .table th {
            background-color: #f9f9f9;
            font-weight: 600;
            color: #333;
        }

        /* ===== TOP NAVBAR ===== */
        .top-navbar {
            position: fixed;
            top: 20px;
            left: 300px;
            right: 40px;
            height: 60px;
            background: linear-gradient(270deg, #800000 0%, #660708 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 1001;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: #fff;
        }

        /* RIGHT USER DROPDOWN */
        .user-dropdown {
            position: relative;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            padding: 6px 10px;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .user-info:hover {
            background-color: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .user-info i {
            font-size: 20px;
        }

        .user-info:hover i {
            color: #fff;
        }

        /* DROPDOWN MENU */
        .dropdown-menu {
            position: absolute;
            top: 55px;
            right: 0;
            width: 220px;
            background: linear-gradient(270deg, #800000 0%, #660708 100%);
            border-radius: 14px;
            padding: 10px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            display: none;
        }

        /* ITEM */
        .dropdown-item {
            color: #fff;
            width: calc(100% - 20px);
            margin: 0 auto;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-size: 14px;
            border-radius: 8px;
            transition: 0.2s ease;
        }

        /* HOVER */
        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-weight: 500;
            box-shadow: none;
        }

        /* ICON */
        .dropdown-item i {
            width: 18px;
            color: #fff;
            transition: 0.2s ease;
        }

        .dropdown-item:hover i {
            color: #fff;
        }

        /* ACTIVE STATE */
        .dropdown-item.active {
            background-color: #fff;
            color: #660708;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .dropdown-item.active i {
            color: #660708;
        }

        /* DIVIDER */
        .dropdown-divider {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.15);
            border: none;
            margin: 10px 15px;
        }

        /* CONTENT AREA  */
        .content {
            margin-left: 260px;
            padding: 100px 30px 30px;
        }

        /* --- CUSTOM STYLE FOR SUMMERNOTE --- */
        .note-editor.note-frame {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Styling untuk Toolbar */
        .note-editor .note-toolbar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 7px;
            border-top-right-radius: 7px;
            padding: 10px;
        }

        /* Styling untuk Area Edit */
        .note-editor .note-editing-area .note-editable {
            background-color: #fff;
            color: #333;
            min-height: 250px;
            padding: 15px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .logo small,
            .sidebar .logo h5,
            .menu-section-title,
            .menu-link span {
                display: none;
            }

            .menu-link {
                justify-content: center;
                padding: 12px;
            }

            .menu-link i {
                margin-right: 0;
            }

            .content {
                margin-left: 70px;
            }
        }
    </style>
</head>

<script>
    function toggleDropdown() {
        const d = document.getElementById("dropdownMenu");
        d.style.display = d.style.display === "block" ? "none" : "block";
    }

    document.addEventListener("click", function(e) {
        const dropdown = document.getElementById("dropdownMenu");
        const button = document.querySelector(".user-info");

        if (!button.contains(e.target)) {
            dropdown.style.display = "none";
        }
    });
</script>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Logo Section -->
        <div class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Kemhan">
            <h5>SIMASTER</h5>
            <small>SISTEM INFORMASI<br>MANAJEMEN ASET TERPADU</small>
        </div>

        <!-- Menu Container -->
        <div class="menu-container">
            <!-- Dashboard - Semua Role -->
            <a href="{{ route('dashboard') }}" class="menu-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>

            @php
                $userRole = Auth::user()->role;
                $OperatorBidang = ['operator banglola', 'operator pamsis', 'operator infratik', 'operator tatausaha'];
            @endphp

            @if ($userRole == 'superadmin')
                <!-- Data Master Section - Hanya Superadmin -->
                <div class="menu-section">
                    <div class="menu-section-title">Data Master</div>
                    <a href="{{ route('superadmin.bidang') }}"
                        class="menu-link {{ request()->is('bidang') ? 'active' : '' }}">
                        <i class="fa-solid fa-briefcase"></i>
                        <span>Bidang</span>
                    </a>
                    <a href="{{ route('superadmin.satuankerja') }}"
                        class="menu-link {{ request()->is('satuankerja') ? 'active' : '' }}">
                        <i class="fa-solid fa-building"></i>
                        <span>Satuan Kerja</span>
                    </a>
                    <a href="{{ route('superadmin.rakserver') }}"
                        class="menu-link {{ request()->is('rakserver') ? 'active' : '' }}">
                        <i class="fa-solid fa-server"></i>
                        <span>Rak Server</span>
                    </a>
                </div>
            @endif

            @if ($userRole == 'superadmin' || in_array($userRole, $OperatorBidang))
                <!-- Manajemen Aset Section - Superadmin & Admin Bidang -->
                <div class="menu-section">
                    <div class="menu-section-title">Manajemen Aset</div>
                    <a href="{{ route('server.index') }}"
                        class="menu-link {{ request()->routeIs('server.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-database"></i>
                        <span>Server</span>
                    </a>
                    <a href="{{ route('website.index') }}"
                        class="menu-link {{ request()->routeIs('website.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-globe"></i>
                        <span>Website</span>
                    </a>
                    <a href="{{ route('pemeliharaan') }}"
                        class="menu-link {{ request()->routeIs('pemeliharaan') ? 'active' : '' }}">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                        <span>Pemeliharaan</span>
                    </a>
                </div>
            @endif

            @if ($userRole == 'superadmin' || in_array($userRole, $OperatorBidang))
                <!-- Sistem Section - Superadmin & Admin Bidang -->
                <div class="menu-section">
                    <div class="menu-section-title">Sistem</div>

                    @if ($userRole == 'superadmin')
                        <!-- Menu Pengguna - Hanya Superadmin -->
                        <a href="{{ route('superadmin.pengguna.index') }}"
                            class="menu-link {{ request()->routeIs('superadmin.pengguna.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-users"></i>
                            <span>Pengguna</span>
                        </a>
                    @endif

                    <!-- Log Aktivitas - Superadmin & Admin Bidang -->
                    <a href="{{ route('logAktivitas') }}"
                        class="menu-link {{ request()->routeIs('logAktivitas') ? 'active' : '' }}">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Log Aktivitas</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
    <!-- TOP HEADER -->
    <div class="top-navbar">
        <div class="page-title">
            @yield('title')
        </div>

        <div class="user-dropdown">
            <div class="user-info" onclick="toggleDropdown()">
                <i class="fa-solid fa-user-circle"></i>
                <span>{{ Auth::user()->role ?? 'Pengguna' }}</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>

            <div class="dropdown-menu" id="dropdownMenu">
                <a href="{{ route('profil.saya') }}"
                    class="dropdown-item {{ request()->routeIs('profil.saya') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i> Profil Saya
                </a>

                <a href="{{ route('ganti.password') }}"
                    class="dropdown-item {{ request()->routeIs('ganti.password') ? 'active' : '' }}">
                    <i class="fa-solid fa-lock"></i> Ganti Password
                </a>

                <a href="{{ route('panduan.pengguna') }}"
                    class="dropdown-item {{ request()->routeIs('panduan.pengguna') ? 'active' : '' }}">
                    <i class="fa-solid fa-book"></i> Panduan Pengguna
                </a>

                <div class="dropdown-divider"></div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="dropdown-item">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Summernote Libraries are already linked in the original file -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>

    <!-- Global Summernote Initialization Script -->
    <script>
        // Gunakan fungsi ready jQuery untuk memastikan DOM telah dimuat sepenuhnya sebelum inisialisasi
        $(document).ready(function() {
            // Secara otomatis inisialisasi Summernote pada setiap textarea yang memiliki kelas 'summernote-editor'
            $('.summernote-editor').summernote({
                placeholder: 'Tulis konten di sini...',
                tabsize: 2,
                height: 150,
                dialogsInBody: true, // Opsional: Membantu memastikan modal Summernote tampil di atas elemen lain
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
