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

        if ($request->filled('debt')) {
            $query->where('status', '!=', 'cancelled')
                  ->whereRaw('total_price > paid_amount');
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $products = \App\Models\Product::orderBy('name')->get();
        $users = \App\Models\User::where('role', 'customer')->orderBy('name')->get();
        return view('admin.orders.create', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'customer_type' => 'required|in:guest,registered',
            'user_id' => 'required_if:customer_type,registered|nullable|exists:users,id',
            'customer_name' => 'required_if:customer_type,guest|nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
            'shipping_address' => 'nullable|string|max:500',
            'delivery_date' => 'nullable|date',
            'payment_method' => 'required|in:transfer,cash',
            'payment_status' => 'required|in:unpaid,partial,paid',
            'paid_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $totalPrice = 0;
            
            foreach ($request->items as $itemData) {
                $product = \App\Models\Product::findOrFail($itemData['product_id']);
                $totalPrice += $product->price * $itemData['quantity'];
            }

            $taxSetting = \App\Models\Setting::where('key', 'tax_enabled')->first();
            $taxEnabled = $taxSetting ? $taxSetting->value == '1' : true;

            if ($taxEnabled) {
                $totalPrice = $totalPrice + ($totalPrice * 0.1);
            }

            $paidAmount = 0;
            if ($request->payment_status === 'paid') {
                $paidAmount = $totalPrice;
            } elseif ($request->payment_status === 'partial') {
                $paidAmount = $request->paid_amount ?? 0;
            }

            $order = Order::create([
                'user_id' => $request->customer_type === 'registered' ? $request->user_id : null, 
                'customer_name' => $request->customer_type === 'guest' ? $request->customer_name : null,
                'phone' => $request->phone,
                'shipping_address' => $request->shipping_address,
                'delivery_date' => $request->delivery_date,
                'payment_method' => $request->payment_method,
                'total_price' => $totalPrice,
                'status' => 'completed',
                'payment_status' => $request->payment_status,
                'paid_amount' => $paidAmount,
            ]);

            foreach ($request->items as $itemData) {
                $product = \App\Models\Product::findOrFail($itemData['product_id']);
                
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'cost_price' => $product->cost_price ?? 0,
                    'quantity' => $itemData['quantity'],
                ]);

                $product->decrement('stock', $itemData['quantity']);
            }

            \App\Models\OrderLog::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'status_from' => 'pending',
                'status_to' => 'completed',
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('admin.orders.index')->with('success', 'Pesanan manual berhasil dibuat.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())->withInput();
        }
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
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'nullable|in:unpaid,verifying,partial,paid',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
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
            $oldStatus = $order->status;
            
            $updateData = ['status' => $request->status];
            if ($request->has('payment_status')) {
                $updateData['payment_status'] = $request->payment_status;
                if ($request->payment_status === 'unpaid') {
                    $updateData['paid_amount'] = 0;
                } elseif ($request->payment_status === 'paid') {
                    $updateData['paid_amount'] = $order->total_price;
                } elseif ($request->payment_status === 'partial') {
                    $updateData['paid_amount'] = $request->paid_amount ?? 0;
                }
            }
            if ($request->hasFile('payment_proof')) {
                $updateData['payment_proof'] = $request->file('payment_proof')->store('payments', 'public');
            }
            $order->update($updateData);

            if ($oldStatus !== $request->status) {
                \App\Models\OrderLog::create([
                    'order_id' => $order->id,
                    'user_id' => auth()->id(),
                    'status_from' => $oldStatus,
                    'status_to' => $request->status,
                ]);
            }

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
            $updateDataFull = [
                'status' => $request->status,
                'phone' => $request->phone,
                'shipping_address' => $request->shipping_address,
                'delivery_date' => $request->delivery_date,
            ];
            if ($request->has('payment_status')) {
                $updateDataFull['payment_status'] = $request->payment_status;
                if ($request->payment_status === 'unpaid') {
                    $updateDataFull['paid_amount'] = 0;
                } elseif ($request->payment_status === 'partial') {
                    $updateDataFull['paid_amount'] = $request->paid_amount ?? 0;
                }
                // Jika 'paid', kita update paid_amount nanti setelah total_price dihitung ulang
            }
            if ($request->hasFile('payment_proof')) {
                $updateDataFull['payment_proof'] = $request->file('payment_proof')->store('payments', 'public');
            }
            $order->update($updateDataFull);

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
                        'cost_price' => $product->cost_price ?? 0,
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

            $finalUpdates = ['total_price' => $total];
            if ($request->payment_status === 'paid') {
                $finalUpdates['paid_amount'] = $total;
            }
            $order->update($finalUpdates);

            if ($oldStatus !== $request->status) {
                \App\Models\OrderLog::create([
                    'order_id' => $order->id,
                    'user_id' => auth()->id(),
                    'status_from' => $oldStatus,
                    'status_to' => $request->status,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.orders.show', $order->id)->with('success', 'Detail pesanan berhasil diperbarui!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Order update failed: ' . $e->getMessage(), ['order_id' => $order->id]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan perubahan. Silakan coba lagi.')->withInput();
        }
    }

    public function invoice(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.invoice', compact('order'));
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
