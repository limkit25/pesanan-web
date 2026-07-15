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
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <h3 class="text-lg font-bold">Daftar Kategori</h3>
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                            <form action="{{ route('admin.categories.index') }}" method="GET" class="flex-1 sm:flex-initial">
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="w-full sm:w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-300 text-sm focus:border-pink-500 focus:ring focus:ring-pink-200">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </form>
                            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 text-sm font-semibold text-center">
                                + Tambah Kategori
                            </a>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b">
                                    <th class="p-4 font-semibold text-sm">ID</th>
                                    <th class="p-4 font-semibold text-sm">Nama</th>
                                    <th class="p-4 font-semibold text-sm">Slug</th>
                                    <th class="p-4 font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-4">{{ $category->id }}</td>
                                    <td class="p-4 font-medium">{{ $category->name }}</td>
                                    <td class="p-4 text-gray-500">{{ $category->slug }}</td>
                                    <td class="p-4 flex gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:underline text-sm font-semibold">Edit</a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-sm font-semibold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-400">
                                        <p class="font-semibold text-sm">{{ request('search') ? 'Tidak ada kategori yang cocok dengan pencarian.' : 'Belum ada kategori.' }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($categories->hasPages())
                        <div class="mt-4">
                            {{ $categories->appends(request()->query())->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
