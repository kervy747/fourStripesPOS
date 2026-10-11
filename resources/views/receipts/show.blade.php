<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #111;
            margin: 0;
            padding: 2px;
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
            padding: 3px 0;
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
            margin-top: 6px;
            text-align: center;
            font-size: 8px;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="center bold" style="font-size:11px;">Four Stripes and Machine Corporation</div>
    <div class="center" style="font-size:8px;">YLG Bldg Door 5, McArthur Highway, Bago Aplaya, Davao City, Philippines</div>

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
                <td style="width:35%;">Item</td>
                <td class="right" style="width:15%;">Qty</td>
                <td class="right" style="width:22%;">Price</td>
                <td class="right" style="width:28%;">Subtotal</td>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>
                        <span style="font-size:7px;">{{ $item->item_name }}</span><br>
                        <span style="font-size:6px; color:#666;">{{ $item->item_code }}</span>
                    </td>
                    <td class="right" style="font-size:7px;">{{ $item->quantity }}</td>
                    <td class="right" style="font-size:7px;">{{ number_format($item->price, 2) }}</td>
                    <td class="right" style="font-size:7px;">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    @php
        $vatableAmount = $sale->total / 1.12;
        $vatAmount     = $sale->total - $vatableAmount;
    @endphp

    <table>
        <tr>
            <td>VATable Sales</td>
            <td class="right">&#8369; {{ number_format($vatableAmount, 2) }}</td>
        </tr>
        <tr>
            <td>VAT 12%</td>
            <td class="right">&#8369; {{ number_format($vatAmount, 2) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <tr class="bold" style="font-size:10px;">
            <td>Total (VAT Incl.)</td>
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
        This is a temporary receipt. Please keep this for your records. Thank you for your purchase!<br>
        Contact: +63 919 287 2915
    </div>

</body>
</html>