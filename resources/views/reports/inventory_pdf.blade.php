<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000000;
            background: #ffffff;
        }

        .page {
            width: 210mm;
            padding: 15mm 15mm 20mm 15mm;
        }

        /* HEADER */
        .header {
            border-bottom: 2px solid #000000;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 4px;
            text-transform: uppercase;
        }

        .report-period {
            font-size: 10px;
            margin-top: 4px;
            color: #333333;
        }

        .report-generated {
            font-size: 10px;
            margin-top: 2px;
            color: #555555;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        thead tr {
            background-color: #000000;
            color: #ffffff;
        }

        thead th {
            padding: 7px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        thead th.text-right {
            text-align: right;
        }

        tbody tr {
            border-bottom: 1px solid #cccccc;
        }

        tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        tbody td {
            padding: 6px 8px;
            font-size: 10px;
            color: #000000;
        }

        tbody td.text-right {
            text-align: right;
        }

        /* FOOTER */
        .footer {
            position: fixed;
            bottom: 10mm;
            left: 15mm;
            right: 15mm;
            border-top: 1px solid #000000;
            padding-top: 6px;
            font-size: 9px;
            color: #555555;
            display: flex;
            justify-content: space-between;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            font-size: 11px;
            color: #666666;
        }
    </style>
</head>
<body>
    <div class="page">

        {{-- HEADER --}}
        <div class="header">
            <div class="company-name">FourStripes</div>
            <div class="report-title">Inventory Report</div>

            @if ($dateFrom && $dateTo)
                <div class="report-period">
                    Period: {{ \Carbon\Carbon::parse($dateFrom)->format('F j, Y') }}
                    &ndash;
                    {{ \Carbon\Carbon::parse($dateTo)->format('F j, Y') }}
                </div>
            @else
                <div class="report-period">Period: All Time</div>
            @endif

            <div class="report-generated">
                Generated: {{ now()->format('F j, Y \a\t g:i A') }}
            </div>
        </div>

        {{-- INVENTORY TABLE --}}
        <table>
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Item</th>
                    <th class="text-right">Beginning Balance</th>
                    <th class="text-right">Stock In</th>
                    <th class="text-right">Stock Out</th>
                    <th class="text-right">Remaining Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    @php
                        $totalStockIn  = $stockIn->get($product->id)?->sum('quantity_added') ?? 0;
                        $totalStockOut = $stockOut->get($product->id)?->sum('quantity') ?? 0;
                        $beginning     = $beginningBalances[$product->id] ?? $product->quantity;
                    @endphp
                    <tr>
                        <td>{{ $product->item_code }}</td>
                        <td>{{ $product->name }}</td>
                        <td class="text-right">{{ $beginning }}</td>
                        <td class="text-right">{{ $totalStockIn }}</td>
                        <td class="text-right">{{ $totalStockOut }}</td>
                        <td class="text-right">{{ $product->quantity }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-data">No inventory data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    {{-- FOOTER --}}
    {{-- <div class="footer">
        <span>FourStripes &mdash; Inventory Report</span>
        @if ($dateFrom && $dateTo)
            <span>{{ \Carbon\Carbon::parse($dateFrom)->format('M j, Y') }} &ndash; {{ \Carbon\Carbon::parse($dateTo)->format('M j, Y') }}</span>
        @else
            <span>All Time</span>
        @endif
    </div> --}}

</body>
</html>