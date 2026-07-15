@extends('layouts.admin')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Edit Profil</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui informasi profil dan kata sandi akun Admin Anda.</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 px-5 py-4 border border-emerald-100 text-emerald-800 shadow-sm dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400" role="alert">
            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-bold">Profil berhasil diperbarui.</span>
        </div>
    @endif

    <div class="space-y-6 max-w-3xl">
        <div class="p-6 bg-white shadow-sm border border-gray-100 rounded-2xl dark:bg-gray-800 dark:border-gray-700">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 bg-white shadow-sm border border-gray-100 rounded-2xl dark:bg-gray-800 dark:border-gray-700">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</div>
@endsection
