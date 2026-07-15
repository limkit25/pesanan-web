{{-- Notification Dropdown Component --}}
<div class="relative" x-data="{
    dropdownOpen: false,
    notifying: false,
    pendingCount: 0,
    latestOrderId: localStorage.getItem('latest_order_id') || 0,
    lastSeenOrderId: localStorage.getItem('last_seen_order_id') || 0,
    readOrders: JSON.parse(localStorage.getItem('read_orders') || '[]'),
    notifications: [],
    init() {
        this.checkNew();
        setInterval(() => this.checkNew(), 10000); // Cek setiap 10 detik
    },
    checkNew() {
        fetch('{{ route('admin.orders.check') }}')
            .then(res => res.json())
            .then(data => {
                this.pendingCount = data.pendingCount;
                
                // Jika ada pesanan baru yang belum dilihat, tampilkan titik notifikasi
                if (data.latestOrderId > this.lastSeenOrderId) {
                    this.notifying = true;
                }
                
                if (data.latestOrderId > this.latestOrderId) {
                    if (this.latestOrderId > 0) {
                        this.playNotificationSound();
                        this.showNotificationToast(data.latestOrderId);
                    }
                    this.latestOrderId = data.latestOrderId;
                    localStorage.setItem('latest_order_id', data.latestOrderId);
                }
            })
            .catch(err => console.error(err));
    },
    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
        if (this.dropdownOpen) {
            this.fetchNotifications();
        }
    },
    markAsRead(orderId) {
        if (!this.readOrders.includes(orderId)) {
            this.readOrders.push(orderId);
            localStorage.setItem('read_orders', JSON.stringify(this.readOrders));
        }
        this.notifying = false;
        this.lastSeenOrderId = this.latestOrderId;
        localStorage.setItem('last_seen_order_id', this.latestOrderId);
    },
    closeDropdown() {
        this.dropdownOpen = false;
    },
    fetchNotifications() {
        fetch('{{ route('admin.orders.check') }}?include_recent=1')
            .then(res => res.json())
            .then(data => {
                // Filter out notifications that are already in readOrders
                this.notifications = data.recentOrders.filter(n => !this.readOrders.includes(n.id));
            })
            .catch(err => console.error(err));
    },
    playNotificationSound() {
        let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-84.wav');
        audio.play().catch(e => console.log('Audio blocked:', e));
    },
    showNotificationToast(orderId) {
        let toast = document.createElement('div');
        toast.className = 'fixed bottom-5 right-5 z-99999 bg-gradient-to-r from-orange-500 to-pink-500 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 transition-all duration-500 transform translate-y-10 opacity-0';
        toast.style.zIndex = '999999';
        toast.innerHTML = `
            <div class='w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white flex-shrink-0 animate-bounce'>
                <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'></path></svg>
            </div>
            <div>
                <p class='font-extrabold text-sm'>Ada Pesanan Masuk Baru!</p>
                <p class='text-xs opacity-90'>Pesanan #${String(orderId).padStart(5, '0')} telah diterima.</p>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        }, 100);
        setTimeout(() => {
            toast.classList.add('translate-y-10', 'opacity-0');
            setTimeout(() => toast.remove(), 500);
        }, 8000);
    }
}" @click.away="closeDropdown()">
    <!-- Notification Button -->
    <button
        class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-full hover:text-dark-900 h-11 w-11 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
        @click="toggleDropdown()"
        type="button"
    >
        <!-- Notification Badge Dot -->
        <span
            x-show="notifying"
            class="absolute right-0 top-0.5 z-1 h-2.5 w-2.5 rounded-full bg-orange-500"
        >
            <span
                class="absolute inline-flex w-full h-full bg-orange-500 rounded-full opacity-75 -z-1 animate-ping"
            ></span>
        </span>

        <!-- Bell Icon -->
        <svg
            class="fill-current"
            width="20"
            height="20"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z"
                fill=""
            />
        </svg>
    </button>

    <!-- Dropdown Start -->
    <div
        x-show="dropdownOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute -right-[240px] mt-[17px] flex h-[480px] w-[350px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark sm:w-[361px] lg:right-0"
        style="display: none;"
    >
        <!-- Dropdown Header -->
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-2">
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white/90">Notifikasi</h5>
            </div>

            <button @click="closeDropdown()" class="text-gray-500 dark:text-gray-400" type="button">
                <svg
                    class="fill-current"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z"
                        fill=""
                    />
                </svg>
            </button>
        </div>

        <!-- Notification List -->
        <ul class="flex flex-col h-auto overflow-y-auto custom-scrollbar flex-1">
            <!-- Empty State -->
            <template x-if="notifications.length === 0">
                <div class="flex flex-col items-center justify-center py-12 text-center text-gray-400">
                    <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <p class="text-sm font-semibold">Tidak ada notifikasi pesanan</p>
                </div>
            </template>

            <!-- List Items -->
            <template x-for="notification in notifications" :key="notification.id">
                <li @click="markAsRead(notification.id)">
                    <a
                        class="flex gap-4 rounded-xl border-b border-gray-50 p-4 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-white/5 transition-all duration-300"
                        :href="`/admin/orders/` + notification.id"
                    >
                        <!-- Order Status Icon -->
                        <span class="relative flex w-10 h-10 rounded-xl bg-orange-50 text-orange-500 items-center justify-center flex-shrink-0 shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <!-- Mini status dot -->
                            <span
                                class="absolute bottom-0 right-0 z-10 h-2.5 w-2.5 rounded-full border border-white dark:border-gray-900"
                                :class="{
                                    'bg-yellow-500': notification.status === 'pending',
                                    'bg-blue-500': notification.status === 'processing',
                                    'bg-green-500': notification.status === 'completed',
                                    'bg-red-500': notification.status === 'cancelled'
                                }"
                            ></span>
                        </span>

                        <span class="block flex-1">
                            <span class="mb-1 block text-sm font-semibold text-gray-800 dark:text-white/90">
                                Pesanan Baru <span class="text-orange-500" x-text="notification.id_formatted"></span>
                            </span>
                            <span class="block text-xs text-gray-500 mb-1.5 font-medium leading-relaxed">
                                Pelanggan: <span class="font-bold text-gray-700" x-text="notification.user_name"></span><br>
                                Total: <span class="font-bold text-gray-700" x-text="notification.total_price"></span>
                            </span>

                            <span class="flex items-center gap-1.5 text-gray-400 text-[10px] font-bold tracking-wider uppercase">
                                <span class="px-2 py-0.5 rounded bg-gray-100" x-text="notification.status"></span>
                                <span class="w-1.5 h-1.5 bg-gray-200 rounded-full"></span>
                                <span x-text="notification.time_ago"></span>
                            </span>
                        </span>
                    </a>
                </li>
            </template>
        </ul>

        <!-- View All Button -->
        <a
            href="{{ route('admin.orders.index') }}"
            class="mt-3 flex justify-center rounded-xl border border-gray-200 bg-white p-3 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-50 hover:text-gray-800 transition-colors duration-300"
            @click="closeDropdown()"
        >
            Lihat Semua Pesanan
        </a>
    </div>
    <!-- Dropdown End -->
</div>
