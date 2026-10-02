<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    // STREAM RECEIPT AS PDF (VIEW IN BROWSER)
    public function show(Sale $sale)
    {
        $sale->load('items', 'customer', 'user');

        $pdf = Pdf::loadView('receipts.show', [
            'sale' => $sale,
        ])->setPaper([0, 0, 226.77, 800], 'portrait'); 

        return $pdf->stream('receipt-' . $sale->id . '.pdf');
    }

    // FORCE DOWNLOAD RECEIPT AS PDF
    public function download(Sale $sale)
    {
        $sale->load('items', 'customer', 'user');

        $pdf = Pdf::loadView('receipts.show', [
            'sale' => $sale,
        ])->setPaper([0, 0, 226.77, 800], 'portrait');

        return $pdf->download('receipt-' . $sale->id . '.pdf');
    }
}