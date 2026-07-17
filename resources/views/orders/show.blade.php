<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                Detail Pesanan #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('orders.invoice', $order->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded-xl shadow-sm hover:bg-gray-800 transition-all duration-300 no-print">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Invoice
                </a>
                <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-100 text-xs font-bold rounded-xl text-gray-600 hover:text-orange-500 shadow-sm hover:shadow-md transition-all duration-300 no-print">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali
                </a>
            </div>
        </div>
        
        @if(session('success'))
            <div class="mt-4 bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm text-sm font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mt-4 bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm text-sm font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                {{ session('error') }}
            </div>
        @endif
    </x-slot>

    <style>
        @media print {
            header, nav, footer, .no-print, .min-h-screen > header {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .min-h-screen {
                background: white !important;
            }
            main {
                padding: 0 !important;
                margin: 0 !important;
            }
            .bg-white, .bg-gray-50, .bg-gradient-to-r, .bg-gradient-to-tr {
                background: white !important;
                border: none !important;
                box-shadow: none !important;
                color: black !important;
            }
            .text-white, .text-gray-400, .text-gray-900, .text-orange-500, .text-orange-400, .text-orange-650 {
                color: black !important;
                -webkit-text-fill-color: black !important;
            }
            .border-gray-100, .border-gray-700\/50, .border-gray-200, .divide-gray-50 > :not([hidden]) ~ :not([hidden]) {
                border-color: #000 !important;
                border-width: 1px !important;
            }
            .shadow-sm, .shadow-md {
                box-shadow: none !important;
            }
            .rounded-2xl, .rounded-xl {
                border-radius: 0 !important;
            }
            .print-header {
                display: block !important;
                border-bottom: 2px solid black;
                margin-bottom: 20px;
                padding-bottom: 10px;
            }
            .print-flex-col {
                display: flex !important;
                flex-direction: column !important;
            }
        }
        .print-header {
            display: none;
        }
    </style>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <!-- Kop Invoice Print -->
            <div class="print-header">
                <div class="flex justify-between items-end">
                    <div>
                        <h1 class="text-2xl font-bold uppercase tracking-tight">INVOICE PESANAN</h1>
                        <p class="text-sm mt-1">Order ID: #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg">FoodieHub</p>
                        <p class="text-xs mt-1">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 print-flex-col">

                <!-- Detail Item Pesanan -->
                <div class="lg:col-span-2 space-y-5">
                    <!-- Status Tracker -->
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm no-print">
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
                                    
                                    @if($order->status === 'completed')
                                        @php
                                            $existingReview = \App\Models\Review::where('order_id', $order->id)->where('product_id', $item->product_id)->first();
                                        @endphp
                                        <div class="mt-3 no-print" x-data="{ showReviewForm: false }">
                                            @if($existingReview)
                                                <div class="bg-yellow-50/50 rounded-lg p-3 border border-yellow-100/50">
                                                    <div class="flex items-center gap-1 mb-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-3.5 h-3.5 {{ $i <= $existingReview->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                        @endfor
                                                    </div>
                                                    @if($existingReview->comment)
                                                        <p class="text-[10px] text-gray-600 font-medium italic">"{{ $existingReview->comment }}"</p>
                                                    @endif
                                                </div>
                                            @else
                                                <button @click="showReviewForm = !showReviewForm" type="button" class="inline-flex items-center gap-1 text-[10px] font-bold text-orange-500 hover:text-orange-600 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                                    Beri Ulasan
                                                </button>
                                                <div x-show="showReviewForm" x-collapse class="mt-3">
                                                    <form action="{{ route('reviews.store', ['order' => $order->id, 'product' => $item->product_id]) }}" method="POST" class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                                        @csrf
                                                        <div class="mb-3" x-data="{ rating: 0, hoverRating: 0 }">
                                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Pilih Rating</label>
                                                            <div class="flex items-center gap-1 cursor-pointer">
                                                                <input type="hidden" name="rating" x-model="rating" required>
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <svg @mouseover="hoverRating = {{ $i }}" @mouseleave="hoverRating = 0" @click="rating = {{ $i }}" class="w-6 h-6 transition-colors" :class="(hoverRating >= {{ $i }} || rating >= {{ $i }}) ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Komentar (Opsional)</label>
                                                            <textarea name="comment" rows="2" class="block w-full text-xs rounded-lg border-gray-200 focus:border-orange-500 focus:ring focus:ring-orange-200/50" placeholder="Bagaimana rasa dan pelayanannya?"></textarea>
                                                        </div>
                                                        <div class="flex justify-end gap-2">
                                                            <button @click="showReviewForm = false" type="button" class="px-3 py-1.5 bg-gray-200 text-gray-700 text-[10px] font-bold rounded-lg hover:bg-gray-300">Batal</button>
                                                            <button type="submit" class="px-3 py-1.5 bg-orange-500 text-white text-[10px] font-bold rounded-lg hover:bg-orange-600">Kirim Ulasan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
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
                            @if($order->payment_status === 'partial')
                            <div class="flex items-center justify-between pt-3 mt-2 border-t border-gray-100">
                                <dt class="text-xs font-bold text-gray-600">Telah Dibayar (DP)</dt>
                                <dd class="text-xs font-bold text-gray-900">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-center justify-between pt-1">
                                <dt class="text-xs font-bold text-red-500">Sisa Tagihan (Piutang)</dt>
                                <dd class="text-xs font-bold text-red-500">Rp {{ number_format($order->total_price - $order->paid_amount, 0, ',', '.') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Detail Metode Pembayaran & Upload Struk -->
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm space-y-4 no-print">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Metode Pembayaran
                        </h3>
                        
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <p class="text-xs font-bold text-gray-900">
                                {{ $order->payment_method === 'transfer' ? 'Transfer Bank' : 'Bayar di Tempat (Cash)' }}
                            </p>
                            @if($order->payment_method === 'transfer')
                                <p class="text-[10px] text-gray-500 mt-1">Ke Rekening {{ $bankName }}: {{ $bankAccount }} a.n. {{ $bankOwner }}</p>
                            @endif
                        </div>

                        @if($order->payment_method === 'transfer')
                            @if($order->payment_proof)
                                <div class="mt-3">
                                    <p class="text-[10px] font-bold text-emerald-600 mb-2 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Bukti Transfer Terkirim
                                    </p>
                                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="block overflow-hidden rounded-xl border border-gray-200">
                                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer" class="w-full h-32 object-cover hover:scale-105 transition-transform duration-300">
                                    </a>
                                </div>
                            @elseif($order->status === 'pending')
                                <div class="mt-3 border-t border-gray-100 pt-3">
                                    <form action="{{ route('orders.upload_payment', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Upload Struk Transfer (JPG/PNG)</label>
                                            <input type="file" name="payment_proof" accept="image/*" required class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 border border-gray-200 rounded-xl bg-white cursor-pointer">
                                            @error('payment_proof')
                                                <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="submit" class="w-full py-2 bg-gray-900 text-white text-xs font-bold rounded-xl shadow-sm hover:bg-gray-800 transition-colors">
                                            Kirim Bukti Pembayaran
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Info Pengiriman / Catatan -->
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm space-y-3">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Alamat / Catatan Meja
                            </h3>
                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 print:border-black print:p-0">
                                <p class="text-xs text-gray-700 print:text-black whitespace-pre-wrap leading-relaxed font-semibold">{{ $order->shipping_address }}</p>
                            </div>
                        </div>

                        @if($order->phone)
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500 fill-current" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.504-5.714-1.464L0 24zm6.59-4.846c1.6.95 3.197 1.451 4.885 1.453 5.325 0 9.66-4.322 9.663-9.65.002-2.578-1.002-5.003-2.826-6.83-1.825-1.829-4.25-2.836-6.837-2.837-5.328 0-9.663 4.323-9.667 9.651-.001 1.776.471 3.51 1.365 5.011l-.99 3.618 3.73-.977zm11.595-6.666c-.29-.145-1.716-.848-1.982-.945-.266-.096-.459-.145-.652.146-.193.29-.747.945-.916 1.139-.17.193-.338.217-.628.072-2.884-1.439-5.116-3.666-6.554-6.551-.29-.489.29-.454.827-.999.072-.072.145-.145.217-.217.072-.072.096-.12.145-.193.048-.073.024-.145-.012-.217-.036-.073-.314-.761-.43-1.039-.113-.274-.249-.237-.34-.241l-.29-.004c-.193 0-.507.072-.772.361-.266.29-1.014.992-1.014 2.417 0 1.425 1.038 2.802 1.182 2.996.145.193 2.043 3.12 4.95 4.378.692.299 1.233.478 1.654.612.695.221 1.328.19 1.828.115.556-.083 1.716-.701 1.958-1.378.24-.677.24-1.258.17-1.378-.07-.12-.266-.193-.556-.339z"/>
                                </svg>
                                Nomor Kontak
                            </h3>
                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 flex items-center justify-between">
                                <span class="text-xs text-gray-700 font-extrabold">{{ $order->phone }}</span>
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $order->phone);
                                    if (strpos($cleanPhone, '0') === 0) {
                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer" class="text-[10px] font-extrabold text-white px-3 py-1.5 rounded-lg transition-all duration-300 no-print" style="background-color: #10b981;">Hubungi WhatsApp</a>
                            </div>
                        </div>
                        @endif
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
                    
                    @if($order->status === 'pending')
                    <div class="pt-5 mt-5 border-t border-gray-200 dark:border-gray-700/50 no-print">
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat dikembalikan.');">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 border border-rose-200 bg-rose-50 text-rose-600 rounded-xl text-xs font-bold hover:bg-rose-100 hover:border-rose-300 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Batalkan Pesanan
                            </button>
                            <p class="text-center text-[10px] text-gray-400 mt-2">Pesanan hanya dapat dibatalkan jika belum diproses oleh dapur.</p>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
