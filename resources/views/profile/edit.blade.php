@extends('layouts.main')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-gradient-primary text-white position-relative">
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fas fa-user-circle fa-10x"></i>
                </div>
                <div class="card-body p-4 p-md-5 position-relative z-1">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h1 class="display-6 fw-bold mb-1">Profil Saya</h1>
                            <p class="lead mb-0 opacity-90">Kelola informasi pribadi dan keamanan akun Anda.</p>
                        </div>
                        <a href="{{ route('dashboard') }}" class="btn btn-light rounded-pill px-4 py-2 fw-semibold shadow-sm hover-scale text-purple">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 h-100 position-relative overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4 pb-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary-custom bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-id-card text-purple fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Informasi Profil</h5>
                            <p class="text-muted small mb-0">Perbarui detail identitas Anda</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold text-secondary small text-uppercase ls-1">Nama Lengkap</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-4 ps-3">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control bg-light border-start-0 rounded-end-4 ps-0" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus placeholder="Nama Anda">
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold text-secondary small text-uppercase ls-1">Alamat Email</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-4 ps-3">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control bg-light border-start-0 rounded-end-4 ps-0" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="email@contoh.com">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning d-flex align-items-center mt-3 rounded-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        Email belum diverifikasi.
                                        <button form="send-verification" class="btn btn-link p-0 text-warning fw-bold text-decoration-underline border-0 bg-transparent ms-1">
                                            Kirim ulang verifikasi
                                        </button>
                                    </div>
                                </div>
                                @if (session('status') === 'verification-link-sent')
                                    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success mt-2 rounded-3">
                                        <i class="fas fa-check-circle me-1"></i> Link verifikasi baru telah dikirim.
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-3 mt-5">
                            @if (session('status') === 'profile-updated')
                                <span class="text-success fw-semibold fade-message">
                                    <i class="fas fa-check-circle me-1"></i> Tersimpan!
                                </span>
                            @endif
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm hover-scale">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Password -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 h-100 position-relative overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4 pb-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle me-3">
                            <i class="fas fa-lock fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Keamanan Akun</h5>
                            <p class="text-muted small mb-0">Ubah password Anda secara berkala</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold text-secondary small text-uppercase ls-1">Password Saat Ini</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-4 ps-3">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input type="password" class="form-control bg-light border-start-0 rounded-end-4 ps-0" 
                                       id="current_password" name="current_password" autocomplete="current-password" placeholder="••••••••">
                            </div>
                            @error('current_password', 'updatePassword')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold text-secondary small text-uppercase ls-1">Password Baru</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-4 ps-3">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control bg-light border-start-0 rounded-end-4 ps-0" 
                                       id="password" name="password" autocomplete="new-password" placeholder="Minimal 8 karakter">
                            </div>
                            @error('password', 'updatePassword')
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold text-secondary small text-uppercase ls-1">Konfirmasi Password</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-4 ps-3">
                                    <i class="fas fa-check-double"></i>
                                </span>
                                <input type="password" class="form-control bg-light border-start-0 rounded-end-4 ps-0" 
                                       id="password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-3 mt-5">
                            @if (session('status') === 'password-updated')
                                <span class="text-success fw-semibold fade-message">
                                    <i class="fas fa-check-circle me-1"></i> Password Diubah!
                                </span>
                            @endif
                            <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 shadow-sm hover-scale text-white">
                                <i class="fas fa-sync-alt me-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 bg-danger bg-opacity-10 position-relative overflow-hidden">
                <div class="position-absolute start-0 top-0 h-100 bg-danger" style="width: 5px;"></div>
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                        <div class="d-flex align-items-start align-items-md-center gap-3">
                            <div class="bg-white p-3 rounded-circle shadow-sm text-danger">
                                <i class="fas fa-radiation fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold text-danger mb-1">Zona Bahaya</h4>
                                <p class="text-muted mb-0">Hapus akun Anda secara permanen. Tindakan ini tidak dapat dibatalkan dan semua data akan hilang.</p>
                            </div>
                        </div>
                        <button class="btn btn-danger btn-lg rounded-pill px-4 shadow-sm hover-scale" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash-alt me-2"></i> Hapus Akun Saya
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
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4 px-5">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex p-4 mb-4">
                        <i class="fas fa-exclamation-triangle fa-3x"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Hapus Akun Permanen?</h3>
                    <p class="text-muted mb-4">Anda akan kehilangan akses ke semua data transaksi, dompet, dan historis. Apakah Anda yakin ingin melanjutkan?</p>
                    
                    <div class="form-floating mb-4 text-start">
                        <input type="password" class="form-control rounded-4 @error('password', 'userDeletion') is-invalid @enderror" 
                               id="delete_password" name="password" placeholder="Password">
                        <label for="delete_password">Masukkan password untuk konfirmasi</label>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-5 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 me-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-5 hover-scale">
                        <i class="fas fa-bomb me-2"></i> Ya, Hapus Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: translateY(-2px); }
    .ls-1 { letter-spacing: 1px; }
    .opacity-10 { opacity: 0.1; }
    .opacity-90 { opacity: 0.9; }
</style>
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
