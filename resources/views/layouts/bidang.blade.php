<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIMASTER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            background: linear-gradient(180deg, #8B0000 0%, #6B0000 100%);
            color: #fff;
            padding: 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        
        /* Scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }
        
        /* Logo Section */
        .sidebar .logo {
            text-align: center;
            padding: 25px 20px;
            background: rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.1);
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
            color: rgba(255,255,255,0.8);
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
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }
        
        .menu-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .menu-section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            margin: 0 0 10px 12px;
            letter-spacing: 1px;
        }
        
        /* Menu Link */
        .menu-link {
            display: flex;
            align-items: center;
            color: rgba(255,255,255,0.85);
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
            background-color: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(3px);
        }
        
        .menu-link.active {
            background-color: #fff;
            color: #8B0000;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .menu-link.active i {
            color: #8B0000;
        }
        
        /* Logout Button */
        .logout-section {
            padding: 15px 12px;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
        }
        
        .logout-btn {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            color: rgba(255,255,255,0.85);
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
            background-color: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(3px);
        }
        
        /* Content Area */
        .content {
            margin-left: 260px;
            padding: 25px 30px;
            min-height: 100vh;
        }
        
        /* Cards */
        .card-summary {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card-summary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        
        .table-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 20px;
        }
        
        .table th {
            background-color: #f9f9f9;
            font-weight: 600;
            color: #333;
        }
        
        /* CUSTOM STYLE FOR SUMMERNOTE */
        .note-editor.note-frame {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .note-editor .note-toolbar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 7px;
            border-top-right-radius: 7px;
            padding: 10px;
        }

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
            <!-- Dashboard -->
            <a href="{{ route('banglola.dashboard') }}" class="menu-link {{ request()->is('banglola/dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>

            <!-- Manajemen Aset Section -->
            <div class="menu-section">
                <div class="menu-section-title">Manajemen Aset</div>
                <a href="{{ route('banglola.server.index') }}" class="menu-link {{ request()->routeIs('banglola.server.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-database"></i>
                    <span>Server</span>
                </a>
                <a href="{{ route('banglola.website.index') }}" class="menu-link {{ request()->routeIs('banglola.website.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-globe"></i>
                    <span>Website</span>
                </a>
                <a href="#" class="menu-link">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    <span>Pemeliharaan</span>
                </a>
            </div>

            <!-- Sistem Section -->
            <div class="menu-section">
                <div class="menu-section-title">Sistem</div>
                <a href="{{ route('banglola.logAktivitas') }}" class="menu-link {{ request()->routeIs('banglola.logAktivitas') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Log Aktivitas</span>
                </a>
                <a href="{{ route('pengaturan') }}" class="menu-link {{ request()->is('pengaturan') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i>
                    <span>Pengaturan</span>
                </a>
            </div>
        </div>

        <!-- Logout Section -->
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.summernote-editor').summernote({
                placeholder: 'Tulis konten di sini...',
                tabsize: 2,
                height: 150,
                dialogsInBody: true,
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