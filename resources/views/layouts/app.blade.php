<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __(config('app.name'), 'မဟာထွန်း') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <main class="nativephp-safe-area mx-auto max-w-4xl min-h-screen">
        <div x-data="{ open: false }" class="flex min-h-screen flex-col">
            <header
                class="sticky top-0 z-40 border-b border-gray-200/80 bg-white/80 shadow-sm backdrop-blur-lg dark:border-gray-700/80 dark:bg-gray-900/80">
                <div class="flex items-center justify-between px-4 py-3">
                    <button @click="open = !open"
                        class="flex h-9 w-9 items-center justify-center rounded-xl text-gray-600 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ config('app.name'), 'မဟာထွန်း' }}
                    </span>
                    <div class="w-9"></div>
                </div>
            </header>

            <div x-cloak x-show="open" class="relative z-50 ">
                <div @click="open = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-2xl dark:bg-gray-900"
                    x-transition:enter="transition-transform duration-300" x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform duration-200"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                    <div class="flex h-full flex-col nativephp-safe-area">
                        <div
                            class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-800">
                            <div>
                                <p class="text-base font-bold text-gray-900 dark:text-white">
                                    {{ config('app.name'), 'မဟာထွန်း' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('POS System') }}</p>
                            </div>
                            <button @click="open = false"
                                class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                                icon="dashboard">
                                {{ __('Dashboard') }}
                            </x-nav-link>

                            <p
                                class="mt-5 mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                {{ __('Inventory') }}</p>

                            <x-nav-link href="{{ route('inventory.categories') }}"
                                :active="request()->routeIs('inventory.categories')" icon="folder">
                                {{ __('Categories') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('inventory.units') }}"
                                :active="request()->routeIs('inventory.units')" icon="list">
                                {{ __('Units') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('inventory.products') }}"
                                :active="request()->routeIs('inventory.products')" icon="products">
                                {{ __('Products') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('inventory') }}" :active="request()->routeIs('inventory')"
                                icon="inventory">
                                {{ __('Add Stock') }}
                            </x-nav-link>
                            <hr class="my-4 border-gray-200 dark:border-gray-800">

                            <x-nav-link href="{{ route('sales') }}" :active="request()->routeIs('sales')"
                                icon="shopping">
                                {{ __('Sales') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('history') }}" :active="request()->routeIs('history')"
                                icon="history">
                                {{ __('History') }}
                            </x-nav-link>
                            <hr class="my-4 border-gray-200 dark:border-gray-800">

                            <p
                                class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                {{ __('Reports') }}</p>

                            <x-nav-link href="{{ route('reports.profit-loss') }}"
                                :active="request()->routeIs('reports.profit-loss')" icon="chart">
                                {{ __('Profit & Loss') }}
                            </x-nav-link>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="px-4 py-3">
                {{ $slot }}
            </div>

            <native:bottom-nav>
                <native:bottom-nav-item id="home" icon="house.fill" label="{{ __('Home') }}"
                    url="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" />
                <native:bottom-nav-item id="sales" icon="shopping.fill" label="{{ __('Sales') }}"
                    url="{{ route('sales') }}" :active="request()->routeIs('sales')" />
                <native:bottom-nav-item id="history" icon="history.fill" label="{{ __('History') }}"
                    url="{{ route('history') }}" :active="request()->routeIs('history')" />
            </native:bottom-nav>
        </div>
    </main>
    @livewireScripts
</body>

</html>