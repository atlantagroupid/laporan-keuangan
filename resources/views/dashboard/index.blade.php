@extends('layouts.main')

@push('styles')
<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
/* Override specific colors to match Theme */
.text-primary-custom { color: #555691 !important; }
.text-purple { color: #555691 !important; }
.badge-wallet { 
    background-color: #555691; 
    color: white; 
    padding: 4px 10px; 
    border-radius: 20px; 
    font-size: 0.85em;
    font-weight: 500;
}

/* Modern Transaction styles */
.trx-card {
    border: none;
    border-radius: 12px;
    background: #fff;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: transform 0.2s, box-shadow 0.2s;
}
.trx-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.trx-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}
.icon-in { background-color: rgba(16, 185, 129, 0.1); color: #10b981; }
.icon-out { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }

.table-transactions thead th {
    background-color: #f8fafc;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.025em;
    border-top: none;
}

/* Modal Modern Styles */
.modal-modern .modal-content {
    border: none;
    border-radius: 20px;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
}
.modal-header-modern {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 1.5rem;
    border-radius: 20px 20px 0 0;
}
.modal-body-modern {
    padding: 2rem;
}

/* Segmented Control for Transaction Type */
.type-selector {
    display: flex;
    background: #f1f5f9;
    padding: 4px;
    border-radius: 12px;
    margin-bottom: 1.5rem;
}
.type-btn {
    flex: 1;
    border: none;
    padding: 10px;
    border-radius: 9px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.2s;
    background: transparent;
    color: #64748b;
}
.type-btn.active[data-type="pemasukan"] {
    background: white;
    color: #10b981;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}
.type-btn.active[data-type="pengeluaran"] {
    background: white;
    color: #ef4444;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

.amount-input-group {
    text-align: center;
    margin-bottom: 2rem;
}
.amount-display-container {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 800;
    color: #1e293b;
}
.amount-display-container span { margin-right: 5px; opacity: 0.4; }
.amount-hidden-input {
    border: none;
    background: transparent;
    text-align: center;
    width: 100%;
    color: inherit;
    font-weight: inherit;
    outline: none;
    padding: 0;
}
.amount-hidden-input::placeholder { color: #cbd5e1; }

.modern-form-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
    display: block;
}
.modern-input-icon {
    position: relative;
    display: flex;
    align-items: center;
}
.modern-input-icon i {
    position: absolute;
    left: 15px;
    color: #94a3b8;
}
.modern-input-icon .form-control, .modern-input-icon .form-select {
    padding-left: 45px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    height: 50px;
    transition: all 0.2s;
}
.modern-input-icon .form-control:focus {
    border-color: #555691;
    box-shadow: 0 0 0 4px rgba(85, 86, 145, 0.1);
}

/* Stat Cards Mini */
.stat-card-mini {
    border: none;
    border-radius: 16px;
    padding: 1.25rem;
    color: white;
    height: 100%;
    transition: transform 0.2s;
    overflow: hidden;
    position: relative;
    z-index: 1;
}
.stat-card-mini:hover {
    transform: translateY(-5px);
}
.stat-card-mini::before {
    content: "";
    position: absolute;
    top: -20px;
    right: -20px;
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    z-index: -1;
}
.stat-bg-purple { background: linear-gradient(135deg, #555691 0%, #7c7eb2 100%); }
.stat-bg-green { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); }
.stat-bg-red { background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); }
.stat-card-mini i {
    font-size: 1.5rem;
    margin-bottom: 0.75rem;
    opacity: 0.9;
}
.stat-card-mini .val {
    font-size: 1.25rem;
    font-weight: 800;
    margin-bottom: 2px;
}
.stat-card-mini .lbl {
    font-size: 0.75rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@media (max-width: 767.98px) {
    .desktop-only { display: none !important; }
    .modal-dialog-bottom {
        margin: 0;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        max-width: 100%;
    }
    .modal-dialog-bottom .modal-content {
        border-radius: 20px 20px 0 0;
        height: auto;
        max-height: 90vh;
        overflow-y: auto;
    }
}
@media (min-width: 768px) {
    .mobile-only { display: none !important; }
}
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="app-header p-4 text-white mb-4 bg-gradient-primary rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1 fw-bold">{{ $setting->app_title }}</h1>
                <p class="mb-0 opacity-75">Kelola banyak dompet dalam satu aplikasi</p>
            </div>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#settingsModal">
                <i class="fas fa-cog"></i>
            </button>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-card-mini stat-bg-green shadow-sm">
                <i class="fas fa-arrow-down"></i>
                <div class="val">Rp {{ number_format($totalIn, 0, ',', '.') }}</div>
                <div class="lbl">Pemasukan</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card-mini stat-bg-red shadow-sm">
                <i class="fas fa-arrow-up"></i>
                <div class="val">Rp {{ number_format($totalOut, 0, ',', '.') }}</div>
                <div class="lbl">Pengeluaran</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card-mini stat-bg-purple shadow-sm">
                <i class="fas fa-wallet"></i>
                <div class="val">Rp {{ number_format($balance, 0, ',', '.') }}</div>
                <div class="lbl">Total Saldo</div>
            </div>
        </div>
    </div>

    <!-- Filters & List -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <!-- Toolbar -->
            <div class="p-3 bg-light border-bottom">
                <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Cari keterangan..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="wallet_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="all">Semua Dompet</option>
                                @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}" {{ request('wallet_id') == $wallet->id ? 'selected' : '' }}>{{ $wallet->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="filter_type" class="form-select" id="filterType" onchange="toggleFilterInputs()">
                                <option value="">Semua Waktu</option>
                                <option value="daily" {{ request('filter_type') == 'daily' ? 'selected' : '' }}>Harian</option>
                                <option value="monthly" {{ request('filter_type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                <option value="yearly" {{ request('filter_type') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="filter_date" class="form-control dynamic-date {{ request('filter_type') == 'daily' ? 'show' : '' }}" id="fDaily" value="{{ request('filter_date', date('Y-m-d')) }}">
                            <input type="month" name="filter_month" class="form-control dynamic-date {{ request('filter_type') == 'monthly' ? 'show' : '' }}" id="fMonthly" value="{{ request('filter_month', date('Y-m')) }}">
                            <input type="number" name="filter_year" class="form-control dynamic-date {{ request('filter_type') == 'yearly' ? 'show' : '' }}" id="fYearly" placeholder="Tahun" value="{{ request('filter_year', date('Y')) }}">
                        </div>
                        <div class="col-md-3 text-end d-flex gap-1 justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                            <a href="{{ route('export.excel', request()->query()) }}" class="btn btn-excel btn-sm text-white"><i class="fas fa-file-excel"></i></a>
                            <a href="{{ route('export.pdf', request()->query()) }}" class="btn btn-pdf btn-sm text-white"><i class="fas fa-file-pdf"></i></a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Statistics Button -->
            <div class="p-3 bg-white border-bottom">
                <button class="btn btn-outline-primary btn-sm w-100 py-2 fw-bold border-2 rounded-3" data-bs-toggle="modal" data-bs-target="#statisticsModal">
                    <i class="fas fa-chart-pie me-2"></i>Lihat Analisis & Statistik Keuangan
                </button>
            </div>

            <!-- Transactions List -->
            <div class="transactions-container">
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover table-transactions mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px">No</th>
                                <th>Dompet</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th class="text-end">Jumlah</th>
                                <th class="text-end">Saldo</th>
                                <th class="text-center" style="width: 120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $index => $trx)
                            <tr>
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td><span class="badge-wallet">{{ $trx->wallet->name }}</span></td>
                                <td class="text-nowrap">{{ $trx->tanggal->translatedFormat('d F Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="trx-icon me-3 {{ $trx->tipe === 'pemasukan' ? 'icon-in' : 'icon-out' }}">
                                            <i class="fas {{ $trx->tipe === 'pemasukan' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                                        </div>
                                        <div class="fw-bold">{{ $trx->keterangan }}</div>
                                    </div>
                                </td>
                                <td class="text-end fw-bold {{ $trx->tipe === 'pemasukan' ? 'text-success-custom' : 'text-danger-custom' }}">
                                    {{ $trx->tipe === 'pemasukan' ? '+' : '-' }} {{ number_format($trx->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="text-end fw-bold {{ $trx->saldo < 0 ? 'text-danger-custom' : 'text-primary-custom' }}">
                                    {{ number_format($trx->saldo, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-warning rounded-start" onclick="editTransaction({{ json_encode($trx) }})"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-outline-danger rounded-end" onclick="confirmDelete('{{ route('transactions.destroy', $trx) }}', 'transaksi ini')"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-wallet fa-3x mb-3 opacity-25"></i>
                                    <p class="mb-0">Tidak ada data ditemukan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card List -->
                <div class="d-block d-md-none p-3 bg-light">
                    @forelse($transactions as $trx)
                    <div class="trx-card p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center">
                                <div class="trx-icon me-2 {{ $trx->tipe === 'pemasukan' ? 'icon-in' : 'icon-out' }}">
                                    <i class="fas {{ $trx->tipe === 'pemasukan' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $trx->keterangan }}</div>
                                    <small class="text-muted">{{ $trx->tanggal->translatedFormat('d F Y') }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold {{ $trx->tipe === 'pemasukan' ? 'text-success-custom' : 'text-danger-custom' }}">
                                    {{ $trx->tipe === 'pemasukan' ? '+' : '-' }} {{ number_format($trx->jumlah, 0, ',', '.') }}
                                </div>
                                <span class="badge-wallet" style="font-size: 0.7em;">{{ $trx->wallet->name }}</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                            <div class="small">
                                <span class="text-muted">Saldo akhir:</span> 
                                <span class="fw-bold {{ $trx->saldo < 0 ? 'text-danger-custom' : 'text-primary-custom' }}">Rp {{ number_format($trx->saldo, 0, ',', '.') }}</span>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-light border" onclick="editTransaction({{ json_encode($trx) }})"><i class="fas fa-edit text-warning"></i></button>
                                <button class="btn btn-sm btn-light border" onclick="confirmDelete('{{ route('transactions.destroy', $trx) }}', 'transaksi ini')"><i class="fas fa-trash text-danger"></i></button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-wallet fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">Tidak ada data ditemukan</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <!-- Statistics Button -->

</div>

<!-- FAB Button -->
<button class="fab-btn" data-bs-toggle="modal" data-bs-target="#transactionModal" onclick="resetForm()">
    <i class="fas fa-plus"></i>
</button>

<!-- Transaction Modal -->
<div class="modal fade modal-modern" id="transactionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-bottom">
        <div class="modal-content">
            <form id="transactionForm" method="POST" action="{{ route('transactions.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="tipe" id="tipeValue" value="pemasukan">
                
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    @if($wallets->isEmpty())
                    <div class="alert alert-warning rounded-4 border-0 shadow-sm">
                        <i class="fas fa-exclamation-triangle me-2"></i>Buat minimal satu dompet di Pengaturan terlebih dahulu!
                    </div>
                    @else
                    
                    <!-- Segmented Control Tip (Income/Expense) -->
                    <div class="type-selector">
                        <button type="button" class="type-btn active" data-type="pemasukan">PEMASUKAN</button>
                        <button type="button" class="type-btn" data-type="pengeluaran">PENGELUARAN</button>
                    </div>

                    <!-- Prominent Amount Input -->
                    <div class="amount-input-group">
                        <label class="modern-form-label">Nominal Transaksi</label>
                        <div class="amount-display-container">
                            <span>Rp</span>
                            <input type="text" id="jumlahDisplay" class="amount-hidden-input" placeholder="0" autocomplete="off">
                            <input type="hidden" name="jumlah" id="jumlahReal">
                        </div>
                        <small class="text-danger d-none" id="balanceWarning">
                            <i class="fas fa-exclamation-triangle me-1"></i>Jumlah melebihi saldo yang tersedia!
                        </small>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="modern-form-label">Keterangan</label>
                            <div class="modern-input-icon">
                                <i class="fas fa-tag"></i>
                                <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Gaji, Belanja Sayur" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-12">
                            <label class="modern-form-label">Dompet</label>
                            <div class="modern-input-icon">
                                <i class="fas fa-wallet"></i>
                                <select name="wallet_id" id="walletSelect" class="form-select" required onchange="updateWalletBalance()">
                                    @foreach($wallets as $wallet)
                                    @php
                                        $walletBalance = $wallet->transactions->sum(function($t) {
                                            return $t->tipe === 'pemasukan' ? $t->jumlah : -$t->jumlah;
                                        });
                                    @endphp
                                    <option value="{{ $wallet->id }}" data-balance="{{ $walletBalance }}">
                                        {{ $wallet->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <label class="modern-form-label">Tanggal</label>
                            <div class="modern-input-icon">
                                <i class="fas fa-calendar"></i>
                                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Wallet Balance Info (shows for pengeluaran) -->
                    <div class="mt-4 d-none" id="balanceInfo">
                        <div class="p-3 bg-light rounded-4 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-white p-2 rounded-3 me-3 text-primary shadow-sm">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Saldo Tersedia</div>
                                    <div class="fw-bold" id="availableBalance">Rp 0</div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="fillMaxAmount()">MAX</button>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer p-3 border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none px-4" data-bs-dismiss="modal">Batal</button>
                    @if(!$wallets->isEmpty())
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold" style="border-radius: 12px;">SIMPAN</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Update Title -->
                <form method="POST" action="{{ route('settings.update-title') }}" class="mb-4">
                    @csrf
                    <label class="form-label">Judul Laporan</label>
                    <div class="input-group">
                        <input type="text" name="app_title" class="form-control" value="{{ $setting->app_title }}" required>
                        <button class="btn btn-primary"><i class="fas fa-save"></i></button>
                    </div>
                </form>

                @if(auth()->user()->isSuperAdmin())
                <!-- Logo Upload (Super Admin Only) -->
                <div class="mb-4">
                    <label class="form-label">Logo Aplikasi</label>
                    @if($setting->app_logo && file_exists(public_path($setting->app_logo)))
                    <div class="mb-2 p-3 bg-light rounded d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset($setting->app_logo) }}" alt="Logo" style="height: 40px; width: auto;" class="me-2">
                            <small class="text-muted">Logo saat ini</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('settings.delete-logo') }}', 'Logo Aplikasi')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @endif
                    <form method="POST" action="{{ route('settings.update-logo') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input type="file" name="app_logo" class="form-control" accept="image/*" required>
                            <button class="btn btn-success"><i class="fas fa-upload"></i></button>
                        </div>
                        <small class="text-muted">Format: JPG, PNG, GIF, WebP. Max: 2MB. Akan diconvert ke WebP.</small>
                    </form>
                </div>
                @endif

                <!-- Wallet Management -->
                <label class="form-label">Kelola Dompet</label>
                <form method="POST" action="{{ route('wallets.store') }}" class="mb-3">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Nama dompet baru..." required>
                        <button class="btn btn-success"><i class="fas fa-plus"></i></button>
                    </div>
                </form>
                
                <div class="list-group mb-4" style="max-height: 200px; overflow-y: auto;">
                    @forelse($wallets as $wallet)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $wallet->name }}</span>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('wallets.destroy', $wallet) }}', 'dompet {{ $wallet->name }}')"><i class="fas fa-times"></i></button>
                    </div>
                    @empty
                    <p class="text-center text-muted py-3">Belum ada dompet.</p>
                    @endforelse
                </div>

                <!-- Danger Zone -->
                <hr>
                <label class="form-label text-danger">Zona Bahaya</label>
                <button class="btn btn-outline-danger w-100" onclick="confirmDelete('{{ route('transactions.destroy-all') }}', 'SEMUA TRANSAKSI', true)"><i class="fas fa-trash me-2"></i>Hapus Semua Transaksi</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Balance Confirmation Modal -->
<div class="modal fade" id="balanceConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-exclamation-circle text-info" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Konfirmasi Saldo Minus</h5>
                <p class="text-muted mb-0">Jumlah pengeluaran melebihi saldo dompet saat ini.</p>
                <p class="text-danger small mt-2">Saldo akan menjadi minus. Apakah Anda yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-info text-white px-4" id="confirmBalanceBtn">Lanjutkan</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="statisticsModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-custom text-white p-2 rounded-3 me-3 shadow-sm">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        Analisis & Statistik
                    </div>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <!-- Global Mini Stats -->
                    <div class="col-6 col-lg-3">
                        <div class="stat-card-mini stat-bg-purple shadow-sm">
                            <i class="fas fa-wallet"></i>
                            <div class="val">Rp {{ number_format($balance, 0, ',', '.') }}</div>
                            <div class="lbl">Total Saldo</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card-mini stat-bg-green shadow-sm">
                            <i class="fas fa-arrow-down"></i>
                            <div class="val">Rp {{ number_format($totalIn, 0, ',', '.') }}</div>
                            <div class="lbl">Total Masuk</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card-mini stat-bg-red shadow-sm">
                            <i class="fas fa-arrow-up"></i>
                            <div class="val">Rp {{ number_format($totalOut, 0, ',', '.') }}</div>
                            <div class="lbl">Total Keluar</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card-mini bg-dark shadow-sm">
                            <i class="fas fa-exchange-alt text-secondary"></i>
                            <div class="val">{{ $statistics['totalTransactions'] }}</div>
                            <div class="lbl">Transaksi</div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Monthly Trend Chart -->
                    <div class="col-lg-8 col-xl-7">
                        <div class="card border-0 shadow-sm h-100 rounded-4">
                            <div class="card-header bg-white border-0 pt-3 pb-0 px-4">
                                <h6 class="mb-0 fw-bold border-start border-primary border-4 ps-3">Trend 6 Bulan Terakhir</h6>
                            </div>
                            <div class="card-body px-4 pb-4">
                                <div style="position: relative; height:300px;">
                                    <canvas id="trendChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Pengeluaran -->
                    <div class="col-lg-4 col-xl-5">
                        <div class="card border-0 shadow-sm h-100 rounded-4">
                            <div class="card-header bg-white border-0 pt-3 pb-0 px-4">
                                <h6 class="mb-0 fw-bold border-start border-danger border-4 ps-3">Top 5 Pengeluaran</h6>
                            </div>
                            <div class="card-body px-4 pb-4 d-flex align-items-center">
                                @if($statistics['topPengeluaran']->isEmpty())
                                <div class="text-center w-100 py-5">
                                    <i class="fas fa-chart-pie fa-3x text-light mb-3"></i>
                                    <p class="text-muted">Belum ada data</p>
                                </div>
                                @else
                                <div style="position: relative; height:300px; width:100%;">
                                    <canvas id="topChart"></canvas>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Month Comparison -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4">
                            <div class="card-header bg-white border-0 pt-3 pb-0 px-4">
                                <h6 class="mb-0 fw-bold border-start border-info border-4 ps-3">Perbandingan Periode</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <div class="p-4 rounded-4 text-center" style="background: #f8fafc; border: 1px dashed #e2e8f0;">
                                            <div class="small text-muted mb-2">BULAN LALU</div>
                                            <div class="d-flex flex-column gap-1">
                                                <div class="fw-bold text-success-custom">📥 Rp {{ number_format($statistics['lastMonth']['pemasukan'], 0, ',', '.') }}</div>
                                                <div class="fw-bold text-danger-custom">📤 Rp {{ number_format($statistics['lastMonth']['pengeluaran'], 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-4 rounded-4 text-center bg-white shadow-sm" style="border: 1px solid #f1f5f9;">
                                            <div class="small text-muted mb-2">BULAN INI</div>
                                            <div class="d-flex flex-column gap-1">
                                                <div class="fw-bold text-success-custom">📥 Rp {{ number_format($statistics['thisMonth']['pemasukan'], 0, ',', '.') }}</div>
                                                <div class="fw-bold text-danger-custom">📤 Rp {{ number_format($statistics['thisMonth']['pengeluaran'], 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $lastNetProfit = $statistics['lastMonth']['pemasukan'] - $statistics['lastMonth']['pengeluaran'];
                                    $thisNetProfit = $statistics['thisMonth']['pemasukan'] - $statistics['thisMonth']['pengeluaran'];
                                    $profitChange = $lastNetProfit != 0 ? (($thisNetProfit - $lastNetProfit) / abs($lastNetProfit)) * 100 : ($thisNetProfit > 0 ? 100 : 0);
                                @endphp
                                <div class="text-center p-3 rounded-4 {{ $profitChange >= 0 ? 'bg-success-light' : 'bg-danger-light' }}" style="background-color: {{ $profitChange >= 0 ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }};">
                                    <div class="h5 fw-bold mb-0 {{ $profitChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        <i class="fas {{ $profitChange >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} me-2"></i>
                                        {{ $profitChange >= 0 ? '+' : '' }}{{ number_format($profitChange, 1) }}% <small class="text-dark">Net Profit</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wallet Stats -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-0 px-4">
                                <h6 class="mb-0 fw-bold border-start border-purple border-4 ps-3">Statistik Dompet</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="wallet-list-modern" style="max-height: 250px; overflow-y: auto;">
                                    @forelse($statistics['walletStats'] as $ws)
                                    <div class="d-flex justify-content-between align-items-center p-3 mb-2 bg-light rounded-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white p-2 rounded-3 me-3 text-purple shadow-sm fw-bold">
                                                {{ substr($ws->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $ws->name }}</div>
                                                <small class="text-muted">{{ $ws->transactions_count }} Transaksi</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-success small fw-bold mb-0">
                                                +{{ number_format($ws->total_pemasukan ?? 0, 0, ',', '.') }}
                                            </div>
                                            <div class="text-danger small fw-bold">
                                                -{{ number_format($ws->total_pengeluaran ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-wallet fa-2x mb-2 text-secondary opacity-25"></i>
                                        <p class="mb-0">Belum ada data</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleFilterInputs() {
    const type = document.getElementById('filterType').value;
    document.querySelectorAll('.dynamic-date').forEach(el => el.classList.remove('show'));
    
    if (type === 'daily') document.getElementById('fDaily').classList.add('show');
    if (type === 'monthly') document.getElementById('fMonthly').classList.add('show');
    if (type === 'yearly') document.getElementById('fYearly').classList.add('show');
}

function resetForm() {
    const form = document.getElementById('transactionForm');
    form.action = "{{ route('transactions.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('modalTitle').textContent = 'Tambah Transaksi';
    form.reset();
    
    // Reset Segmented UI
    document.querySelector('.type-btn[data-type="pemasukan"]').click();
    
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayString = `${yyyy}-${mm}-${dd}`;
    
    const dateInput = form.querySelector('[name="tanggal"]');
    dateInput.value = todayString;
    
    if (dateInput._flatpickr) {
        dateInput._flatpickr.setDate(todayString);
    }
}

function editTransaction(trx) {
    const form = document.getElementById('transactionForm');
    if (!form) return;

    form.action = "{{ url('transactions') }}/" + trx.id;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('modalTitle').textContent = 'Edit Transaksi';
    
    // Update Segmented UI
    document.querySelector(`.type-btn[data-type="${trx.tipe}"]`).click();

    const walletSelect = document.getElementById('walletSelect');
    if (walletSelect) {
        walletSelect.value = trx.wallet_id;
        walletSelect.dispatchEvent(new Event('change'));
    }

    const tanggalInput = form.querySelector('[name="tanggal"]');
    if (tanggalInput && trx.tanggal) {
        tanggalInput.value = trx.tanggal.split('T')[0];
    }

    const ketInput = form.querySelector('[name="keterangan"]');
    if (ketInput) ketInput.value = trx.keterangan;
    
    setJumlahValue(parseInt(trx.jumlah));
    
    new bootstrap.Modal(document.getElementById('transactionModal')).show();
}

// Segmented Control Handler
document.querySelectorAll('.type-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const type = this.dataset.type;
        document.getElementById('tipeValue').value = type;
        
        // Update styling based on type
        const displayContainer = document.querySelector('.amount-display-container');
        if (type === 'pemasukan') {
            displayContainer.style.color = '#10b981';
        } else {
            displayContainer.style.color = '#ef4444';
        }
        
        updateWalletBalance();
    });
});

// Initialize filter display
toggleFilterInputs();

// ... existing Chart.js code ... (rest of search/charts)


// Chart.js - Monthly Trend
const trendCtx = document.getElementById('trendChart');
if (trendCtx) {
    const trendData = @json($statistics['monthlyTrend']);
    const months = trendData.map(d => {
        const [y, m] = d.month.split('-');
        return new Date(y, m - 1).toLocaleDateString('id-ID', { month: 'short', year: '2-digit' });
    });
    
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: trendData.map(d => d.pemasukan),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Pengeluaran',
                    data: trendData.map(d => d.pengeluaran),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value);
                        }
                    }
                }
            }
        }
    });
}

// Chart.js - Top Pengeluaran
const topCtx = document.getElementById('topChart');
if (topCtx) {
    const topData = @json($statistics['topPengeluaran']);
    
    new Chart(topCtx, {
        type: 'doughnut',
        data: {
            labels: topData.map(d => d.keterangan.length > 15 ? d.keterangan.substring(0, 15) + '...' : d.keterangan),
            datasets: [{
                data: topData.map(d => d.total),
                backgroundColor: ['#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 12, font: { size: 10 } } }
            }
        }
    });
}

// Currency Formatting for IDR
const jumlahDisplay = document.getElementById('jumlahDisplay');
const jumlahReal = document.getElementById('jumlahReal');

function formatRupiah(angka) {
    if (!angka) return '';
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function parseRupiah(rupiah) {
    return rupiah.replace(/\./g, '');
}

function setJumlahValue(value) {
    const jDisplay = document.getElementById('jumlahDisplay');
    const jReal = document.getElementById('jumlahReal');
    
    if (jDisplay) jDisplay.value = formatRupiah(value);
    if (jReal) jReal.value = value;
}

if (jumlahDisplay) {
    jumlahDisplay.addEventListener('input', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        this.value = formatRupiah(value);
        jumlahReal.value = value;
    });
    
    jumlahDisplay.addEventListener('keypress', function(e) {
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    });
}



// Wallet Balance Management
let currentWalletBalance = 0;

function updateWalletBalance() {
    const walletSelect = document.getElementById('walletSelect');
    const tipeVal = document.getElementById('tipeValue') ? document.getElementById('tipeValue').value : 'pemasukan';
    const balanceInfo = document.getElementById('balanceInfo');
    const availableBalance = document.getElementById('availableBalance');
    
    if (!walletSelect) return;
    
    const selectedOption = walletSelect.options[walletSelect.selectedIndex];
    currentWalletBalance = parseInt(selectedOption.dataset.balance) || 0;
    
    // Show balance info only for pengeluaran
    if (tipeVal === 'pengeluaran') {
        if (balanceInfo) balanceInfo.classList.remove('d-none');
        if (availableBalance) availableBalance.textContent = 'Rp ' + formatRupiah(currentWalletBalance);
    } else {
        if (balanceInfo) balanceInfo.classList.add('d-none');
    }
    
    // Validate current input
    validateBalance();
}

function fillMaxAmount() {
    if (currentWalletBalance > 0) {
        setJumlahValue(currentWalletBalance);
        validateBalance();
    }
}

function validateBalance() {
    const tipeVal = document.getElementById('tipeValue') ? document.getElementById('tipeValue').value : 'pemasukan';
    const balanceWarning = document.getElementById('balanceWarning');
    const jumlahRealInp = document.getElementById('jumlahReal');
    const jumlahValue = jumlahRealInp ? (parseInt(jumlahRealInp.value) || 0) : 0;
    
    if (!balanceWarning) return;
    
    if (tipeVal === 'pengeluaran' && jumlahValue > currentWalletBalance) {
        balanceWarning.classList.remove('d-none');
    } else {
        balanceWarning.classList.add('d-none');
    }
}

// Add validation on input change
if (jumlahDisplay) {
    jumlahDisplay.addEventListener('input', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        this.value = formatRupiah(value);
        if (jumlahReal) jumlahReal.value = value;
        validateBalance();
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateWalletBalance();
    
    // Initialize Flatpickr
    flatpickr("input[type=date]", {
        altInput: true,
        altFormat: "j F Y",
        dateFormat: "Y-m-d",
        locale: "id",
        disableMobile: true // Force flatpickr even on mobile to ensure Indo format
    });

    // Auto-format initial value (e.g. after validation error)
    const jDisplay = document.getElementById('jumlahDisplay');
    if (jDisplay && jDisplay.value) {
        let value = jDisplay.value.replace(/[^0-9]/g, '');
        jDisplay.value = formatRupiah(value);
        document.getElementById('jumlahReal').value = value;
    }
});

// Form submit validation
document.getElementById('transactionForm')?.addEventListener('submit', function(e) {
    const tipeValue = document.getElementById('tipeValue').value;
    const jumlahValue = parseInt(jumlahReal.value) || 0;
    
    // Check if pengeluaran exceeds balance
    if (tipeValue === 'pengeluaran' && jumlahValue > currentWalletBalance) {
        e.preventDefault();
        
        // Show Bootstrap Modal
        const modal = new bootstrap.Modal(document.getElementById('balanceConfirmModal'));
        modal.show();
        
        // Handle confirmation button click
        document.getElementById('confirmBalanceBtn').onclick = function() {
            // Remove listener to prevent loop and submit programmatically
            e.target.submit();
        };
        
        return false;
    }
});

// Chart JS Initialization
document.addEventListener('DOMContentLoaded', function() {
    const stats = @json($statistics);
    
    const formatCurrency = (value) => {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
    };

    // Trend Chart
    if(document.getElementById('trendChart')) {
        new Chart(document.getElementById('trendChart'), {
            type: 'bar',
            data: {
                labels: stats.monthlyTrend.map(d => {
                    const date = new Date(d.month + '-01');
                    return date.toLocaleDateString('id-ID', { month: 'short', year: '2-digit' });
                }),
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: stats.monthlyTrend.map(d => d.pemasukan),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderWidth: 1,
                        borderRadius: 8,
                        barThickness: 'flex',
                        maxBarThickness: 40,
                    },
                    {
                        label: 'Pengeluaran',
                        data: stats.monthlyTrend.map(d => d.pengeluaran),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderWidth: 1,
                        borderRadius: 8,
                        barThickness: 'flex',
                        maxBarThickness: 40,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { 
                        position: 'top',
                        labels: { usePointStyle: true, padding: 20 }
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) label += formatCurrency(context.parsed.y);
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], drawBorder: false },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value);
                            }
                        }
                    }
                }
            }
        });
    }

    // Top Expense Chart
    if(document.getElementById('topChart')) {
        new Chart(document.getElementById('topChart'), {
            type: 'doughnut',
            data: {
                labels: stats.topPengeluaran.map(d => d.keterangan),
                datasets: [{
                    data: stats.topPengeluaran.map(d => d.total),
                    backgroundColor: [
                        '#ef4444', '#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#6366f1'
                    ],
                    weight: 1,
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            boxWidth: 10, 
                            padding: 15,
                            usePointStyle: true
                        } 
                    },
                    tooltip: {
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) label += ': ';
                                if (context.parsed !== null) label += formatCurrency(context.parsed);
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
});


@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        var transactionModal = new bootstrap.Modal(document.getElementById('transactionModal'));
        transactionModal.show();
    });
@endif
</script>
@endpush
