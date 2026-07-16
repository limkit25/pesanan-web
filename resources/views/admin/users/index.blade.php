@extends('layouts.admin')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Manajemen Pengguna</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola akun Admin dan Customer sistem FoodieHub.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex-1 sm:flex-initial">
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="w-full sm:w-56 pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:border-pink-500 focus:ring focus:ring-pink-200">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white transition-all duration-300 bg-gradient-to-r from-orange-500 to-pink-500 border border-transparent rounded-xl shadow-md hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Pengguna
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 px-5 py-4 border border-emerald-100 text-emerald-800 shadow-sm dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400" role="alert">
            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-red-50 px-5 py-4 border border-red-100 text-red-800 shadow-sm dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400" role="alert">
            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <span class="text-sm font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Role Filter Tabs -->
    <div class="mb-6 flex gap-2 overflow-x-auto custom-scrollbar pb-2">
        <a href="{{ route('admin.users.index') }}" class="px-5 py-2 rounded-xl text-sm font-bold transition-all duration-300 {{ !request('role') ? 'bg-gray-900 text-white shadow-md dark:bg-white dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700' }}">Semua</a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="px-5 py-2 rounded-xl text-sm font-bold transition-all duration-300 {{ request('role') == 'admin' ? 'bg-orange-50 text-orange-600 border border-orange-200 shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700' }}">Admin</a>
        <a href="{{ route('admin.users.index', ['role' => 'dapur']) }}" class="px-5 py-2 rounded-xl text-sm font-bold transition-all duration-300 {{ request('role') == 'dapur' ? 'bg-pink-50 text-pink-600 border border-pink-200 shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700' }}">Staf Dapur</a>
        <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" class="px-5 py-2 rounded-xl text-sm font-bold transition-all duration-300 {{ request('role') == 'customer' ? 'bg-blue-50 text-blue-600 border border-blue-200 shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700' }}">Customer</a>
    </div>

    <!-- Users Table -->
    <div class="overflow-hidden bg-white shadow-sm border border-gray-100 rounded-2xl dark:bg-gray-800/50 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/50">
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-[11px] font-bold tracking-wider text-gray-500 uppercase dark:text-gray-400">Nama Pengguna</th>
                        <th scope="col" class="px-3 py-4 text-left text-[11px] font-bold tracking-wider text-gray-500 uppercase dark:text-gray-400">Email</th>
                        <th scope="col" class="px-3 py-4 text-left text-[11px] font-bold tracking-wider text-gray-500 uppercase dark:text-gray-400">Peran</th>
                        <th scope="col" class="px-3 py-4 text-left text-[11px] font-bold tracking-wider text-gray-500 uppercase dark:text-gray-400">Terdaftar Pada</th>
                        <th scope="col" class="py-4 pl-3 pr-6 text-right text-[11px] font-bold tracking-wider text-gray-500 uppercase dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse ($users as $user)
                        <tr class="transition-colors hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
                            <td class="py-4 pl-6 pr-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold shadow-inner">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->email }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-600 border border-orange-100 dark:bg-orange-500/10 dark:border-orange-500/20 dark:text-orange-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        Admin
                                    </span>
                                @elseif($user->role === 'dapur')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-pink-50 text-pink-600 border border-pink-100 dark:bg-pink-500/10 dark:border-pink-500/20 dark:text-pink-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                        Staf Dapur
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100 dark:bg-blue-500/10 dark:border-blue-500/20 dark:text-blue-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Customer
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-gray-400 transition-colors bg-white border border-gray-200 rounded-xl hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700" title="Edit Pengguna">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 transition-colors bg-white border border-gray-200 rounded-xl hover:text-red-600 hover:border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700" title="Hapus Pengguna">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p class="text-base font-bold text-gray-500 dark:text-gray-400">Belum ada pengguna ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 dark:border-gray-700 dark:bg-gray-900/50">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
