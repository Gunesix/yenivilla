<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { 
    HomeIcon, 
    BuildingOfficeIcon, 
    CalendarDaysIcon, 
    UserGroupIcon,
    ChartBarIcon,
    CurrencyDollarIcon,
    StarIcon,
    MapPinIcon
} from '@heroicons/vue/24/outline';

// Mock data - replace with real data from backend
const stats = [
    { name: 'Toplam Villa', value: '10', icon: BuildingOfficeIcon, color: 'blue' },
    { name: 'Aktif Rezervasyon', value: '24', icon: CalendarDaysIcon, color: 'green' },
    { name: 'Bu Ay Gelir', value: '₺125,000', icon: CurrencyDollarIcon, color: 'purple' },
    { name: 'Müşteri Sayısı', value: '156', icon: UserGroupIcon, color: 'orange' }
];

const recentBookings = [
    { id: 1, villa: 'Bodrum Lüks Villa', guest: 'Ahmet Yılmaz', dates: '15-22 Ağu', status: 'confirmed' },
    { id: 2, villa: 'Antalya Deniz Villa', guest: 'Ayşe Kaya', dates: '20-27 Ağu', status: 'pending' },
    { id: 3, villa: 'Kaş Butik Villa', guest: 'Mehmet Demir', dates: '25 Ağu - 1 Eyl', status: 'confirmed' }
];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Admin Dashboard
            </h2>
            <p class="text-gray-600 mt-2">Villa kiralama sistemi yönetim paneli</p>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div v-for="stat in stats" :key="stat.name" class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">{{ stat.name }}</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stat.value }}</p>
                            </div>
                            <div :class="`w-12 h-12 bg-${stat.color}-100 rounded-2xl flex items-center justify-center`">
                                <component :is="stat.icon" :class="`h-6 w-6 text-${stat.color}-600`" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Recent Bookings -->
                    <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Son Rezervasyonlar</h3>
                            <CalendarDaysIcon class="h-6 w-6 text-blue-600" />
                        </div>
                        <div class="space-y-4">
                            <div v-for="booking in recentBookings" :key="booking.id" class="flex items-center justify-between p-4 bg-white/30 rounded-2xl border border-white/20">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ booking.villa }}</p>
                                    <p class="text-sm text-gray-600">{{ booking.guest }}</p>
                                    <p class="text-xs text-gray-500">{{ booking.dates }}</p>
                                </div>
                                <div>
                                    <span :class="booking.status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'" class="px-3 py-1 rounded-full text-xs font-medium">
                                        {{ booking.status === 'confirmed' ? 'Onaylandı' : 'Bekliyor' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Hızlı İşlemler</h3>
                            <ChartBarIcon class="h-6 w-6 text-purple-600" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-2xl transition-colors group">
                                <BuildingOfficeIcon class="h-8 w-8 text-blue-600 mb-2 group-hover:scale-110 transition-transform" />
                                <span class="text-sm font-medium text-blue-900">Yeni Villa</span>
                            </button>
                            <button class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-2xl transition-colors group">
                                <CalendarDaysIcon class="h-8 w-8 text-green-600 mb-2 group-hover:scale-110 transition-transform" />
                                <span class="text-sm font-medium text-green-900">Rezervasyon</span>
                            </button>
                            <button class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-2xl transition-colors group">
                                <UserGroupIcon class="h-8 w-8 text-purple-600 mb-2 group-hover:scale-110 transition-transform" />
                                <span class="text-sm font-medium text-purple-900">Müşteriler</span>
                            </button>
                            <button class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-2xl transition-colors group">
                                <ChartBarIcon class="h-8 w-8 text-orange-600 mb-2 group-hover:scale-110 transition-transform" />
                                <span class="text-sm font-medium text-orange-900">Raporlar</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Performance Overview -->
                <div class="mt-8 backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Performans Özeti</h3>
                        <StarIcon class="h-6 w-6 text-yellow-600" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-white/30 rounded-2xl border border-white/20">
                            <div class="text-3xl font-bold text-blue-600 mb-2">4.8</div>
                            <div class="text-sm text-gray-600">Ortalama Puan</div>
                            <div class="flex justify-center mt-2">
                                <StarIcon class="h-4 w-4 text-yellow-400" />
                                <StarIcon class="h-4 w-4 text-yellow-400" />
                                <StarIcon class="h-4 w-4 text-yellow-400" />
                                <StarIcon class="h-4 w-4 text-yellow-400" />
                                <StarIcon class="h-4 w-4 text-yellow-400" />
                            </div>
                        </div>
                        <div class="text-center p-4 bg-white/30 rounded-2xl border border-white/20">
                            <div class="text-3xl font-bold text-green-600 mb-2">89%</div>
                            <div class="text-sm text-gray-600">Doluluk Oranı</div>
                        </div>
                        <div class="text-center p-4 bg-white/30 rounded-2xl border border-white/20">
                            <div class="text-3xl font-bold text-purple-600 mb-2">₺8,500</div>
                            <div class="text-sm text-gray-600">Ortalama Günlük Gelir</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
