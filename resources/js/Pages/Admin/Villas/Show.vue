<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
    PencilIcon,
    TrashIcon,
    EyeIcon,
    MapPinIcon,
    UserGroupIcon,
    HomeIcon,
    CurrencyDollarIcon,
    CalendarIcon,
    PhotoIcon,
    CheckCircleIcon,
    XCircleIcon,
    ArrowLeftIcon,
    CogIcon,
    ClockIcon,
    ChartBarIcon
} from '@heroicons/vue/24/outline';
import { StarIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    villa: Object,
    auth: Object
});

const deleteVilla = () => {
    if (confirm('Bu villayı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')) {
        router.delete(route('admin.villas.destroy', props.villa.id));
    }
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
    }).format(price);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('tr-TR');
};

const getStatusColor = (isActive) => {
    return isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
};

const getStatusText = (isActive) => {
    return isActive ? 'Aktif' : 'Pasif';
};
</script>

<template>
    <Head :title="`${villa.title} - Villa Detayı`" />
    
    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <Link :href="route('admin.villas.index')" 
                                      class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <ArrowLeftIcon class="-ml-0.5 mr-2 h-4 w-4" />
                                    Villa Listesi
                                </Link>
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">{{ villa.title }}</h1>
                                    <div class="flex items-center space-x-4 mt-1">
                                        <span :class="getStatusColor(villa.is_active)" 
                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            {{ getStatusText(villa.is_active) }}
                                        </span>
                                        <span class="text-sm text-gray-600 flex items-center">
                                            <MapPinIcon class="h-4 w-4 mr-1" />
                                            {{ villa.city }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <Link :href="route('admin.villas.edit', villa.id)"
                                      class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <PencilIcon class="-ml-1 mr-2 h-4 w-4" />
                                    Düzenle
                                </Link>
                                <button @click="deleteVilla"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <TrashIcon class="-ml-1 mr-2 h-4 w-4" />
                                    Sil
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <Link :href="route('admin.villas.edit', villa.id) + '#pricing'"
                          class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <CurrencyDollarIcon class="h-8 w-8 text-green-600" />
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Fiyat Yönetimi</h3>
                                    <p class="text-sm text-gray-600">Villa düzenle sayfasında yönet</p>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <Link :href="route('admin.villas.edit', villa.id) + '#availability'"
                          class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <CalendarIcon class="h-8 w-8 text-blue-600" />
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Uygunluk Yönetimi</h3>
                                    <p class="text-sm text-gray-600">Villa düzenle sayfasında yönet</p>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <Link :href="route('admin.bookings.index', { villa_id: villa.id })"
                          class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <ClockIcon class="h-8 w-8 text-purple-600" />
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Rezervasyonlar</h3>
                                    <p class="text-sm text-gray-600">Villa rezervasyonları</p>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <a :href="route('villas.show', villa.id)" target="_blank"
                       class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <EyeIcon class="h-8 w-8 text-indigo-600" />
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Önizleme</h3>
                                    <p class="text-sm text-gray-600">Sitede görüntüle</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Villa Details -->
                    <div class="lg:col-span-2">
                        <!-- Basic Information -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Temel Bilgiler</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Villa Adı</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ villa.title }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Şehir</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ villa.city }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Gecelik Fiyat</label>
                                        <p class="mt-1 text-sm text-gray-900 font-semibold text-green-600">{{ formatPrice(villa.price_per_night) }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Maksimum Misafir</label>
                                        <p class="mt-1 text-sm text-gray-900 flex items-center">
                                            <UserGroupIcon class="h-4 w-4 mr-1 text-gray-400" />
                                            {{ villa.max_guests }} kişi
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Yatak Odası</label>
                                        <p class="mt-1 text-sm text-gray-900 flex items-center">
                                            <HomeIcon class="h-4 w-4 mr-1 text-gray-400" />
                                            {{ villa.bedrooms }} oda
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Banyo</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ villa.bathrooms }} banyo</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Alan</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ villa.area_sqm }} m²</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Durum</label>
                                        <span :class="getStatusColor(villa.is_active)" 
                                              class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            {{ getStatusText(villa.is_active) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Açıklama</h2>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ villa.description }}</p>
                            </div>
                        </div>

                        <!-- Amenities -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Özellikler</h2>
                                <div v-if="villa.amenities && villa.amenities.length > 0" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <div v-for="amenity in villa.amenities" :key="amenity.id" 
                                         class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />
                                        <span class="text-sm text-gray-700">{{ amenity.amenity_type }}</span>
                                    </div>
                                </div>
                                <p v-else class="text-sm text-gray-500">Henüz özellik eklenmemiş.</p>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Fotoğraflar</h2>
                                <div v-if="villa.images && villa.images.length > 0" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <div v-for="image in villa.images" :key="image.id" class="relative group">
                                        <img :src="image.image_url" :alt="image.alt_text || villa.title" 
                                             class="w-full h-32 object-cover rounded-lg shadow-sm group-hover:shadow-md transition-shadow duration-200">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-opacity duration-200 rounded-lg"></div>
                                    </div>
                                </div>
                                <div v-else class="text-center py-8">
                                    <PhotoIcon class="mx-auto h-12 w-12 text-gray-400" />
                                    <p class="mt-2 text-sm text-gray-500">Henüz fotoğraf eklenmemiş.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Statistics -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">İstatistikler</h2>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Toplam Rezervasyon</span>
                                        <span class="text-sm font-medium text-gray-900">{{ villa.bookings ? villa.bookings.length : 0 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Aktif Rezervasyon</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ villa.bookings ? villa.bookings.filter(b => b.status === 'confirmed').length : 0 }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Oluşturulma Tarihi</span>
                                        <span class="text-sm font-medium text-gray-900">{{ formatDate(villa.created_at) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Son Güncelleme</span>
                                        <span class="text-sm font-medium text-gray-900">{{ formatDate(villa.updated_at) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Bookings -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Son Rezervasyonlar</h2>
                                <div v-if="villa.bookings && villa.bookings.length > 0" class="space-y-3">
                                    <div v-for="booking in villa.bookings.slice(0, 5)" :key="booking.id" 
                                         class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ booking.user?.name || 'Misafir' }}</p>
                                            <p class="text-xs text-gray-600">{{ formatDate(booking.check_in) }} - {{ formatDate(booking.check_out) }}</p>
                                        </div>
                                        <span :class="{
                                            'bg-green-100 text-green-800': booking.status === 'confirmed',
                                            'bg-yellow-100 text-yellow-800': booking.status === 'pending',
                                            'bg-red-100 text-red-800': booking.status === 'cancelled'
                                        }" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                                            {{ booking.status === 'confirmed' ? 'Onaylandı' : 
                                               booking.status === 'pending' ? 'Bekliyor' : 'İptal' }}
                                        </span>
                                    </div>
                                    <Link :href="route('admin.bookings.index', { villa_id: villa.id })"
                                          class="block text-center text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                        Tüm Rezervasyonları Görüntüle
                                    </Link>
                                </div>
                                <p v-else class="text-sm text-gray-500">Henüz rezervasyon yok.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>