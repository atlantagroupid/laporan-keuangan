@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="fas fa-users me-2 text-primary"></i>Kelola User</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah User
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }} .</td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->isSuperAdmin())
                                <span class="badge bg-primary">Super Admin</span>
                                @else
                                <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                <span class="badge status-active">Aktif</span>
                                @else
                                <span class="badge status-inactive">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->translatedFormat('d M Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm {{ $user->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>
                                
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.users.destroy', $user) }}', 'User {{ $user->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada user ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
