<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])->withSum('orderItems as total_qty', 'quantity');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengedit detail pesanan.');
        }

        $order->load(['user', 'orderItems.product']);
        $products = \App\Models\Product::orderBy('name')->get();
        
        $orderItemsJson = $order->orderItems->map(function($item) {
            return [
                'product_id' => $item->product_id,
                'name' => $item->product ? $item->product->name : 'Produk Dihapus',
                'image' => $item->product ? $item->product->image : '',
                'price' => (float)$item->price,
                'quantity' => (int)$item->quantity
            ];
        })->toArray();

        return view('admin.orders.edit', compact('order', 'products', 'orderItemsJson'));
    }

    public function update(Request $request, Order $order)
    {
        $rules = [
            'status' => 'required|in:pending,processing,completed,cancelled'
        ];

        $isFullEdit = $request->has('phone') || $request->has('shipping_address') || $request->has('items');

        if ($isFullEdit) {
            $rules['phone'] = 'required|string|max:30';
            $rules['shipping_address'] = 'required|string|max:500';
            $rules['delivery_date'] = 'nullable|date';
            $rules['items'] = 'required|array|min:1';
            $rules['items.*.product_id'] = 'required|exists:products,id';
            $rules['items.*.quantity'] = 'required|integer|min:1';
        }

        $request->validate($rules);

        if (!$isFullEdit) {
            $order->update(['status' => $request->status]);
            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $oldStatus = $order->status;
            
            // Refund stok lama jika status lama bukan cancelled
            if ($oldStatus !== 'cancelled') {
                foreach ($order->orderItems as $oldItem) {
                    if ($oldItem->product) {
                        $oldItem->product->increment('stock', $oldItem->quantity);
                    }
                }
            }

            // Update order details
            $order->update([
                'status' => $request->status,
                'phone' => $request->phone,
                'shipping_address' => $request->shipping_address,
                'delivery_date' => $request->delivery_date,
            ]);

            // Delete old items
            $order->orderItems()->delete();

            $total = 0;
            foreach ($request->items as $itemData) {
                $product = \App\Models\Product::find($itemData['product_id']);
                if ($product) {
                    $qty = intval($itemData['quantity']);
                    $price = $product->price;
                    $total += ($price * $qty);

                    \App\Models\OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $price,
                    ]);
                    
                    // Potong stok baru jika status pesanan bukan cancelled
                    if ($request->status !== 'cancelled') {
                        $product->decrement('stock', $qty);
                    }
                }
            }

            // Apply 10% tax if enabled
            $taxSetting = \App\Models\Setting::where('key', 'tax_enabled')->first();
            $taxEnabled = $taxSetting ? $taxSetting->value == '1' : true;
            if ($taxEnabled) {
                $total = $total + ($total * 0.1);
            }

            $order->update(['total_price' => $total]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.orders.show', $order->id)->with('success', 'Detail pesanan berhasil diperbarui!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Order update failed: ' . $e->getMessage(), ['order_id' => $order->id]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan perubahan. Silakan coba lagi.')->withInput();
        }
    }

    public function checkNewOrders(Request $request)
    {
        $pendingCount = Order::where('status', 'pending')->count();
        $latestOrder = Order::orderBy('created_at', 'desc')->first();
        
        $response = [
            'pendingCount' => $pendingCount,
            'latestOrderId' => $latestOrder ? $latestOrder->id : 0,
        ];
        
        if ($request->has('include_recent')) {
            $recentOrders = Order::with('user')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'id_formatted' => '#' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                        'user_name' => $order->user->name,
                        'total_price' => 'Rp ' . number_format($order->total_price, 0, ',', '.'),
                        'status' => $order->status,
                        'time_ago' => $order->created_at->diffForHumans(),
                    ];
                });
            $response['recentOrders'] = $recentOrders;
        }
        
        return response()->json($response);
    }
}
