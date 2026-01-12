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
        $categories = $user->categories;

        $query = $user->transactions()->with(['wallet', 'category']);

        // Filter by wallet
        if ($request->filled('wallet_id') && $request->wallet_id !== 'all') {
            $query->where('wallet_id', $request->wallet_id);
        }

        // Filter by search (Unified: Keterangan or Category Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
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

        // Get logo from super admin
        $superAdmin = \App\Models\User::where('role', 'super_admin')->first();
        if ($superAdmin && $superAdmin->setting && $superAdmin->setting->app_logo) {
            $setting->app_logo = $superAdmin->setting->app_logo;
        }

        // === STATISTICS & INSIGHTS ===
        $statistics = $this->getStatistics($user);
        $insights = $this->getInsights($statistics);

        // Wallet data for Slider (including balance and sparkline data)
        $walletCards = $wallets->map(function ($wallet) use ($user) {
            $balance = $wallet->transactions()->selectRaw("SUM(CASE WHEN tipe = 'pemasukan' THEN jumlah ELSE -jumlah END) as total")->value('total') ?? 0;

            // Sparkline data for last 7 days
            $sparkline = $wallet->transactions()
                ->where('tanggal', '>', now()->subDays(7))
                ->select(DB::raw('DATE(tanggal) as date'), DB::raw('SUM(CASE WHEN tipe = "pemasukan" THEN jumlah ELSE -jumlah END) as daily_total'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('daily_total')
                ->toArray();

            return [
                'id' => $wallet->id,
                'name' => $wallet->name,
                'balance' => $balance,
                'sparkline' => $sparkline,
                'last_trx' => $wallet->transactions()->latest('tanggal')->first(),
            ];
        });

        return view('dashboard.index', compact(
            'wallets',
            'categories',
            'transactions',
            'totalIn',
            'totalOut',
            'balance',
            'setting',
            'statistics',
            'insights',
            'walletCards'
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

        // 2. Pengeluaran per Kategori (Unlimited)
        $topPengeluaran = $user->transactions()
            ->leftJoin('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                DB::raw('COALESCE(categories.name, transactions.keterangan) as label'),
                DB::raw('SUM(transactions.jumlah) as total')
            )
            ->where('transactions.tipe', 'pengeluaran')
            ->groupBy(DB::raw('COALESCE(categories.name, transactions.keterangan)'))
            ->orderByDesc('total')
            ->get();

        // 3. Pemasukan per Kategori (Unlimited)
        $topPemasukan = $user->transactions()
            ->leftJoin('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                DB::raw('COALESCE(categories.name, transactions.keterangan) as label'),
                DB::raw('SUM(transactions.jumlah) as total')
            )
            ->where('transactions.tipe', 'pemasukan')
            ->groupBy(DB::raw('COALESCE(categories.name, transactions.keterangan)'))
            ->orderByDesc('total')
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
            'topPemasukan' => $topPemasukan,
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

    private function getInsights($statistics): array
    {
        $insights = [];

        // 1. Savings/Profit Insight
        $lastNet = $statistics['lastMonth']['pemasukan'] - $statistics['lastMonth']['pengeluaran'];
        $thisNet = $statistics['thisMonth']['pemasukan'] - $statistics['thisMonth']['pengeluaran'];

        if ($lastNet > 0 && $thisNet > $lastNet) {
            $diff = (($thisNet - $lastNet) / $lastNet) * 100;
            $insights[] = [
                'type' => 'success',
                'title' => 'Pertumbuhan Positif',
                'text' => 'Keuntungan bersih Anda meningkat sebesar ' . number_format($diff, 1) . '% dibandingkan bulan lalu. Pertahankan efisiensi pengeluaran Anda.',
                'icon' => 'fas fa-trending-up'
            ];
        } elseif ($thisNet < 0) {
            $insights[] = [
                'type' => 'danger',
                'title' => 'Defisit Anggaran',
                'text' => 'Pengeluaran Anda melampaui pemasukan bulan ini. Pertimbangkan untuk meninjau kembali kategori pengeluaran terbesar Anda.',
                'icon' => 'fas fa-exclamation-triangle'
            ];
        }

        // 2. High Spending Category Insight
        if (!$statistics['topPengeluaran']->isEmpty()) {
            $top = $statistics['topPengeluaran']->first();
            $insights[] = [
                'type' => 'warning',
                'title' => 'Analisis Pengeluaran',
                'text' => 'Kategori "' . $top->keterangan . '" merupakan pengeluaran tertinggi Anda. Fokus pada area ini untuk penghematan lebih lanjut.',
                'icon' => 'fas fa-search-dollar'
            ];
        }

        // 3. Wallet Diversification
        if ($statistics['walletStats']->count() > 1) {
            $insights[] = [
                'type' => 'info',
                'title' => 'Manajemen Portofolio',
                'text' => 'Anda mengelola ' . $statistics['walletStats']->count() . ' dompet dengan aktif. Distribusi saldo yang merata membantu keamanan finansial.',
                'icon' => 'fas fa-shield-alt'
            ];
        }

        return $insights;
    }
}
