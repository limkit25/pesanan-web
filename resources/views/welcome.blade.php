<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Vite Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .gradient-text {
            background: linear-gradient(90deg, #ff512f 0%, #dd2476 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-bg {
            background: radial-gradient(circle at 10% 20%, rgba(255, 237, 213, 1) 0%, rgba(255, 255, 255, 1) 90%);
        }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800">
    
    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="/" class="flex-shrink-0 flex items-center gap-2 cursor-pointer">
                    <x-application-logo class="w-10 h-10" />
                    <span class="font-extrabold text-2xl tracking-tight">Foodie<span class="text-orange-500">Hub</span></span>
                </a>
                
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#menu" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Menu</a>
                    <a href="#categories" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Kategori</a>
                    @auth
                        @if(Auth::user()->role !== 'admin')
                            <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Pesanan Saya</a>
                        @endif
                    @endauth
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        @php
                            $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                        @endphp
                        <a id="cart-icon" href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-orange-500 transition-all duration-300 p-2 mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span id="cart-badge" class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-pink-500 rounded-full transition-all duration-300">{{ $cartCount }}</span>
                        </a>
                    @endauth

                    @if (Route::has('login'))
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="font-semibold text-gray-600 hover:text-orange-500 transition-colors">Dashboard Admin</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="ml-4 px-4 py-2 rounded-full bg-red-50 text-red-600 font-semibold hover:bg-red-100 transition-all">Keluar</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-orange-500 transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-gray-900 text-white font-semibold hover:bg-gray-800 transition-all hover:shadow-lg hover:-translate-y-0.5">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg min-h-screen flex items-center pt-20 relative overflow-hidden">
        <!-- Decorative blobs -->
        <div class="absolute top-20 right-10 w-72 h-72 bg-orange-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-40 right-40 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8 text-center lg:text-left">
                    <div class="inline-block px-4 py-1.5 rounded-full bg-orange-100 text-orange-600 font-semibold text-sm mb-4">
                        🔥 Sensasi Rasa Terbaik di Kota Anda
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight">
                        Pesan Makanan <br />
                        <span class="gradient-text">Lebih Cepat & Mudah</span>
                    </h1>
                    <p class="text-xl text-gray-600 max-w-lg mx-auto lg:mx-0">
                        Jelajahi berbagai menu favorit dari koki terbaik kami. Dari makanan berat hingga camilan lezat, semua siap diantar ke pintu Anda.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                        <a href="#menu" class="px-8 py-4 rounded-full bg-gradient-to-r from-orange-500 to-pink-500 text-white font-bold text-lg hover:shadow-xl hover:shadow-orange-200 hover:-translate-y-1 transition-all duration-300">
                            Pesan Sekarang
                        </a>
                        <a href="#" class="px-8 py-4 rounded-full bg-white text-gray-800 font-bold text-lg shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 flex items-center justify-center gap-2">
                            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Lihat Promo
                        </a>
                    </div>
                </div>
                <div class="hidden lg:block relative">
                    <div class="relative w-full h-[600px] flex items-center justify-center">
                        <div class="absolute inset-0 bg-gradient-to-tr from-orange-400 to-pink-500 rounded-full blur-3xl opacity-20"></div>
                        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Delicious Food" class="rounded-[40px] shadow-2xl relative z-10 w-4/5 object-cover h-4/5 transform hover:scale-105 transition-transform duration-700 ease-out">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Eksplorasi Kategori</h2>
                <div class="w-24 h-1 bg-orange-500 mx-auto rounded-full"></div>
            </div>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/#menu" class="px-6 py-3 rounded-2xl border-2 transition-all duration-300 font-semibold hover:shadow-md hover:-translate-y-1 {{ empty($categoryId) ? 'border-orange-500 bg-orange-50 text-orange-600' : 'border-gray-100 text-gray-700 hover:border-orange-400 hover:bg-orange-50' }}">
                    Semua Kategori
                </a>
                @foreach($categories as $category)
                <a href="?category={{ $category->id }}#menu" class="px-6 py-3 rounded-2xl border-2 transition-all duration-300 font-semibold hover:shadow-md hover:-translate-y-1 {{ $categoryId == $category->id ? 'border-orange-500 bg-orange-50 text-orange-600' : 'border-gray-100 text-gray-700 hover:border-orange-400 hover:bg-orange-50' }}">
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Product Grid Section -->
    <section id="menu" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-2">Menu Favorit</h2>
                    <p class="text-gray-500">Pilihan hidangan terbaik khusus untuk Anda</p>
                </div>
                <a href="#" class="hidden sm:inline-flex items-center gap-2 text-orange-500 font-bold hover:text-orange-600 transition-colors">
                    Lihat Semua Menu
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 group border border-gray-100">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out">
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-bold text-gray-800 shadow-sm">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white p-2.5 rounded-full text-pink-500 shadow-lg hover:bg-pink-50 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-orange-500 transition-colors line-clamp-1" title="{{ $product->name }}">
                                {{ $product->name }}
                            </h3>
                        </div>
                        <p class="text-gray-500 text-sm mb-6 line-clamp-2">{{ $product->description }}</p>
                        
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Harga</span>
                                <span class="text-2xl font-extrabold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form flex items-center gap-2">
                                @csrf
                                <!-- Qty Selector -->
                                <div class="flex items-center border border-gray-200 rounded-2xl overflow-hidden bg-gray-50 shadow-inner h-10">
                                    <button type="button" onclick="let input = this.parentNode.querySelector('input'); if(input.value > 1) input.value--;" class="px-3 text-gray-500 hover:bg-gray-200 transition-colors font-bold text-sm h-full select-none">-</button>
                                    <input type="number" name="quantity" value="1" min="1" class="w-8 text-center text-xs font-black text-gray-800 bg-transparent border-0 p-0 focus:ring-0 focus:outline-hidden [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none h-full">
                                    <button type="button" onclick="let input = this.parentNode.querySelector('input'); input.value++;" class="px-3 text-gray-500 hover:bg-gray-200 transition-colors font-bold text-sm h-full select-none">+</button>
                                </div>
                                <button type="submit" class="w-10 h-10 rounded-2xl bg-gray-900 text-white flex items-center justify-center hover:bg-gradient-to-r hover:from-orange-500 hover:to-pink-500 transition-all duration-300 shadow-md hover:shadow-lg transform active:scale-95" title="Tambah ke Keranjang">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <span class="font-extrabold text-2xl tracking-tight mb-4 block">Foodie<span class="text-orange-500">Hub</span></span>
                <p class="text-gray-400">Menyajikan makanan terbaik dengan kualitas premium. Cepat, mudah, dan lezat.</p>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-4">Tautan</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-orange-400">Beranda</a></li>
                    <li><a href="#" class="hover:text-orange-400">Menu</a></li>
                    <li><a href="#" class="hover:text-orange-400">Tentang Kami</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-4">Kontak</h4>
                <ul class="space-y-2 text-gray-400">
                    <li>Email: support@foodiehub.com</li>
                    <li>Telepon: +62 812 3456 7890</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} FoodieHub. All rights reserved.
        </div>
    </footer>
    <!-- Script Animasi Flying Cart -->
    <script>
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                // Jika belum login, biarkan form disubmit biasa agar diredirect ke login page oleh Laravel
                @guest
                    return;
                @endguest

                e.preventDefault();
                
                const submitBtn = this.querySelector('button[type="submit"]');
                const card = this.closest('.group'); 
                const img = card.querySelector('img'); 
                const cartIcon = document.getElementById('cart-icon');
                
                submitBtn.disabled = true;
                
                const qtyInput = this.querySelector('input[name="quantity"]');
                const quantity = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
                
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ quantity: quantity })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if(data.success && img && cartIcon) {
                        flyToCart(img, cartIcon);
                        if (qtyInput) qtyInput.value = 1;
                        
                        setTimeout(() => {
                            const badge = document.getElementById('cart-badge');
                            if (badge) {
                                badge.textContent = data.cart_count;
                                badge.classList.remove('hidden');
                                
                                // Efek pop up badge keranjang
                                badge.classList.add('scale-125', 'bg-pink-600');
                                setTimeout(() => {
                                    badge.classList.remove('scale-125', 'bg-pink-600');
                                }, 300);
                            }
                        }, 800);
                    }
                    submitBtn.disabled = false;
                })
                .catch(err => {
                    console.error(err);
                    submitBtn.disabled = false;
                });
            });
        });

        function flyToCart(imgElement, cartElement) {
            const imgRect = imgElement.getBoundingClientRect();
            const cartRect = cartElement.getBoundingClientRect();

            // Kloning element gambar untuk dijadikan objek terbang
            const clone = imgElement.cloneNode();
            clone.style.position = 'fixed';
            clone.style.top = `${imgRect.top}px`;
            clone.style.left = `${imgRect.left}px`;
            clone.style.width = `${imgRect.width}px`;
            clone.style.height = `${imgRect.height}px`;
            clone.style.zIndex = '9999';
            clone.style.borderRadius = '50%';
            clone.style.transition = 'all 0.8s cubic-bezier(0.25, 1, 0.5, 1)';
            clone.style.pointerEvents = 'none';

            document.body.appendChild(clone);

            // Trigger animation di frame berikutnya
            requestAnimationFrame(() => {
                clone.style.top = `${cartRect.top + 10}px`;
                clone.style.left = `${cartRect.left + 10}px`;
                clone.style.width = '30px';
                clone.style.height = '30px';
                clone.style.opacity = '0.3';
                clone.style.transform = 'scale(0.1) rotate(360deg)';
            });

            // Hapus klon dan goyang ikon keranjang setelah selesai animasi
            setTimeout(() => {
                clone.remove();
                cartElement.classList.add('scale-110', 'text-pink-500');
                setTimeout(() => {
                    cartElement.classList.remove('scale-110', 'text-pink-500');
                }, 300);
            }, 800);
        }
    </script>
</body>
</html>
