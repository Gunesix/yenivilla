<template>
    <header class="w-full fixed top-0 z-50 flex justify-center pointer-events-none">
        <nav class="mt-4 px-8 py-4 rounded-2xl backdrop-blur-xl shadow-2xl border border-white/40 pointer-events-auto flex items-center justify-between bg-white/80 text-gray-800 max-w-6xl w-full mx-4">
            <!-- Logo/Brand -->
            <div class="flex items-center">
                <!-- Dynamic Logo -->
                <div v-if="$page.props.settings?.general?.logo" class="w-14 h-14 mr-3">
                    <img :src="$page.props.settings.general.logo" :alt="($page.props.settings?.general?.site_name || 'Logo') + ' Logo'" class="w-14 h-14 object-contain" />
                </div>
                <svg v-else class="w-14 h-14 mr-3" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Shell Icon -->
                    <path d="M50 15C35 15 25 25 25 40C25 50 30 58 38 63L50 85L62 63C70 58 75 50 75 40C75 25 65 15 50 15Z" stroke="#374151" stroke-width="3" fill="none"/>
                    <circle cx="50" cy="40" r="8" fill="#374151"/>
                    <path d="M42 32L50 40L58 32" stroke="#374151" stroke-width="2" fill="none"/>
                    <path d="M42 48L50 40L58 48" stroke="#374151" stroke-width="2" fill="none"/>
                    <path d="M34 40L42 32" stroke="#374151" stroke-width="2" fill="none"/>
                    <path d="M34 40L42 48" stroke="#374151" stroke-width="2" fill="none"/>
                    <path d="M66 40L58 32" stroke="#374151" stroke-width="2" fill="none"/>
                    <path d="M66 40L58 48" stroke="#374151" stroke-width="2" fill="none"/>
                </svg>
                <span class="text-xl font-bold text-gray-900">{{ $page.props.settings?.site_name || $page.props.settings?.general?.site_name || 'İnci Villa' }}</span>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden md:flex items-center gap-8">
                <Link :href="route('home')" class="font-semibold text-gray-700 hover:text-blue-600 transition-colors duration-200 relative group">
                    Ana Sayfa
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-200 group-hover:w-full"></span>
                </Link>
                <Link :href="route('villas.index')" class="font-semibold text-gray-700 hover:text-blue-600 transition-colors duration-200 relative group">
                    Villalar
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-200 group-hover:w-full"></span>
                </Link>
                <Link :href="route('locations')" class="font-semibold text-gray-700 hover:text-blue-600 transition-colors duration-200 relative group">
                    Harita Görünümü
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-200 group-hover:w-full"></span>
                </Link>
            </div>
            
            <!-- User Actions -->
            <div class="flex items-center gap-3">
                <div v-if="$page?.props?.auth?.user" class="flex items-center gap-3">
                    <Link :href="route('bookings.index')" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                        Rezervasyonlarım
                    </Link>
                    <Link :href="route('logout')" method="post" class="bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 transition-all duration-200 font-semibold">
                        Çıkış Yap
                    </Link>
                </div>
                <div v-else class="flex items-center gap-3">
                    <Link :href="route('login')" class="font-semibold text-gray-700 hover:text-blue-600 transition-colors">
                        Giriş
                    </Link>
                    <Link :href="route('register')" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2.5 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Kayıt
                    </Link>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>