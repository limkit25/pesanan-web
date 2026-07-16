@extends('layouts.admin')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Edit Pengguna</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ubah informasi akun atau reset password pengguna.</p>
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
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">Nama Lengkap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-200 text-gray-900 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-colors dark:bg-gray-900 dark:border-gray-700 dark:text-white" required>
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-200 text-gray-900 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-colors dark:bg-gray-900 dark:border-gray-700 dark:text-white" required>
                </div>
            </div>

            <div class="pt-4 mt-2 border-t border-gray-100 dark:border-gray-700">
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Ubah Password <span class="text-gray-400 font-normal">(Opsional)</span></h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kosongkan bidang ini jika Anda tidak ingin mengubah password.</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Password -->
                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">Password (Kosongkan jika tidak diubah)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input x-bind:type="show ? 'text' : 'password'" name="password" id="password" class="block w-full pl-11 pr-10 py-3 bg-gray-50 border-gray-200 text-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Biarkan kosong jika tidak mengubah password">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-cloak x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                    <!-- Confirm Password -->
                <div x-data="{ show: false }">
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-2">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <input x-bind:type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" class="block w-full pl-11 pr-10 py-3 bg-gray-50 border-gray-200 text-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-colors dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Ulangi password baru">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-cloak x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>
                </div>
            </div>

            <!-- Role Selection -->
            <div class="pt-4 mt-2 border-t border-gray-100 dark:border-gray-700">
                <label class="block text-sm font-bold text-gray-900 dark:text-gray-200 mb-3">Ubah Peran (Role)</label>
                
                @if($user->id === auth()->id())
                    <div class="p-4 bg-orange-50 border border-orange-100 rounded-xl text-orange-700 text-sm font-medium flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Anda tidak dapat mengubah peran Anda sendiri saat sedang login untuk mencegah hilangnya akses admin.
                    </div>
                    <input type="hidden" name="role" value="{{ $user->role }}">
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="role" value="customer" class="peer sr-only" {{ old('role', $user->role) == 'customer' ? 'checked' : '' }}>
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
                            <input type="radio" name="role" value="dapur" class="peer sr-only" {{ old('role', $user->role) == 'dapur' ? 'checked' : '' }}>
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
                            <input type="radio" name="role" value="admin" class="peer sr-only" {{ old('role', $user->role) == 'admin' ? 'checked' : '' }}>
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
                @endif
            </div>

            <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl shadow-md transition-all duration-300 hover:-translate-y-0.5 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
