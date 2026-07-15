@extends('layouts.admin')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    <!-- Hero Welcome Section -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-gray-900 to-gray-800 p-6 sm:p-10 shadow-lg">
        <!-- Decorative Background Elements -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-orange-500 to-pink-500 rounded-full mix-blend-screen opacity-20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-12 w-48 h-48 bg-gradient-to-tr from-pink-500 to-orange-400 rounded-full mix-blend-screen opacity-20 blur-2xl"></div>
        
        <div class="relative z-10">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mb-2">
                Selamat Datang Kembali, <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-pink-400">{{ auth()->user()->name }}</span>! 👋
            </h1>
            <p class="text-gray-400 text-sm sm:text-base max-w-2xl font-medium">
                Pusat kendali FoodieHub Anda siap digunakan. Pantau pesanan terbaru, kelola menu, dan tingkatkan performa bisnis Anda hari ini.
            </p>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white dark:bg-gray-dark border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-orange-200 transition-all duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pesanan</p>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalOrders ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-500/10 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white dark:bg-gray-dark border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-pink-200 transition-all duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Menunggu Diproses</p>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $pendingOrders ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-pink-50 dark:bg-pink-500/10 text-pink-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white dark:bg-gray-dark border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Produk</p>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalProducts ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white dark:bg-gray-dark border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kategori Menu</p>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalCategories ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <h2 class="text-xs font-bold text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Akses Cepat
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('admin.orders.index') }}" class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-dark border border-gray-100 dark:border-gray-800 p-6 shadow-sm hover:shadow-lg transition-all duration-300 flex items-center gap-5 hover:-translate-y-1">
                <div class="absolute right-0 top-0 w-32 h-32 bg-orange-500/5 rounded-bl-full -z-10 group-hover:bg-orange-500/10 transition-colors"></div>
                <div class="w-14 h-14 rounded-full bg-orange-50 dark:bg-orange-500/10 text-orange-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-orange-500 transition-colors">Kelola Pesanan Masuk</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Pantau pesanan baru dan perbarui status pengiriman.</p>
                </div>
            </a>

            <a href="{{ route('admin.products.create') }}" class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-dark border border-gray-100 dark:border-gray-800 p-6 shadow-sm hover:shadow-lg transition-all duration-300 flex items-center gap-5 hover:-translate-y-1">
                <div class="absolute right-0 top-0 w-32 h-32 bg-pink-500/5 rounded-bl-full -z-10 group-hover:bg-pink-500/10 transition-colors"></div>
                <div class="w-14 h-14 rounded-full bg-pink-50 dark:bg-pink-500/10 text-pink-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-pink-500 transition-colors">Tambah Menu Baru</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Unggah gambar, tentukan harga, dan rilis menu baru.</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
