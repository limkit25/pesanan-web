<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        $taxSetting = \App\Models\Setting::where('key', 'tax_enabled')->first();
        $taxEnabled = $taxSetting ? $taxSetting->value == '1' : true;
        
        return view('cart.index', compact('carts', 'taxEnabled'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        if ($request->wantsJson()) {
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
            return response()->json([
                'success' => true,
                'message' => 'Produk ditambahkan ke keranjang!',
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Keranjang diperbarui!');
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang!');
    }
}
