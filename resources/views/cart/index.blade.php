<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
            {{ __('Keranjang Belanja') }}
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

            @if($carts->isEmpty())
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                    <div class="p-12 text-center max-w-md mx-auto">
                        <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-orange-500 shadow-inner">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Keranjang belanja kosong</h3>
                        <p class="text-gray-500 text-xs mb-6">Anda belum menambahkan menu apa pun ke keranjang. Ayo jelajahi pilihan menu lezat kami!</p>
                        <div>
                            <a href="/" class="inline-flex items-center px-6 py-3 border border-transparent text-xs font-bold rounded-xl text-white bg-gradient-to-r from-orange-500 to-pink-500 hover:shadow-lg hover:shadow-orange-200/50 hover:-translate-y-0.5 transition-all duration-300">
                                Mulai Belanja
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-5 flex items-center gap-2">
                                <span class="w-1.5 h-4 bg-gradient-to-b from-orange-500 to-pink-500 rounded-full inline-block"></span>
                                Menu di Keranjang
                            </h3>
                            <ul class="divide-y divide-gray-50">
                                @php $total = 0; @endphp
                                @foreach($carts as $cart)
                                    @php
                                        $subtotal = $cart->product->price * $cart->quantity;
                                        $total += $subtotal;
                                    @endphp
                                    <li class="py-3.5 flex items-center group first:pt-0 last:pb-0 gap-3">
                                        <div class="flex-shrink-0 w-14 h-14 border border-gray-100 rounded-xl overflow-hidden shadow-sm relative">
                                            <img src="{{ $cart->product->image }}" alt="{{ $cart->product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div>
                                                <div class="flex justify-between items-start gap-4">
                                                    <h3 class="text-sm font-bold text-gray-900 group-hover:text-orange-500 transition-colors truncate leading-tight">{{ $cart->product->name }}</h3>
                                                    <p class="text-sm font-black text-gray-900 whitespace-nowrap ml-2">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                                </div>
                                                <p class="text-[10px] text-gray-400 font-semibold mt-0.5">@ Rp {{ number_format($cart->product->price, 0, ',', '.') }}</p>
                                            </div>
                                            <div class="flex items-center justify-between mt-2.5">
                                                <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-gray-50 max-w-fit shadow-inner">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" class="w-12 border-0 bg-transparent text-center focus:ring-0 py-0.5 px-1.5 text-xs font-bold text-gray-850" onchange="this.form.submit()">
                                                </form>

                                                <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-500 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 p-1.5 rounded-lg transition-all shadow-sm" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="lg:col-span-1 space-y-4">
                        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Ringkasan Pesanan
                            </h2>
                            <div class="flow-root mb-5">
                                <dl class="space-y-3 text-xs font-semibold">
                                    <div class="flex items-center justify-between">
                                        <dt class="text-gray-400">Subtotal</dt>
                                        <dd class="text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</dd>
                                    </div>
                                    @if($taxEnabled)
                                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                        <dt class="text-gray-400">Pajak (10%)</dt>
                                        <dd class="text-gray-900">Rp {{ number_format($total * 0.1, 0, ',', '.') }}</dd>
                                    </div>
                                    @endif
                                    <div class="flex items-center justify-between pt-1">
                                        <dt class="text-xs font-black text-gray-900">Total Pembayaran</dt>
                                        <dd class="text-base font-extrabold bg-gradient-to-r from-orange-500 to-pink-500 bg-clip-text text-transparent">Rp {{ number_format($taxEnabled ? $total + ($total * 0.1) : $total, 0, ',', '.') }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="space-y-3">
                                <a href="{{ route('checkout.index') }}" class="w-full flex justify-center items-center px-5 py-3 border border-transparent rounded-xl shadow-md text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-pink-500 hover:shadow-lg hover:shadow-orange-200/50 hover:-translate-y-0.5 transition-all duration-350">
                                    Lanjut ke Pembayaran
                                </a>
                                <a href="/" class="w-full flex justify-center items-center px-5 py-2.5 border border-gray-200 rounded-xl text-xs font-semibold text-gray-650 hover:text-orange-500 hover:bg-orange-50/50 transition-all duration-350">
                                    Lanjutkan Belanja &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
