<script setup>
import { ref, computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();

// Check user role for navigation
const userRole = computed(() => page.props.auth.user?.role || 'guest');
const isOwnerOrAdmin = computed(() => ['owner', 'admin'].includes(userRole.value));
</script>

<template>
    <div>
        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
            <!-- Fixed Glassmorphism Navigation -->
            <header class="w-full fixed top-0 z-50 flex justify-center pointer-events-none">
                <nav class="mt-4 px-6 py-3 rounded-full backdrop-blur-xl shadow-lg border border-white/40 pointer-events-auto flex items-center gap-6 bg-white/70 text-gray-800">
                    <!-- Logo -->
                    <Link :href="route('dashboard')" class="font-bold text-xl text-blue-600 hover:text-blue-700 transition">
                        {{ isOwnerOrAdmin ? 'VillaRentals Admin' : 'VillaRentals' }}
                    </Link>
                    
                    <!-- Navigation Links -->
                    <Link :href="route('dashboard')" class="font-medium hover:text-blue-600 transition">
                        Dashboard
                    </Link>
                    <Link :href="route('villas.index')" class="font-medium hover:text-blue-600 transition">
                        Villalar
                    </Link>
                    <Link :href="route('bookings.index')" class="font-medium hover:text-blue-600 transition">
                        {{ isOwnerOrAdmin ? 'Tüm Rezervasyonlar' : 'Rezervasyonlarım' }}
                    </Link>
                    <!-- Owner/Admin only links -->
                    <template v-if="isOwnerOrAdmin">
                        <Link :href="route('villas.create')" class="font-medium hover:text-blue-600 transition">
                            Villa Ekle
                        </Link>
                    </template>

                    <!-- User Menu -->
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600">{{ $page.props.auth.user.name }}</span>
                        <Link
                            :href="route('profile.edit')"
                            class="text-gray-700 hover:text-blue-600 transition text-sm"
                        >
                            Profil
                        </Link>
                        <Link
                            :href="route('logout')"
                            method="post"
                            class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700 transition text-sm"
                        >
                            Çıkış
                        </Link>
                    </div>
                </nav>
            </header>



            <!-- Page Content with top padding for fixed nav -->
            <main class="pt-20">
                <!-- Page Heading -->
                <div v-if="$slots.header" class="mb-8">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg">
                            <slot name="header" />
                        </div>
                    </div>
                </div>
                
                <slot />
            </main>
        </div>
    </div>
</template>
