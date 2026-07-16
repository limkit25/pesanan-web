<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order, Product $product)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'completed') {
            return back()->with('error', 'Ulasan hanya dapat diberikan untuk pesanan yang sudah selesai.');
        }

        // Check if product is in the order
        if (!$order->orderItems()->where('product_id', $product->id)->exists()) {
            abort(403, 'Produk tidak ditemukan dalam pesanan ini.');
        }

        // Check if already reviewed
        if (Review::where('order_id', $order->id)->where('product_id', $product->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini pada pesanan ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}
