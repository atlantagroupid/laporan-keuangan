@extends('layouts.main')

@push('styles')
<style>
    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .badge-pill-modern {
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-active-pill { background-color: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-inactive-pill { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .table-modern thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border: none;
        padding: 15px 20px;
    }
    .table-modern tbody td {
        padding: 15px 20px;
        vertical-align: middle;
        border-color: #f1f5f9;
        color: #1e293b;
    }
    .user-card-header {
        background: linear-gradient(135deg, #555691 0%, #253650 100%);
        border-radius: 20px 20px 0 0;
        padding: 2.5rem 2rem;
    }
    .modern-input-role {
        position: relative;
    }
    .modern-form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Premium Header -->
    <div class="user-card-header text-white mb-n4 position-relative z-1 shadow-sm">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h1 class="h3 mb-1 fw-bold"><i class="fas fa-users-cog me-2"></i>Kelola User</h1>
                <p class="mb-0 opacity-75">Manajemen akses dan status pengguna sistem</p>
            </div>
            <button type="button" class="btn btn-light btn-lg px-4 fw-bold rounded-3 shadow-sm text-purple" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fas fa-plus-circle me-2"></i>Tambah User
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden pt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-modern mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 70px">No</th>
                            <th>User</th>
                            <th>Role & Status</th>
                            <th>Aktivitas</th>
                            <th class="text-center" style="width: 150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td class="text-center text-muted fw-bold">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $colors = ['#555691', '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'];
                                        $color = $colors[$user->id % count($colors)];
                                    @endphp
                                    <div class="avatar-circle me-3" style="background-color: {{ $color }}">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold fs-6">{{ $user->name }}</div>
                                        <div class="text-muted small"><i class="far fa-envelope me-1"></i>{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    @if($user->isSuperAdmin())
                                    <span class="badge bg-primary-custom px-3 rounded-pill" style="font-size: 0.65rem;">ADMIN</span>
                                    @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 rounded-pill border" style="font-size: 0.65rem;">USER</span>
                                    @endif
                                </div>
                                <span class="badge-pill-modern {{ $user->is_active ? 'status-active-pill' : 'status-inactive-pill' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-muted">Terdaftar:</span><br>
                                    <span class="fw-medium">{{ $user->created_at->translatedFormat('d F Y') }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm btn-light border shadow-sm rounded-3 p-2 px-3" 
                                            onclick="openEditModal({{ json_encode($user) }}, '{{ route('admin.users.update', $user) }}')" title="Edit">
                                        <i class="fas fa-edit text-warning"></i>
                                    </button>
                                    
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-light border shadow-sm rounded-3 p-2 px-3" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas {{ $user->is_active ? 'fa-ban text-muted' : 'fa-check-circle text-success' }}"></i>
                                        </button>
                                    </form>
                                    
                                    <button class="btn btn-sm btn-light border shadow-sm rounded-3 p-2 px-3" onclick="confirmDelete('{{ route('admin.users.destroy', $user) }}', 'User {{ $user->name }}')" title="Hapus">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0 fs-5">Tidak ada user ditemukan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
            <div class="px-4 py-3 bg-light border-top">
                <div class="d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #555691 0%, #253650 100%); padding: 2rem;">
                <div class="w-100 text-center">
                    <div class="bg-white bg-opacity-10 w-60 h-60 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-plus fa-lg"></i>
                    </div>
                    <h5 class="modal-title fw-bold mb-0">Tambah User Baru</h5>
                </div>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="modern-form-label">Nama Lengkap</label>
                        <div class="modern-input-role">
                            <i class="fas fa-user text-muted position-absolute ms-3 mt-3" style="z-index: 5;"></i>
                            <input type="text" name="name" class="form-control ps-5 rounded-3 border-light shadow-none" value="{{ old('name') }}" placeholder="Contoh: Ahmad" required>
                        </div>
                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="modern-form-label">Alamat Email</label>
                        <div class="modern-input-role">
                            <i class="fas fa-envelope text-muted position-absolute ms-3 mt-3" style="z-index: 5;"></i>
                            <input type="email" name="email" class="form-control ps-5 rounded-3 border-light shadow-none" value="{{ old('email') }}" placeholder="user@example.com" required>
                        </div>
                        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="modern-form-label">Password</label>
                            <input type="password" name="password" class="form-control rounded-3 border-light shadow-none" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-6">
                            <label class="modern-form-label">Konfirmasi</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3 border-light shadow-none" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="mb-4 text-center">
                        <label class="modern-form-label d-block text-center mt-2">Role Pengguna</label>
                        <div class="d-flex background-light p-1 rounded-3">
                            <input type="radio" class="btn-check" name="role" id="create_role_user" value="user" checked>
                            <label class="btn btn-outline-light text-muted w-100 border-0 fw-bold" for="create_role_user">USER</label>

                            <input type="radio" class="btn-check" name="role" id="create_role_admin" value="super_admin">
                            <label class="btn btn-outline-light text-muted w-100 border-0 fw-bold" for="create_role_admin">ADMIN</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow">
                        <i class="fas fa-save me-2"></i>Simpan User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 2rem;">
                <div class="w-100 text-center">
                    <div class="bg-white bg-opacity-10 w-60 h-60 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-edit fa-lg"></i>
                    </div>
                    <h5 class="modal-title fw-bold mb-0">Edit Informasi User</h5>
                </div>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editUserForm" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="mb-3">
                        <label class="modern-form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" class="form-control rounded-3 border-light shadow-none" required>
                    </div>

                    <div class="mb-3">
                        <label class="modern-form-label">Alamat Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control rounded-3 border-light shadow-none" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="modern-form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control rounded-3 border-light shadow-none" placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div class="col-md-6">
                            <label class="modern-form-label">Konfirmasi</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3 border-light shadow-none" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="mb-4 text-center">
                        <label class="modern-form-label d-block text-center mt-2">Role Pengguna</label>
                        <div class="d-flex background-light p-1 rounded-3">
                            <input type="radio" class="btn-check" name="role" id="edit_role_user" value="user">
                            <label class="btn btn-outline-light text-muted w-100 border-0 fw-bold" for="edit_role_user">USER</label>

                            <input type="radio" class="btn-check" name="role" id="edit_role_admin" value="super_admin">
                            <label class="btn btn-outline-light text-muted w-100 border-0 fw-bold" for="edit_role_admin">ADMIN</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold rounded-3 shadow text-white border-0" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="fas fa-save me-2"></i>Update User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openEditModal(user, updateUrl) {
    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    const form = document.getElementById('editUserForm');
    
    // Set form action
    form.action = updateUrl;
    
    // Set values
    document.getElementById('edit_name').value = user.name;
    document.getElementById('edit_email').value = user.email;
    
    // Set radio buttons
    if (user.role === 'super_admin') {
        document.getElementById('edit_role_admin').checked = true;
    } else {
        document.getElementById('edit_role_user').checked = true;
    }
    
    modal.show();
}

// Show validation errors in modals if they exist
@if($errors->any())
    $(document).ready(function() {
        @if(old('_method') == 'PUT')
            // This was likely an edit attempt (though standard form redirect might not preserve modal state easily without more JS)
            // But if we're redirecting back, we might need a way to tell which modal to show.
            // For now, let's just handle the Create error which is most common.
        @else
            const createModal = new bootstrap.Modal(document.getElementById('createUserModal'));
            createModal.show();
        @endif
    });
@endif
</script>

<style>
    .background-light { background: #f1f5f9; }
    .btn-check:checked + label { 
        background-color: white !important; 
        color: #555691 !important; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
    }
</style>
@endpush
@endsection
