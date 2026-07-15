<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                Detail Pesanan #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
            </h2>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-100 text-xs font-bold rounded-xl text-gray-600 hover:text-orange-500 shadow-sm hover:shadow-md transition-all duration-300">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Daftar Pesanan
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                <!-- Detail Item Pesanan -->
                <div class="lg:col-span-2 space-y-5">
                    <!-- Status Tracker -->
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-5 flex items-center gap-2">
                            <span class="w-1.5 h-4 bg-gradient-to-b from-orange-500 to-pink-500 rounded-full inline-block"></span>
                            Status Pesanan
                        </h3>
                        <div class="flex items-center justify-between mt-2 max-w-sm mx-auto">
                            @php
                                $steps = ['pending' => 'Menunggu', 'processing' => 'Diproses', 'completed' => 'Selesai'];
                                $statusOrder = array_keys($steps);
                                $currentIndex = array_search($order->status, $statusOrder);
                                if ($order->status === 'cancelled') $currentIndex = -1;
                            @endphp
                            @foreach($steps as $key => $label)
                                @php
                                    $stepIndex = array_search($key, $statusOrder);
                                    $isActive = $currentIndex >= $stepIndex && $order->status !== 'cancelled';
                                    $isCurrent = $order->status === $key;
                                @endphp
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-500
                                        {{ $isActive ? 'bg-gradient-to-r from-orange-500 to-pink-500 text-white shadow-md' : 'bg-gray-100 text-gray-400' }}
                                        {{ $isCurrent ? 'ring-2 ring-orange-100' : '' }}">
                                        @if($isActive && !$isCurrent)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            {{ $stepIndex + 1 }}
                                        @endif
                                    </div>
                                    <span class="mt-1.5 text-[10px] font-bold tracking-wide uppercase {{ $isActive ? 'text-orange-600' : 'text-gray-400' }}">{{ $label }}</span>
                                </div>
                                @if(!$loop->last)
                                    <div class="flex-1 h-0.5 mx-1 mb-4 {{ $currentIndex > $stepIndex ? 'bg-gradient-to-r from-orange-400 to-pink-400' : 'bg-gray-200' }}"></div>
                                @endif
                            @endforeach
                        </div>
                        @if($order->status === 'cancelled')
                            <div class="mt-4 bg-rose-50 border border-rose-100 text-rose-800 px-4 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Pesanan ini telah dibatalkan.
                            </div>
                        @endif
                    </div>

                    <!-- Daftar Menu -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 flex items-center gap-2">
                                <span class="w-1.5 h-4 bg-gradient-to-b from-orange-500 to-pink-500 rounded-full inline-block"></span>
                                Menu Dipesan
                            </h3>
                        </div>
                        <ul class="divide-y divide-gray-50 px-5">
                            @foreach($order->orderItems as $item)
                            <li class="flex py-3.5 group items-center gap-3">
                                <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl border border-gray-100 shadow-sm relative">
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start gap-4">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-orange-500 transition-colors truncate">{{ $item->product->name }}</h4>
                                        <p class="text-sm font-black text-gray-900 whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-semibold text-gray-400 bg-gray-50 px-2 py-0.5 rounded border border-gray-100">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        <span class="text-[10px] font-bold text-orange-650 bg-orange-50/70 px-2 py-0.5 rounded">× {{ $item->quantity }}</span>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="space-y-5">
                    <!-- Ringkasan Pembayaran -->
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Ringkasan Pembayaran
                        </h3>
                        @php
                            $subtotal = $order->total_price / 1.1;
                        @endphp
                        <dl class="space-y-3 text-xs font-semibold">
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-400">Subtotal</dt>
                                <dd class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                            </div>
                            @if($order->total_price > $subtotal)
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                <dt class="text-gray-400">Pajak (10%)</dt>
                                <dd class="text-gray-900">Rp {{ number_format($order->total_price - $subtotal, 0, ',', '.') }}</dd>
                            </div>
                            @endif
                            <div class="flex items-center justify-between pt-1">
                                <dt class="text-xs font-black text-gray-900">Total Tagihan</dt>
                                <dd class="text-base font-extrabold bg-gradient-to-r from-orange-500 to-pink-500 bg-clip-text text-transparent">Rp {{ number_format($order->total_price, 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Info Pengiriman / Catatan -->
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Alamat / Catatan Meja
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <p class="text-xs text-gray-700 whitespace-pre-wrap leading-relaxed font-semibold">{{ $order->shipping_address }}</p>
                        </div>
                    </div>

                    <!-- Info Waktu -->
                    <div class="bg-gradient-to-tr from-gray-900 to-gray-800 text-white rounded-2xl p-5 shadow-md">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 flex items-center gap-2 text-white">
                            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Detail Waktu
                        </h3>
                        <dl class="space-y-3 text-xs font-semibold">
                            @if($order->delivery_date)
                            <div class="flex items-center justify-between border-b border-gray-700/50 pb-2.5">
                                <dt class="text-orange-400 font-extrabold">Jadwal Kirim/Ambil</dt>
                                <dd class="text-white font-extrabold">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y, H:i') }} WIB</dd>
                            </div>
                            @endif
                            <div class="flex items-center justify-between border-b border-gray-700/50 pb-2.5">
                                <dt class="text-gray-400">Tanggal Pesan</dt>
                                <dd class="text-white">{{ $order->created_at->format('d M Y') }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-400">Waktu Transaksi</dt>
                                <dd class="text-white">{{ $order->created_at->format('H:i:s') }} WIB</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
