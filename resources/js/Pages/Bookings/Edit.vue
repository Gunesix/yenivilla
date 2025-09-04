<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Header Component -->
        <Header />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pt-20">
            <!-- Villa Title & Info -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ booking?.villa?.title || 'Villa' }} - Rezervasyon Düzenle</h1>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Price Summary Sidebar -->
                <div class="lg:col-span-1 lg:order-2 order-1">
                    <div class="bg-white rounded-xl shadow-xl border border-blue-100 p-6 lg:sticky lg:top-24">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Güncellenmiş Fiyat Özeti</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ nightCount }} gece x ₺{{ formatPrice(booking.villa.price_per_night) }}</span>
                                <span class="font-medium">₺{{ formatPrice(subtotal) }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Temizlik ücreti</span>
                                <span class="font-medium">₺{{ formatPrice(cleaningFee) }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Hizmet bedeli</span>
                                <span class="font-medium">₺{{ formatPrice(serviceFee) }}</span>
                            </div>
                            
                            <hr class="border-gray-200">
                            
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span>Toplam</span>
                                <span class="text-blue-600">₺{{ formatPrice(totalAmount) }}</span>
                            </div>
                        </div>
                        
                        <!-- Booking Details -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-3">Rezervasyon Detayları</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Giriş:</span>
                                    <span class="font-medium">{{ formatDate(form.check_in) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Çıkış:</span>
                                    <span class="font-medium">{{ formatDate(form.check_out) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Misafir:</span>
                                    <span class="font-medium">{{ form.guests }} kişi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content - Villa Info & Edit Form -->
                <div class="lg:col-span-2 lg:order-1 order-2 space-y-8">
                    <!-- Villa Info -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Villa Bilgileri</h2>
                        
                        <!-- Villa Main Info -->
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 mb-6">
                            <div class="flex flex-col lg:flex-row gap-6">
                                <!-- Villa Image -->
                                <div class="lg:w-1/3">
                                    <img 
                                        :src="booking.villa.images?.[0]?.image_url || `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20${booking.villa.id}&image_size=landscape_4_3`" 
                                        :alt="booking?.villa?.title || 'Villa'"
                                        class="w-full h-48 lg:h-56 rounded-xl object-cover shadow-lg"
                                    >
                                </div>
                                
                                <!-- Villa Info -->
                                <div class="lg:w-2/3 space-y-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ booking?.villa?.title || 'Villa' }}</h3>
                                        <p class="text-gray-600 flex items-center mb-3">
                                            <MapPinIcon class="w-5 h-5 mr-2 text-blue-500" />
                                            {{ booking.villa.location }}
                                        </p>
                                        
                                        <!-- Rating -->
                                        <div class="flex items-center mb-3" v-if="booking.villa.rating">
                                            <div class="flex items-center">
                                                <span class="text-yellow-400 text-lg">★</span>
                                                <span class="ml-1 font-semibold text-gray-900">{{ booking.villa.rating }}</span>
                                                <span class="ml-1 text-gray-600">({{ booking.villa.reviews_count || 0 }} değerlendirme)</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Villa Specs -->
                                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                            <div class="flex items-center text-gray-700">
                                                <HomeIcon class="h-5 w-5 text-blue-500 mr-2" />
                                                <span class="text-sm">{{ booking.villa.bedrooms || 'N/A' }} Yatak Odası</span>
                                            </div>
                                            <div class="flex items-center text-gray-700">
                                                <UserGroupIcon class="h-5 w-5 text-blue-500 mr-2" />
                                                <span class="text-sm">{{ booking.villa.max_guests }} Kişi Kapasitesi</span>
                                            </div>
                                            <div class="flex items-center text-gray-700">
                                                <BuildingOfficeIcon class="h-5 w-5 text-blue-500 mr-2" />
                                                <span class="text-sm">{{ booking.villa.bathrooms || 'N/A' }} Banyo</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Price -->
                                        <div class="bg-white rounded-lg p-3 inline-block">
                                            <p class="text-2xl font-bold text-blue-600">
                                                ₺{{ formatPrice(booking.villa.price_per_night) }}
                                                <span class="text-sm font-normal text-gray-600">/ gece</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Edit Form -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Rezervasyon Bilgilerini Düzenle</h2>
                        <form @submit.prevent="updateBooking" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Giriş Tarihi</label>
                                    <input 
                                        type="date" 
                                        v-model="form.check_in"
                                        :min="today"
                                        required
                                        class="date-input-custom w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 bg-white font-semibold"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Çıkış Tarihi</label>
                                    <input 
                                        type="date" 
                                        v-model="form.check_out"
                                        :min="form.check_in"
                                        required
                                        class="date-input-custom w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 bg-white font-semibold"
                                    >
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Misafir Sayısı</label>
                                <select 
                                    v-model="form.guests"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 bg-white"
                                >
                                    <option value="">Misafir sayısını seçin</option>
                                    <option v-for="i in booking.villa.max_guests" :key="i" :value="i">{{ i }} Misafir</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Özel İstekler (Opsiyonel)</label>
                                <textarea 
                                    v-model="form.special_requests"
                                    rows="3"
                                    placeholder="Özel isteklerinizi buraya yazabilirsiniz..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 bg-white placeholder-gray-500"
                                ></textarea>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <button 
                                    type="submit" 
                                    :disabled="processing || !isFormValid"
                                    class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-6 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="processing">Güncelleniyor...</span>
                                    <span v-else>Rezervasyonu Güncelle</span>
                                </button>
                                
                                <Link 
                                    :href="`/bookings/${booking.id}`"
                                    class="border border-gray-300 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                                >
                                    İptal
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="md:col-span-2">
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-4">
                            AYO Villa
                        </h3>
                        <p class="text-gray-400 mb-6">
                            Türkiye'nin en güzel villarında unutulmaz tatil deneyimleri yaşayın.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <span class="sr-only">Facebook</span>
                                📘
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <span class="sr-only">Instagram</span>
                                📷
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <span class="sr-only">Twitter</span>
                                🐦
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-4">Keşfet</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Villalar</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Lokasyonlar</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Özel Teklifler</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-4">Destek</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">İletişim</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">SSS</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Yardım</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        © 2024 AYO Villa. Tüm hakları saklıdır.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Gizlilik Politikası</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Kullanım Şartları</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Çerez Politikası</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import { MapPinIcon, StarIcon, HomeIcon, UserGroupIcon, BuildingOfficeIcon } from '@heroicons/vue/24/outline'
import Header from '@/Components/Header.vue'

const props = defineProps({
    booking: Object
})

const processing = ref(false)

const form = ref({
    check_in: props.booking.check_in,
    check_out: props.booking.check_out,
    guests: props.booking.guests,
    special_requests: props.booking.special_requests || ''
})

const today = computed(() => {
    return new Date().toISOString().split('T')[0]
})

const nightCount = computed(() => {
    if (!form.value.check_in || !form.value.check_out) return 0
    const checkIn = new Date(form.value.check_in)
    const checkOut = new Date(form.value.check_out)
    const diffTime = Math.abs(checkOut - checkIn)
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24))
})

const subtotal = computed(() => {
    return nightCount.value * (props.booking.villa.price_per_night || 0)
})

const cleaningFee = computed(() => {
    return Math.round(subtotal.value * 0.1) // 10% cleaning fee
})

const serviceFee = computed(() => {
    return Math.round(subtotal.value * 0.05) // 5% service fee
})

const totalAmount = computed(() => {
    return subtotal.value + cleaningFee.value + serviceFee.value
})

const isFormValid = computed(() => {
    return form.value.check_in && 
           form.value.check_out && 
           form.value.guests && 
           new Date(form.value.check_out) > new Date(form.value.check_in)
})

const formatPrice = (price) => {
    return new Intl.NumberFormat('tr-TR').format(price)
}

const formatDate = (date) => {
    if (!date) return ''
    return new Date(date).toLocaleDateString('tr-TR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const updateBooking = () => {
    if (!isFormValid.value) return
    
    processing.value = true
    
    router.put(`/bookings/${props.booking.id}`, {
        check_in: form.value.check_in,
        check_out: form.value.check_out,
        guests: form.value.guests,
        special_requests: form.value.special_requests,
        total_amount: totalAmount.value
    }, {
        onSuccess: () => {
            processing.value = false
        },
        onError: () => {
            processing.value = false
        }
    })
}
</script>

<style scoped>
.date-input-custom::-webkit-calendar-picker-indicator {
    filter: invert(0.5);
}

.date-input-custom:focus::-webkit-calendar-picker-indicator {
    filter: invert(0.3);
}
</style>