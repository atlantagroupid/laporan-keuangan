<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $user = $request->user();
        $data = $this->getFilteredData($request);

        $admin = \App\Models\User::where('role', 'super_admin')->first();
        $setting = $admin?->setting ?? $user->setting ?? new \App\Models\Setting(['app_title' => 'Laporan Keuangan']);

        $walletName = $request->wallet_id === 'all' || !$request->filled('wallet_id')
            ? 'Semua Dompet'
            : $user->wallets()->find($request->wallet_id)?->name ?? 'Semua Dompet';

        $pdf = Pdf::loadView('exports.pdf', [
            'transactions' => $data['transactions'],
            'totalIn' => $data['totalIn'],
            'totalOut' => $data['totalOut'],
            'balance' => $data['balance'],
            'appTitle' => $setting->app_title,
            'appLogo' => $setting->app_logo,
            'walletName' => $walletName,
            'walletName' => $walletName,
            'printDate' => now()->translatedFormat('d F Y H:i'),
            'period' => $this->getPeriodText($request),
        ]);

        return $pdf->stream($setting->app_title . '_' . $walletName . '.pdf');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $user = $request->user();
        $data = $this->getFilteredData($request);

        $admin = \App\Models\User::where('role', 'super_admin')->first();
        $setting = $admin?->setting ?? $user->setting ?? new \App\Models\Setting(['app_title' => 'Laporan Keuangan']);

        $walletName = $request->wallet_id === 'all' || !$request->filled('wallet_id')
            ? 'Semua Dompet'
            : $user->wallets()->find($request->wallet_id)?->name ?? 'Semua Dompet';

        // Calculate Category Statistics
        $categoryStats = $data['transactions']->groupBy(function ($trx) {
            // Group by Type AND Category Name to ensure separation
            return $trx->tipe . '_' . ($trx->category->name ?? 'Lainnya');
        })->map(function ($group) use ($data) {
            $total = $group->sum('jumlah');
            $type = $group->first()->tipe;
            $percentage = $type === 'pemasukan'
                ? ($data['totalIn'] > 0 ? $total / $data['totalIn'] : 0)
                : ($data['totalOut'] > 0 ? $total / $data['totalOut'] : 0);

            return [
                'label' => $group->first()->category->name ?? 'Lainnya',
                'total' => $total,
                'percentage' => $percentage,
                'type' => $type
            ];
        })->values()->sortByDesc('total');

        return Excel::download(
            new TransactionsExport(
                $data['transactions'],
                $data['totalIn'],
                $data['totalOut'],
                $data['balance'],
                $data['initialBalance'],
                $setting->app_title,
                $walletName,
                $this->getPeriodText($request),
                $categoryStats
            ),
            $setting->app_title . '_' . $walletName . '.xlsx'
        );
    }

    private function getFilteredData(Request $request): array
    {
        $user = $request->user();
        $query = $user->transactions()->with(['wallet', 'category']);

        if ($request->filled('wallet_id') && $request->wallet_id !== 'all') {
            $query->where('wallet_id', $request->wallet_id);
        }

        if ($request->filled('search')) {
            $query->where('keterangan', 'like', '%' . $request->search . '%');
        }

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

        // Initial Balance Calculation
        $initialBalance = 0;
        $previousQuery = $user->transactions();

        if ($request->filled('wallet_id') && $request->wallet_id !== 'all') {
            $previousQuery->where('wallet_id', $request->wallet_id);
        }

        if ($request->filled('filter_type')) {
            switch ($request->filter_type) {
                case 'daily':
                    $previousQuery->whereDate('tanggal', '<', $request->filter_date ?? now()->toDateString());
                    break;
                case 'monthly':
                    if ($request->filled('filter_month')) {
                        $date = \Carbon\Carbon::createFromFormat('Y-m', $request->filter_month)->startOfMonth();
                        $previousQuery->whereDate('tanggal', '<', $date);
                    }
                    break;
                case 'yearly':
                    if ($request->filled('filter_year')) {
                        $date = \Carbon\Carbon::createFromDate($request->filter_year, 1, 1)->startOfYear();
                        $previousQuery->whereDate('tanggal', '<', $date);
                    }
                    break;
            }

            $initialBalanceModel = $previousQuery->selectRaw('
                SUM(CASE WHEN tipe = "pemasukan" THEN jumlah ELSE 0 END) as total_in,
                SUM(CASE WHEN tipe = "pengeluaran" THEN jumlah ELSE 0 END) as total_out
            ')->first();

            $initialBalance = ($initialBalanceModel->total_in ?? 0) - ($initialBalanceModel->total_out ?? 0);
        }

        $transactions = $query->orderBy('tanggal', 'asc')->get();

        $balance = $initialBalance;
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

        return compact('transactions', 'totalIn', 'totalOut', 'balance', 'initialBalance');
    }

    private function getPeriodText(Request $request): string
    {
        if ($request->filter_type === 'daily') {
            return \Carbon\Carbon::parse($request->filter_date ?? now())->translatedFormat('d F Y');
        }

        if ($request->filter_type === 'monthly' && $request->filled('filter_month')) {
            return \Carbon\Carbon::createFromFormat('Y-m', $request->filter_month)->translatedFormat('F Y');
        }

        if ($request->filter_type === 'yearly' && $request->filled('filter_year')) {
            return 'Tahun ' . $request->filter_year;
        }

        return 'Semua Periode';
    }
}
