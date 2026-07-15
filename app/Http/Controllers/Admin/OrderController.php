<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
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
