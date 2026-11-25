<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Struk {{ $transaction->invoice_number }}</title>
    <style>
        @page { size: 58mm auto; margin: 0; }
        * { box-sizing: border-box; }
        body {
            margin: 0; background: #e5e7eb; color: #000;
            font-family: 'Courier New', monospace;
            font-size: 11px; line-height: 1.35;
        }
        .struk {
            width: 58mm; padding: 3mm 5mm;
            margin: 12px auto; background: #fff;
        }
        .center { text-align: center; }
        .row { display: flex; justify-content: space-between; }
        .name { word-break: break-word; }
        .line { border-top: 1px dashed #000; margin: 4px 0; }
        .actions { text-align: center; margin: 12px; }

        @media print {
            body { background: #fff; }
            .struk { margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="struk">
        <div class="center"><strong>POS BAROKAH MART</strong></div>
        <div class="center">{{ $transaction->invoice_number }}</div>
        <div class="center">{{ $transaction->created_at->format('d/m/Y H:i') }}</div>
        <div class="center">Kasir: {{ $transaction->user->name }}</div>
        <div class="line"></div>

        @foreach ($transaction->details as $detail)
            <div class="name">{{ $detail->product->name }}</div>
            <div class="row">
                <span>{{ $detail->qty }} x {{ number_format($detail->price, 0, ',', '.') }}</span>
                <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
        @endforeach

        <div class="line"></div>
        <div class="row"><strong>Total</strong>
            <strong>{{ number_format($transaction->total, 0, ',', '.') }}</strong></div>
        <div class="row"><span>Bayar</span>
            <span>{{ number_format($transaction->pay, 0, ',', '.') }}</span></div>
        <div class="row"><span>Kembali</span>
            <span>{{ number_format($transaction->change, 0, ',', '.') }}</span></div>
        <div class="line"></div>
        <div class="center">Terima kasih atas kunjungan Anda</div>
    </div>

    <div class="actions no-print">
        <button onclick="window.print()">Cetak</button>
        <a href="{{ route('pos.index') }}">Transaksi Baru</a>
    </div>

    <script>window.addEventListener('load', () => window.print());</script>
</body>
</html>