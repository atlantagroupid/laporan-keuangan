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
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Header -->
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
        <div class="col-md-4">
            <div class="stat-card p-3 text-center">
                <div class="stat-label text-muted">Pemasukan</div>
                <div class="stat-value text-success-custom">{{ number_format($totalIn, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card p-3 text-center">
                <div class="stat-label text-muted">Pengeluaran</div>
                <div class="stat-value text-danger-custom">{{ number_format($totalOut, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card p-3 text-center">
                <div class="stat-label text-muted">Saldo</div>
                <div class="stat-value text-primary-custom">{{ number_format($balance, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Filters & Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Toolbar -->
            <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                <div class="row g-2 mb-3">
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
                    <div class="col-md-3 text-end">
                        <button type="submit" class="btn btn-primary btn-sm me-1"><i class="fas fa-search"></i></button>
                        <a href="{{ route('export.excel', request()->query()) }}" class="btn btn-excel btn-sm text-white me-1"><i class="fas fa-file-excel"></i></a>
                        <a href="{{ route('export.pdf', request()->query()) }}" class="btn btn-pdf btn-sm text-white"><i class="fas fa-file-pdf"></i></a>
                    </div>
                </div>
            </form>

            <!-- Statistics Button -->
            <div class="mb-4">
                <button class="btn btn-outline-primary w-100 py-2 shadow-sm fw-bold border-2 rounded-3" data-bs-toggle="modal" data-bs-target="#statisticsModal">
                    <i class="fas fa-chart-pie me-2"></i>Lihat Analisis & Statistik Keuangan
                </button>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover table-transactions">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px">No</th>
                            <th>Dompet</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th class="text-end">Masuk</th>
                            <th class="text-end">Keluar</th>
                            <th class="text-end">Saldo</th>
                            <th class="text-center" style="width: 100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $index => $trx)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td><span class="badge-wallet">{{ $trx->wallet->name }}</span></td>
                            <td>{{ $trx->tanggal->translatedFormat('d F Y') }}</td>
                            <td><strong>{{ $trx->keterangan }}</strong></td>
                            <td class="text-end text-success-custom">{{ $trx->tipe === 'pemasukan' ? number_format($trx->jumlah, 0, ',', '.') : '-' }}</td>
                            <td class="text-end text-danger-custom">{{ $trx->tipe === 'pengeluaran' ? number_format($trx->jumlah, 0, ',', '.') : '-' }}</td>
                            <td class="text-end fw-bold {{ $trx->saldo < 0 ? 'text-danger-custom' : 'text-primary-custom' }}">{{ number_format($trx->saldo, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning" onclick="editTransaction({{ json_encode($trx) }})"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('transactions.destroy', $trx) }}', 'transaksi ini')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-wallet fa-3x mb-3 opacity-50"></i>
                                <p class="mb-0">Tidak ada data ditemukan</p>
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
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
<div class="modal fade" id="transactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="transactionForm" method="POST" action="{{ route('transactions.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($wallets->isEmpty())
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Buat minimal satu dompet di Pengaturan terlebih dahulu!
                    </div>
                    @else
                    <div class="mb-3">
                        <label class="form-label">Pilih Dompet</label>
                        <select name="wallet_id" id="walletSelect" class="form-select @error('wallet_id') is-invalid @enderror" required onchange="updateWalletBalance()">
                            @foreach($wallets as $wallet)
                            @php
                                $walletBalance = $wallet->transactions->sum(function($t) {
                                    return $t->tipe === 'pemasukan' ? $t->jumlah : -$t->jumlah;
                                });
                            @endphp
                            <option value="{{ $wallet->id }}" data-balance="{{ $walletBalance }}">
                                {{ $wallet->name }} (Saldo: Rp {{ number_format($walletBalance, 0, ',', '.') }})
                            </option>
                            @endforeach
                        </select>
                        @error('wallet_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Tipe</label>
                            <select name="tipe" id="tipeSelect" class="form-select" required onchange="updateWalletBalance()">
                                <option value="pemasukan">Pemasukan (+)</option>
                                <option value="pengeluaran">Pengeluaran (-)</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Wallet Balance Info (shows for pengeluaran) -->
                    <div class="mb-3 d-none" id="balanceInfo">
                        <div class="alert alert-info py-2 mb-0 d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-wallet me-2"></i>
                                Saldo tersedia: <strong id="availableBalance">Rp 0</strong>
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="fillMaxAmount()">
                                <i class="fas fa-arrow-up me-1"></i>MAX
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Contoh: Gaji, Belanja Sayur" value="{{ old('keterangan') }}" required>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah (Rp)</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text fw-bold">Rp</span>
                            <input type="text" name="jumlah_display" id="jumlahDisplay" class="form-control fs-5 fw-bold @error('jumlah') is-invalid @enderror" placeholder="0" value="{{ old('jumlah_display') }}" required autocomplete="off">
                            <input type="hidden" name="jumlah" id="jumlahReal" value="{{ old('jumlah') }}">
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-danger d-none" id="balanceWarning">
                            <i class="fas fa-exclamation-triangle me-1"></i>Jumlah melebihi saldo yang tersedia!
                        </small>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    @if(!$wallets->isEmpty())
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
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


<!-- Statistics Modal -->
<div class="modal fade" id="statisticsModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content bg-light">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i>Analisis & Statistik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pb-4">
                <div class="row g-3">
                    <!-- Monthly Trend Chart -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i>Trend 6 Bulan Terakhir</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="trendChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Top Pengeluaran -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2 text-danger"></i>Top 5 Pengeluaran</h6>
                            </div>
                            <div class="card-body">
                                @if($statistics['topPengeluaran']->isEmpty())
                                <p class="text-muted text-center py-4">Belum ada data pengeluaran</p>
                                @else
                                <canvas id="topChart" height="200"></canvas>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Month Comparison -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2 text-info"></i>Perbandingan Periode</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="border rounded p-3 text-center">
                                            <small class="text-muted">Bulan Lalu</small>
                                            <div class="mt-2">
                                                <div class="text-success-custom small"><i class="fas fa-arrow-up me-1"></i>{{ number_format($statistics['lastMonth']['pemasukan'], 0, ',', '.') }}</div>
                                                <div class="text-danger-custom small"><i class="fas fa-arrow-down me-1"></i>{{ number_format($statistics['lastMonth']['pengeluaran'], 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-3 text-center bg-light">
                                            <small class="text-muted">Bulan Ini</small>
                                            <div class="mt-2">
                                                <div class="text-success-custom small"><i class="fas fa-arrow-up me-1"></i>{{ number_format($statistics['thisMonth']['pemasukan'], 0, ',', '.') }}</div>
                                                <div class="text-danger-custom small"><i class="fas fa-arrow-down me-1"></i>{{ number_format($statistics['thisMonth']['pengeluaran'], 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $lastNetProfit = $statistics['lastMonth']['pemasukan'] - $statistics['lastMonth']['pengeluaran'];
                                    $thisNetProfit = $statistics['thisMonth']['pemasukan'] - $statistics['thisMonth']['pengeluaran'];
                                    $profitChange = $lastNetProfit != 0 ? (($thisNetProfit - $lastNetProfit) / abs($lastNetProfit)) * 100 : ($thisNetProfit > 0 ? 100 : 0);
                                @endphp
                                <div class="text-center mt-3">
                                    <span class="badge {{ $profitChange >= 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                                        <i class="fas {{ $profitChange >= 0 ? 'fa-trending-up' : 'fa-trending-down' }} me-1"></i>
                                        {{ $profitChange >= 0 ? '+' : '' }}{{ number_format($profitChange, 1) }}% Net Profit
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wallet Stats -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-wallet me-2 text-purple"></i>Statistik per Dompet</h6>
                                <span class="badge bg-secondary">{{ $statistics['totalTransactions'] }} transaksi</span>
                            </div>
                            <div class="card-body">
                                <div class="wallet-list">
                                    @forelse($statistics['walletStats'] as $ws)
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark">{{ $ws->name }}</h6>
                                            <span class="badge bg-light text-dark border">{{ $ws->transactions_count }} Transaksi</span>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-success small fw-bold mb-1">
                                                <i class="fas fa-arrow-up me-1"></i>{{ number_format($ws->total_pemasukan ?? 0, 0, ',', '.') }}
                                            </div>
                                            <div class="text-danger small fw-bold">
                                                <i class="fas fa-arrow-down me-1"></i>{{ number_format($ws->total_pengeluaran ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-wallet fa-2x mb-2 text-secondary"></i>
                                        <p class="mb-0">Belum ada data dompet.</p>
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
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayString = `${yyyy}-${mm}-${dd}`;
    
    const dateInput = form.querySelector('[name="tanggal"]');
    dateInput.value = todayString;
    
    // Update flatpickr instance if exists
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
    
    // Use IDs for better reliability
    const walletSelect = document.getElementById('walletSelect');
    if (walletSelect) {
        walletSelect.value = trx.wallet_id;
        // Trigger change event to update balance if needed
        walletSelect.dispatchEvent(new Event('change'));
    }

    const tanggalInput = form.querySelector('[name="tanggal"]');
    if (tanggalInput && trx.tanggal) {
        tanggalInput.value = trx.tanggal.split('T')[0];
    }

    const tipeSelect = document.getElementById('tipeSelect');
    if (tipeSelect) {
        tipeSelect.value = trx.tipe;
        tipeSelect.dispatchEvent(new Event('change'));
    }

    const ketInput = form.querySelector('[name="keterangan"]');
    if (ketInput) ketInput.value = trx.keterangan;
    
    // Parse integer to avoid decimal issues (e.g. 50000.00 -> 50.000.00 -> 5.000.000 bug)
    setJumlahValue(parseInt(trx.jumlah));
    
    new bootstrap.Modal(document.getElementById('transactionModal')).show();
}

// Initialize filter display
toggleFilterInputs();

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
    const tipeSelect = document.getElementById('tipeSelect');
    const balanceInfo = document.getElementById('balanceInfo');
    const availableBalance = document.getElementById('availableBalance');
    
    if (!walletSelect || !tipeSelect) return;
    
    const selectedOption = walletSelect.options[walletSelect.selectedIndex];
    currentWalletBalance = parseInt(selectedOption.dataset.balance) || 0;
    
    // Show balance info only for pengeluaran
    if (tipeSelect.value === 'pengeluaran') {
        balanceInfo.classList.remove('d-none');
        availableBalance.textContent = 'Rp ' + formatRupiah(currentWalletBalance);
    } else {
        balanceInfo.classList.add('d-none');
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
    const tipeSelect = document.getElementById('tipeSelect');
    const balanceWarning = document.getElementById('balanceWarning');
    const jumlahValue = parseInt(jumlahReal.value) || 0;
    
    if (!tipeSelect || !balanceWarning) return;
    
    if (tipeSelect.value === 'pengeluaran' && jumlahValue > currentWalletBalance) {
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
        jumlahReal.value = value;
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
    const tipeSelect = document.getElementById('tipeSelect');
    const jumlahValue = parseInt(jumlahReal.value) || 0;
    
    // Check if pengeluaran exceeds balance
    if (tipeSelect && tipeSelect.value === 'pengeluaran' && jumlahValue > currentWalletBalance) {
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
            type: 'bar', // Changed to Bar for better comparison clarity
            data: {
                labels: stats.monthlyTrend.map(d => {
                    const date = new Date(d.month + '-01');
                    return date.toLocaleDateString('id-ID', { month: 'short', year: '2-digit' });
                }),
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: stats.monthlyTrend.map(d => d.pemasukan),
                        borderColor: '#2ecc71',
                        backgroundColor: 'rgba(46, 204, 113, 0.7)',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'Pengeluaran',
                        data: stats.monthlyTrend.map(d => d.pengeluaran),
                        borderColor: '#e74c3c',
                        backgroundColor: 'rgba(231, 76, 60, 0.7)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += formatCurrency(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatCurrency(value);
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
                        '#e74c3c', '#e67e22', '#f1c40f', '#9b59b6', '#3498db', '#2ecc71'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 12 } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += formatCurrency(context.parsed);
                                }
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
