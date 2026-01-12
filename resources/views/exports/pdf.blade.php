<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $appTitle }}</title>
    <style>
        @page { margin: 20px 25px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #555691; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; color: #1e293b; letter-spacing: 1px; }
        .header .subtitle { font-size: 12px; color: #64748b; margin-top: 5px; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { border: none; padding: 2px; width: auto; font-size: 11px; color: #475569; }
        .info-label { font-weight: bold; width: 80px; }

        .transactions-table { width: 100%; border-collapse: collapse; border-radius: 8px; overflow: hidden; }
        .transactions-table th, .transactions-table td { padding: 8px 10px; text-align: left; vertical-align: middle; border-bottom: 1px solid #e2e8f0; }
        
        .transactions-table th { 
            background-color: #555691; 
            color: #ffffff; 
            font-size: 10px; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
            border: none;
        }

        .transactions-table tr:nth-child(even) { background-color: #f8fafc; }
        .transactions-table tr:hover { background-color: #f1f5f9; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .amount-in { color: #10b981; font-weight: 600; }
        .amount-out { color: #ef4444; font-weight: 600; }
        .amount-balance { color: #1e293b; font-weight: 700; }
        .amount-balance.negative { color: #ef4444; }
        
        .total-row td { 
            background-color: #eef2ff; 
            color: #1e293b;
            font-weight: 800; 
            border-top: 2px solid #555691;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        @if(isset($appLogo) && $appLogo && file_exists(public_path($appLogo)))
            <img src="{{ public_path($appLogo) }}" style="height: 50px; margin-bottom: 10px;">
        @else
            <div style="font-size: 24px; font-weight: bold; color: #555691; margin-bottom: 5px; letter-spacing: 2px;">[ LOGO ]</div>
        @endif
        <h1>{{ $appTitle }}</h1>
        <div class="subtitle">Laporan Keuangan & Transaksi</div>
    </div>
    
    <table class="info-table">
        <tr>
            <td class="info-label">Dompet</td>
            <td>: {{ $walletName }}</td>
            <td class="info-label text-right">Dicetak</td>
            <td class="text-right">: {{ $printDate }}</td>
        </tr>
        <tr>
            <td class="info-label">Periode</td>
            <td>: {{ $period }}</td>
            <td colspan="2"></td>
        </tr>
    </table>

    <table class="transactions-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px">No</th>
                <th style="width: 65px">Tanggal</th>
                <th style="width: 80px">Dompet</th>
                <th style="width: 90px">Kategori</th>
                <th>Keterangan</th>
                <th class="text-right" style="width: 85px">Masuk</th>
                <th class="text-right" style="width: 85px">Keluar</th>
                <th class="text-right" style="width: 90px">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $trx)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $trx->tanggal->format('d/m/y') }}</td>
                <td>{{ $trx->wallet->name }}</td>
                <td>{{ $trx->category->name ?? '-' }}</td>
                <td>{{ $trx->keterangan }}</td>
                <td class="text-right amount-in">{{ $trx->tipe === 'pemasukan' ? 'Rp ' . number_format($trx->jumlah, 0, ',', '.') : '-' }}</td>
                <td class="text-right amount-out">{{ $trx->tipe === 'pengeluaran' ? 'Rp ' . number_format($trx->jumlah, 0, ',', '.') : '-' }}</td>
                <td class="text-right amount-balance {{ $trx->saldo < 0 ? 'negative' : '' }}">Rp {{ number_format($trx->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="text-center">TOTAL PERIODE INI</td>
                <td class="text-right amount-in">Rp {{ number_format($totalIn, 0, ',', '.') }}</td>
                <td class="text-right amount-out">Rp {{ number_format($totalOut, 0, ',', '.') }}</td>
                <td class="text-right amount-balance">Rp {{ number_format($balance, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
