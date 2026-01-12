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
                    <div class="row g-2 align-items-center">
                        <!-- 1. Search -->
                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- 2. Wallet -->
                        <div class="col-md-3">
                            <select name="wallet_id" class="form-select">
                                <option value="all">Semua Dompet</option>
                                @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}" {{ request('wallet_id') == $wallet->id ? 'selected' : '' }}>{{ $wallet->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 3. Filter Type -->
                        <div class="col-md-2">
                            <select name="filter_type" class="form-select" id="filterType" onchange="toggleFilterInputs()">
                                <option value="">Semua Waktu</option>
                                <option value="daily" {{ request('filter_type') == 'daily' ? 'selected' : '' }}>Harian</option>
                                <option value="monthly" {{ request('filter_type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                <option value="yearly" {{ request('filter_type') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                        </div>

                        <!-- 4. Date Inputs -->
                        <div class="col-md-2">
                            <input type="date" name="filter_date" class="form-control dynamic-date {{ request('filter_type') == 'daily' ? 'show' : '' }}" id="fDaily" value="{{ request('filter_date', date('Y-m-d')) }}">
                            <input type="month" name="filter_month" class="form-control dynamic-date {{ request('filter_type') == 'monthly' ? 'show' : '' }}" id="fMonthly" value="{{ request('filter_month', date('Y-m')) }}">
                            <input type="number" name="filter_year" class="form-control dynamic-date {{ request('filter_type') == 'yearly' ? 'show' : '' }}" id="fYearly" placeholder="Tahun" value="{{ request('filter_year', date('Y')) }}">
                        </div>

                        <!-- 5. Actions -->
                        <div class="col-md-3 text-end d-flex gap-1 justify-content-end">
                            <button type="submit" class="btn btn-primary" title="Terapkan Filter"><i class="fas fa-filter"></i> Filter</button>
                            
                            @if(request()->anyFilled(['search', 'wallet_id', 'filter_type']))
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary" title="Reset Filter"><i class="fas fa-undo"></i></a>
                            @endif

                            <div class="vr mx-1"></div>

                            <a href="{{ route('export.excel', request()->query()) }}" class="btn btn-excel text-white d-flex align-items-center justify-content-center px-3" title="Export Excel"><i class="fas fa-file-excel"></i></a>
                            <button type="button" onclick="previewPdf()" class="btn btn-pdf text-white d-flex align-items-center justify-content-center px-3" title="Preview PDF"><i class="fas fa-file-pdf"></i></button>
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
                                <th>Kategori</th>
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
                                    @if($trx->category)
                                        <div class="d-flex align-items-center">
                                            <div class="me-2 rounded-2 px-2 py-1" style="background: {{ $trx->category->color }}20; color: {{ $trx->category->color }}; font-size: 0.8rem;">
                                                <i class="{{ $trx->category->icon }} me-1"></i>{{ $trx->category->name }}
                                            </div>
                                        </div>
                                    @else
                                        <small class="text-muted">--</small>
                                    @endif
                                </td>
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
                                <div class="d-flex flex-column align-items-end gap-1">
                                    <span class="badge-wallet" style="font-size: 0.7em;">{{ $trx->wallet->name }}</span>
                                    @if($trx->category)
                                        <span class="rounded-2 px-2 py-0" style="background: {{ $trx->category->color }}20; color: {{ $trx->category->color }}; font-size: 0.7em; font-weight: 500;">
                                            <i class="{{ $trx->category->icon }} me-1"></i>{{ $trx->category->name }}
                                        </span>
                                    @endif
                                </div>
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
                        
                        <div class="col-12">
                            <label class="modern-form-label">Kategori</label>
                            <input type="hidden" name="category_id" id="categoryInput">
                            <div class="dropdown w-100">
                                <button class="form-select text-start d-flex align-items-center" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="categoryLabel"><i class="fas fa-folder me-2 text-muted"></i>-- Pilih Kategori --</span>
                                </button>
                                <ul class="dropdown-menu w-100" id="categoryList" style="max-height: 250px; overflow-y: auto;">
                                    <li class="category-header-pemasukan"><h6 class="dropdown-header text-success"><i class="fas fa-arrow-down me-1"></i>Pemasukan</h6></li>
                                    @foreach($categories->where('type', 'pemasukan') as $cat)
                                    <li class="category-item" data-type="pemasukan">
                                        <a class="dropdown-item d-flex align-items-center" href="#" onclick="selectCategory({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->icon }}', '{{ $cat->color }}')">
                                            <span class="me-2 rounded p-1" style="background: {{ $cat->color }}20; color: {{ $cat->color }};"><i class="{{ $cat->icon }}"></i></span>
                                            {{ $cat->name }}
                                        </a>
                                    </li>
                                    @endforeach
                                    <li class="category-divider"><hr class="dropdown-divider"></li>
                                    <li class="category-header-pengeluaran"><h6 class="dropdown-header text-danger"><i class="fas fa-arrow-up me-1"></i>Pengeluaran</h6></li>
                                    @foreach($categories->where('type', 'pengeluaran') as $cat)
                                    <li class="category-item" data-type="pengeluaran">
                                        <a class="dropdown-item d-flex align-items-center" href="#" onclick="selectCategory({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->icon }}', '{{ $cat->color }}')">
                                            <span class="me-2 rounded p-1" style="background: {{ $cat->color }}20; color: {{ $cat->color }};"><i class="{{ $cat->icon }}"></i></span>
                                            {{ $cat->name }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
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

<!-- Settings Modal with Tabs -->
<div class="modal fade" id="settingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-cog me-2 text-purple"></i>Pengaturan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs px-4 pt-3" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-umum" data-bs-toggle="tab" data-bs-target="#umum" type="button" role="tab">
                            <i class="fas fa-sliders-h me-2"></i>Umum
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-dompet" data-bs-toggle="tab" data-bs-target="#dompet" type="button" role="tab">
                            <i class="fas fa-wallet me-2"></i>Dompet
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-kategori" data-bs-toggle="tab" data-bs-target="#kategori" type="button" role="tab">
                            <i class="fas fa-tags me-2"></i>Kategori
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content p-4" id="settingsTabContent">
                    <!-- Tab Umum -->
                    <div class="tab-pane fade show active" id="umum" role="tabpanel">
                        <!-- Update Title -->
                        <form method="POST" action="{{ route('settings.update-title') }}" class="mb-4">
                            @csrf
                            <label class="form-label fw-bold">Judul Laporan</label>
                            <div class="input-group">
                                <input type="text" name="app_title" class="form-control" value="{{ $setting->app_title }}" required>
                                <button class="btn btn-primary"><i class="fas fa-save"></i></button>
                            </div>
                        </form>

                        @if(auth()->user()->isSuperAdmin())
                        <!-- Logo Upload (Super Admin Only) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Logo Aplikasi</label>
                            @if($setting->app_logo && file_exists(public_path($setting->app_logo)))
                            <div class="mb-2 p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
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
                                <small class="text-muted">Format: JPG, PNG, GIF, WebP. Max: 2MB.</small>
                            </form>
                        </div>
                        @endif
                    </div>

                    <!-- Tab Dompet -->
                    <div class="tab-pane fade" id="dompet" role="tabpanel">
                        <!-- Wallet Management -->
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-plus-circle me-2"></i>Tambah Dompet Baru</label>
                            <form method="POST" action="{{ route('wallets.store') }}" class="mb-3">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="name" class="form-control" placeholder="Nama dompet baru..." required>
                                    <button class="btn btn-success"><i class="fas fa-plus"></i> Tambah</button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-list me-2"></i>Daftar Dompet</label>
                            <div class="list-group" style="max-height: 200px; overflow-y: auto;">
                                @forelse($wallets as $wallet)
                                <div class="list-group-item d-flex justify-content-between align-items-center border-0 bg-light rounded-3 mb-2">
                                    <span><i class="fas fa-wallet me-2 text-purple"></i>{{ $wallet->name }}</span>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" onclick="editWallet({{ $wallet->id }}, '{{ $wallet->name }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" onclick="confirmDelete('{{ route('wallets.destroy', $wallet) }}', 'dompet {{ $wallet->name }}')"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                                @empty
                                <p class="text-center text-muted py-3">Belum ada dompet.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Danger Zone -->
                        <hr>
                        <label class="form-label text-danger fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Zona Bahaya</label>
                        <button class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteAllModal"><i class="fas fa-trash me-2"></i>Hapus Semua Transaksi</button>
                    </div>

                    <!-- Tab Kategori -->
                    <div class="tab-pane fade" id="kategori" role="tabpanel">
                        <!-- Add New Category -->
                        <form method="POST" action="{{ route('categories.store') }}" class="mb-4 p-3 bg-light rounded-4">
                            @csrf
                            <h6 class="fw-bold mb-3"><i class="fas fa-plus-circle me-2"></i>Tambah Kategori Baru</h6>
                            <div class="row g-2">
                                <div class="col-12">
                                    <input type="text" name="name" class="form-control" placeholder="Nama Kategori" required>
                                </div>
                                <div class="col-6">
                                    <select name="type" class="form-select" required>
                                        <option value="pengeluaran">Pengeluaran</option>
                                        <option value="pemasukan">Pemasukan</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input type="color" name="color" class="form-control form-control-color w-100" value="#667eea" title="Pilih warna">
                                </div>
                                <div class="col-8">
                                    <input type="text" name="icon" class="form-control" placeholder="Icon (fas fa-coffee)" value="fas fa-tag">
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-plus"></i> Tambah</button>
                                </div>
                            </div>
                        </form>

                        <!-- Category List -->
                        <div class="row">
                            <!-- Pengeluaran -->
                            <div class="col-md-6 mb-3">
                                <h6 class="text-danger fw-bold mb-2"><i class="fas fa-arrow-up me-1"></i>Pengeluaran</h6>
                                <div class="list-group" style="max-height: 250px; overflow-y: auto;">
                                    @foreach($categories->where('type', 'pengeluaran') as $cat)
                                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 bg-light rounded-3 mb-2 py-2">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2 rounded-2 p-1" style="background: {{ $cat->color }}20; color: {{ $cat->color }};">
                                                <i class="{{ $cat->icon }}"></i>
                                            </div>
                                            <small>{{ $cat->name }}</small>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->icon }}', '{{ $cat->color }}', '{{ $cat->type }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($cat->transactions->count() == 0)
                                            <form method="POST" action="{{ route('categories.destroy', $cat) }}" class="d-inline" onsubmit="return confirm('Hapus?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Pemasukan -->
                            <div class="col-md-6">
                                <h6 class="text-success fw-bold mb-2"><i class="fas fa-arrow-down me-1"></i>Pemasukan</h6>
                                <div class="list-group" style="max-height: 250px; overflow-y: auto;">
                                    @foreach($categories->where('type', 'pemasukan') as $cat)
                                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 bg-light rounded-3 mb-2 py-2">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2 rounded-2 p-1" style="background: {{ $cat->color }}20; color: {{ $cat->color }};">
                                                <i class="{{ $cat->icon }}"></i>
                                            </div>
                                            <small>{{ $cat->name }}</small>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->icon }}', '{{ $cat->color }}', '{{ $cat->type }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($cat->transactions->count() == 0)
                                            <form method="POST" action="{{ route('categories.destroy', $cat) }}" class="d-inline" onsubmit="return confirm('Hapus?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete All Transactions Confirmation Modal -->
<div class="modal fade" id="deleteAllModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="POST" action="{{ route('transactions.destroy-all') }}">
                @csrf
                @method('DELETE')
                
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4 px-5">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex p-4 mb-4">
                        <i class="fas fa-bomb fa-3x"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Zona Bahaya!</h3>
                    <p class="text-muted mb-4">Anda akan menghapus <strong>SEMUA</strong> data transaksi. Tindakan ini tidak dapat dibatalkan.</p>
                    
                    <div class="form-floating mb-4 text-start">
                        <input type="password" class="form-control rounded-4 @error('password') is-invalid @enderror" 
                               id="delete_all_password" name="password" placeholder="Password" required>
                        <label for="delete_all_password">Masukkan password konfirmasi</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-5 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 me-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-5 hover-scale">
                        <i class="fas fa-trash-alt me-2"></i> Ya, Hapus Semuanya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->has('password'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('deleteAllModal'));
        myModal.show();
    });
</script>
@endif

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
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white border-0 pt-3 pb-0 px-4">
                                <h6 class="mb-0 fw-bold border-start border-primary border-4 ps-3">Trend 6 Bulan Terakhir</h6>
                            </div>
                            <div class="card-body px-4 pb-4">
                                <div style="position: relative; height:300px; width:100%;">
                                    <canvas id="trendChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Pengeluaran -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4">
                            <div class="card-header bg-white border-0 pt-3 pb-0 px-4">
                                <h6 class="mb-0 fw-bold border-start border-danger border-4 ps-3">Distribusi Pengeluaran</h6>
                            </div>
                            <div class="card-body px-4 pb-4 d-flex align-items-center justify-content-center">
                                @if($statistics['topPengeluaran']->isEmpty())
                                <div class="text-center w-100 py-5">
                                    <i class="fas fa-chart-pie fa-3x text-light mb-3"></i>
                                    <p class="text-muted">Belum ada data</p>
                                </div>
                                @else
                                <div style="position: relative; height:300px; width:100%;">
                                    <canvas id="expenseChart"></canvas>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Top Pemasukan -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4">
                            <div class="card-header bg-white border-0 pt-3 pb-0 px-4">
                                <h6 class="mb-0 fw-bold border-start border-success border-4 ps-3">Distribusi Pemasukan</h6>
                            </div>
                            <div class="card-body px-4 pb-4 d-flex align-items-center justify-content-center">
                                @if($statistics['topPemasukan']->isEmpty())
                                <div class="text-center w-100 py-5">
                                    <i class="fas fa-chart-line fa-3x text-light mb-3"></i>
                                    <p class="text-muted">Belum ada data</p>
                                </div>
                                @else
                                <div style="position: relative; height:300px; width:100%;">
                                    <canvas id="incomeChart"></canvas>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Month Comparison -->
                    <div class="col-12">

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

                    <div class="col-12">
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

<!-- Categories Modal -->
<div class="modal fade" id="categoriesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-tags me-2 text-purple"></i>Kelola Kategori
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Add New Category -->
                <form method="POST" action="{{ route('categories.store') }}" class="mb-4 p-3 bg-light rounded-4">
                    @csrf
                    <h6 class="fw-bold mb-3"><i class="fas fa-plus-circle me-2"></i>Tambah Kategori</h6>
                    <div class="row g-2">
                        <div class="col-12">
                            <input type="text" name="name" class="form-control" placeholder="Nama Kategori" required>
                        </div>
                        <div class="col-6">
                            <select name="type" class="form-select" required>
                                <option value="pengeluaran">Pengeluaran</option>
                                <option value="pemasukan">Pemasukan</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <input type="color" name="color" class="form-control form-control-color w-100" value="#667eea" title="Pilih warna">
                        </div>
                        <div class="col-8">
                            <input type="text" name="icon" class="form-control" placeholder="Icon (mis: fas fa-coffee)" value="fas fa-tag">
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </form>

                <!-- Category List -->
                <h6 class="fw-bold mb-3"><i class="fas fa-list me-2"></i>Daftar Kategori</h6>
                
                <!-- Pengeluaran -->
                <div class="mb-3">
                    <small class="text-danger fw-bold"><i class="fas fa-arrow-up me-1"></i>PENGELUARAN</small>
                    <div class="list-group mt-2">
                        @foreach($categories->where('type', 'pengeluaran') as $cat)
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 bg-light rounded-3 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="me-3 rounded-2 p-2" style="background: {{ $cat->color }}20; color: {{ $cat->color }};">
                                    <i class="{{ $cat->icon }}"></i>
                                </div>
                                <span>{{ $cat->name }}</span>
                            </div>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->icon }}', '{{ $cat->color }}', '{{ $cat->type }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($cat->transactions->count() == 0)
                                <form method="POST" action="{{ route('categories.destroy', $cat) }}" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                                @else
                                <button class="btn btn-sm btn-secondary" disabled title="Ada transaksi"><i class="fas fa-trash"></i></button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pemasukan -->
                <div>
                    <small class="text-success fw-bold"><i class="fas fa-arrow-down me-1"></i>PEMASUKAN</small>
                    <div class="list-group mt-2">
                        @foreach($categories->where('type', 'pemasukan') as $cat)
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 bg-light rounded-3 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="me-3 rounded-2 p-2" style="background: {{ $cat->color }}20; color: {{ $cat->color }};">
                                    <i class="{{ $cat->icon }}"></i>
                                </div>
                                <span>{{ $cat->name }}</span>
                            </div>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->icon }}', '{{ $cat->color }}', '{{ $cat->type }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($cat->transactions->count() == 0)
                                <form method="POST" action="{{ route('categories.destroy', $cat) }}" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                                @else
                                <button class="btn btn-sm btn-secondary" disabled title="Ada transaksi"><i class="fas fa-trash"></i></button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form id="editCategoryForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" id="editCatName" class="form-control" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Tipe</label>
                            <select name="type" id="editCatType" class="form-select">
                                <option value="pengeluaran">Pengeluaran</option>
                                <option value="pemasukan">Pemasukan</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Warna</label>
                            <input type="color" name="color" id="editCatColor" class="form-control form-control-color w-100">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon (FontAwesome)</label>
                        <input type="text" name="icon" id="editCatIcon" class="form-control" placeholder="fas fa-tag">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Wallet Modal -->
<div class="modal fade" id="editWalletModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form id="editWalletForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-wallet me-2"></i>Edit Dompet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Dompet</label>
                        <input type="text" name="name" id="editWalletName" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- PDF Preview Modal -->
<div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; height: 90vh;">
            <div class="modal-header border-bottom bg-light px-4 py-3">
                <h5 class="modal-title fw-bold text-danger"><i class="fas fa-file-pdf me-2"></i>Pratinjau Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="background: #525659;">
                <iframe id="pdfFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
function previewPdf() {
    // Collect current filter parameters
    const params = new URLSearchParams(new FormData(document.getElementById('filterForm')));
    const url = "{{ route('export.pdf') }}?" + params.toString();
    
    // Set iframe src and show modal
    document.getElementById('pdfFrame').src = url;
    const modal = new bootstrap.Modal(document.getElementById('pdfPreviewModal'));
    modal.show();
}
</script>
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
    if (!form) return;

    form.action = "{{ route('transactions.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('modalTitle').textContent = 'Tambah Transaksi';
    form.reset();
    
    // Reset warning state
    if (document.getElementById('balanceWarning')) {
        document.getElementById('balanceWarning').classList.add('d-none');
    }

    // Update balance context for the default selected wallet after reset
    updateWalletBalance();

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

    // Reset category selection
    document.getElementById('categoryInput').value = '';
    document.getElementById('categoryLabel').innerHTML = '<i class="fas fa-folder me-2 text-muted"></i>-- Pilih Kategori --';

    isEditing = false;
    originalAmount = 0;
    originalType = '';
    originalWalletId = 0;
}

