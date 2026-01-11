<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $setting->app_title ?? 'Laporan Keuangan' }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/themes/material_blue.css">
    
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/id.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Favicon -->
    @if(isset($setting) && $setting->app_logo && file_exists(public_path($setting->app_logo)))
    <link rel="icon" href="{{ asset($setting->app_logo) }}">
    @else
    <link rel="icon" href="https://via.placeholder.com/32x32.png?text=LK"> 
    @endif
    
    <style>
        :root {
            --gradient-primary: linear-gradient(135deg, #555691 0%, #253650 100%);
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(85, 86, 145, 0.2);
            --primary-soft: rgba(85, 86, 145, 0.1);
        }
        body { 
            font-family: 'Poppins', sans-serif; 
            min-height: 100vh;
            padding-top: 85px;
        }
        .bg-gradient-primary { background: var(--gradient-primary) !important; }
        .btn-primary { background: var(--gradient-primary); border: 0; }
        .btn-primary:hover { opacity: 0.9; }
        .text-purple { color: #555691; }
        .bg-primary-custom { background-color: #555691 !important; }
        
        /* Vibrant Glass Navbar */
        .modern-nav {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-top: 3px solid #555691; /* Colorful Accent Top */
            position: fixed;
            top: 10px;
            left: 15px;
            right: 15px;
            width: auto;
            border-radius: 18px;
            padding: 8px 15px;
            z-index: 1050;
            box-shadow: 0 10px 30px rgba(85, 86, 145, 0.1);
        }
        .navbar-brand {
            color: #555691 !important;
            font-size: 1.25rem;
            letter-spacing: -0.5px;
        }
        .nav-link {
            color: #475569 !important;
            font-weight: 600;
            padding: 10px 18px !important;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin: 0 2px;
        }
        .nav-link i {
            font-size: 1.2rem;
            margin-right: 8px;
            transition: transform 0.3s;
        }
        /* Icon Colors */
        .nav-link[href*="dashboard"] i { color: #555691; }
        .nav-link[href*="users"] i { color: #0ea5e9; }
        
        .nav-link:hover {
            color: #555691 !important;
            background: var(--primary-soft);
        }
        .nav-link:hover i { transform: scale(1.1); }
        
        .nav-link.active {
            color: #555691 !important;
            background: var(--primary-soft);
            box-shadow: inset 0 0 0 1px rgba(85, 86, 145, 0.1);
        }
        
        .user-dropdown-btn {
            background: white;
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            padding: 5px 15px;
            transition: all 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        .user-dropdown-btn:hover {
            border-color: #555691;
            background: #f8fafc;
        }

        .dropdown-menu {
            border: 1px solid rgba(85, 86, 145, 0.1);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(85, 86, 145, 0.15);
            padding: 10px;
            margin-top: 15px !important;
        }
        .dropdown-item {
            border-radius: 10px;
            padding: 12px 18px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .dropdown-item:hover {
            background: var(--primary-soft);
            color: #555691;
        }
        .dropdown-item i {
            width: 25px;
            margin-right: 5px;
            color: #555691;
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: white;
                margin: 15px -15px -8px -15px;
                padding: 20px;
                border-radius: 0 0 16px 16px;
                border-top: 1px solid #f1f5f9;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100 overflow-x-hidden">
    <!-- Premium Vibrant Mesh Background -->
    <div class="vibrant-mesh-bg"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top modern-nav">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
                @if($setting->app_logo && file_exists(public_path($setting->app_logo)))
                    <img src="{{ asset($setting->app_logo) }}" alt="Logo" style="height: 32px; width: auto;" class="me-2 rounded-2">
                @else
                    <div class="bg-primary-custom text-white p-2 rounded-3 me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                        <i class="fas fa-wallet text-white"></i>
                    </div>
                @endif
                <span>{{ $setting->app_title ?? 'Laporan Keuangan' }}</span>
            </a>
            
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars text-dark"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-chart-pie"></i> Dashboard
                        </a>
                    </li>
                    @if(auth()->user()->isSuperAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users-cog"></i> Kelola User
                        </a>
                    </li>
                    @endif
                </ul>
                
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item dropdown w-100">
                        <a class="dropdown-toggle user-dropdown-btn d-flex align-items-center text-decoration-none text-dark fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="bg-primary-custom text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="me-1">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->isSuperAdmin())
                            <span class="badge bg-primary-custom ms-1" style="font-size: 0.6rem;">ADMIN</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-edit"></i>Profil Akun</a></li>
                            <li><hr class="dropdown-divider opacity-50"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                        <i class="fas fa-power-off"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white py-4 mt-5 border-top">
        <div class="container text-center">
            <p class="mb-0 text-muted">
                &copy; {{ date('Y') }} <strong>{{ $setting->app_title ?? 'Laporan Keuangan' }}</strong>. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Global Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Konfirmasi Hapus</h5>
                    <p class="text-muted mb-0" id="deleteConfirmText">Apakah Anda yakin ingin menghapus item ini?</p>
                    <p class="text-danger small mt-2 d-none" id="deleteWarningText"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pt-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4"><i class="fas fa-trash me-2"></i>Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Global Confirmation Delete Function
    function confirmDelete(url, itemName, isDanger = false) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteConfirmText').textContent = 'Apakah Anda yakin ingin menghapus ' + itemName + '?';
        
        const warningText = document.getElementById('deleteWarningText');
        if (warningText) {
            if (isDanger) {
                warningText.classList.remove('d-none');
            } else {
                warningText.classList.add('d-none');
            }
        }
        
        new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
    }
    </script>
    @stack('scripts')
</body>
</html>
