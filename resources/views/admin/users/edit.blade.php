@extends('layouts.main')

@push('styles')
<style>
    .glass-card-form {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
    }
    .form-header-premium {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 24px 24px 0 0;
        padding: 2.5rem 2rem;
        text-align: center;
    }
    .role-toggle-container {
        display: flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
    }
    .role-toggle-btn {
        flex: 1;
        border: none;
        padding: 10px;
        border-radius: 9px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
        background: transparent;
        color: #64748b;
    }
    .role-toggle-input:checked + .role-toggle-btn {
        background: white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .role-toggle-input:checked[value="super_admin"] + .role-toggle-btn { color: #f59e0b; }
    .role-toggle-input:checked[value="user"] + .role-toggle-btn { color: #64748b; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="glass-card-form overflow-hidden border-0 shadow-lg">
                <div class="form-header-premium text-white">
                    <div class="bg-white bg-opacity-10 w-60 h-60 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-user-edit fa-2x"></i>
                    </div>
                    <h2 class="h4 fw-bold mb-1">Edit User</h2>
                    <p class="mb-0 opacity-75 small">Perbarui informasi akun pengguna</p>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf @method('PUT')
                        
                        <div class="mb-4">
                            <label class="modern-form-label">Nama Lengkap</label>
                            <div class="modern-input-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" placeholder="Contoh: Ahmad" required>
                            </div>
                            @error('name')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="modern-form-label">Alamat Email</label>
                            <div class="modern-input-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="user@example.com" required>
                            </div>
                            @error('email')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="modern-form-label">Password Baru</label>
                                <div class="modern-input-icon">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                                </div>
                                <small class="text-muted mt-2 d-block" style="font-size: 0.7rem;">Kosongkan jika tidak diubah</small>
                                @error('password')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="modern-form-label">Konfirmasi</label>
                                <div class="modern-input-icon">
                                    <i class="fas fa-shield-alt"></i>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 text-center">
                            <label class="modern-form-label">Role Pengguna</label>
                            <div class="role-toggle-container">
                                <label class="flex-grow-1 mb-0">
                                    <input type="radio" name="role" value="user" class="role-toggle-input d-none" {{ old('role', $user->role) == 'user' ? 'checked' : '' }}>
                                    <div class="role-toggle-btn cursor-pointer" style="cursor: pointer;">USER</div>
                                </label>
                                <label class="flex-grow-1 mb-0">
                                    <input type="radio" name="role" value="super_admin" class="role-toggle-input d-none" {{ old('role', $user->role) == 'super_admin' ? 'checked' : '' }}>
                                    <div class="role-toggle-btn cursor-pointer" style="cursor: pointer;">ADMIN</div>
                                </label>
                            </div>
                            @error('role')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                        </div>

                        <div class="pt-2 d-flex flex-column gap-2">
                            <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold rounded-3 py-3 shadow text-white" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-link text-muted text-decoration-none fw-semibold">
                                <i class="fas fa-arrow-left me-1"></i>Batal & Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
