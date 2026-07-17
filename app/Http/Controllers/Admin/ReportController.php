<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default: bulan ini
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)->startOfDay() 
            : Carbon::now()->startOfMonth();
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date)->endOfDay() 
            : Carbon::now()->endOfDay();

        // Statistik Utama
        $completedOrdersList = Order::with('orderItems')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalRevenue = $completedOrdersList->sum('total_price');
        
        $totalCost = $completedOrdersList->sum(function($order) {
            return $order->orderItems->sum(function($item) {
                // Gunakan cost_price yang tersimpan, jika 0 atau null, fallback ke product->cost_price atau 0
                $cost = $item->cost_price;
                if (!$cost || $cost == 0) {
                    $cost = $item->product ? $item->product->cost_price : 0;
                }
                return $cost * $item->quantity;
            });
        });

        // Hitung total pajak untuk dikurangkan dari revenue agar profit kotor murni (Opsional, tergantung aturan bisnis, anggap total_price sudah termasuk tax jika ada)
        // Profit = Revenue - Modal
        $totalProfit = $totalRevenue - $totalCost;

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        $completedOrders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $cancelledOrders = Order::where('status', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $pendingOrders = Order::where('status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Uang Kas Masuk (Aktual)
        $totalActualRevenue = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('paid_amount');

        // Piutang (Sisa tagihan yang belum dibayar)
        $totalReceivables = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->sum(function($order) {
                return max(0, $order->total_price - $order->paid_amount);
            });

        // Produk Terlaris
        $topProducts = Product::withCount(['orderItems as total_sold' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                    $q->where('status', 'completed')
                      ->whereBetween('created_at', [$startDate, $endDate]);
                });
            }])
            ->withSum(['orderItems as total_qty' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                    $q->where('status', 'completed')
                      ->whereBetween('created_at', [$startDate, $endDate]);
                });
            }], 'quantity')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Pendapatan Per Hari (untuk grafik)
        $dailyRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Pesanan Terbaru
        $recentOrders = Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue', 'totalCost', 'totalProfit', 'totalOrders', 'completedOrders', 'cancelledOrders',
            'pendingOrders', 'newCustomers', 'topProducts', 'dailyRevenue',
            'recentOrders', 'startDate', 'endDate', 'totalActualRevenue', 'totalReceivables'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)->startOfDay() 
            : Carbon::now()->startOfMonth();
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date)->endOfDay() 
            : Carbon::now()->endOfDay();

        $orders = Order::with(['user', 'orderItems.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $fileName = 'laporan_penjualan_' . $startDate->format('Ymd') . '_to_' . $endDate->format('Ymd') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('ID Pesanan', 'Pelanggan', 'Email', 'Total Harga', 'Total Modal', 'Laba (Profit)', 'Status', 'Tanggal & Waktu', 'Detail Menu');

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $orderCost = 0;
                $itemsArray = [];
                foreach ($order->orderItems as $item) {
                    $productName = $item->product ? $item->product->name : 'Produk Dihapus';
                    $itemsArray[] = $productName . ' (x' . $item->quantity . ')';
                    
                    $cost = $item->cost_price;
                    if (!$cost || $cost == 0) {
                        $cost = $item->product ? $item->product->cost_price : 0;
                    }
                    $orderCost += ($cost * $item->quantity);
                }
                $itemsString = implode(', ', $itemsArray);
                
                $orderProfit = $order->total_price - $orderCost;

                fputcsv($file, array(
                    '#' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                    $order->user ? $order->user->name : 'Umum',
                    $order->user ? $order->user->email : '-',
                    $order->total_price,
                    $orderCost,
                    $orderProfit,
                    strtoupper($order->status),
                    $order->created_at->format('Y-m-d H:i:s'),
                    $itemsString
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function deliveryRecap(Request $request)
    {
        // Default filter: hari ini
        $date = $request->filled('date') 
            ? Carbon::parse($request->date)->format('Y-m-d') 
            : Carbon::today()->format('Y-m-d');

        $startDateTime = Carbon::parse($date)->startOfDay();
        $endDateTime = Carbon::parse($date)->endOfDay();

        // Rekap Qty berdasarkan masing-masing menu
        $recap = \App\Models\OrderItem::with('product')
            ->whereHas('order', function ($query) use ($startDateTime, $endDateTime) {
                $query->where('status', '!=', 'cancelled')
                      ->whereBetween('delivery_date', [$startDateTime, $endDateTime]);
            })
            ->selectRaw('product_id, SUM(quantity) as total_qty')
            ->groupBy('product_id')
            ->get();

        // List detail pesanan terjadwal hari ini
        $orders = Order::with(['user', 'orderItems.product'])
            ->where('status', '!=', 'cancelled')
            ->whereBetween('delivery_date', [$startDateTime, $endDateTime])
            ->orderBy('delivery_date', 'asc')
            ->get();
            
        // Pesanan H-3 (Tiga hari ke depan dari hari ini)
        $upcomingStart = Carbon::now()->startOfDay();
        $upcomingEnd = Carbon::now()->addDays(3)->endOfDay();
        
        $upcomingOrders = Order::with(['user', 'orderItems.product'])
            ->whereIn('status', ['pending', 'processing'])
            ->whereNotNull('delivery_date')
            ->whereBetween('delivery_date', [$upcomingStart, $upcomingEnd])
            ->orderBy('delivery_date', 'asc')
            ->get();

        return view('admin.reports.delivery', compact('recap', 'orders', 'date', 'upcomingOrders'));
    }
}
