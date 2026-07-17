@extends('layouts.admin')

@section('content')
<style>
    @media print {
        /* Sembunyikan navigasi sidebar, header global, form filter, dan tombol ekspor */
        aside,
        header,
        #sidebar,
        .no-print,
        .no-print * {
            display: none !important;
        }

        /* Atur area utama agar memenuhi kertas */
        .flex-1,
        main,
        .p-4,
        .sm\:p-6,
        .lg\:p-8 {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        body, html {
            background: white !important;
            color: black !important;
            font-size: 12px !important;
        }

        /* Tampilkan judul print */
        .print-header {
            display: block !important;
        }

        /* Rapikan kartu stat agar tidak pecah/terpotong */
        .grid {
            display: grid !important;
            gap: 10px !important;
        }
        
        /* Hindari pemotongan tabel di tengah baris */
        tr {
            page-break-inside: avoid !important;
        }
    }
    
    /* Secara default sembunyikan kop surat cetak di layar biasa */
    .print-header {
        display: none;
    }
</style>

<!-- Kop Cetak Laporan (Hanya Muncul Saat Print/PDF) -->
<div class="print-header mb-8 border-b-2 border-gray-900 pb-4">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold uppercase tracking-tight text-gray-900">FoodieHub - Laporan Penjualan</h1>
            <p class="text-xs text-gray-500 mt-1">Periode: {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-gray-900">FoodieHub Management System</p>
            <p class="text-[10px] text-gray-500 mt-0.5">Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
        </div>
    </div>
</div>

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header & Date Filter -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8 no-print">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Laporan Penjualan</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Statistik dan ringkasan performa bisnis Anda.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap items-center gap-3">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="rounded-lg border border-gray-300 text-sm py-2 px-3 focus:border-pink-500 focus:ring focus:ring-pink-200">
                <span class="text-gray-400 text-sm font-bold">s/d</span>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="rounded-lg border border-gray-300 text-sm py-2 px-3 focus:border-pink-500 focus:ring focus:ring-pink-200">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 text-sm font-bold">Filter</button>
            </form>
            
            <div class="h-8 w-[1px] bg-gray-200 hidden sm:block"></div>

            <a href="{{ route('admin.reports.export', request()->all()) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-bold inline-flex items-center gap-1.5 shadow-sm transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Ekspor CSV
            </a>
            
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-bold inline-flex items-center gap-1.5 shadow-sm transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak PDF
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <!-- Total Pendapatan -->
        <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-orange-500 to-pink-500 rounded-2xl p-5 text-white shadow-lg">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-bold opacity-90">Total Pendapatan</span>
            </div>
            <p class="text-xl font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>

        <!-- Total Modal -->
        <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl p-5 text-white shadow-lg">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <span class="text-xs font-bold opacity-90">Total Modal</span>
            </div>
            <p class="text-xl font-black">Rp {{ number_format($totalCost, 0, ',', '.') }}</p>
        </div>

        <!-- Profit/Laba -->
        <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl p-5 text-white shadow-lg">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <span class="text-xs font-bold opacity-90">Total Laba</span>
            </div>
            <p class="text-xl font-black">Rp {{ number_format($totalProfit, 0, ',', '.') }}</p>
        </div>

        <!-- Total Pesanan -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Pesanan</span>
            </div>
            <p class="text-xl font-black text-gray-900">{{ $totalOrders }}</p>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Selesai</span>
            </div>
            <p class="text-xl font-black text-emerald-600">{{ $completedOrders }}</p>
        </div>

        <!-- Batal -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Batal</span>
            </div>
            <p class="text-xl font-black text-red-600">{{ $cancelledOrders }}</p>
        </div>

        <!-- Customer Baru -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Customer Baru</span>
            </div>
            <p class="text-xl font-black text-purple-600">{{ $newCustomers }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Grafik Pendapatan Harian -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                Pendapatan Harian
            </h3>
            @if($dailyRevenue->count() > 0)
                <div class="relative" style="height: 250px;">
                    @php
                        $maxRevenue = $dailyRevenue->max('revenue') ?: 1;
                    @endphp
                    <div class="flex items-end justify-between gap-1 h-full px-2">
                        @foreach($dailyRevenue as $day)
                            @php
                                $heightPercent = ($day->revenue / $maxRevenue) * 100;
                            @endphp
                            <div class="flex-1 flex flex-col items-center gap-1 group relative">
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[9px] font-bold px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                    Rp {{ number_format($day->revenue, 0, ',', '.') }} ({{ $day->orders }} pesanan)
                                </div>
                                <div class="w-full bg-gradient-to-t from-orange-500 to-pink-400 rounded-t-lg transition-all duration-500 hover:from-orange-600 hover:to-pink-500 min-h-[4px]" style="height: {{ max($heightPercent, 2) }}%;"></div>
                                <span class="text-[9px] text-gray-400 font-bold">{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="flex items-center justify-center h-48 text-gray-400">
                    <p class="text-sm font-semibold">Belum ada data pendapatan untuk periode ini.</p>
                </div>
            @endif
        </div>

        <!-- Produk Terlaris -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                Produk Terlaris
            </h3>
            @if($topProducts->where('total_qty', '>', 0)->count() > 0)
                <div class="space-y-4">
                    @foreach($topProducts->where('total_qty', '>', 0) as $index => $product)
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black 
                                {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-gray-100 text-gray-600' : 'bg-orange-50 text-orange-500') }}">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-shrink-0 w-9 h-9 rounded-lg overflow-hidden border border-gray-100">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900 truncate">{{ $product->name }}</p>
                                <p class="text-[10px] text-gray-400 font-semibold">{{ (int)$product->total_qty }} terjual</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-center h-48 text-gray-400">
                    <p class="text-sm font-semibold">Belum ada data penjualan.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 pb-0">
            <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Pesanan Terbaru ({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="py-3 pl-6 pr-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">ID</th>
                        <th class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">Pelanggan</th>
                        <th class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">Total</th>
                        <th class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">Laba</th>
                        <th class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">Status Pesanan</th>
                        <th class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">Pembayaran</th>
                        <th class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 pl-6 pr-3 text-xs font-bold text-gray-900">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-3 py-3 text-xs font-semibold text-gray-600">{{ $order->user->name }}</td>
                            <td class="px-3 py-3 text-xs font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            @php
                                $cost = $order->orderItems->sum(function($item) {
                                    $c = $item->cost_price;
                                    if (!$c || $c == 0) $c = $item->product ? $item->product->cost_price : 0;
                                    return $c * $item->quantity;
                                });
                                $profit = $order->total_price - $cost;
                            @endphp
                            <td class="px-3 py-3 text-xs font-bold text-emerald-600">Rp {{ number_format($profit, 0, ',', '.') }}</td>
                            <td class="px-3 py-3 text-xs">
                                @if($order->status === 'pending')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-0.5 text-[10px] font-bold text-yellow-600 border border-yellow-100">Menunggu</span>
                                @elseif($order->status === 'processing')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-600 border border-blue-100">Diproses</span>
                                @elseif($order->status === 'completed')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-600 border border-emerald-100">Selesai</span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-bold text-red-600 border border-red-100">Batal</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-xs">
                                @if($order->payment_status === 'paid')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-600 border border-emerald-100" title="Rp {{ number_format($order->paid_amount, 0, ',', '.') }}">Lunas</span>
                                @elseif($order->payment_status === 'partial')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-0.5 text-[10px] font-bold text-yellow-600 border border-yellow-100" title="Rp {{ number_format($order->paid_amount, 0, ',', '.') }}">Sebagian</span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-bold text-red-600 border border-red-100" title="Belum ada pembayaran">Belum Lunas</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">
                                <p class="text-sm font-semibold">Tidak ada pesanan di periode ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
