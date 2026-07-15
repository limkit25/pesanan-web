<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <!-- Form Checkout -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Informasi Pengiriman / Nomor Meja
                        </h3>
                        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                            @csrf
                            <div class="mb-4">
                                <label for="shipping_address" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Alamat Pengiriman (atau Nomor Meja / Catatan Tambahan)</label>
                                <textarea name="shipping_address" id="shipping_address" rows="3" class="mt-1 p-3 block w-full rounded-xl border-gray-200 shadow-inner focus:border-pink-500 focus:ring focus:ring-pink-200/50 text-xs transition-all duration-300 font-semibold" required placeholder="Contoh: Jl. Sudirman No 12, atau Meja Makan Nomor 05..."></textarea>
                                @error('shipping_address')
                                    <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="phone" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Nomor Telepon / WhatsApp yang Bisa Dihubungi</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 p-3 block w-full rounded-xl border-gray-200 shadow-inner focus:border-pink-500 focus:ring focus:ring-pink-200/50 text-xs transition-all duration-300 font-semibold text-gray-700" required placeholder="Contoh: 081234567890">
                                @error('phone')
                                    <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="delivery_date" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Jadwal Pengiriman / Pengambilan (Opsional)</label>
                                <input type="datetime-local" name="delivery_date" id="delivery_date" min="{{ now()->format('Y-m-d\TH:i') }}" class="mt-1 p-3 block w-full rounded-xl border-gray-200 shadow-inner focus:border-pink-500 focus:ring focus:ring-pink-200/50 text-xs transition-all duration-300 font-semibold text-gray-700">
                                <p class="text-[10px] text-gray-400 font-medium mt-1">Kosongkan jika ingin segera diproses langsung setelah pesanan dibuat.</p>
                                @error('delivery_date')
                                    <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="w-full flex justify-center items-center px-5 py-3 border border-transparent rounded-xl shadow-md text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-pink-500 hover:shadow-lg hover:shadow-orange-200/50 hover:-translate-y-0.5 transition-all duration-355">
                                    Konfirmasi & Buat Pesanan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm h-fit">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Rincian Pesanan
                        </h2>
                        <ul class="divide-y divide-gray-50 mb-4">
                            @php $total = 0; @endphp
                            @foreach($carts as $cart)
                                @php
                                    $itemSubtotal = $cart->product->price * $cart->quantity;
                                    $total += $itemSubtotal;
                                @endphp
                                <li class="py-2.5 flex justify-between items-start text-xs group first:pt-0">
                                    <div class="flex flex-col pr-3">
                                        <span class="text-gray-900 font-bold group-hover:text-orange-500 transition-colors truncate max-w-[150px]">{{ $cart->product->name }}</span>
                                        <span class="text-[10px] text-gray-400 font-semibold mt-0.5">{{ $cart->quantity }}x @ Rp {{ number_format($cart->product->price, 0, ',', '.') }}</span>
                                    </div>
                                    <span class="text-gray-900 font-extrabold whitespace-nowrap ml-2">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                        
                        <div class="flow-root pt-3 border-t border-gray-50">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
