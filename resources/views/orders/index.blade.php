<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
            {{ __('Pesanan Saya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">

            @if(session('success'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-2xl flex items-center gap-2.5 shadow-sm" role="alert">
                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="font-semibold text-xs">{{ session('success') }}</span>
                </div>
            @endif

            @if($orders->isEmpty())
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                    <div class="p-12 text-center max-w-md mx-auto">
                        <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-orange-500 shadow-inner">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Belum ada pesanan</h3>
                        <p class="text-gray-500 text-xs mb-6">Anda belum pernah membuat pesanan. Ayo mulai jelajahi hidangan lezat kami!</p>
                        <div>
                            <a href="/" class="inline-flex items-center px-6 py-3 border border-transparent text-xs font-bold rounded-xl text-white bg-gradient-to-r from-orange-500 to-pink-500 hover:shadow-lg hover:shadow-orange-200/50 hover:-translate-y-0.5 transition-all duration-300">
                                Lihat Menu Utama
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <a href="{{ route('orders.show', $order->id) }}" class="block bg-white overflow-hidden shadow-sm hover:shadow-md rounded-2xl border border-gray-100 hover:border-orange-200 transition-all duration-300 group transform hover:-translate-y-0.5">
                            <div class="p-4 sm:p-5">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Order Status Icon -->
                                        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 shadow-inner
                                            @if($order->status === 'pending') bg-amber-50 text-amber-600
                                            @elseif($order->status === 'processing') bg-blue-50 text-blue-600
                                            @elseif($order->status === 'completed') bg-emerald-50 text-emerald-600
                                            @else bg-rose-50 text-rose-600
                                            @endif">
                                            @if($order->status === 'pending')
                                                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @elseif($order->status === 'processing')
                                                <svg class="w-5.5 h-5.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            @elseif($order->status === 'completed')
                                                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @else
                                                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-gray-900 group-hover:text-orange-500 transition-colors">
                                                Pesanan #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                            </h3>
                                            <p class="text-xs text-gray-400 mt-0.5 font-medium">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between sm:justify-end gap-5 border-t border-gray-50 pt-3 sm:pt-0 sm:border-0">
                                        <!-- Status Badge -->
                                        <div>
                                            @if($order->status === 'pending')
                                                <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-[10px] font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20 shadow-sm">⏳ Menunggu</span>
                                            @elseif($order->status === 'processing')
                                                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-[10px] font-bold text-blue-700 ring-1 ring-inset ring-blue-700/20 shadow-sm">🔄 Diproses</span>
                                            @elseif($order->status === 'completed')
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 shadow-sm">✅ Selesai</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-rose-50 px-3 py-1 text-[10px] font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20 shadow-sm">❌ Dibatalkan</span>
                                            @endif
                                        </div>
                                        <!-- Total -->
                                        <div class="text-right">
                                            <span class="text-[9px] text-gray-400 font-semibold uppercase tracking-wider block mb-0.5">Total Tagihan</span>
                                            <p class="text-lg font-black text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        </div>
                                        <!-- Arrow Icon -->
                                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-gradient-to-r group-hover:from-orange-500 group-hover:to-pink-500 group-hover:text-white transition-all duration-300">
                                            <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
