<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #000000; background: #ffffff; padding: 40px; }
        .company-name { font-size: 18px; font-weight: bold; margin-bottom: 2px; }
        .report-title { font-size: 13px; font-weight: bold; margin-bottom: 2px; }
        .report-meta { font-size: 11px; margin-bottom: 4px; }
        .report-generated { font-size: 10px; margin-bottom: 24px; color: #555555; }
        hr { border: none; border-top: 1px solid #000000; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead th { border-top: 1px solid #000000; border-bottom: 1px solid #000000; padding: 6px 8px; font-size: 11px; font-weight: bold; text-align: left; }
        thead th.text-right { text-align: right; }
        tbody td { padding: 6px 8px; font-size: 11px; border-bottom: 1px solid #cccccc; }
        tbody td.text-right { text-align: right; }
        .footer { font-size: 10px; margin-top: 24px; border-top: 1px solid #000000; padding-top: 8px; }
    </style>
</head>
<body>

    {{-- COMPANY HEADER --}}
    <div class="company-name">FourStripes</div>
    <div class="report-title">Inventory Report</div>
    <div class="report-meta">
        @if ($dateFrom && $dateTo)
            Period: {{ \Carbon\Carbon::parse($dateFrom)->format('F d, Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('F d, Y') }}
        @else
            Period: All Records
        @endif
    </div>
    <div class="report-generated">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</div>

    <hr>

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
                    $beginning     = $product->quantity + $totalStockOut - $totalStockIn;
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
                    <td colspan="6" style="text-align: center; padding: 20px;">
                        No inventory activity found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    {{-- <div class="footer">
        @if ($dateFrom && $dateTo)
            Filtered from {{ \Carbon\Carbon::parse($dateFrom)->format('F d, Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('F d, Y') }}
        @else
            No date filter applied — showing all records
        @endif
    </div> --}}

</body>
</html>