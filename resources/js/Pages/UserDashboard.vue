<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    CalendarDaysIcon, 
    CurrencyDollarIcon,
    MapPinIcon,
    ClockIcon,
    CheckCircleIcon,
    XCircleIcon,
    EyeIcon,
    PlusIcon
} from '@heroicons/vue/24/outline';
import { computed } from 'vue';

const props = defineProps({
    bookings: Array,
    stats: Object,
    user: Object
});

const getStatusColor = (status) => {
    switch (status) {
        case 'confirmed': return 'bg-green-100 text-green-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getStatusText = (status) => {
    switch (status) {
        case 'confirmed': return 'Onaylandı';
        case 'pending': return 'Beklemede';
        case 'cancelled': return 'İptal Edildi';
        default: return status;
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('tr-TR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Hoş Geldiniz, {{ user.name }}!
                    </h2>
                    <p class="text-gray-600 mt-2">Rezervasyonlarınızı yönetin ve yeni tatil planları yapın</p>
                </div>
                <Link :href="route('villas.index')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-colors flex items-center gap-2">
                    <PlusIcon class="h-5 w-5" />
                    Yeni Rezervasyon
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Toplam Rezervasyon</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.total_bookings }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                                <CalendarDaysIcon class="h-6 w-6 text-blue-600" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Onaylanan Rezervasyon</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.confirmed_bookings }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
                                <CheckCircleIcon class="h-6 w-6 text-green-600" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Toplam Harcama</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">₺{{ stats.total_spent?.toLocaleString() || '0' }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center">
                                <CurrencyDollarIcon class="h-6 w-6 text-purple-600" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Yaklaşan Seyahat</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.upcoming_trips }}</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center">
                                <ClockIcon class="h-6 w-6 text-orange-600" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Son Rezervasyonlarım</h3>
                        <Link :href="route('bookings.index')" class="text-blue-600 hover:text-blue-700 font-medium">
                            Tümünü Gör
                        </Link>
                    </div>
                    
                    <div v-if="bookings.length > 0" class="space-y-4">
                        <div v-for="booking in bookings" :key="booking.id" class="flex items-center justify-between p-4 bg-white/30 rounded-2xl border border-white/20 hover:bg-white/40 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div v-if="booking.villa.images && booking.villa.images.length > 0" class="w-16 h-16 rounded-xl overflow-hidden">
                                    <img :src="booking.villa.images[0].image_url" :alt="booking.villa.title" class="w-full h-full object-cover">
                                </div>
                                <div v-else class="w-16 h-16 bg-gray-200 rounded-xl flex items-center justify-center">
                                    <MapPinIcon class="h-8 w-8 text-gray-400" />
                                </div>
                                
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ booking.villa.title }}</h4>
                                    <p class="text-sm text-gray-600">{{ formatDate(booking.check_in) }} - {{ formatDate(booking.check_out) }}</p>
                                    <p class="text-xs text-gray-500">{{ booking.guests }} misafir • ₺{{ booking.total_amount?.toLocaleString() }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <span :class="getStatusColor(booking.status)" class="px-3 py-1 rounded-full text-xs font-medium">
                                    {{ getStatusText(booking.status) }}
                                </span>
                                <Link :href="route('bookings.show', booking.id)" class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                    <EyeIcon class="h-5 w-5" />
                                </Link>
                            </div>
                        </div>
                    </div>
                    
                    <div v-else class="text-center py-12">
                        <CalendarDaysIcon class="h-16 w-16 text-gray-300 mx-auto mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz rezervasyonunuz yok</h3>
                        <p class="text-gray-600 mb-6">Harika villalarımızı keşfedin ve ilk rezervasyonunuzu yapın!</p>
                        <Link :href="route('villas.index')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-colors inline-flex items-center gap-2">
                            <PlusIcon class="h-5 w-5" />
                            Villa Keşfet
                        </Link>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <Link :href="route('villas.index')" class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Villa Keşfet</h3>
                            <MapPinIcon class="h-6 w-6 text-blue-600 group-hover:scale-110 transition-transform" />
                        </div>
                        <p class="text-gray-600 text-sm">Harika villalarımızı keşfedin ve yeni rezervasyon yapın</p>
                    </Link>
                    
                    <Link :href="route('bookings.index')" class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Rezervasyonlarım</h3>
                            <CalendarDaysIcon class="h-6 w-6 text-green-600 group-hover:scale-110 transition-transform" />
                        </div>
                        <p class="text-gray-600 text-sm">Tüm rezervasyonlarınızı görüntüleyin ve yönetin</p>
                    </Link>
                    
                    <Link :href="route('profile.edit')" class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Profil Ayarları</h3>
                            <CurrencyDollarIcon class="h-6 w-6 text-purple-600 group-hover:scale-110 transition-transform" />
                        </div>
                        <p class="text-gray-600 text-sm">Profil bilgilerinizi güncelleyin</p>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>