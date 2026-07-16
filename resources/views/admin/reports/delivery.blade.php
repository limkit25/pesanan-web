@extends('layouts.admin')

@section('content')
<style>
    @media print {
        /* Hide sidebar, global header, filter form, and buttons */
        aside,
        header,
        #sidebar,
        .no-print,
        .no-print * {
            display: none !important;
        }

        /* Set main area to full page width */
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

        /* Show print header */
        .print-header {
            display: block !important;
        }

        /* Prevent table split within row */
        tr {
            page-break-inside: avoid !important;
        }
    }
    
    .print-header {
        display: none;
    }
</style>

<!-- Kop Cetak Laporan (Hanya Muncul Saat Print/PDF) -->
<div class="print-header mb-6 border-b-2 border-gray-900 pb-3">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold uppercase tracking-tight text-gray-900">FoodieHub - Rekap Dapur & Pengiriman</h1>
            <p class="text-xs text-gray-500 mt-1">Tanggal Jadwal: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-gray-900">FoodieHub Management System</p>
            <p class="text-[10px] text-gray-500 mt-0.5">Dicetak pada: {{ now()->format('d M Y H:i') }} WIB</p>
        </div>
    </div>
</div>

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header & Date Filter -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8 no-print">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                <span>Manajemen Laporan</span>
                <span>/</span>
                <span class="text-orange-500">Rekap Kirim-Ambil</span>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Rekap Kirim / Ambil</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Total kuantitas kebutuhan menu dapur berdasarkan jadwal kirim atau ambil.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center gap-3">
            <form action="{{ route('admin.reports.delivery') }}" method="GET" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $date }}" class="rounded-lg border border-gray-300 text-sm py-2 px-3 focus:border-pink-500 focus:ring focus:ring-pink-200 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white dark:bg-orange-500 dark:hover:bg-orange-600 hover:bg-gray-800 text-sm font-bold rounded-lg transition-colors duration-200">
                    Filter
                </button>
            </form>
            
            <div class="h-8 w-[1px] bg-gray-200 dark:bg-gray-800 hidden sm:block"></div>

            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-bold inline-flex items-center gap-1.5 shadow-sm transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Dapur
            </button>
        </div>
    </div>

    <!-- H-3 Upcoming Deliveries Section -->
    @if(isset($upcomingOrders) && $upcomingOrders->count() > 0)
    <div class="mb-10 no-print">
        <div class="bg-gradient-to-r from-orange-500 to-pink-500 rounded-2xl p-5 sm:p-6 shadow-lg text-white">
            <h3 class="text-lg font-extrabold flex items-center gap-2 mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Jadwal Kirim/Ambil Mendatang (H-3)
            </h3>
            <p class="text-sm font-medium text-white/90 mb-5">Terdapat {{ $upcomingOrders->count() }} pesanan yang dijadwalkan dalam 3 hari ke depan.</p>
            
            <div class="bg-white/10 rounded-xl overflow-hidden border border-white/20">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-white/10 font-bold uppercase tracking-wider text-[10px] text-white">
                            <tr>
                                <th class="px-4 py-3">ID Pesanan</th>
                                <th class="px-4 py-3">Jadwal (WIB)</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 font-medium">
                            @foreach($upcomingOrders as $order)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="font-bold underline hover:text-orange-200">
                                        #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-white/20 rounded text-[10px] font-bold uppercase tracking-wider">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Section 1: Rekap Kebutuhan Dapur (Grouped Qty) -->
    <div class="mb-8">
        <h3 class="text-sm font-extrabold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            Rekap Kebutuhan Porsi Dapur (Total Qty)
        </h3>
        
        @if($recap->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($recap as $item)
                    <div class="flex items-center gap-3 bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-800 rounded-xl p-4 shadow-theme-sm">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden border border-gray-100 dark:border-gray-800 bg-gray-50">
                            <img src="{{ $item->product ? $item->product->image : '' }}" alt="{{ $item->product ? $item->product->name : 'Dihapus' }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $item->product ? $item->product->name : 'Produk Dihapus' }}</p>
                            <p class="text-[10px] text-gray-400 font-semibold mb-1">
                                {{ $item->product && $item->product->category ? $item->product->category->name : '-' }}
                            </p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-extrabold bg-orange-50 dark:bg-orange-950/20 text-orange-500">
                                × {{ (int)$item->total_qty }} porsi
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-10 bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-800 rounded-xl shadow-theme-sm text-gray-400 dark:text-gray-500">
                <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="font-bold text-xs">Tidak ada jadwal kirim/ambil menu pada tanggal ini.</p>
            </div>
        @endif
    </div>

    <!-- Section 2: Rincian Daftar Pesanan Terjadwal -->
    <div class="bg-white dark:bg-gray-dark rounded-2xl border border-gray-250 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-sm font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Rincian Jadwal & Detail Pesanan
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-150 dark:divide-gray-800">
                <thead class="bg-gray-50/50 dark:bg-gray-900/30">
                    <tr>
                        <th class="py-3 pl-6 pr-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">Jam</th>
                        <th class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">ID</th>
                        <th class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            Pelanggan @if(auth()->check() && auth()->user()->role === 'admin') & WA @endif
                        </th>
                        <th class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">Alamat / Meja</th>
                        <th class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">Detail Menu Dipesan</th>
                        <th class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-wider text-gray-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/30 dark:hover:bg-white/[0.01] transition-colors">
                            <!-- Delivery Time -->
                            <td class="py-3.5 pl-6 pr-3 whitespace-nowrap text-xs font-black text-orange-600 dark:text-orange-400">
                                {{ \Carbon\Carbon::parse($order->delivery_date)->format('H:i') }} WIB
                            </td>
                            
                            <!-- ID -->
                            <td class="px-3 py-3.5 whitespace-nowrap text-xs font-bold text-gray-900 dark:text-white">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">
                                    #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            
                            <!-- User Name & Phone -->
                            <td class="px-3 py-3.5 text-xs">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $order->user->name }}</div>
                                @if($order->phone && auth()->check() && auth()->user()->role === 'admin')
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $order->phone);
                                        if (strpos($cleanPhone, '0') === 0) {
                                            $cleanPhone = '62' . substr($cleanPhone, 1);
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer" class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-0.5 mt-0.5">
                                        {{ $order->phone }}
                                        <svg class="w-2.5 h-2.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @endif
                            </td>
                            
                            <!-- Table / Address -->
                            <td class="px-3 py-3.5 text-xs text-gray-700 dark:text-gray-300 max-w-[200px] truncate" title="{{ $order->shipping_address }}">
                                <span class="font-semibold">{{ $order->shipping_address }}</span>
                            </td>
                            
                            <!-- Ordered Items list -->
                            <td class="px-3 py-3.5 text-xs">
                                <div class="space-y-1">
                                    @foreach($order->orderItems as $item)
                                        <div class="flex items-center gap-1.5 text-gray-800 dark:text-gray-200">
                                            <span class="w-4 h-4 rounded bg-gray-100 dark:bg-gray-800 inline-flex items-center justify-center text-[10px] font-bold text-orange-500">
                                                {{ $item->quantity }}
                                            </span>
                                            <span class="font-bold">{{ $item->product ? $item->product->name : 'Produk Dihapus' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-3 py-3.5 whitespace-nowrap text-center text-xs">
                                @if($order->status === 'pending')
                                    <span class="inline-flex items-center rounded-full bg-warning-50 px-2 py-0.5 text-[9px] font-bold text-warning-600 border border-warning-100 dark:bg-warning-500/10 dark:text-warning-400 dark:border-warning-500/20">Menunggu</span>
                                @elseif($order->status === 'processing')
                                    <span class="inline-flex items-center rounded-full bg-brand-50 px-2 py-0.5 text-[9px] font-bold text-brand-500 border border-brand-100 dark:bg-brand-500/10 dark:text-brand-400 dark:border-brand-500/20">Diproses</span>
                                @elseif($order->status === 'completed')
                                    <span class="inline-flex items-center rounded-full bg-success-50 px-2 py-0.5 text-[9px] font-bold text-success-600 border border-success-100 dark:bg-success-500/10 dark:text-success-400 dark:border-success-500/20">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 dark:text-gray-500">
                                <p class="text-xs font-bold">Tidak ada rincian jadwal untuk tanggal ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
