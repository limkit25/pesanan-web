@extends('layouts.admin')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Tambah Pengguna Baru</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Buat akun baru untuk staf admin atau customer.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 px-4 py-2 rounded-xl transition-colors dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
            &larr; Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl bg-red-50 p-5 border border-red-100 dark:bg-red-500/10 dark:border-red-500/20">
            <h3 class="text-sm font-bold text-red-800 dark:text-red-400 mb-2">Terdapat beberapa kesalahan:</h3>
            <ul class="list-disc pl-5 text-sm text-red-700 dark:text-red-300 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden dark:bg-gray-800/50 dark:border-gray-700">
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">Nama Lengkap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-200 text-gray-900 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-colors dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Masukkan nama lengkap" required>
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-200 text-gray-900 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-colors dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="contoh@email.com" required>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" name="password" id="password" class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-200 text-gray-900 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-colors dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Min. 8 karakter" required>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-200 text-gray-900 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-colors dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Ulangi password" required>
                    </div>
                </div>
            </div>

            <!-- Role Selection -->
            <div>
                <label class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-3">Pilih Peran (Role)</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <label class="relative flex cursor-pointer">
                        <input type="radio" name="role" value="customer" class="peer sr-only" {{ old('role', 'customer') == 'customer' ? 'checked' : '' }}>
                        <div class="w-full rounded-2xl border-2 border-gray-100 bg-white p-5 text-gray-600 transition-all hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50/50 peer-checked:text-blue-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:peer-checked:border-blue-500 dark:peer-checked:bg-blue-900/20 dark:peer-checked:text-blue-300">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-500 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm">Customer</h4>
                                    <p class="text-[11px] mt-0.5 opacity-80">Akses berbelanja.</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex cursor-pointer">
                        <input type="radio" name="role" value="dapur" class="peer sr-only" {{ old('role') == 'dapur' ? 'checked' : '' }}>
                        <div class="w-full rounded-2xl border-2 border-gray-100 bg-white p-5 text-gray-600 transition-all hover:bg-gray-50 peer-checked:border-pink-500 peer-checked:bg-pink-50/50 peer-checked:text-pink-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:peer-checked:border-pink-500 dark:peer-checked:bg-pink-900/20 dark:peer-checked:text-pink-300">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-pink-100 dark:bg-pink-900/50 text-pink-500 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm">Dapur</h4>
                                    <p class="text-[11px] mt-0.5 opacity-80">Akses kelola produksi.</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex cursor-pointer">
                        <input type="radio" name="role" value="admin" class="peer sr-only" {{ old('role') == 'admin' ? 'checked' : '' }}>
                        <div class="w-full rounded-2xl border-2 border-gray-100 bg-white p-5 text-gray-600 transition-all hover:bg-gray-50 peer-checked:border-orange-500 peer-checked:bg-orange-50/50 peer-checked:text-orange-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:peer-checked:border-orange-500 dark:peer-checked:bg-orange-900/20 dark:peer-checked:text-orange-300">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/50 text-orange-500 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm">Admin</h4>
                                    <p class="text-[11px] mt-0.5 opacity-80">Akses penuh fitur.</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl shadow-md transition-all duration-300 hover:-translate-y-0.5 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
