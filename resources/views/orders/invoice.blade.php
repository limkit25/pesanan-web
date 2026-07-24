<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 8px;
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none;
                padding: 20px 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Navigasi (Sembunyi saat cetak) -->
    <div class="max-w-[800px] mx-auto mb-6 flex justify-between items-center no-print">
        @if(auth()->check())
            @php
                $backRoute = auth()->user()->role === 'admin' 
                    ? route('admin.orders.show', $order->id) 
                    : route('orders.show', $order->id);
            @endphp
            <a href="{{ $backRoute }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        @else
            <div></div> <!-- Spacer -->
        @endif
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-bold shadow-md transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak / Download PDF
        </button>
    </div>

    <!-- Area Invoice -->
    <div class="invoice-container">
        
        <!-- Header -->
        <div class="flex justify-between items-start border-b-2 border-gray-100 pb-8 mb-8">
            <div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">INVOICE</h1>
                <p class="text-gray-500 mt-1 font-medium">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="text-right">
                <div class="text-xl font-bold text-gray-900">FoodieHub</div>
                <p class="text-sm text-gray-500 mt-1">Sistem Pemesanan Restoran</p>
                <p class="text-sm text-gray-500">Jakarta, Indonesia</p>
            </div>
        </div>

        <!-- Info Pembeli & Tagihan -->
        <div class="flex justify-between items-start mb-8">
            <div class="w-1/2 pr-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Ditagihkan Kepada:</h3>
                <p class="font-bold text-gray-900 text-lg">{{ $order->user ? $order->user->name : ($order->customer_name ?? 'Pelanggan Umum') }}</p>
                @if($order->user)
                    <p class="text-sm text-gray-600 mt-1">{{ $order->user->email }}</p>
                @endif
                <p class="text-sm text-gray-600 mt-1">{{ $order->phone ?? '-' }}</p>
                <p class="text-sm text-gray-600 mt-2 whitespace-pre-wrap">{{ $order->shipping_address ?? 'Alamat tidak tersedia' }}</p>
            </div>
            <div class="w-1/2 pl-4 text-right">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Detail Pesanan:</h3>
                <table class="w-full text-sm">
                    <tr>
                        <td class="text-gray-500 py-1 text-right pr-4">Tanggal Pesan:</td>
                        <td class="font-semibold text-gray-900 text-right">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 py-1 text-right pr-4">Jadwal Kirim:</td>
                        <td class="font-semibold text-gray-900 text-right">{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 py-1 text-right pr-4">Metode Bayar:</td>
                        <td class="font-semibold text-gray-900 text-right uppercase">{{ $order->payment_method }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 py-1 text-right pr-4">Status Bayar:</td>
                        <td class="text-right">
                            @if($order->payment_status === 'paid')
                                <span class="font-bold text-emerald-600">LUNAS</span>
                            @elseif($order->payment_status === 'partial')
                                <span class="font-bold text-yellow-600">SEBAGIAN (DP)</span>
                            @elseif($order->status === 'cancelled')
                                <span class="font-bold text-red-600">BATAL</span>
                            @else
                                <span class="font-bold text-red-600">BELUM LUNAS</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Tabel Item -->
        <table class="w-full mb-8">
            <thead>
                <tr class="border-y-2 border-gray-100">
                    <th class="py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Harga</th>
                    <th class="py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-16">Qty</th>
                    <th class="py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php $subtotalRaw = 0; @endphp
                @foreach($order->orderItems as $item)
                    @php 
                        $itemTotal = $item->price * $item->quantity; 
                        $subtotalRaw += $itemTotal;
                    @endphp
                    <tr>
                        <td class="py-4">
                            <p class="font-bold text-gray-900">{{ $item->product ? $item->product->name : 'Produk Dihapus' }}</p>
                        </td>
                        <td class="py-4 text-center text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="py-4 text-center text-gray-900 font-semibold">{{ $item->quantity }}</td>
                        <td class="py-4 text-right font-bold text-gray-900">Rp {{ number_format($itemTotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Ringkasan Total -->
        <div class="flex justify-end border-t-2 border-gray-100 pt-6">
            <div class="w-1/2 md:w-1/3">
                <div class="flex justify-between py-2 text-sm text-gray-600">
                    <span>Subtotal:</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($subtotalRaw, 0, ',', '.') }}</span>
                </div>
                
                @php
                    $isTaxEnabled = \App\Models\Setting::where('key', 'tax_enabled')->first()->value ?? '1';
                    $taxAmount = 0;
                    if ($isTaxEnabled == '1') {
                        $taxAmount = $subtotalRaw * 0.1;
                    }
                @endphp
                
                @if($taxAmount > 0)
                <div class="flex justify-between py-2 text-sm text-gray-600 border-b border-gray-100">
                    <span>Pajak (10%):</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
                </div>
                @endif
                
                <div class="flex justify-between py-3 mt-2 text-lg font-black text-gray-900 rounded-lg">
                    <span>TOTAL:</span>
                    <span class="text-orange-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                
                @if($order->payment_status === 'partial')
                <div class="flex justify-between py-2 text-sm text-gray-600">
                    <span>Telah Dibayar (DP):</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 text-sm font-bold text-red-600 border-t border-gray-100 mt-1">
                    <span>Sisa Tagihan:</span>
                    <span>Rp {{ number_format($order->total_price - $order->paid_amount, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-16 pt-8 border-t border-gray-100 text-center text-sm text-gray-500">
            <p class="font-bold text-gray-900">Terima kasih atas pesanan Anda!</p>
            <p class="mt-1">Invoice ini sah dan diproses secara otomatis oleh komputer.</p>
        </div>

    </div>
</body>
</html>
