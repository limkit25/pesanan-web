@extends('layouts.admin')

@section('content')

    <div class="p-4 sm:p-6">
        <div class="sm:flex sm:items-center sm:justify-between mb-5">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">Daftar Produk</h1>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kelola semua menu hidangan dan minuman yang tersedia.</p>
            </div>
            <div class="mt-3 sm:mt-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <form action="{{ route('admin.products.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk atau kategori..." class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:border-brand-500 focus:ring focus:ring-brand-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-brand-500 dark:focus:ring-brand-500/20">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-500 text-white rounded-lg hover:bg-brand-600 text-sm font-semibold shadow-theme-sm transition-colors w-full sm:w-auto whitespace-nowrap dark:bg-brand-500 dark:hover:bg-brand-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Produk
                </a>
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
                            <th scope="col" class="py-3 pl-5 pr-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Gambar</th>
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Nama Produk</th>
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Kategori</th>
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Harga Jual</th>
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Harga Modal</th>
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Stok</th>
                            <th scope="col" class="relative py-3 pl-3 pr-5 text-right">
                                <span class="sr-only">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-dark">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors {{ $product->is_active ? '' : 'opacity-70' }}">
                                <td class="whitespace-nowrap py-3 pl-5 pr-3 text-xs">
                                    @if($product->image)
                                        <div class="h-10 w-10 flex-shrink-0 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-10 w-10 object-cover {{ $product->is_active ? '' : 'grayscale' }}">
                                        </div>
                                    @else
                                        <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center border border-gray-200 dark:border-gray-700 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-xs">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $product->name }}</div>
                                    @if(!$product->is_active)
                                        <div class="text-[9px] font-semibold text-gray-500 mt-0.5">(Disembunyikan)</div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-xs">
                                    @if($product->is_active)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-success-50 px-2 py-0.5 text-[10px] font-bold text-success-600 dark:bg-success-500/10 dark:text-success-400 border border-success-100 dark:border-success-500/20">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-bold text-gray-600 dark:bg-gray-800 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-600 dark:text-gray-300 font-medium">
                                    {{ $product->category->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-xs font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                    Rp {{ number_format($product->cost_price, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-xs font-bold {{ $product->stock > 10 ? 'text-gray-900 dark:text-white' : 'text-red-500' }}">
                                    {{ $product->stock }}
                                </td>
                                <td class="relative whitespace-nowrap py-3 pl-3 pr-5 text-right text-xs font-semibold">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold text-blue-500 border border-blue-500 hover:bg-blue-500 hover:text-white transition-all shadow-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold text-red-500 border border-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500">
                                    <svg class="mx-auto h-8 w-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="font-semibold text-xs">{{ request('search') ? 'Tidak ada produk yang cocok dengan pencarian.' : 'Belum ada produk.' }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
