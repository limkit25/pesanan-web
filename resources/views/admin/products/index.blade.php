@extends('layouts.admin')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Daftar Produk</h3>
                        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 text-sm font-semibold">
                            + Tambah Produk
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b">
                                    <th class="p-4 font-semibold text-sm">Gambar</th>
                                    <th class="p-4 font-semibold text-sm">Nama Produk</th>
                                    <th class="p-4 font-semibold text-sm">Kategori</th>
                                    <th class="p-4 font-semibold text-sm">Harga</th>
                                    <th class="p-4 font-semibold text-sm">Stok</th>
                                    <th class="p-4 font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-4">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-md">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded-md"></div>
                                        @endif
                                    </td>
                                    <td class="p-4 font-medium">{{ $product->name }}</td>
                                    <td class="p-4">{{ $product->category->name ?? '-' }}</td>
                                    <td class="p-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="p-4">{{ $product->stock }}</td>
                                    <td class="p-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline text-sm font-semibold">Edit</a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline text-sm font-semibold">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
