<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected Collection $transactions;
    protected float $totalIn;
    protected float $totalOut;
    protected float $balance;
    protected string $appTitle;
    protected string $walletName;

    public function __construct(
        Collection $transactions,
        float $totalIn,
        float $totalOut,
        float $balance,
        string $appTitle,
        string $walletName
    ) {
        $this->transactions = $transactions;
        $this->totalIn = $totalIn;
        $this->totalOut = $totalOut;
        $this->balance = $balance;
        $this->appTitle = $appTitle;
        $this->walletName = $walletName;
    }

    public function collection(): Collection
    {
        $data = $this->transactions->map(function ($trx, $index) {
            return [
                'no' => $index + 1,
                'dompet' => $trx->wallet->name,
                'tanggal' => $trx->tanggal->translatedFormat('d M Y'),
                'keterangan' => $trx->keterangan,
                'masuk' => $trx->tipe === 'pemasukan' ? $trx->jumlah : 0,
                'keluar' => $trx->tipe === 'pengeluaran' ? $trx->jumlah : 0,
                'saldo' => $trx->saldo,
            ];
        });

        // Add total row
        $data->push([
            'no' => 'TOTAL',
            'dompet' => '',
            'tanggal' => '',
            'keterangan' => '',
            'masuk' => $this->totalIn,
            'keluar' => $this->totalOut,
            'saldo' => $this->balance,
        ]);

        return $data;
    }

    public function headings(): array
    {
        return ['No', 'Dompet', 'Tanggal', 'Keterangan', 'Masuk', 'Keluar', 'Saldo'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return $this->walletName;
    }
}
