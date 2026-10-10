<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #111;
            margin: 0;
            padding: 10px;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .divider {
            border-top: 1px dashed #333;
            margin: 6px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 2px 0;
            vertical-align: top;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid #333;
            border-radius: 4px;
            font-size: 10px;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="center bold" style="font-size:14px;">Four Stripes and Machine Corporation</div>
    <div class="center" style="font-size:10px;"> YLG Bldg Door 5, McArthur Highway, Bago Aplaya, Davao City, Philippines</div>
    <div class="center" style="font-size:10px;">+63 919 287 2915</div>

    <div class="divider"></div>

    <table>
        <tr>
            <td>Receipt #</td>
            <td class="right">{{ $sale->id }}</td>
        </tr>
        <tr>
            <td>Date</td>
            <td class="right">{{ $sale->created_at->format('M d, Y h:i A') }}</td>
        </tr>
        <tr>
            <td>Cashier</td>
            <td class="right">{{ $sale->user->getFullNameAttribute() ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td>Customer</td>
            <td class="right">{{ $sale->customer->name ?? '-' }}</td>
        </tr>
        @if($sale->customer->phone_number ?? false)
        <tr>
            <td>Phone</td>
            <td class="right">{{ $sale->customer->phone_number }}</td>
        </tr>
        @endif
        <tr>
            <td>Address</td>
            <td class="right">{{ $sale->customer->address ?? '-' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <thead>
            <tr class="bold">
                <td>Item</td>
                <td class="right">Qty</td>
                <td class="right">Price</td>
                <td class="right">Subtotal</td>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>
                        {{ $item->item_name }}<br>
                        <span style="font-size:9px; color:#666;">{{ $item->item_code }}</span>
                    </td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">{{ number_format($item->price, 2) }}</td>
                    <td class="right">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table>
        <tr class="bold" style="font-size:13px;">
            <td>Total</td>
            <td class="right">&#8369; {{ number_format($sale->total, 2) }}</td>
        </tr>
        <tr>
            <td>Cash Received</td>
            <td class="right">&#8369; {{ number_format($sale->cash_received, 2) }}</td>
        </tr>
        <tr>
            <td>Change</td>
            <td class="right">&#8369; {{ number_format($sale->change, 2) }}</td>
        </tr>
    </table>

    @if($sale->notes)
        <div class="divider"></div>
        <div>
            <span class="bold">Notes:</span><br>
            {{ $sale->notes }}
        </div>
    @endif

    <div class="divider"></div>

    <div class="footer">
        This is a temporary receipt. Please keep this for your records. Thank you for your purchase!
    </div>

</body>
</html>