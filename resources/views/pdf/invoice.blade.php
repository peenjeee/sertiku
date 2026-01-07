<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #333;
            background: #fff;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            /* Align to top to handle height differences */
            margin-bottom: 40px;
            border-bottom: 3px solid #3B82F6;
            padding-bottom: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            /* Center icon and text vertically */
            font-size: 28px;
            font-weight: bold;
            color: #1E3A8F;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            font-size: 24px;
            color: #10B981;
            margin-bottom: 12px;
            /* Increased separation */
            margin-top: 5px;
            /* Align with logo text */
        }

        .invoice-title .status {
            display: inline-block;
            background: #10B981;
            color: white;
            padding: 8px 20px;
            /* More padding */
            border-radius: 20px;
            font-size: 13px;
            /* Slightly larger text */
            font-weight: bold;
            vertical-align: middle;
        }

        .info-section {
            margin-bottom: 30px;
        }

        /* Use table layout for better DomPDF compatibility than flex */
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            /* Increased separation between rows */
        }

        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
            /* Space between columns */
        }

        .info-label {
            color: #666;
            font-size: 11px;
            /* Slightly smaller for contrast */
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 8px;
            /* More space between label and value */
            text-transform: uppercase;
        }

        .info-value {
            font-weight: bold;
            font-size: 14px;
            line-height: 1.5;
            /* Better line height for multi-line text */
            color: #1f2937;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }

        .table th {
            background: #1E3A8F;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 12px;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            background: #f8f9fa;
            font-weight: bold;
        }

        .total-row td {
            font-size: 16px;
            color: #1E3A8F;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .footer a {
            color: #3B82F6;
            text-decoration: none;
        }

        .notes {
            background: #f0f9ff;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 12px;
            color: #0369a1;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                {{-- SertiKu Logo Image --}}
                <img src="{{ public_path('favicon.svg') }}" width="40" height="40" alt="Logo"
                    style="vertical-align: middle; margin-right: 8px;">
                <span style="font-size: 28px; font-weight: bold; color: #1E3A8F;">  SertiKu</span>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <span class="status">
                    {{-- Checkmark Icon (Base64 SVG) --}}
                    LUNAS
                </span>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-col">
                    <div class="info-label">Invoice Number</div>
                    <div class="info-value">{{ $order->order_number }}</div>
                </div>
                <div class="info-col">
                    <div class="info-label">Tanggal Pembayaran</div>
                    <div class="info-value">
                        {{ $order->paid_at ? $order->paid_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-col">
                    <div class="info-label">Kepada</div>
                    <div class="info-value">{{ $order->name }}</div>
                    <div style="color: #666; font-size: 12px;">{{ $order->email }}</div>
                    @if($order->institution)
                        <div style="color: #666; font-size: 12px;">{{ $order->institution }}</div>
                    @endif
                </div>
                <div class="info-col">
                    <div class="info-label">Metode Pembayaran</div>
                    <div class="info-value">
                        @php
                            $paymentType = $order->payment_type ?? 'Online Payment';
                            if (Str::contains($paymentType, ['snap', 'midtrans'])) {
                                echo 'Online Payment (Midtrans)';
                            } else {
                                echo ucfirst(str_replace('_', ' ', $paymentType));
                            }
                        @endphp
                    </div>
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60%">Deskripsi</th>
                    <th style="width: 20%; text-align: center;">Qty</th>
                    <th style="width: 20%; text-align: right;">Harga</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Paket {{ $order->package->name ?? 'Premium' }}</strong><br>
                        <span style="font-size: 12px; color: #666;">Langganan Bulanan SertiKu</span>
                    </td>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: right;">{{ $order->formatted_amount }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">Total</td>
                    <td style="text-align: right;">{{ $order->formatted_amount }}</td>
                </tr>
            </tbody>
        </table>

        <div class="notes">
            <strong>Catatan:</strong><br>
            Paket Anda sudah aktif dan siap digunakan. Akses dashboard Anda di: {{ config('app.url') }}/lembaga
        </div>

        <div class="footer">
            <p>Terima kasih telah berlangganan SertiKu!</p>
            <p style="margin-top: 10px;">
                <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
            </p>
            <p style="margin-top: 10px; color: #999;">
                Invoice Pembayaran SertiKu.
            </p>
        </div>
    </div>
</body>

</html>