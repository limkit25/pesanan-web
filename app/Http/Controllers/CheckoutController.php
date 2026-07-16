<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }
        $taxSetting = \App\Models\Setting::where('key', 'tax_enabled')->first();
        $taxEnabled = $taxSetting ? $taxSetting->value == '1' : true;
        
        return view('checkout.index', compact('carts', 'taxEnabled'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'phone' => 'required|string|max:30',
            'delivery_date' => 'nullable|date|after_or_equal:today',
        ]);

        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($carts as $cart) {
                if ($cart->product->stock < $cart->quantity) {
                    throw new \Exception('Stok produk ' . $cart->product->name . ' tidak mencukupi.');
                }
                $total += ($cart->product->price * $cart->quantity);
            }
            $taxSetting = \App\Models\Setting::where('key', 'tax_enabled')->first();
            $taxEnabled = $taxSetting ? $taxSetting->value == '1' : true;

            // Add 10% tax if enabled
            if ($taxEnabled) {
                $total = $total + ($total * 0.1);
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $total,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'phone' => $request->phone,
                'delivery_date' => $request->delivery_date,
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);
                
                // Kurangi stok produk
                $cart->product->decrement('stock', $cart->quantity);
            }

            // Empty cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('home')->with('success', 'Pesanan berhasil dibuat! Kami akan segera memprosesnya.');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMsg = str_contains($e->getMessage(), 'Stok produk') ? $e->getMessage() : 'Terjadi kesalahan saat memproses pesanan.';
            return back()->with('error', $errorMsg);
        }
    }
}
