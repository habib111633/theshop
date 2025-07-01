<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        /* DOMPDF-compatible styles */
        @page { margin: 50px 40px; }
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #333;
            line-height: 1.4;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 1px solid #ff0042;
            padding-bottom: 20px;
        }
        .logo {
            height: 60px;
            margin-bottom: 5px;
        }
        .invoice-title {
            color: #ff0042;
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }
        .invoice-subtitle {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        .company-address {
            font-size: 12px;
            color: #777;
            line-height: 1.6;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 10px;
            vertical-align: top;
        }
        .meta-label {
            font-weight: bold;
            color: #555;
            width: 120px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #ff0042;
            color: white;
            text-align: left;
            padding: 12px 10px;
            font-weight: bold;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }
        .total-row {
            font-weight: bold;
            font-size: 16px;
        }
        .grand-total {
            color: #ff0042;
            font-size: 18px;
            border-top: 2px solid #ff0042;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #777;
            text-align: center;
        }
        .status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending { background: #FFF3CD; color: #856404; }
        .status-completed { background: #D4EDDA; color: #155724; }
        .status-cancelled { background: #F8D7DA; color: #721C24; }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <h1 class="invoice-title">INVOICE</h1>
                    <div class="invoice-subtitle">#{{ $order->id }}</div>
                </td>
                <td align="right">
                                    <!-- Logo with inline width for DOMPDF reliability -->
                    <img src="{{ public_path('images/template-logo.png') }}" 
                    style="width: 150px; height: auto; "
                    alt="{{ config('app.name') }}"> 
                 <div class="company-address">
                123 Business Street<br>
                City, State 10001<br>
                Phone: (123) 456-7890<br>
                Email: info@example.com
                </div>  
             </td>
            </tr>
        </table>
    </div>

    <!-- Rest of your existing content remains exactly the same -->
    <!-- Client and Invoice Info -->
    <table class="meta-table">
        <tr>
            <td>
                <div style="font-weight: bold; margin-bottom: 5px; color: #ff0042;">BILL TO:</div>
                <div>{{ $order->user->name }}</div>
                <div>{{ $order->user->email }}</div>
                @if($order->shipping_address)
                <div>{{ $order->shipping_address }}</div>
                @endif
            </td>
            <td align="right">
                <table>
                    <tr>
                        <td class="meta-label">Invoice Date:</td>
                        <td>{{ $order->created_at->format('M j, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Status:</td>
                        <td><span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    </tr>
                    <tr>
                        <td class="meta-label">Payment Method:</td>
                        <td>{{ ucfirst($order->payment_method) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Order Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="50%">Description</th>
                <th width="15%">Qty</th>
                <th width="20%">Unit Price</th>
                <th width="15%">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <table class="total-table">
        <tr>
            <td>Subtotal:</td>
            <td align="right">${{ number_format($order->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>Shipping:</td>
            <td align="right">${{ number_format($order->shipping_cost, 2) }}</td>
        </tr>
        <tr>
            <td>Tax:</td>
            <td align="right">${{ number_format($order->tax, 2) }}</td>
        </tr>
        <tr class="total-row grand-total">
            <td>TOTAL:</td>
            <td align="right">${{ number_format($order->total, 2) }}</td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div style="margin-bottom: 10px;">Thank you for your business!</div>
        <div>This is a system-generated invoice. Please make payments within 15 days.</div>
        <div style="margin-top: 10px;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
    </div>
</body>
</html>