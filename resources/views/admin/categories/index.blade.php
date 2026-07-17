@extends('layouts.admin')

@section('content')

<div class="p-4 sm:p-6 lg:p-8">
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Daftar Kategori</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola kategori menu untuk memudahkan pelanggan.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="flex-1 sm:flex-initial">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="w-full sm:w-56 pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:border-pink-500 focus:ring focus:ring-pink-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white transition-all duration-300 bg-gradient-to-r from-orange-500 to-pink-500 border border-transparent rounded-xl shadow-md hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Kategori
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
                            <th scope="col" class="py-3 pl-5 pr-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-16">ID</th>
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Nama Kategori</th>
                            <th scope="col" class="px-3 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Slug URL</th>
                            <th scope="col" class="relative py-3 pl-3 pr-5 text-right">
                                <span class="sr-only">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-dark">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="whitespace-nowrap py-3 pl-5 pr-3 text-xs font-bold text-gray-900 dark:text-white">
                                    {{ $category->id }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-xs font-semibold text-gray-900 dark:text-white">
                                    {{ $category->name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-xs text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $category->slug }}
                                </td>
                                <td class="relative whitespace-nowrap py-3 pl-3 pr-5 text-right text-xs font-semibold">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold text-blue-500 border border-blue-500 hover:bg-blue-500 hover:text-white transition-all shadow-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');" class="inline-block">
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
                                <td colspan="4" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500">
                                    <svg class="mx-auto h-8 w-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="font-semibold text-xs">{{ request('search') ? 'Tidak ada kategori yang cocok dengan pencarian.' : 'Belum ada kategori.' }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $categories->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
