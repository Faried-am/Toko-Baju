<nav x-data="{ open: false }" class="sticky top-0 z-40 shadow-lg"
    style="background: linear-gradient(90deg, #14532d 0%, #15803d 55%, #16a34a 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">

                <!-- Brand (tanpa logo Laravel) -->
                <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center gap-2">
                    <span class="flex items-center justify-center rounded-xl bg-white text-green-700 shadow"
                        style="height: 38px; width: 38px;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                        </svg>
                    </span>
                    <span class="hidden md:block text-white font-bold text-lg tracking-tight">Toko Baju</span>
                </a>

                <!-- Menu desktop -->
                <div class="hidden sm:flex sm:items-center sm:ms-8 gap-1">
                    @if (auth()->user()->role === 'admin')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-nav-link>
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">{{ __('Produk') }}</x-nav-link>
                        <x-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.*')">{{ __('Penjualan') }}</x-nav-link>
                        <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">{{ __('Pesanan') }}</x-nav-link>
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">{{ __('Kategori') }}</x-nav-link>
                        <x-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')">{{ __('Rating') }}</x-nav-link>
                        <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">{{ __('Laporan') }}</x-nav-link>
                    @else
                        <x-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')">{{ __('Toko') }}</x-nav-link>

                        <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                            <span class="relative flex items-center gap-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ __('Keranjang') }}
                                @if ($cartCount > 0)
                                    <span class="bg-red-500 text-white text-xs rounded-full flex items-center justify-center absolute"
                                        style="height: 20px; width: 20px; top: -12px; right: -16px;">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </span>
                        </x-nav-link>

                        <x-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.*')">{{ __('Pesanan Saya') }}</x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Dropdown user -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-full text-white text-sm font-medium focus:outline-none transition duration-150"
                            style="background: rgba(255,255,255,0.15); padding: 4px 12px 4px 4px;">
                            <span class="flex items-center justify-center rounded-full bg-white text-green-700 font-bold"
                                style="height: 32px; width: 32px;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-white hover:bg-white/10 focus:outline-none transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            @if (auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">{{ __('Produk') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.*')">{{ __('Penjualan') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">{{ __('Pesanan') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">{{ __('Kategori') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')">{{ __('Rating') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">{{ __('Laporan') }}</x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')">{{ __('Toko') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">{{ __('Keranjang') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.*')">{{ __('Pesanan Saya') }}</x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-2 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>