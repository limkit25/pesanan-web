<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class InvoiceController extends Controller
{
    /**
     * Show the digital invoice.
     * This route is protected by Laravel Signed URL middleware.
     */
    public function show(Request $request, Order $order)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Link invoice tidak valid atau telah kedaluwarsa.');
        }

        $order->load(['user', 'orderItems.product']);
        return view('orders.invoice', compact('order'));
    }
}
