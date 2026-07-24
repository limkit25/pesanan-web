@extends('layouts.admin')

@section('content')
    <div class="p-4 sm:p-6" x-data="orderEditor()">
        <!-- Top Header & Back Link -->
        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                    <span>Manajemen Pesanan</span>
                    <span>/</span>
                    <span class="text-brand-500">Buat Baru (POS)</span>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Pesanan Manual (POS)
                </h1>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-white border border-gray-200 px-3 py-1.5 text-xs font-bold text-gray-600 shadow-sm hover:bg-gray-50 transition-colors dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar
            </a>
        </div>

        @if(session('error'))
            <div class="mb-4 flex items-center gap-2 rounded-xl bg-red-50 px-3 py-2.5 border border-red-100 text-red-600 dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400" role="alert">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-xs font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.orders.store') }}" method="POST" id="create-order-form">
            @csrf

            <!-- Main Layout Grid -->
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                
                <!-- Left Side (Details & Items) -->
                <div class="lg:col-span-2 space-y-5">
                    
                    <!-- Basic Information -->
                    <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-sm dark:border-gray-800 dark:bg-gray-dark">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">Informasi Pesanan</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Tipe Pelanggan -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">Tipe Pelanggan</label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="customer_type" value="guest" x-model="customerType" class="text-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700">
                                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Pelanggan Baru (Guest)</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="customer_type" value="registered" x-model="customerType" class="text-orange-500 focus:ring-orange-500 dark:bg-gray-900 dark:border-gray-700">
                                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Pilih Pelanggan Terdaftar</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Customer Name (Guest) -->
                            <div x-show="customerType === 'guest'">
                                <label for="customer_name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">Nama Pelanggan (Guest) <span class="text-red-500">*</span></label>
                                <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" :required="customerType === 'guest'" placeholder="Contoh: Budi, Meja 5, dsb." class="block w-full rounded-lg border-gray-300 bg-gray-50 py-2 px-3 text-xs font-semibold focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            </div>

                            <!-- Registered User Select (Searchable) -->
                            <div x-show="customerType === 'registered'" style="display: none;" class="relative" x-data="{ 
                                search: '', 
                                open: false, 
                                selectedUserId: '{{ old('user_id') }}',
                                selectedUserName: '{{ old('user_id') ? collect($users)->firstWhere('id', old('user_id'))?->name ?? '' : '' }}',
                                users: @js($users->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email]))
                            }">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">Pilih Pelanggan <span class="text-red-500">*</span></label>
                                
                                <input type="hidden" name="user_id" x-model="selectedUserId" :required="customerType === 'registered'">
                                
                                <div @click.away="open = false" class="relative">
                                    <div @click="open = true" class="block w-full rounded-lg border border-gray-300 bg-gray-50 py-1.5 px-3 focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-500/20 dark:border-gray-800 dark:bg-gray-900 cursor-text flex items-center justify-between transition-all">
                                        <input type="text" 
                                               x-model="search" 
                                               @focus="open = true" 
                                               :placeholder="selectedUserName ? selectedUserName : 'Ketik nama / email...'" 
                                               class="bg-transparent border-none p-0 focus:ring-0 text-xs w-full font-semibold text-gray-900 dark:text-white placeholder-gray-500" 
                                               @input="selectedUserId = ''; selectedUserName = ''">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    
                                    <div x-show="open" x-transition class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 max-h-56 overflow-y-auto">
                                        <template x-for="user in users.filter(u => u.name.toLowerCase().includes(search.toLowerCase()) || u.email.toLowerCase().includes(search.toLowerCase()))" :key="user.id">
                                            <div @click="selectedUserId = user.id; selectedUserName = user.name; search = ''; open = false" class="px-3 py-2 cursor-pointer hover:bg-orange-50 dark:hover:bg-gray-700 border-b border-gray-50 dark:border-gray-700/50 last:border-0 transition-colors">
                                                <div class="text-xs font-bold text-gray-900 dark:text-white" x-text="user.name"></div>
                                                <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5" x-text="user.email"></div>
                                            </div>
                                        </template>
                                        <div x-show="users.filter(u => u.name.toLowerCase().includes(search.toLowerCase()) || u.email.toLowerCase().includes(search.toLowerCase())).length === 0" class="px-3 py-4 text-center text-xs text-gray-500">
                                            Tidak ada pelanggan ditemukan
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">Nomor Telepon / WA</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Opsional" class="block w-full rounded-lg border-gray-300 bg-gray-50 py-2 px-3 text-xs font-semibold focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            </div>

                            <!-- Status Pesanan -->
                            <div>
                                <label for="status" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">Status Pesanan</label>
                                <select id="status" name="status" class="block w-full rounded-lg border-gray-300 bg-gray-50 py-2 pl-3 pr-8 text-xs font-semibold focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                                    <option value="pending">Pending (Menunggu)</option>
                                    <option value="processing">Diproses</option>
                                    <option value="completed" selected>Selesai</option>
                                </select>
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label for="payment_method" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">Metode Pembayaran</label>
                                <select id="payment_method" name="payment_method" class="block w-full rounded-lg border-gray-300 bg-gray-50 py-2 pl-3 pr-8 text-xs font-semibold focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                                    <option value="cash">Bayar Tunai (Cash/Di Tempat)</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>

                            <!-- Payment Status -->
                            <div>
                                <label for="payment_status" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">Status Pembayaran</label>
                                <select id="payment_status" name="payment_status" x-model="paymentStatus" class="block w-full rounded-lg border-gray-300 bg-gray-50 py-2 pl-3 pr-8 text-xs font-semibold focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                                    <option value="paid">Lunas (Langsung Bayar)</option>
                                    <option value="unpaid">Belum Lunas (Bayar Nanti)</option>
                                    <option value="partial">Sebagian (DP)</option>
                                </select>
                            </div>

                            <!-- Nominal Dibayar (Hanya Muncul Jika Partial) -->
                            <div x-show="paymentStatus === 'partial'" x-transition class="md:col-span-2">
                                <label for="paid_amount" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">Nominal yang Dibayar / DP (Rp)</label>
                                <input type="number" id="paid_amount" name="paid_amount" value="{{ old('paid_amount', 0) }}" class="block w-full rounded-lg border-gray-300 bg-gray-50 py-2 px-3 text-xs font-semibold focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            </div>

                            <!-- Delivery Date / Schedule -->
                            <div class="md:col-span-2">
                                <label for="delivery_date" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">Jadwal Kirim / Ambil (Opsional)</label>
                                <input type="datetime-local" id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}" class="block w-full rounded-lg border-gray-300 bg-gray-50 py-2 px-3 text-xs font-semibold focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            </div>

                            <!-- Address / Table -->
                            <div class="md:col-span-2">
                                <label for="shipping_address" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">Catatan / Alamat / Nomor Meja (Opsional)</label>
                                <textarea id="shipping_address" name="shipping_address" rows="2" class="block w-full rounded-lg border-gray-300 bg-gray-50 py-2 px-3 text-xs font-semibold focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 dark:border-gray-800 dark:bg-gray-900 dark:text-white">{{ old('shipping_address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Items Ordered -->
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-dark overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Daftar Menu Dipesan</h3>
                            <span class="bg-orange-50 text-orange-500 text-[10px] font-bold px-2 py-0.5 rounded-full" x-text="totalQty + ' item'"></span>
                        </div>

                        <!-- Add Item Controls -->
                        <div class="p-4 bg-gray-50/50 dark:bg-gray-900/30 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row gap-3 items-end">
                            <div class="flex-1 w-full">
                                <label for="product_select" class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Tambah Menu Baru</label>
                                <select id="product_select" x-model="selectedProductId" class="block w-full rounded-lg border-gray-300 bg-white py-1.5 pl-3 pr-8 text-xs font-semibold focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                                    <option value="">-- Pilih Menu --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->image }}">
                                            {{ $product->name }} (Rp {{ number_format($product->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" @click="addItem()" class="w-full sm:w-auto bg-gray-900 text-white dark:bg-orange-500 hover:bg-gray-800 dark:hover:bg-orange-600 font-bold px-4 py-2 rounded-lg text-xs transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah ke Pesanan
                            </button>
                        </div>

                        <!-- Items Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                                <thead class="bg-gray-50/50 dark:bg-gray-900/20">
                                    <tr>
                                        <th scope="col" class="py-2.5 pl-5 pr-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">Menu</th>
                                        <th scope="col" class="px-3 py-2.5 text-left text-[10px] font-bold uppercase tracking-wider text-gray-400">Harga</th>
                                        <th scope="col" class="px-3 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-gray-400" style="width: 120px;">Jumlah</th>
                                        <th scope="col" class="px-3 py-2.5 text-right text-[10px] font-bold uppercase tracking-wider text-gray-400">Subtotal</th>
                                        <th scope="col" class="relative py-2.5 pl-3 pr-5 text-right" style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    <template x-for="(item, index) in items" :key="item.product_id">
                                        <tr class="hover:bg-gray-50/30 dark:hover:bg-white/[0.01] transition-colors">
                                            <td class="whitespace-nowrap py-3 pl-5 pr-3 text-xs font-bold text-gray-900 dark:text-white">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="flex-shrink-0 overflow-hidden rounded-lg border border-gray-100 dark:border-gray-800 bg-gray-50" style="width: 48px; height: 48px;">
                                                        <img :src="item.image" :alt="item.name" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                    <div>
                                                        <span class="font-bold text-gray-900 dark:text-white" x-text="item.name"></span>
                                                        <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-600 dark:text-gray-300 font-semibold" x-text="formatRupiah(item.price)"></td>
                                            <td class="whitespace-nowrap px-3 py-3 text-center">
                                                <div class="inline-flex items-center gap-1">
                                                    <button type="button" @click="decreaseQty(index)" class="w-6 h-6 rounded bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold flex items-center justify-center text-xs">-</button>
                                                    <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity" min="1" @input="validateQty(index)" class="w-12 h-6 text-center text-xs font-bold rounded border-gray-200 bg-white py-0 dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                                                    <button type="button" @click="increaseQty(index)" class="w-6 h-6 rounded bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold flex items-center justify-center text-xs">+</button>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-3 text-right text-xs font-bold text-orange-500" x-text="formatRupiah(item.price * item.quantity)"></td>
                                            <td class="whitespace-nowrap py-3 pl-3 pr-5 text-right text-xs">
                                                <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="items.length === 0">
                                        <td colspan="5" class="py-8 text-center text-gray-400 dark:text-gray-500">
                                            <p class="text-xs font-bold">Belum ada menu di dalam pesanan. Silakan pilih menu di atas.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Side (Summary & Actions) -->
                <div class="space-y-5">
                    <!-- Pricing Summary -->
                    <div class="rounded-xl border border-gray-200 bg-gray-50/50 px-5 py-4 shadow-sm dark:border-gray-800 dark:bg-gray-900/40">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Estimasi Ringkasan Bayar</h3>
                        <dl class="space-y-2.5">
                            <div class="flex items-center justify-between text-xs">
                                <dt class="text-gray-500 font-medium">Subtotal</dt>
                                <dd class="font-bold text-gray-900 dark:text-white" x-text="formatRupiah(subtotal)"></dd>
                            </div>
                            
                            @php
                                $taxSetting = \App\Models\Setting::where('key', 'tax_enabled')->first();
                                $taxEnabled = $taxSetting ? $taxSetting->value == '1' : true;
                            @endphp

                            @if($taxEnabled)
                                <div class="flex items-center justify-between text-xs border-b border-gray-200 pb-2.5 dark:border-gray-700">
                                    <dt class="text-gray-500 font-medium">Pajak (10%)</dt>
                                    <dd class="font-bold text-gray-900 dark:text-white" x-text="formatRupiah(tax)"></dd>
                                </div>
                            @endif

                            <div class="flex items-center justify-between pt-1">
                                <dt class="text-xs font-black text-gray-900 dark:text-white">Total Akhir</dt>
                                <dd class="text-base font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-pink-500" x-text="formatRupiah(total)"></dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Actions -->
                    <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-sm dark:border-gray-800 dark:bg-gray-dark">
                        <button type="submit" :disabled="items.length === 0" class="w-full bg-gradient-to-r from-orange-500 to-pink-500 hover:from-orange-600 hover:to-pink-600 text-white font-bold py-2.5 px-4 rounded-lg shadow-sm active:scale-98 transition-all text-xs flex items-center justify-center gap-1.5 disabled:opacity-50 disabled:pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            Buat Pesanan Sekarang
                        </button>
                        
                        <a href="{{ route('admin.orders.index') }}" class="mt-2.5 block text-center border border-gray-200 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-gray-850 hover:bg-gray-50 text-gray-600 font-bold py-2 px-4 rounded-lg text-xs transition-all">
                            Batal
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function orderEditor() {
        return {
            items: [],
            selectedProductId: '',
            taxEnabled: @json(\App\Models\Setting::where('key', 'tax_enabled')->first()?->value == '1'),
            paymentStatus: 'paid',
            customerType: 'guest',
            
            get totalQty() {
                return this.items.reduce((sum, item) => sum + item.quantity, 0);
            },
            
            get subtotal() {
                return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            },
            
            get tax() {
                return this.taxEnabled ? (this.subtotal * 0.1) : 0;
            },
            
            get total() {
                return this.subtotal + this.tax;
            },
            
            addItem() {
                if (!this.selectedProductId) return;
                
                const selectEl = document.getElementById('product_select');
                const selectedOpt = selectEl.options[selectEl.selectedIndex];
                
                const productId = parseInt(this.selectedProductId);
                const name = selectedOpt.getAttribute('data-name');
                const price = parseFloat(selectedOpt.getAttribute('data-price'));
                const image = selectedOpt.getAttribute('data-image');
                
                // Check if already in items
                const existingIndex = this.items.findIndex(item => item.product_id === productId);
                if (existingIndex > -1) {
                    this.items[existingIndex].quantity += 1;
                } else {
                    this.items.push({
                        product_id: productId,
                        name: name,
                        image: image,
                        price: price,
                        quantity: 1
                    });
                }
                
                // Reset select
                this.selectedProductId = '';
            },
            
            removeItem(index) {
                this.items.splice(index, 1);
            },
            
            increaseQty(index) {
                this.items[index].quantity += 1;
            },
            
            decreaseQty(index) {
                if (this.items[index].quantity > 1) {
                    this.items[index].quantity -= 1;
                }
            },
            
            validateQty(index) {
                if (isNaN(this.items[index].quantity) || this.items[index].quantity < 1) {
                    this.items[index].quantity = 1;
                }
            },
            
            formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(number);
            }
        }
    }
</script>
@endpush
