<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $wallets = $user->wallets;

        $query = $user->transactions()->with('wallet');

        // Filter by wallet
        if ($request->filled('wallet_id') && $request->wallet_id !== 'all') {
            $query->where('wallet_id', $request->wallet_id);
        }

        // Filter by search
        if ($request->filled('search')) {
            $query->where('keterangan', 'like', '%' . $request->search . '%');
        }

        // Filter by date type
        if ($request->filled('filter_type')) {
            switch ($request->filter_type) {
                case 'daily':
                    $query->whereDate('tanggal', $request->filter_date ?? now()->toDateString());
                    break;
                case 'monthly':
                    if ($request->filled('filter_month')) {
                        $query->whereYear('tanggal', substr($request->filter_month, 0, 4))
                            ->whereMonth('tanggal', substr($request->filter_month, 5, 2));
                    }
                    break;
                case 'yearly':
                    if ($request->filled('filter_year')) {
                        $query->whereYear('tanggal', $request->filter_year);
                    }
                    break;
            }
        }

        $transactions = $query->orderBy('tanggal', 'asc')->get();

        // Calculate running balance
        $balance = 0;
        $totalIn = 0;
        $totalOut = 0;

        $transactions = $transactions->map(function ($trx) use (&$balance, &$totalIn, &$totalOut) {
            if ($trx->tipe === 'pemasukan') {
                $balance += $trx->jumlah;
                $totalIn += $trx->jumlah;
            } else {
                $balance -= $trx->jumlah;
                $totalOut += $trx->jumlah;
            }
            $trx->saldo = $balance;
            return $trx;
        });

        // Get or create user setting
        $setting = $user->setting ?? $user->setting()->create(['app_title' => 'Laporan Keuangan']);

        // Get logo from super admin (shared across all users)
        $superAdmin = \App\Models\User::where('role', 'super_admin')->first();
        if ($superAdmin && $superAdmin->setting && $superAdmin->setting->app_logo) {
            $setting->app_logo = $superAdmin->setting->app_logo;
        }

        // === STATISTICS DATA ===
        $statistics = $this->getStatistics($user);

        return view('dashboard.index', compact(
            'wallets',
            'transactions',
            'totalIn',
            'totalOut',
            'balance',
            'setting',
            'statistics'
        ));
    }

    private function getStatistics($user): array
    {
        // 1. Monthly Trend (Last 6 months)
        $monthlyTrend = $user->transactions()
            ->select(
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as month"),
                DB::raw("SUM(CASE WHEN tipe = 'pemasukan' THEN jumlah ELSE 0 END) as pemasukan"),
                DB::raw("SUM(CASE WHEN tipe = 'pengeluaran' THEN jumlah ELSE 0 END) as pengeluaran")
            )
            ->where('tanggal', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 2. Top 5 Pengeluaran by Keterangan
        $topPengeluaran = $user->transactions()
            ->select('keterangan', DB::raw('SUM(jumlah) as total'))
            ->where('tipe', 'pengeluaran')
            ->groupBy('keterangan')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 3. This Month vs Last Month
        $thisMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $thisMonthData = $user->transactions()
            ->whereYear('tanggal', $thisMonth->year)
            ->whereMonth('tanggal', $thisMonth->month)
            ->selectRaw("
                SUM(CASE WHEN tipe = 'pemasukan' THEN jumlah ELSE 0 END) as pemasukan,
                SUM(CASE WHEN tipe = 'pengeluaran' THEN jumlah ELSE 0 END) as pengeluaran
            ")
            ->first();

        $lastMonthData = $user->transactions()
            ->whereYear('tanggal', $lastMonth->year)
            ->whereMonth('tanggal', $lastMonth->month)
            ->selectRaw("
                SUM(CASE WHEN tipe = 'pemasukan' THEN jumlah ELSE 0 END) as pemasukan,
                SUM(CASE WHEN tipe = 'pengeluaran' THEN jumlah ELSE 0 END) as pengeluaran
            ")
            ->first();

        // 4. Transaction Count per Wallet
        $walletStats = $user->wallets()
            ->withCount('transactions')
            ->withSum(['transactions as total_pemasukan' => function ($q) {
                $q->where('tipe', 'pemasukan');
            }], 'jumlah')
            ->withSum(['transactions as total_pengeluaran' => function ($q) {
                $q->where('tipe', 'pengeluaran');
            }], 'jumlah')
            ->get();

        // 5. Total transaction count
        $totalTransactions = $user->transactions()->count();

        return [
            'monthlyTrend' => $monthlyTrend,
            'topPengeluaran' => $topPengeluaran,
            'thisMonth' => [
                'pemasukan' => $thisMonthData->pemasukan ?? 0,
                'pengeluaran' => $thisMonthData->pengeluaran ?? 0,
            ],
            'lastMonth' => [
                'pemasukan' => $lastMonthData->pemasukan ?? 0,
                'pengeluaran' => $lastMonthData->pengeluaran ?? 0,
            ],
            'walletStats' => $walletStats,
            'totalTransactions' => $totalTransactions,
        ];
    }
}
