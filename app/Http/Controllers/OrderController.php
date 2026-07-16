<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Pastikan pelanggan hanya bisa lihat pesanannya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product');

        $bankName = \App\Models\Setting::where('key', 'bank_name')->first()->value ?? 'BCA';
        $bankAccount = \App\Models\Setting::where('key', 'bank_account')->first()->value ?? '123456789';
        $bankOwner = \App\Models\Setting::where('key', 'bank_owner')->first()->value ?? 'FoodieHub Official';

        return view('orders.show', compact('order', 'bankName', 'bankAccount', 'bankOwner'));
    }

    public function uploadPaymentProof(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payments', 'public');
            $order->update(['payment_proof' => $path]);
            return back()->with('success', 'Bukti transfer berhasil diunggah! Mohon tunggu konfirmasi dari admin.');
        }

        return back()->with('error', 'Gagal mengunggah bukti transfer.');
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $order->update(['status' => 'cancelled']);

            // Refund stock
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pesanan.');
        }
    }
}