function editTransaction(trx) {
    const form = document.getElementById('transactionForm');
    if (!form) return;

    // Reset warning state
    if (document.getElementById('balanceWarning')) {
        document.getElementById('balanceWarning').classList.add('d-none');
    }

    form.action = "{{ url('transactions') }}/" + trx.id;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('modalTitle').textContent = 'Edit Transaksi';
    
    isEditing = true;
    originalAmount = parseInt(trx.jumlah);
    originalType = trx.tipe;
    originalWalletId = parseInt(trx.wallet_id);
    
    // 1. Set Wallet FIRST to load correct currentWalletBalance
    const walletSelect = document.getElementById('walletSelect');
    if (walletSelect) {
        walletSelect.value = trx.wallet_id;
        updateWalletBalance(); 
    }

    // 2. Set amount (triggers validateBalance with correct context)
    setJumlahValue(parseInt(trx.jumlah));

    // 3. Set type (triggers UI updates and validateBalance again)
    document.querySelector(`.type-btn[data-type="${trx.tipe}"]`).click();

    const tanggalInput = form.querySelector('[name="tanggal"]');
    if (tanggalInput && trx.tanggal) {
        tanggalInput.value = trx.tanggal.split('T')[0];
    }

    const ketInput = form.querySelector('[name="keterangan"]');
    if (ketInput) ketInput.value = trx.keterangan;
    
    // Set category
    if (trx.category) {
        selectCategory(trx.category.id, trx.category.name, trx.category.icon, trx.category.color);
    } else {
        document.getElementById('categoryInput').value = '';
        document.getElementById('categoryLabel').innerHTML = '<i class="fas fa-folder me-2 text-muted"></i>-- Pilih Kategori --';
    }
    
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
        
        // Filter categories based on type
        filterCategoriesByType(type);
        
        updateWalletBalance();
    });
});

