<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\WithColumnWidths;

class TransactionsExport implements FromView, ShouldAutoSize, WithTitle, WithEvents, WithColumnFormatting, WithColumnWidths
{
    protected Collection $transactions;
    protected float $totalIn;
    protected float $totalOut;
    protected float $balance;
    protected float $initialBalance;
    protected string $appTitle;
    protected string $walletName;
    protected string $period;
    protected Collection $categoryStats;

    public function __construct(
        Collection $transactions,
        float $totalIn,
        float $totalOut,
        float $balance,
        float $initialBalance,
        string $appTitle,
        string $walletName,
        string $period,
        Collection $categoryStats
    ) {
        $this->transactions = $transactions;
        $this->totalIn = $totalIn;
        $this->totalOut = $totalOut;
        $this->balance = $balance;
        $this->initialBalance = $initialBalance;
        $this->appTitle = $appTitle;
        $this->walletName = $walletName;
        $this->period = $period;
        $this->categoryStats = $categoryStats;
    }

    public function view(): View
    {
        return view('exports.excel', [
            'transactions' => $this->transactions,
            'totalIn' => $this->totalIn,
            'totalOut' => $this->totalOut,
            'balance' => $this->balance,
            'initialBalance' => $this->initialBalance,
            'appTitle' => $this->appTitle,
            'walletName' => $this->walletName,
            'period' => $this->period,
            'categoryStats' => $this->categoryStats
        ]);
    }

    public function title(): string
    {
        return $this->walletName;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,  // No
            'B' => 14, // Date / Income Label
            'C' => 20, // Wallet / Income Total
            'D' => 20, // Category / Income %
            'E' => 12, // Type / Spacing
            'F' => 30, // Description / Expense Label (Wide)
            'G' => 20, // Amount / Expense Total
            'H' => 20, // Balance / Expense % 
        ];
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Calculate Stats Rows
                $incomeStatsCount = $this->categoryStats->where('type', 'pemasukan')->count();
                $expenseStatsCount = $this->categoryStats->where('type', 'pengeluaran')->count();
                $maxStatsRows = max($incomeStatsCount, $expenseStatsCount);

                // Row Pointers
                $statsStartRow = 13;
                $statsEndRow = $maxStatsRows > 0 ? $statsStartRow + $maxStatsRows - 1 : $statsStartRow;
                $transHeaderRow = $statsEndRow + 3;
                $transStartRow = $transHeaderRow + 1;
                $transEndRow = $transStartRow + $this->transactions->count() - 1;
                $totalRow = $transEndRow > $transStartRow ? $transEndRow + 1 : $transStartRow + 1;

                // --- 1. FORMATTING (Currency & Percent) ---
                $currencyFormat = '_("Rp"* #,##0_);_("Rp"* (#,##0);_("Rp"* "-"_);_(@_)';

                // Summary Section
                $sheet->getStyle('C6:C9')->getNumberFormat()->setFormatCode($currencyFormat);

                // Stats Section
                if ($maxStatsRows > 0) {
                    $sheet->getStyle("C{$statsStartRow}:C{$statsEndRow}")->getNumberFormat()->setFormatCode($currencyFormat); // Inc Total
                    $sheet->getStyle("D{$statsStartRow}:D{$statsEndRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00); // Inc %
                    $sheet->getStyle("G{$statsStartRow}:G{$statsEndRow}")->getNumberFormat()->setFormatCode($currencyFormat); // Exp Total
                    $sheet->getStyle("H{$statsStartRow}:H{$statsEndRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00); // Exp %
                }

                // Transactions Section
                if ($this->transactions->count() > 0) {
                    $sheet->getStyle("G{$transStartRow}:G{$transEndRow}")->getNumberFormat()->setFormatCode($currencyFormat); // Amount (Col G)
                    $sheet->getStyle("H{$transStartRow}:H{$transEndRow}")->getNumberFormat()->setFormatCode($currencyFormat); // Balance (Col H)
                }

                // Final Total
                $sheet->getStyle("G{$totalRow}")->getNumberFormat()->setFormatCode($currencyFormat);

                // --- 2. STYLING & BORDERS ---

                // Universal Alignment
                $sheet->getStyle('A:H')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A:H')->getAlignment()->setWrapText(true);

                // Base Header Style
                $headerStyle = [
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                ];

                // 2.1 Summary Header
                $sheet->getStyle('A5:H5')->applyFromArray(array_merge($headerStyle, [
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']]
                ]));

                // 2.2 Stats Headers
                // Income: B-D
                $sheet->getStyle("B11:D11")->applyFromArray(array_merge($headerStyle, [
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD1FAE5']]
                ]));
                // Expense: F-H
                $sheet->getStyle("F11:H11")->applyFromArray(array_merge($headerStyle, [
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFEE2E2']]
                ]));
                // Sub-headers
                $sheet->getStyle("B12:D12")->applyFromArray($headerStyle);
                $sheet->getStyle("F12:H12")->applyFromArray($headerStyle);

                // 2.3 Transaction Headers
                $sheet->getStyle("A" . ($transHeaderRow - 1) . ":H" . ($transHeaderRow - 1))->applyFromArray(array_merge($headerStyle, [
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE5E7EB']]
                ]));
                $sheet->getStyle("A{$transHeaderRow}:H{$transHeaderRow}")->applyFromArray(array_merge($headerStyle, [
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']]
                ]));

                // Total Row Style
                $sheet->getStyle("A{$totalRow}:H{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN]]
                ]);

                // --- 3. BORDERS ---
                $borderStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];

                // Stats Border
                if ($maxStatsRows > 0) {
                    $sheet->getStyle("B11:D{$statsEndRow}")->applyFromArray($borderStyle); // Income
                    $sheet->getStyle("F11:H{$statsEndRow}")->applyFromArray($borderStyle); // Expense
                } else {
                    $sheet->getStyle("B11:D12")->applyFromArray($borderStyle);
                    $sheet->getStyle("F11:H12")->applyFromArray($borderStyle);
                }

                // Summary Border (A5:H9)
                $sheet->getStyle('A5:H9')->applyFromArray($borderStyle);

                // Stats Alignment
                if ($maxStatsRows > 0) {
                    // Categs, %, Amounts -> Center
                    $sheet->getStyle("B{$statsStartRow}:D{$statsEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("F{$statsStartRow}:H{$statsEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Transaction Border
                $sheet->getStyle("A" . ($transHeaderRow - 1) . ":H{$totalRow}")->applyFromArray($borderStyle);

                // Transaction Alignment
                // Center: No, Date, Wallet, Category, Type
                $sheet->getStyle("A{$transStartRow}:E{$transEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Left: Description (F)
                $sheet->getStyle("F{$transStartRow}:F{$transEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Center: Amount (G), Balance (H)
                $sheet->getStyle("G{$transStartRow}:H{$transEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Total Row Alignment
                $sheet->getStyle("G{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
