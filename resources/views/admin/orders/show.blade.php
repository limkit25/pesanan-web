@extends('layouts.admin')

@section('content')
    <div class="p-4 sm:p-6">
        <!-- Top Header & Back Link -->
        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                    <span>Manajemen Pesanan</span>
                    <span>/</span>
                    <span class="text-orange-500">Detail</span>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Detail Pesanan <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-pink-500">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                </h1>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-white border border-gray-200 px-3 py-1.5 text-xs font-bold text-gray-600 shadow-sm hover:bg-gray-50 transition-colors dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 flex items-center gap-2 rounded-xl bg-success-50 px-3 py-2.5 border border-success-100 text-success-600 dark:bg-success-500/10 dark:border-success-500/20 dark:text-success-400" role="alert">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-xs font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Main Layout Grid -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            
            <!-- Left Side -->
            <div class="lg:col-span-2 space-y-5">
                
                <!-- Status Flow Tracker -->
                <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-sm dark:border-gray-800 dark:bg-gray-dark">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-4">Status Pesanan</h3>
                    
                    @if($order->status === 'cancelled')
                        <div class="flex items-center gap-2 rounded-lg bg-red-50 px-3 py-2 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs font-bold">Pesanan ini telah Dibatalkan.</span>
                        </div>
                    @else
                        <div class="relative flex items-center justify-between max-w-xs">
                            <div class="absolute left-4 right-4 top-3 h-px bg-gray-200 dark:bg-gray-700"></div>
                            
                            <div class="flex flex-col items-center gap-1.5 relative z-10 bg-white dark:bg-gray-dark px-2">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[10px] {{ in_array($order->status, ['pending', 'processing', 'completed']) ? 'bg-gradient-to-r from-orange-500 to-pink-500 text-white' : 'bg-gray-100 text-gray-400 dark:bg-gray-800' }}">1</div>
                                <span class="text-[9px] font-bold uppercase tracking-wider {{ in_array($order->status, ['pending', 'processing', 'completed']) ? 'text-orange-500' : 'text-gray-400' }}">Menunggu</span>
                            </div>

                            <div class="flex flex-col items-center gap-1.5 relative z-10 bg-white dark:bg-gray-dark px-2">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[10px] {{ in_array($order->status, ['processing', 'completed']) ? 'bg-gradient-to-r from-orange-500 to-pink-500 text-white' : 'bg-gray-100 text-gray-400 dark:bg-gray-800' }}">2</div>
                                <span class="text-[9px] font-bold uppercase tracking-wider {{ in_array($order->status, ['processing', 'completed']) ? 'text-orange-500' : 'text-gray-400' }}">Diproses</span>
                            </div>

                            <div class="flex flex-col items-center gap-1.5 relative z-10 bg-white dark:bg-gray-dark px-2">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[10px] {{ $order->status === 'completed' ? 'bg-gradient-to-r from-orange-500 to-pink-500 text-white' : 'bg-gray-100 text-gray-400 dark:bg-gray-800' }}">3</div>
                                <span class="text-[9px] font-bold uppercase tracking-wider {{ $order->status === 'completed' ? 'text-orange-500' : 'text-gray-400' }}">Selesai</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Update Status Control -->
                <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-sm dark:border-gray-800 dark:bg-gray-dark">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Ubah Status</h3>
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="flex items-end gap-3">
                        @csrf
                        @method('PATCH')
                        <div class="flex-1">
                            <select id="status" name="status" class="block w-full rounded-lg border-gray-200 bg-gray-50 py-2 pl-3 pr-8 text-xs font-semibold focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-gradient-to-r from-orange-500 to-pink-500 hover:from-orange-600 hover:to-pink-600 text-white font-bold px-4 py-2 rounded-lg shadow-sm active:scale-95 transition-all text-xs flex items-center gap-1.5 whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            Simpan
                        </button>
                    </form>
                </div>

                <!-- Items Ordered -->
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-dark overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Menu yang Dipesan</h3>
                        <span class="bg-orange-50 text-orange-500 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $order->orderItems->sum('quantity') }} item</span>
                    </div>
                    <ul class="divide-y divide-gray-50 dark:divide-gray-800">
                        @foreach($order->orderItems as $item)
                        <li class="flex py-3 px-5 items-center gap-3">
                            <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg border border-gray-100 dark:border-gray-800">
                                <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $item->product->name }}</h4>
                                    <p class="text-sm font-bold text-orange-500 ml-2 flex-shrink-0">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-semibold text-gray-400 bg-gray-50 dark:bg-gray-900 px-1.5 py-0.5 rounded">@ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-1.5 py-0.5 rounded">× {{ $item->quantity }}</span>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Right Side -->
            <div class="space-y-5">
                
                <!-- Customer Information -->
                <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-sm dark:border-gray-800 dark:bg-gray-dark">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Informasi Pelanggan</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama</p>
                                <p class="text-xs font-bold text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Email</p>
                                <p class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ $order->user->email }}</p>
                            </div>
                        </div>

                        @if($order->phone)
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: #ecfdf5; color: #10b981;">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.504-5.714-1.464L0 24zm6.59-4.846c1.6.95 3.197 1.451 4.885 1.453 5.325 0 9.66-4.322 9.663-9.65.002-2.578-1.002-5.003-2.826-6.83-1.825-1.829-4.25-2.836-6.837-2.837-5.328 0-9.663 4.323-9.667 9.651-.001 1.776.471 3.51 1.365 5.011l-.99 3.618 3.73-.977zm11.595-6.666c-.29-.145-1.716-.848-1.982-.945-.266-.096-.459-.145-.652.146-.193.29-.747.945-.916 1.139-.17.193-.338.217-.628.072-2.884-1.439-5.116-3.666-6.554-6.551-.29-.489.29-.454.827-.999.072-.072.145-.145.217-.217.072-.072.096-.12.145-.193.048-.073.024-.145-.012-.217-.036-.073-.314-.761-.43-1.039-.113-.274-.249-.237-.34-.241l-.29-.004c-.193 0-.507.072-.772.361-.266.29-1.014.992-1.014 2.417 0 1.425 1.038 2.802 1.182 2.996.145.193 2.043 3.12 4.95 4.378.692.299 1.233.478 1.654.612.695.221 1.328.19 1.828.115.556-.083 1.716-.701 1.958-1.378.24-.677.24-1.258.17-1.378-.07-.12-.266-.193-.556-.339z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Telepon / WhatsApp</p>
                                @php
                                    $cleanPhoneAdmin = preg_replace('/[^0-9]/', '', $order->phone);
                                    if (strpos($cleanPhoneAdmin, '0') === 0) {
                                        $cleanPhoneAdmin = '62' . substr($cleanPhoneAdmin, 1);
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $cleanPhoneAdmin }}" target="_blank" rel="noopener noreferrer" class="text-xs font-bold hover:underline flex items-center gap-1" style="color: #059669;">
                                    {{ $order->phone }}
                                    <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Waktu Transaksi</p>
                                <p class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        @if($order->delivery_date)
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0 animate-pulse">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">Jadwal Kirim/Ambil</p>
                                <p class="text-xs font-black text-orange-600 dark:text-orange-400">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-sm dark:border-gray-800 dark:bg-gray-dark">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Alamat / Meja
                    </h3>
                    <div class="rounded-lg bg-gray-50 dark:bg-gray-900/50 px-3 py-2.5 border border-gray-100 dark:border-gray-800">
                        <p class="text-xs text-gray-700 dark:text-gray-200 whitespace-pre-wrap font-semibold leading-relaxed">{{ $order->shipping_address }}</p>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="rounded-xl border border-gray-200 bg-gray-50/50 px-5 py-4 shadow-sm dark:border-gray-800 dark:bg-gray-900/40">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Ringkasan Bayar</h3>
                    <dl class="space-y-2.5">
                        @php 
                            $subtotal = $order->orderItems->sum(function($item) {
                                return $item->price * $item->quantity;
                            });
                        @endphp
                        <div class="flex items-center justify-between text-xs">
                            <dt class="text-gray-500 font-medium">Subtotal</dt>
                            <dd class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                        </div>
                        @if($order->total_price > $subtotal)
                        <div class="flex items-center justify-between text-xs border-b border-gray-200 pb-2.5 dark:border-gray-700">
                            <dt class="text-gray-500 font-medium">Pajak (10%)</dt>
                            <dd class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($order->total_price - $subtotal, 0, ',', '.') }}</dd>
                        </div>
                        @endif
                        <div class="flex items-center justify-between pt-1">
                            <dt class="text-xs font-black text-gray-900 dark:text-white">Total</dt>
                            <dd class="text-base font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-pink-500">Rp {{ number_format($order->total_price, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
