<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .invoice-header {
            margin-bottom: 30px;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 20px;
        }
        
        .invoice-header h1 {
            color: #4f46e5;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .invoice-header .store-info {
            color: #666;
            font-size: 11px;
        }
        
        .invoice-meta {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .invoice-meta .left,
        .invoice-meta .right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .invoice-meta .right {
            text-align: right;
        }
        
        .invoice-meta h3 {
            font-size: 14px;
            color: #4f46e5;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .invoice-meta p {
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .invoice-meta .label {
            color: #666;
            display: inline-block;
            width: 120px;
        }
        
        .invoice-meta .value {
            font-weight: bold;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-shipping {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table thead {
            background-color: #f3f4f6;
        }
        
        .items-table th {
            padding: 12px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            color: #374151;
            text-transform: uppercase;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .items-table th.text-center {
            text-align: center;
        }
        
        .items-table th.text-right {
            text-align: right;
        }
        
        .items-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        .items-table td {
            padding: 12px 10px;
            font-size: 11px;
        }
        
        .items-table td.text-center {
            text-align: center;
        }
        
        .items-table td.text-right {
            text-align: right;
        }
        
        .items-table .product-name {
            font-weight: 600;
            color: #111827;
        }
        
        .summary-table {
            width: 300px;
            margin-left: auto;
            margin-bottom: 30px;
        }
        
        .summary-table tr td {
            padding: 8px 10px;
            font-size: 11px;
        }
        
        .summary-table tr td:first-child {
            color: #666;
        }
        
        .summary-table tr td:last-child {
            text-align: right;
            font-weight: 600;
        }
        
        .summary-table .discount-row td {
            color: #059669;
        }
        
        .summary-table .total-row {
            border-top: 2px solid #e5e7eb;
            font-size: 14px;
        }
        
        .summary-table .total-row td {
            padding-top: 12px;
            font-weight: bold;
        }
        
        .summary-table .total-row td:last-child {
            color: #4f46e5;
            font-size: 16px;
        }
        
        .shipping-address {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 30px;
        }
        
        .shipping-address h3 {
            font-size: 12px;
            color: #4f46e5;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .shipping-address p {
            font-size: 11px;
            line-height: 1.8;
            white-space: pre-line;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .footer p {
            margin-bottom: 5px;
        }
        
        .notes {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .notes h4 {
            font-size: 12px;
            color: #92400e;
            margin-bottom: 8px;
        }
        
        .notes p {
            font-size: 10px;
            color: #78350f;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    {{-- Invoice Header --}}
    <div class="invoice-header">
        <h1>INVOICE</h1>
        <div class="store-info">
            <strong>{{ $storeName }}</strong><br>
            @if($storeAddress)
                {{ $storeAddress }}<br>
            @endif
            @if($storePhone)
                Telp: {{ $storePhone }}
            @endif
            @if($storeEmail)
                | Email: {{ $storeEmail }}
            @endif
        </div>
    </div>

    {{-- Invoice Meta Information --}}
    <div class="invoice-meta">
        <div class="left">
            <h3>Informasi Pesanan</h3>
            <p><span class="label">No. Invoice:</span> <span class="value">#{{ $order->order_number }}</span></p>
            <p><span class="label">Tanggal Pesanan:</span> <span class="value">{{ $order->created_at->format('d F Y, H:i') }}</span></p>
            @if($order->paid_at)
                <p><span class="label">Tanggal Bayar:</span> <span class="value">{{ $order->paid_at->format('d F Y, H:i') }}</span></p>
            @endif
            <p><span class="label">Metode Pembayaran:</span> <span class="value">{{ ucfirst($order->payment_gateway ?? '-') }}</span></p>
            <p>
                <span class="label">Status:</span>
                @php
                    $statusClass = match($order->status) {
                        'pending' => 'status-pending',
                        'paid' => 'status-paid',
                        'shipping' => 'status-shipping',
                        'completed' => 'status-completed',
                        'cancelled' => 'status-cancelled',
                        default => 'status-pending',
                    };
                    $statusLabel = match($order->status) {
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'shipping' => 'Shipping',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($order->status),
                    };
                @endphp
                <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
            </p>
        </div>
        <div class="right">
            <h3>Informasi Pelanggan</h3>
            <p><span class="value">{{ $order->user->name }}</span></p>
            <p>{{ $order->user->email }}</p>
            @if($order->user->phone)
                <p>{{ $order->user->phone }}</p>
            @endif
        </div>
    </div>

    {{-- Shipping Address --}}
    <div class="shipping-address">
        <h3>Alamat Pengiriman</h3>
        <p>{{ $order->shipping_address }}</p>
        @if($order->tracking_number)
            <p style="margin-top: 10px;"><strong>No. Resi:</strong> {{ $order->tracking_number }}</p>
        @endif
    </div>

    {{-- Order Items Table --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 45%;">Produk</th>
                <th class="text-center" style="width: 15%;">Harga</th>
                <th class="text-center" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 25%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="product-name">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                    <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Price Summary --}}
    <table class="summary-table">
        <tr>
            <td>Subtotal:</td>
            <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($order->discount_amount > 0)
            <tr class="discount-row">
                <td>Diskon:</td>
                <td>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
            <td>Ongkos Kirim:</td>
            <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>TOTAL:</td>
            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- Notes for Pending Orders --}}
    @if($order->status === 'pending')
        <div class="notes">
            <h4>Catatan Penting:</h4>
            <p>
                Invoice ini masih dalam status <strong>PENDING</strong>. Silakan selesaikan pembayaran Anda untuk memproses pesanan.
                Setelah pembayaran dikonfirmasi, pesanan akan segera diproses.
            </p>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p><strong>Terima kasih atas pesanan Anda!</strong></p>
        <p>Invoice ini digenerate secara otomatis pada {{ now()->format('d F Y, H:i') }}</p>
        @if($storeWebsite)
            <p>{{ $storeWebsite }}</p>
        @endif
    </div>
</body>
</html>