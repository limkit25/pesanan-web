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
        $totalRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

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
            'totalRevenue', 'totalOrders', 'completedOrders', 'cancelledOrders',
            'pendingOrders', 'newCustomers', 'topProducts', 'dailyRevenue',
            'recentOrders', 'startDate', 'endDate'
        ));
    }
}
