@extends('layouts.admin')

@section('content')
<div class="p-4 sm:p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Pengaturan Sistem</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola konfigurasi global aplikasi FoodieHub.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-2xl flex items-center gap-2.5 shadow-sm" role="alert">
            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-semibold text-xs">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-2xl">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-50 pb-2">Pengaturan Pajak (Tax)</h2>
                
                <div class="flex items-center justify-between">
                    <div>
                        <label class="font-bold text-gray-800 text-sm">Aktifkan Pajak Pesanan (10%)</label>
                        <p class="text-xs text-gray-500 mt-1">Jika diaktifkan, setiap pesanan yang dilakukan pelanggan akan dikenakan tambahan pajak sebesar 10% dari subtotal.</p>
                    </div>
                    
                    <input type="hidden" name="tax_enabled" value="0">
                    <label class="relative inline-flex items-center cursor-pointer ml-4">
                        <input type="checkbox" name="tax_enabled" value="1" class="sr-only peer" {{ $taxEnabledSetting->value == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                    </label>
                </div>
            </div>
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-50 pb-2">Pengaturan Pembayaran Transfer</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="font-bold text-gray-800 text-sm block mb-1">Nama Bank</label>
                        <input type="text" name="bank_name" value="{{ $bankNameSetting->value }}" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200/50 text-sm" placeholder="Contoh: BCA">
                        @error('bank_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="font-bold text-gray-800 text-sm block mb-1">Nomor Rekening</label>
                        <input type="text" name="bank_account" value="{{ $bankAccountSetting->value }}" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200/50 text-sm" placeholder="Contoh: 1234567890">
                        @error('bank_account') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="font-bold text-gray-800 text-sm block mb-1">Atas Nama (Pemilik Rekening)</label>
                        <input type="text" name="bank_owner" value="{{ $bankOwnerSetting->value }}" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200/50 text-sm" placeholder="Contoh: FoodieHub Official">
                        @error('bank_owner') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div class="flex justify-end pt-4 border-t border-gray-50">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl shadow-md hover:bg-gray-800 hover:-translate-y-0.5 transition-all duration-300">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
