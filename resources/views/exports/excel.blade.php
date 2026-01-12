<table>
    <thead>
        <tr>
            <th colspan="8" style="font-weight: bold; font-size: 14px; text-align: center; vertical-align: middle; height: 30px;">
                {{ strtoupper($appTitle ?? 'APLIKASI KEUANGAN') }}
            </th>
        </tr>
        <tr>
            <th colspan="8" style="font-weight: bold; font-size: 12px; text-align: center;">
                LAPORAN KEUANGAN: {{ strtoupper($walletName ?? 'SEMUA DOMPET') }}
            </th>
        </tr>
        <tr>
            <th colspan="8" style="font-weight: bold; text-align: center; font-style: italic; color: #555555;">
                Periode: {{ $period }} | Cetak: {{ now()->translatedFormat('d F Y H:i') }}
            </th>
        </tr>
        <tr><td colspan="8"></td></tr> <tr>
            <th colspan="8" style="font-weight: bold; text-decoration: underline;">RANGKUMAN KEUANGAN</th>
        </tr>
        <tr>
            <td colspan="2" style="font-weight: bold;">Saldo Awal</td>
            <td colspan="6" style="text-align: left;">: Rp {{ number_format($initialBalance, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight: bold;">Total Pemasukan</td>
            <td colspan="6" style="text-align: left;">: Rp {{ number_format($totalIn, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight: bold;">Total Pengeluaran</td>
            <td colspan="6" style="text-align: left;">: Rp {{ number_format($totalOut, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight: bold; background-color: #e3e3e3; border: 1px solid #000000;">Saldo Akhir</td>
            <td colspan="6" style="font-weight: bold; background-color: #e3e3e3; border: 1px solid #000000; text-align: left;">
                : Rp {{ number_format($balance, 0, ',', '.') }}
            </td>
        </tr>
        <tr><td colspan="8"></td></tr> </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="width: 5px;"></th>

            <th colspan="3" style="font-weight: bold; text-align: center; background-color: #d1e7dd; border: 1px solid #000000; color: #0f5132;">
                STATISTIK PEMASUKAN
            </th>

            <th style="width: 25px; border: none; background-color: #ffffff;"></th>

            <th colspan="3" style="font-weight: bold; text-align: center; background-color: #f8d7da; border: 1px solid #000000; color: #842029;">
                STATISTIK PENGELUARAN
            </th>
        </tr>
        <tr>
            <th></th> <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">Kategori</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">Total</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">%</th>

            <th style="border: none; background-color: #ffffff;"></th>

            <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">Kategori</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">Total</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; background-color: #f0f0f0;">%</th>
        </tr>
    </thead>
    <tbody>
        @php
            $incomeStats = $categoryStats->where('type', 'pemasukan')->values();
            $expenseStats = $categoryStats->where('type', 'pengeluaran')->values();
            $maxRows = max($incomeStats->count(), $expenseStats->count());
        @endphp

        @for($i = 0; $i < $maxRows; $i++)
        <tr>
            <td></td> @if(isset($incomeStats[$i]))
                <td style="border: 1px solid #000;">{{ $incomeStats[$i]['label'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">
                    Rp {{ number_format($incomeStats[$i]['total'], 0, ',', '.') }}
                </td>
                <td style="border: 1px solid #000; text-align: center;">
                    {{ number_format($incomeStats[$i]['percentage'] < 1 ? $incomeStats[$i]['percentage'] * 100 : $incomeStats[$i]['percentage'], 2) }}%
                </td>
            @else
                <td style="border: 1px solid #000;"></td><td style="border: 1px solid #000;"></td><td style="border: 1px solid #000;"></td>
            @endif

            <td style="border: none; background-color: #ffffff;"></td>

            @if(isset($expenseStats[$i]))
                <td style="border: 1px solid #000;">{{ $expenseStats[$i]['label'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">
                    Rp {{ number_format($expenseStats[$i]['total'], 0, ',', '.') }}
                </td>
                <td style="border: 1px solid #000; text-align: center;">
                    {{ number_format($expenseStats[$i]['percentage'] < 1 ? $expenseStats[$i]['percentage'] * 100 : $expenseStats[$i]['percentage'], 2) }}%
                </td>
            @else
                <td style="border: 1px solid #000;"></td><td style="border: 1px solid #000;"></td><td style="border: 1px solid #000;"></td>
            @endif
        </tr>
        @endfor
        <tr><td colspan="8"></td></tr> </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="8" style="font-weight: bold; background-color: #cfe2ff; text-align: center; border: 1px solid #000000; font-size: 12px; height: 25px; vertical-align: middle;">
                DETAIL RIWAYAT TRANSAKSI
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000; width: 5px; background-color: #f0f0f0;">No</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000; width: 12px; background-color: #f0f0f0;">Tanggal</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000; width: 20px; background-color: #f0f0f0;">Dompet</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000; width: 20px; background-color: #f0f0f0;">Kategori</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000; width: 15px; background-color: #f0f0f0;">Tipe</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000; width: 35px; background-color: #f0f0f0;">Keterangan</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000; width: 18px; background-color: #f0f0f0;">Jumlah</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000; width: 18px; background-color: #f0f0f0;">Saldo</th>
        </tr>
    </thead>
<tbody>
        @foreach($transactions as $index => $trx)
        <tr>
            <td style="font-weight: normal; text-align: center; border: 1px solid #000; vertical-align: top;">
                {{ $index + 1 }}
            </td>
            
            <td style="font-weight: normal; text-align: center; border: 1px solid #000; vertical-align: top;">
                {{ $trx->tanggal->format('d/m/Y') }}
            </td>
            
            <td style="font-weight: normal; border: 1px solid #000; vertical-align: top;">
                {{ $trx->wallet->name ?? '-' }}
            </td>
            
            <td style="font-weight: normal; border: 1px solid #000; vertical-align: top;">
                {{ $trx->category->name ?? 'Lainnya' }}
            </td>
            
            <td style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: top; color: {{ strtolower($trx->tipe) == 'pemasukan' ? '#198754' : '#dc3545' }}">
                {{ ucfirst($trx->tipe) }}
            </td>

            <td style="font-weight: normal; border: 1px solid #000; vertical-align: top;">
                {{ $trx->keterangan }}
            </td>
            
            <td style="font-weight: normal; text-align: right; border: 1px solid #000; vertical-align: top;">
                Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
            </td>
            
            <td style="font-weight: normal; text-align: right; border: 1px solid #000; vertical-align: top;">
                Rp {{ number_format($trx->saldo, 0, ',', '.') }}
            </td>
        </tr>
        @endforeach

        <tr>
            <td colspan="6" style="font-weight: bold; text-align: right; border: 1px solid #000; background-color: #e2e3e5; height: 25px; vertical-align: middle;">
                SISA SALDO SAAT INI
            </td>
            <td colspan="2" style="font-weight: bold; text-align: left; border: 1px solid #000; background-color: #e2e3e5; vertical-align: middle; color: #000;">
                 Rp {{ number_format($balance, 0, ',', '.') }}
            </td>
        </tr>
    </tbody>
</table>