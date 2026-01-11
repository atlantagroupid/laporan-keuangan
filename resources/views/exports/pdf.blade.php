<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $appTitle }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; border-bottom: 2px solid #333; display: inline-block; padding-bottom: 5px; }
        .info { margin-bottom: 15px; font-size: 11px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #4f46e5; color: white; font-size: 10px; text-transform: uppercase; }
        td { font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #10b981; }
        .text-danger { color: #ef4444; }
        .text-primary { color: #4f46e5; }
        .fw-bold { font-weight: bold; }
        .total-row { background: #f0f0f0; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $appTitle }}</h1>
    </div>
    
    <div class="info">
        <p><strong>Dompet:</strong> {{ $walletName }}</p>
        <p><strong>Dicetak:</strong> {{ $printDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px">No</th>
                <th>Dompet</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th class="text-right">Masuk</th>
                <th class="text-right">Keluar</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $trx)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $trx->wallet->name }}</td>
                <td>{{ $trx->tanggal->translatedFormat('d M Y') }}</td>
                <td>{{ $trx->keterangan }}</td>
                <td class="text-right text-success">{{ $trx->tipe === 'pemasukan' ? number_format($trx->jumlah, 0, ',', '.') : '' }}</td>
                <td class="text-right text-danger">{{ $trx->tipe === 'pengeluaran' ? number_format($trx->jumlah, 0, ',', '.') : '' }}</td>
                <td class="text-right fw-bold {{ $trx->saldo < 0 ? 'text-danger' : 'text-primary' }}">{{ number_format($trx->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-center">TOTAL</td>
                <td class="text-right text-success">{{ number_format($totalIn, 0, ',', '.') }}</td>
                <td class="text-right text-danger">{{ number_format($totalOut, 0, ',', '.') }}</td>
                <td class="text-right text-primary">{{ number_format($balance, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
