<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Header Component -->
        <Header />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pt-20">
            <!-- Villa Title & Info -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ booking?.villa?.title || 'Villa' }} - Rezervasyon Detayı</h1>
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <div class="flex items-center">
                        <StarIcon class="h-4 w-4 text-yellow-400 fill-current mr-1" />
                        <span class="font-semibold">{{ booking.villa.rating || '4.8' }}</span>
                        <span class="text-gray-600 ml-1">({{ booking.villa.reviews_count || '24' }} değerlendirme)</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <MapPinIcon class="h-4 w-4 mr-1" />
                        <span class="underline cursor-pointer hover:text-gray-900">{{ booking.villa.location }}</span>
                    </div>
                </div>
            </div>

            <!-- Booking Details Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-500 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-white">Rezervasyon #{{ booking.id }}</h1>
                            <p class="text-blue-100 mt-1">{{ booking?.villa?.title || 'Villa' }}</p>
                        </div>
                        <span :class="statusClasses" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                            <span class="w-2 h-2 rounded-full mr-2" :class="statusDotClasses"></span>
                            {{ statusText }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Villa Information -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Villa Bilgileri</h3>
                            <div class="flex items-start space-x-3">
                                <img 
                                    :src="villaImage" 
                                    :alt="booking?.villa?.title || 'Villa'"
                                    class="w-16 h-16 rounded-lg object-cover"
                                >
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ booking?.villa?.title || 'Villa' }}</h4>
                                    <p class="text-gray-600 text-sm flex items-center mt-1">
                                        <MapPinIcon class="w-4 h-4 mr-1" />
                                        {{ booking.villa.location }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Information -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Rezervasyon Detayları</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Giriş:</span>
                                    <span class="font-medium">{{ formatDate(booking.check_in) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Çıkış:</span>
                                    <span class="font-medium">{{ formatDate(booking.check_out) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Misafir:</span>
                                    <span class="font-medium">{{ booking.guests }} kişi</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="text-gray-900 font-semibold">Toplam:</span>
                                    <span class="text-blue-600 font-semibold">{{ formatPrice(booking.total_amount) }} TL</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="mt-6">
                        <div v-if="booking.payments && booking.payments.length > 0" class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <CreditCardIcon class="w-5 h-5 text-green-600 mr-2" />
                                <span class="text-green-800 font-medium">Ödeme Tamamlandı</span>
                            </div>
                        </div>
                        
                        <div v-else class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <ExclamationTriangleIcon class="w-5 h-5 text-yellow-600 mr-2" />
                                <span class="text-yellow-800 font-medium">Ödeme Bekleniyor</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex flex-wrap gap-3">
                        <Link 
                            v-if="canMakePayment" 
                            :href="`/payments/create/${booking.id}`"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            <CreditCardIcon class="w-4 h-4 mr-2" />
                            Ödeme Yap
                        </Link>
                        
                        <button 
                            v-if="canCancel" 
                            @click="cancelBooking"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            <XMarkIcon class="w-4 h-4 mr-2" />
                            İptal Et
                        </button>
                        
                        <Link 
                            href="/bookings"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            <ArrowLeftIcon class="w-4 h-4 mr-2" />
                            Geri Dön
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Footer -->
        <footer class="bg-gray-50 border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="md:col-span-2">
                        <div class="flex items-center mb-4">
                            <!-- Dynamic Logo -->
                            <div v-if="$page.props.settings?.general?.logo" class="w-11 h-11 mr-2">
                                <img :src="$page.props.settings.general.logo" :alt="$page.props.settings?.site_name || 'Logo'" class="w-11 h-11 object-contain" />
                            </div>
                            <svg v-else class="w-11 h-11 mr-2 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                            <span class="text-xl font-bold text-gray-900">{{ $page.props.settings?.site_name || $page.props.settings?.general?.site_name || 'İnci Villa' }}</span>
                        </div>
                        <p class="text-gray-600 mb-6 max-w-md">
                            Türkiye'nin en güzel destinasyonlarında unutulmaz tatil deneyimleri yaşayın. Premium villa koleksiyonumuzla hayalinizdeki tatili gerçekleştirin.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4">Keşfet</h4>
                        <ul class="space-y-3">
                            <li><Link :href="route('home')" class="text-gray-600 hover:text-gray-900 transition-colors">Ana Sayfa</Link></li>
                            <li><Link :href="route('villas.index')" class="text-gray-600 hover:text-gray-900 transition-colors">Villalar</Link></li>
                            <li><Link :href="route('locations')" class="text-gray-600 hover:text-gray-900 transition-colors">Lokasyonlar</Link></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Hakkımızda</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4">Destek</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Yardım Merkezi</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">İletişim</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Güvenlik</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Gizlilik</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 mt-8 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-gray-500 text-sm">
                            © 2024 {{ $page.props.settings?.site_name || $page.props.settings?.general?.site_name || 'İnci Villa' }}. Tüm hakları saklıdır.
                        </p>
                        <div class="flex space-x-6 mt-4 md:mt-0">
                            <a href="#" class="text-gray-500 hover:text-gray-900 text-sm transition-colors">Gizlilik Politikası</a>
                            <a href="#" class="text-gray-500 hover:text-gray-900 text-sm transition-colors">Kullanım Şartları</a>
                            <a href="#" class="text-gray-500 hover:text-gray-900 text-sm transition-colors">Çerez Politikası</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { 
    HomeIcon, 
    ChevronRightIcon, 
    MapPinIcon, 
    CalendarIcon, 
    UserGroupIcon, 
    CreditCardIcon, 
    ExclamationTriangleIcon,
    XMarkIcon,
    ArrowLeftIcon,
    StarIcon
} from '@heroicons/vue/24/outline'
import Header from '@/Components/Header.vue'

const props = defineProps({
    booking: Object
})

// Debug booking data



// Computed properties
const villaImage = computed(() => {
    return props.booking.villa.images?.[0]?.image_url || 
           `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20${props.booking.villa.id}&image_size=landscape_16_9`
})

const nightCount = computed(() => {
    const checkIn = new Date(props.booking.check_in)
    const checkOut = new Date(props.booking.check_out)
    const diffTime = Math.abs(checkOut - checkIn)
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24))
})

const statusClasses = computed(() => {
    const status = props.booking.status
    switch (status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800'
        case 'confirmed':
            return 'bg-green-100 text-green-800'
        case 'cancelled':
            return 'bg-red-100 text-red-800'
        case 'completed':
            return 'bg-blue-100 text-blue-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
})

const statusDotClasses = computed(() => {
    const status = props.booking.status
    switch (status) {
        case 'pending':
            return 'bg-yellow-400'
        case 'confirmed':
            return 'bg-green-400'
        case 'cancelled':
            return 'bg-red-400'
        case 'completed':
            return 'bg-blue-400'
        default:
            return 'bg-gray-400'
    }
})

const statusText = computed(() => {
    const status = props.booking.status
    switch (status) {
        case 'pending':
            return 'Beklemede'
        case 'confirmed':
            return 'Onaylandı'
        case 'cancelled':
            return 'İptal Edildi'
        case 'completed':
            return 'Tamamlandı'
        default:
            return 'Bilinmiyor'
    }
})

const canMakePayment = computed(() => {
    return props.booking.status === 'pending' && 
           (!props.booking.payments || props.booking.payments.length === 0 || 
            props.booking.payments.every(p => p.status !== 'completed'))
})

const canCancel = computed(() => {
    return ['pending', 'confirmed'].includes(props.booking.status)
})

// Methods
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('tr-TR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const formatPrice = (price) => {
    return new Intl.NumberFormat('tr-TR').format(price)
}

const getPaymentStatusClasses = (status) => {
    switch (status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800'
        case 'completed':
            return 'bg-green-100 text-green-800'
        case 'failed':
            return 'bg-red-100 text-red-800'
        case 'refunded':
            return 'bg-gray-100 text-gray-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

const getPaymentStatusText = (status) => {
    switch (status) {
        case 'pending':
            return 'Beklemede'
        case 'completed':
            return 'Tamamlandı'
        case 'failed':
            return 'Başarısız'
        case 'refunded':
            return 'İade Edildi'
        default:
            return 'Bilinmiyor'
    }
}

const cancelBooking = () => {
    if (confirm('Rezervasyonu iptal etmek istediğinizden emin misiniz?')) {
        router.delete(`/bookings/${props.booking.id}`, {
            onSuccess: () => {
                router.visit('/bookings')
            }
        })
    }
}
</script>

<style scoped>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>