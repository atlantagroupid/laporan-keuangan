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
        }
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; }
        .bg-gradient-primary { background: var(--gradient-primary) !important; }
        .btn-primary { background: var(--gradient-primary); border: 0; }
        .btn-primary:hover { opacity: 0.9; }
        .text-purple { color: #555691; }
        
        /* Navbar Active State Customization */
        .navbar-dark .navbar-nav .nav-link { color: rgba(255,255,255,0.8); }
        .navbar-dark .navbar-nav .nav-link:hover { color: #fff; }
        .navbar-dark .navbar-nav .nav-link.active { 
            color: #ffd700 !important; 
            font-weight: 600;
            border-bottom: 2px solid #ffd700;
            background-color: transparent !important;
            box-shadow: none !important;
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="{{ route('dashboard') }}">
                @if($setting->app_logo && file_exists(public_path($setting->app_logo)))
                    <img src="{{ asset($setting->app_logo) }}" alt="Logo" style="height: 36px; width: auto;" class="me-2">
                @endif
                {{ $setting->app_title ?? 'Laporan Keuangan' }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-chart-pie me-1"></i> Dashboard
                        </a>
                    </li>
                    @if(auth()->user()->isSuperAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users me-1"></i> Kelola User
                        </a>
                    </li>
                    @endif
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
                            @if(auth()->user()->isSuperAdmin())
                            <span class="badge bg-primary ms-1">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
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
