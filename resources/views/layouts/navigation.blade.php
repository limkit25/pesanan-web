<nav x-data="{ open: false }" class="sticky top-0 w-full z-50 glass-nav transition-all duration-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/" class="flex items-center gap-2 cursor-pointer">
                        <x-application-logo class="w-10 h-10" />
                        <span class="font-extrabold text-2xl tracking-tight text-gray-900">Foodie<span class="text-orange-500">Hub</span></span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ request()->routeIs('home') ? '#menu' : route('home').'#menu' }}" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Menu</a>
                    <a href="{{ request()->routeIs('home') ? '#categories' : route('home').'#categories' }}" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Kategori</a>
                    @auth
                        @if(Auth::user()->role !== 'admin')
                            <a href="{{ route('orders.index') }}" class="font-semibold transition-colors {{ request()->routeIs('orders.*') ? 'text-orange-500' : 'text-gray-600 hover:text-orange-500' }}">Pesanan Saya</a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings & Cart Dropdown (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                @auth
                    @if(Auth::user()->role !== 'admin')
                        @php
                            $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                        @endphp
                        <a id="cart-icon" href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-orange-500 transition-all duration-300 p-2 mr-2 {{ request()->routeIs('cart.*') ? 'text-orange-500' : '' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span id="cart-badge" class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-pink-500 rounded-full transition-all duration-300">{{ $cartCount }}</span>
                        </a>
                    @endif
                @endauth

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-gray-100 text-sm font-semibold rounded-full text-gray-700 bg-white hover:text-orange-500 focus:outline-none transition duration-150 ease-in-out shadow-sm hover:shadow-md">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1.5">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="font-semibold text-gray-700 hover:text-orange-500 hover:bg-orange-50">
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>
                        
                        @if(Auth::user()->role === 'admin')
                            <x-dropdown-link :href="route('admin.dashboard')" class="font-semibold text-gray-700 hover:text-orange-500 hover:bg-orange-50">
                                {{ __('Dashboard Admin') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="font-semibold text-red-600 hover:text-red-700 hover:bg-red-50">
                                {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden gap-3">
                @auth
                    @if(Auth::user()->role !== 'admin')
                        @php
                            $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                        @endphp
                        <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-orange-500 p-2 mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-pink-500 rounded-full">{{ $cartCount }}</span>
                        </a>
                    @endif
                @endauth

                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/95 backdrop-blur-md border-b border-gray-100">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" class="rounded-xl font-semibold text-gray-700">
                {{ __('Beranda') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('home').'#menu'" class="rounded-xl font-semibold text-gray-700">
                {{ __('Menu') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('home').'#categories'" class="rounded-xl font-semibold text-gray-700">
                {{ __('Kategori') }}
            </x-responsive-nav-link>

            @if(Auth::user()->role !== 'admin')
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="rounded-xl font-semibold text-gray-700">
                    {{ __('Pesanan Saya') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-gray-100 px-4">
            <div class="px-4 mb-3">
                <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl font-semibold text-gray-700">
                    {{ __('Profil Saya') }}
                </x-responsive-nav-link>

                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" class="rounded-xl font-semibold text-gray-700">
                        {{ __('Dashboard Admin') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="rounded-xl font-semibold text-red-600 hover:text-red-700 hover:bg-red-50">
                        {{ __('Keluar') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
