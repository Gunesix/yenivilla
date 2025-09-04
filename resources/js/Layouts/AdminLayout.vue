<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-blue-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 bg-blue-800">
                <div class="flex items-center">
                    <img 
                        v-if="safePageAccess?.props?.settings?.general?.logo" 
                        :src="safePageAccess.props.settings.general.logo" 
                        :alt="(safePageAccess?.props?.settings?.general?.site_name || 'Villa Admin') + ' Logo'" 
                        class="w-8 h-8 mr-2 object-contain"
                    >
                    <h1 class="text-xl font-bold text-white">{{ safePageAccess?.props?.settings?.general?.site_name || 'Villa Admin' }}</h1>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200"
                        :class="[
                            safePageAccess?.url && safePageAccess.url.startsWith(item.href)
                                ? 'bg-blue-800 text-white'
                                : 'text-blue-100 hover:bg-blue-800 hover:text-white'
                        ]"
                    >
                        <component v-if="item.icon" :is="item.icon" class="w-5 h-5 mr-3" />
                        {{ item.name }}
                    </Link>
                </div>
            </nav>

            <!-- User info -->
            <div class="absolute bottom-0 left-0 right-0 p-4 bg-blue-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-white">{{ userInitials }}</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ safePageAccess?.props?.auth?.user?.name }}</p>
                        <p class="text-xs text-blue-200">{{ safePageAccess?.props?.auth?.user?.roles?.[0] }}</p>
                    </div>
                    <button
                        @click="logout"
                        class="ml-auto text-blue-200 hover:text-white transition-colors duration-200"
                        title="Çıkış Yap"
                    >
                        <ArrowRightOnRectangleIcon class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar overlay -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <!-- Main content -->
        <div class="lg:pl-64">
            <!-- Top bar -->
            <div class="sticky top-0 z-40 flex h-16 bg-white border-b border-gray-200 shadow-sm">
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 lg:hidden"
                >
                    <Bars3Icon class="w-6 h-6" />
                </button>

                <div class="flex flex-1 justify-between px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-1">
                        <!-- Breadcrumb -->
                        <nav class="flex items-center" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-4">
                                <li>
                                    <div class="flex items-center">
                                        <Link href="/admin/dashboard" class="text-gray-400 hover:text-gray-500">
                                            <HomeIcon class="w-5 h-5" />
                                        </Link>
                                    </div>
                                </li>
                                <li v-if="breadcrumbs.length > 0">
                                    <div class="flex items-center">
                                        <ChevronRightIcon class="w-5 h-5 text-gray-400" />
                                        <span class="ml-4 text-sm font-medium text-gray-500">
                                            {{ typeof breadcrumbs[breadcrumbs.length - 1] === 'object' ? breadcrumbs[breadcrumbs.length - 1].name : breadcrumbs[breadcrumbs.length - 1] }}
                                        </span>
                                    </div>
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- Notifications -->
                        <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <BellIcon class="w-6 h-6" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import {
    Bars3Icon,
    HomeIcon,
    ChartBarIcon,
    BuildingOfficeIcon,
    CalendarDaysIcon,
    UsersIcon,
    CreditCardIcon,
    DocumentChartBarIcon,
    CogIcon,
    BellIcon,
    ChevronRightIcon,
    ArrowRightOnRectangleIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
    breadcrumbs: {
        type: Array,
        default: () => []
    }
})

const sidebarOpen = ref(false)

const navigation = [
    { name: 'Dashboard', href: '/admin/dashboard', icon: ChartBarIcon },
    { name: 'Villa Yönetimi', href: '/admin/villas', icon: BuildingOfficeIcon },
    { name: 'Rezervasyonlar', href: '/admin/bookings', icon: CalendarDaysIcon },
    { name: 'Kullanıcılar', href: '/admin/users', icon: UsersIcon },
    { name: 'Ödemeler', href: '/admin/payments', icon: CreditCardIcon },
    { name: 'Raporlar', href: '/admin/reports', icon: DocumentChartBarIcon },
    { name: 'Ayarlar', href: '/admin/settings', icon: CogIcon },
]

const page = usePage()

// Null check for page object
const safePageAccess = computed(() => {
    return page && page.value ? page.value : null
})

const userInitials = computed(() => {
    const name = safePageAccess.value?.props?.auth?.user?.name || ''
    return name.split(' ').map(n => n[0]).join('').toUpperCase()
})

const logout = () => {
    if (router && router.post) {
        router.post('/admin/logout')
    }
}
</script>