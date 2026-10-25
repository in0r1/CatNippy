<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function download(Request $request, Order $order)
    {
        // The order itself must belong to the signed-in customer.
        abort_unless(
            $order->user_id !== null
                && (int) $order->user_id === (int) $request->user()->id,
            404
        );

        $filename = $request->query('file');

        abort_unless(
            is_string($filename)
                && $filename !== ''
                && strlen($filename) <= 255,
            404
        );

        /*
         * directory traversal sequences are not removed.
         */
        $path = storage_path('app/invoices/' . $filename);

        abort_unless(is_file($path), 404);

        return response()->download($path);
    }
}