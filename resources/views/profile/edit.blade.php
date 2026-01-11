@extends('layouts.main')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="bg-gradient-primary text-white p-4 rounded-3 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1 fw-bold"><i class="fas fa-user-circle me-2"></i>Profil Saya</h1>
                <p class="mb-0 opacity-75">Kelola informasi profil dan keamanan akun Anda</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Information -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-id-card me-2 text-primary"></i>Informasi Profil</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Perbarui informasi nama dan email akun Anda.</p>
                    
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="text-warning small mb-0">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Email belum diverifikasi.
                                        <button form="send-verification" class="btn btn-link btn-sm p-0 ms-1">
                                            Kirim ulang email verifikasi
                                        </button>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="text-success small mt-1">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Link verifikasi baru telah dikirim ke email Anda.
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>

                            @if (session('status') === 'profile-updated')
                                <span class="text-success small fade-message">
                                    <i class="fas fa-check-circle me-1"></i> Tersimpan!
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Password -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-lock me-2 text-success"></i>Ubah Password</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Gunakan password yang kuat dan unik untuk keamanan akun.</p>
                    
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-medium">Password Saat Ini</label>
                            <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                   id="current_password" name="current_password" autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium">Password Baru</label>
                            <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                   id="password" name="password" autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-medium">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-key me-1"></i> Ubah Password
                            </button>

                            @if (session('status') === 'password-updated')
                                <span class="text-success small fade-message">
                                    <i class="fas fa-check-circle me-1"></i> Password diubah!
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="col-12">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger bg-opacity-10 py-3">
                    <h5 class="mb-0 fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Zona Bahaya</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <h6 class="fw-bold mb-1">Hapus Akun</h6>
                            <p class="text-muted mb-0 small">Setelah akun dihapus, semua data akan hilang permanen. Pastikan Anda sudah mengunduh data yang diperlukan.</p>
                        </div>
                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash me-1"></i> Hapus Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Hapus Akun Permanen?</h5>
                    <p class="text-muted mb-4">Semua data termasuk transaksi, dompet, dan pengaturan akan dihapus. Tindakan ini tidak dapat dibatalkan.</p>
                    
                    <div class="mb-3 text-start">
                        <label for="delete_password" class="form-label fw-medium">Konfirmasi Password</label>
                        <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               id="delete_password" name="password" placeholder="Masukkan password Anda">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center pt-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-trash me-1"></i> Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-hide success messages
document.querySelectorAll('.fade-message').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    }, 3000);
});

// Show delete modal if there are deletion errors
@if($errors->userDeletion->isNotEmpty())
    new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
@endif
</script>
@endpush