// Filter categories dropdown based on transaction type
function filterCategoriesByType(type) {
    // Show/hide category items based on type
    document.querySelectorAll('.category-item').forEach(item => {
        if (item.dataset.type === type) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
    
    // Show/hide headers and divider
    document.querySelector('.category-header-pemasukan').style.display = type === 'pemasukan' ? '' : 'none';
    document.querySelector('.category-header-pengeluaran').style.display = type === 'pengeluaran' ? '' : 'none';
    document.querySelector('.category-divider').style.display = 'none';
    
    // Reset category selection
    document.getElementById('categoryInput').value = '';
    document.getElementById('categoryLabel').innerHTML = '<i class="fas fa-folder me-2 text-muted"></i>-- Pilih Kategori --';
}

// Initialize category filter on page load (default: pemasukan)
document.addEventListener('DOMContentLoaded', function() {
    filterCategoriesByType('pemasukan');
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
    
    validateBalance();
}

if (jumlahDisplay) {
    jumlahDisplay.addEventListener('input', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        this.value = formatRupiah(value);
        if (jumlahReal) jumlahReal.value = value;
        validateBalance(); // Single place for validation on input
    });
    
    jumlahDisplay.addEventListener('keypress', function(e) {
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    });
}



// Wallet Balance Management
let currentWalletBalance = 0;
let isEditing = false;
let originalAmount = 0;
let originalType = '';
let originalWalletId = 0;

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
    const walletVal = document.getElementById('walletSelect') ? parseInt(document.getElementById('walletSelect').value) : 0;
    const balanceWarning = document.getElementById('balanceWarning');
    const jumlahRealInp = document.getElementById('jumlahReal');
    const jumlahValue = jumlahRealInp ? (parseInt(jumlahRealInp.value) || 0) : 0;
    
    if (!balanceWarning) return;
    
    let effectiveBalance = currentWalletBalance;
    
    // If we are editing a transaction in its original wallet, 
    // we need to "undo" its impact on the balance for validation purposes.
    if (isEditing && walletVal === originalWalletId) {
        if (originalType === 'pengeluaran') {
            effectiveBalance += originalAmount;
        } else if (originalType === 'pemasukan') {
            effectiveBalance -= originalAmount;
        }
    }
    
    // Only show warning if:
    // 1. It's an expense (pengeluaran)
    // 2. Amount is greater than 0
    // 3. Amount exceeds effective available balance
    if (tipeVal === 'pengeluaran' && jumlahValue > 0 && jumlahValue > effectiveBalance) {
        balanceWarning.classList.remove('d-none');
    } else {
        balanceWarning.classList.add('d-none');
    }
}

// Consolidated validation onto the main input listener above.

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
// Initialize Charts when Modal is Shown
document.getElementById('statisticsModal').addEventListener('shown.bs.modal', function () {
    const stats = @json($statistics);
    const formatCurrency = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);

    // Destroy existing charts if they exist to prevent duplicates
    Chart.getChart("trendChart")?.destroy();
    Chart.getChart("expenseChart")?.destroy();
    Chart.getChart("incomeChart")?.destroy();

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
    if(document.getElementById('expenseChart')) {
        new Chart(document.getElementById('expenseChart'), {
            type: 'doughnut',
            data: {
                labels: stats.topPengeluaran.map(d => d.label),
                datasets: [{
                    data: stats.topPengeluaran.map(d => d.total),
                    backgroundColor: [
                        '#ef4444', '#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#6366f1', '#ec4899', '#84cc16'
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

    // Top Pemasukan Chart
    if(document.getElementById('incomeChart')) {
        new Chart(document.getElementById('incomeChart'), {
            type: 'doughnut',
            data: {
                labels: stats.topPemasukan.map(d => d.label),
                datasets: [{
                    data: stats.topPemasukan.map(d => d.total),
                    backgroundColor: [
                        '#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444', '#6366f1', '#ec4899', '#84cc16'
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

// Edit Category Function
function editCategory(id, name, icon, color, type) {
    document.getElementById('editCategoryForm').action = '/categories/' + id;
    document.getElementById('editCatName').value = name;
    document.getElementById('editCatIcon').value = icon || '';
    document.getElementById('editCatColor').value = color || '#667eea';
    document.getElementById('editCatType').value = type;
    
    var editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
    editModal.show();
}

// Select Category for Transaction
function selectCategory(id, name, icon, color) {
    document.getElementById('categoryInput').value = id;
    document.getElementById('categoryLabel').innerHTML = '<span class="me-2 rounded p-1" style="background: ' + color + '20; color: ' + color + ';"><i class="' + icon + '"></i></span>' + name;
}

// Edit Wallet Function
function editWallet(id, name) {
    document.getElementById('editWalletForm').action = '/wallets/' + id;
    document.getElementById('editWalletName').value = name;
    
    var editModal = new bootstrap.Modal(document.getElementById('editWalletModal'));
    editModal.show();
}
</script>
@endpush
