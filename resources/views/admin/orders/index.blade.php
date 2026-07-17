@extends('layouts.admin')

@section('content')
    <div class="p-4 sm:p-6">
        <div class="sm:flex sm:items-center sm:justify-between mb-5">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">Pesanan Masuk</h1>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kelola dan pantau seluruh pesanan pelanggan secara real-time.</p>
            </div>
            <div class="mt-3 sm:mt-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="flex items-center gap-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID / nama pelanggan..." class="w-full sm:w-56 pl-10 pr-4 py-2 rounded-lg border border-gray-300 text-sm focus:border-pink-500 focus:ring focus:ring-pink-200">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <select name="status" onchange="this.form.submit()" class="rounded-lg border border-gray-300 text-sm py-2 px-3 focus:border-pink-500 focus:ring focus:ring-pink-200">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Batal</option>
                    </select>
                </form>
            </div>
        </div>
        
        @if(session('success'))
            <div class="mb-4 flex items-center gap-2 rounded-xl bg-success-50 px-3 py-2.5 border border-success-100 text-success-600 dark:bg-success-500/10 dark:border-success-500/20 dark:text-success-400" role="alert">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-xs font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flow-root">
            <div class="overflow-x-auto bg-white shadow-theme-sm border border-gray-200 rounded-xl dark:bg-gray-dark dark:border-gray-800">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th scope="col" class="py-3 pl-5 pr-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">ID</th>
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pelanggan</th>
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Jumlah (Qty)</th>
                            @if(auth()->user()->role === 'admin')
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total</th>
                            @endif
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pembayaran</th>
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Waktu</th>
                            <th scope="col" class="relative py-3 pl-3 pr-5 text-right">
                                <span class="sr-only">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-dark">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="whitespace-nowrap py-3 pl-5 pr-3 text-xs font-bold text-gray-900 dark:text-white">
                                    #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-600 dark:text-gray-300">
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-[9px]">
                                            {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                        </div>
                                        <span class="font-semibold">{{ $order->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-xs">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ (int)$order->total_qty }} item</div>
                                    <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5 max-w-[180px] truncate" title="{{ $order->orderItems->map(fn($item) => ($item->product ? $item->product->name : 'Produk Dihapus') . ' (x' . $item->quantity . ')')->implode(', ') }}">
                                        {{ $order->orderItems->map(fn($item) => ($item->product ? $item->product->name : 'Produk Dihapus') . ' (x' . $item->quantity . ')')->implode(', ') }}
                                    </div>
                                </td>
                                @if(auth()->user()->role === 'admin')
                                <td class="whitespace-nowrap px-3 py-3 text-xs font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                @endif
                                <td class="whitespace-nowrap px-3 py-3 text-xs">
                                    @if($order->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-bold text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400" title="Rp {{ number_format($order->paid_amount, 0, ',', '.') }}">
                                            Lunas
                                        </span>
                                    @elseif($order->payment_status === 'partial')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-1 text-[10px] font-bold text-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-400" title="Rp {{ number_format($order->paid_amount, 0, ',', '.') }}">
                                            DP / Sebagian
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[10px] font-bold text-red-600 dark:bg-red-500/10 dark:text-red-400" title="Belum ada pembayaran">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-xs">
                                    @if($order->status === 'pending')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-warning-50 px-2 py-1 text-[10px] font-bold text-warning-600 dark:bg-warning-500/10 dark:text-warning-400">
                                            <span class="h-1 w-1 rounded-full bg-warning-500 animate-pulse"></span>
                                            Menunggu
                                        </span>
                                    @elseif($order->status === 'processing')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-brand-50 px-2 py-1 text-[10px] font-bold text-brand-500 dark:bg-brand-500/10 dark:text-brand-400">
                                            <span class="h-1 w-1 rounded-full bg-brand-500"></span>
                                            Diproses
                                        </span>
                                    @elseif($order->status === 'completed')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-success-50 px-2 py-1 text-[10px] font-bold text-success-600 dark:bg-success-500/10 dark:text-success-400">
                                            <span class="h-1 w-1 rounded-full bg-success-500"></span>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-error-50 px-2 py-1 text-[10px] font-bold text-error-600 dark:bg-error-500/10 dark:text-error-400">
                                            <span class="h-1 w-1 rounded-full bg-error-500"></span>
                                            Batal
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-500 dark:text-gray-400">
                                    <div class="font-medium text-gray-600 dark:text-gray-300">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $order->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="relative whitespace-nowrap py-3 pl-3 pr-5 text-right text-xs font-semibold">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold text-orange-500 border border-orange-500 hover:bg-orange-500 hover:text-white transition-all shadow-sm">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500">
                                    <svg class="mx-auto h-8 w-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="font-semibold text-xs">Tidak ada pesanan masuk</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
