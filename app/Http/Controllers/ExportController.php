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
        $user = auth()->user();
        $data = $this->getFilteredData($request);

        $setting = $user->setting ?? $user->setting()->create(['app_title' => 'Laporan Keuangan']);
        $walletName = $request->wallet_id === 'all' || !$request->filled('wallet_id')
            ? 'Semua Dompet'
            : $user->wallets()->find($request->wallet_id)?->name ?? 'Semua Dompet';

        $pdf = Pdf::loadView('exports.pdf', [
            'transactions' => $data['transactions'],
            'totalIn' => $data['totalIn'],
            'totalOut' => $data['totalOut'],
            'balance' => $data['balance'],
            'appTitle' => $setting->app_title,
            'walletName' => $walletName,
            'printDate' => now()->translatedFormat('d F Y'),
        ]);

        return $pdf->download($setting->app_title . '_' . $walletName . '.pdf');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $user = auth()->user();
        $data = $this->getFilteredData($request);

        $setting = $user->setting ?? $user->setting()->create(['app_title' => 'Laporan Keuangan']);
        $walletName = $request->wallet_id === 'all' || !$request->filled('wallet_id')
            ? 'Semua Dompet'
            : $user->wallets()->find($request->wallet_id)?->name ?? 'Semua Dompet';

        return Excel::download(
            new TransactionsExport(
                $data['transactions'],
                $data['totalIn'],
                $data['totalOut'],
                $data['balance'],
                $setting->app_title,
                $walletName
            ),
            $setting->app_title . '_' . $walletName . '.xlsx'
        );
    }

    private function getFilteredData(Request $request): array
    {
        $user = auth()->user();
        $query = $user->transactions()->with('wallet');

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

        $transactions = $query->orderBy('tanggal', 'asc')->get();

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

        return compact('transactions', 'totalIn', 'totalOut', 'balance');
    }
}
