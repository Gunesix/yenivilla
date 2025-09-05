<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Header Component -->
        <Header />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pt-20">
            <!-- Villa Title & Info -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ villa?.title || 'Villa' }} - Rezervasyon</h1>
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <div class="flex items-center">
                        <StarIcon class="h-4 w-4 text-yellow-400 fill-current mr-1" />
                        <span class="font-semibold">{{ villa.rating || '4.8' }}</span>
                        <span class="text-gray-600 ml-1">({{ villa.reviews_count || '24' }} değerlendirme)</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <MapPinIcon class="h-4 w-4 mr-1" />
                        <span class="underline cursor-pointer hover:text-gray-900">{{ villa.location }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Price Summary Sidebar - Mobile First (shows at top on mobile) -->
                <div class="lg:col-span-1 lg:order-2 order-1">
                    <div class="bg-white rounded-xl shadow-xl border border-blue-100 p-6 lg:sticky lg:top-24">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Fiyat Özeti</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ nightCount }} gece x ₺{{ formatPrice(villa.price_per_night) }}</span>
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

                <!-- Main Content - Villa Info & Booking Form -->
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
                                                :src="villa.images?.[0]?.image_url || `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20${villa.id}&image_size=landscape_4_3`" 
                                                :alt="villa?.title || 'Villa'"
                                                class="w-full h-48 lg:h-56 rounded-xl object-cover shadow-lg"
                                            >
                                        </div>
                                        
                                        <!-- Villa Info -->
                                        <div class="lg:w-2/3 space-y-4">
                                            <div>
                                                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ villa?.title || 'Villa' }}</h3>
                                                <p class="text-gray-600 flex items-center mb-3">
                                                    <MapPinIcon class="w-5 h-5 mr-2 text-blue-500" />
                                                    {{ villa.location }}
                                                </p>
                                                
                                                <!-- Rating -->
                                                <div class="flex items-center mb-3" v-if="villa.rating">
                                                    <div class="flex items-center">
                                                        <span class="text-yellow-400 text-lg">★</span>
                                                        <span class="ml-1 font-semibold text-gray-900">{{ villa.rating }}</span>
                                                        <span class="ml-1 text-gray-600">({{ villa.reviews_count || 0 }} değerlendirme)</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Villa Specs -->
                                                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                                    <div class="flex items-center text-gray-700">
                                                        <HomeIcon class="h-5 w-5 text-blue-500 mr-2" />
                                                        <span class="text-sm">{{ villa.bedrooms || 'N/A' }} Yatak Odası</span>
                                                    </div>
                                                    <div class="flex items-center text-gray-700">
                                                        <UserGroupIcon class="h-5 w-5 text-blue-500 mr-2" />
                                                        <span class="text-sm">{{ villa.max_guests }} Kişi Kapasitesi</span>
                                                    </div>
                                                    <div class="flex items-center text-gray-700">
                                                        <BuildingOfficeIcon class="h-5 w-5 text-blue-500 mr-2" />
                                                        <span class="text-sm">{{ villa.bathrooms || 'N/A' }} Banyo</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Price -->
                                                <div class="bg-white rounded-lg p-3 inline-block">
                                                    <p class="text-2xl font-bold text-blue-600">
                                                        ₺{{ formatPrice(villa.price_per_night) }}
                                                        <span class="text-sm font-normal text-gray-600">/ gece</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Villa Description -->
                                <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6" v-if="villa.description">
                                    <h4 class="font-semibold text-gray-900 mb-3">Villa Açıklaması</h4>
                                    <p class="text-gray-700 leading-relaxed">{{ villa.description }}</p>
                                </div>
                    </div>
                    
                    <!-- Booking Form -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Rezervasyon Bilgileri</h2>
                        <form @submit.prevent="submitBooking" class="space-y-6">
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
                                <label class="block text-sm font-medium text-gray-900 mb-2">Misafir Sayısı (Opsiyonel)</label>
                                <select 
                                    v-model="form.guests"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 bg-white"
                                >
                                    <option value="">Maksimum {{ villa.max_guests }} misafir</option>
                                    <option v-for="i in villa.max_guests" :key="i" :value="i">{{ i }} Misafir</option>
                                </select>
                                <p class="text-xs text-gray-600 mt-1">Seçilmezse maksimum misafir sayısı ({{ villa.max_guests }}) kullanılır</p>
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

                             <!-- Payment Method -->
                             <div>
                                 <label class="block text-sm font-medium text-gray-900 mb-4">Ödeme Yöntemi</label>
                                 <div class="space-y-3">
                                     <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                         <input 
                                             type="radio" 
                                             v-model="form.payment_method" 
                                             value="stripe" 
                                             class="text-blue-600 focus:ring-blue-500"
                                         >
                                         <div class="ml-3 flex-1">
                                             <div class="font-medium text-gray-900">Kredi Kartı</div>
                                             <div class="text-sm text-gray-500">Visa, Mastercard, American Express</div>
                                         </div>
                                         <div class="text-2xl">💳</div>
                                     </label>
                                     
                                     <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                         <input 
                                             type="radio" 
                                             v-model="form.payment_method" 
                                             value="paypal" 
                                             class="text-blue-600 focus:ring-blue-500"
                                         >
                                         <div class="ml-3 flex-1">
                                             <div class="font-medium text-gray-900">PayPal</div>
                                             <div class="text-sm text-gray-500">PayPal hesabınızla güvenli ödeme</div>
                                         </div>
                                         <div class="text-2xl">🅿️</div>
                                     </label>
                                        
                                     <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                         <input 
                                             type="radio" 
                                             v-model="form.payment_method" 
                                             value="bank_transfer" 
                                             class="text-blue-600 focus:ring-blue-500"
                                         >
                                         <div class="ml-3 flex-1">
                                             <div class="font-medium text-gray-900">Banka Havalesi</div>
                                             <div class="text-sm text-gray-500">Rezervasyon sonrası banka bilgileri gönderilecek</div>
                                         </div>
                                         <div class="text-2xl">🏦</div>
                                     </label>
                                 </div>
                             </div>

                             <!-- Credit Card Form (only show if stripe selected) -->
                             <div v-if="form.payment_method === 'stripe'" class="space-y-4 p-4 bg-gray-50 rounded-lg">
                                 <h4 class="font-medium text-gray-900">Kredi Kartı Bilgileri</h4>
                                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                     <div class="md:col-span-2">
                                         <label class="block text-sm font-medium text-gray-900 mb-2">Kart Numarası</label>
                                         <input 
                                             type="text" 
                                             v-model="form.card_number"
                                             placeholder="1234 5678 9012 3456"
                                             maxlength="19"
                                             @input="formatCardNumber"
                                             class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 bg-white placeholder-gray-500"
                                         >
                                     </div>
                                     <div>
                                         <label class="block text-sm font-medium text-gray-900 mb-2">Son Kullanma</label>
                                         <input 
                                             type="text" 
                                             v-model="form.card_expiry"
                                             placeholder="MM/YY"
                                             maxlength="5"
                                             @input="formatCardExpiry"
                                             class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 bg-white placeholder-gray-500"
                                         >
                                     </div>
                                     <div>
                                         <label class="block text-sm font-medium text-gray-900 mb-2">CVV</label>
                                         <input 
                                             type="text" 
                                             v-model="form.card_cvv"
                                             placeholder="123"
                                             maxlength="4"
                                             class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 bg-white placeholder-gray-500"
                                         >
                                     </div>
                                     <div class="md:col-span-2">
                                         <label class="block text-sm font-medium text-gray-900 mb-2">Kart Sahibi</label>
                                         <input 
                                             type="text" 
                                             v-model="form.card_holder"
                                             placeholder="Ad Soyad"
                                             class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 bg-white placeholder-gray-500"
                                         >
                                     </div>
                                 </div>
                             </div>

                             <button 
                                 type="submit" 
                                 :disabled="processing || !isFormValid"
                                 class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                             >
                                 <span v-if="processing">İşleniyor...</span>
                                 <span v-else>Rezervasyonu Onayla ve Öde</span>
                             </button>
                            </form>
                        </div>
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
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import { ChevronRightIcon, MapPinIcon, StarIcon, HomeIcon, UserGroupIcon, BuildingOfficeIcon } from '@heroicons/vue/24/outline'
import Header from '@/Components/Header.vue'

const props = defineProps({
    villa: Object,
    checkIn: String,
    checkOut: String,
    guests: [String, Number]
})

// Debug villa data


const processing = ref(false)

const form = ref({
    villa_id: props.villa.id,
    check_in: props.checkIn || '',
    check_out: props.checkOut || '',
    guests: props.guests || '',
    special_requests: '',
    payment_method: 'stripe',
    card_number: '',
    card_expiry: '',
    card_cvv: '',
    card_holder: ''
})

const today = computed(() => {
    return new Date().toISOString().split('T')[0]
})

const nightCount = computed(() => {
    if (form.value.check_in && form.value.check_out) {
        const checkIn = new Date(form.value.check_in)
        const checkOut = new Date(form.value.check_out)
        const diffTime = Math.abs(checkOut - checkIn)
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    }
    return 0
})

const subtotal = computed(() => {
    return props.villa.price_per_night * nightCount.value
})

const cleaningFee = computed(() => {
    return Math.round(props.villa.price_per_night * 0.1) // 10% cleaning fee
})

const serviceFee = computed(() => {
    return Math.round(subtotal.value * 0.05) // 5% service fee
})

const totalAmount = computed(() => {
    if (nightCount.value > 0) {
        return subtotal.value + cleaningFee.value + serviceFee.value
    }
    return 0
})

const isFormValid = computed(() => {
    const basicValid = form.value.check_in && 
                      form.value.check_out && 
                      form.value.payment_method
    
    if (form.value.payment_method === 'stripe') {
        return basicValid && 
               form.value.card_number && 
               form.value.card_expiry && 
               form.value.card_cvv && 
               form.value.card_holder
    }
    
    return basicValid
})

const formatPrice = (price) => {
    return new Intl.NumberFormat('tr-TR').format(price)
}

const formatDate = (date) => {
    if (!date) return ''
    return new Date(date).toLocaleDateString('tr-TR')
}

const formatCardNumber = (event) => {
    let value = event.target.value.replace(/\s/g, '').replace(/\D/g, '')
    value = value.replace(/(\d{4})(?=\d)/g, '$1 ')
    form.value.card_number = value
}

const formatCardExpiry = (event) => {
    let value = event.target.value.replace(/\D/g, '')
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4)
    }
    form.value.card_expiry = value
}

const getAmenityIcon = (amenityName) => {
    const iconMap = {
        'WiFi': '📶',
        'Klima': '❄️',
        'Havuz': '🏊',
        'Jakuzi': '🛁',
        'Barbekü': '🔥',
        'Otopark': '🚗',
        'Mutfak': '🍳',
        'TV': '📺',
        'Çamaşır Makinesi': '🧺',
        'Bulaşık Makinesi': '🍽️',
        'Bahçe': '🌿',
        'Teras': '🏡',
        'Deniz Manzarası': '🌊',
        'Dağ Manzarası': '🏔️',
        'Güvenlik': '🔒',
        'Temizlik': '🧹',
        'Çocuk Oyun Alanı': '🎮',
        'Spor Salonu': '💪',
        'Sauna': '🧖',
        'Masa Tenisi': '🏓',
        'Bilardo': '🎱',
        'Oyun Konsolu': '🎮',
        'Ses Sistemi': '🔊',
        'Projeksiyon': '📽️',
        'Kütüphane': '📚',
        'Çalışma Odası': '💻',
        'Balkon': '🏠',
        'Şömine': '🔥',
        'Merkezi Isıtma': '🔥',
        'Klima': '❄️',
        'Vantilatör': '🌀',
        'Ütü': '👔',
        'Saç Kurutma Makinesi': '💨',
        'Havlu': '🏖️',
        'Yatak Takımı': '🛏️',
        'Kahvaltı': '🥐',
        'Minibar': '🍷',
        'Kahve Makinesi': '☕',
        'Mikrodalga': '📡',
        'Buzdolabı': '🧊',
        'Fırın': '🔥',
        'Ocak': '🔥',
        'Mutfak Eşyaları': '🍴',
        'Yemek Masası': '🍽️',
        'Oturma Grubu': '🛋️',
        'Çalışma Masası': '💻',
        'Gardırop': '👗',
        'Ayna': '🪞',
        'Perde': '🪟',
        'Halı': '🏠',
        'Dekorasyon': '🎨',
        'Aydınlatma': '💡',
        'Güneş Şemsiyesi': '☂️',
        'Şezlong': '🏖️',
        'Bahçe Mobilyası': '🪑',
        'Çiçek': '🌸',
        'Ağaç': '🌳',
        'Çim': '🌱',
        'Yürüyüş Yolu': '🚶',
        'Bisiklet': '🚲',
        'Tekne': '⛵',
        'Balık Tutma': '🎣',
        'Su Sporları': '🏄',
        'Dalış': '🤿',
        'Sörf': '🏄',
        'Kano': '🛶',
        'Jet Ski': '🛥️',
        'Parasailing': '🪂',
        'Tenis Kortu': '🎾',
        'Basketbol Sahası': '🏀',
        'Futbol Sahası': '⚽',
        'Voleybol Sahası': '🏐',
        'Golf Sahası': '⛳',
        'Yüzme Havuzu': '🏊',
        'Çocuk Havuzu': '🧒',
        'Sıcak Su': '🔥',
        'Soğuk Su': '❄️',
        'Duş': '🚿',
        'Küvet': '🛁',
        'Tuvalet': '🚽',
        'Lavabo': '🚰',
        'Ayna': '🪞',
        'Havlu Askısı': '🏖️',
        'Sabun': '🧼',
        'Şampuan': '🧴',
        'Diş Fırçası': '🪥',
        'Diş Macunu': '🦷',
        'Tıraş Bıçağı': '🪒',
        'Tıraş Köpüğü': '🧴',
        'Parfüm': '🌸',
        'Deodorant': '🧴',
        'Krem': '🧴',
        'Güneş Kremi': '☀️',
        'İlk Yardım': '🩹',
        'Ateş Ölçer': '🌡️',
        'Ağrı Kesici': '💊',
        'Vitamin': '💊',
        'Antibiyotik': '💊',
        'Bandaj': '🩹',
        'Pamuk': '🧻',
        'Alkol': '🧴',
        'Dezenfektan': '🧴',
        'Maske': '😷',
        'Eldiven': '🧤',
        'Termometre': '🌡️',
        'Tansiyon Aleti': '🩺',
        'Nebulizatör': '💨',
        'Oksijen Tüpü': '🫁',
        'Serum': '💉',
        'İğne': '💉',
        'Şırınga': '💉',
        'Pansuman': '🩹',
        'Sargı': '🩹',
        'Koltuk Değneği': '🦽',
        'Tekerlekli Sandalye': '♿',
        'Yürüteç': '🦯',
        'Gözlük': '👓',
        'Lens': '👁️',
        'İşitme Cihazı': '👂',
        'Protez': '🦷',
        'Kalp Pili': '❤️',
        'Diyaliz': '🩸',
        'Kemoterapı': '💊',
        'Radyoterapi': '☢️',
        'Ameliyat': '🔪',
        'Anestezi': '💤',
        'Röntgen': '🩻',
        'MR': '🧠',
        'Tomografi': '🧠',
        'Ultrason': '🤰',
        'EKG': '❤️',
        'EEG': '🧠',
        'Kan Tahlili': '🩸',
        'İdrar Tahlili': '🧪',
        'Gaita Tahlili': '💩',
        'Biyopsi': '🔬',
        'Endoskopi': '🔍',
        'Kolonoskopi': '🔍',
        'Bronkoskopi': '🫁',
        'Laparoskopi': '🔍',
        'Artroskopi': '🦴',
        'Kateter': '🩸',
        'Sonda': '🩸',
        'Dren': '🩸',
        'Stent': '❤️',
        'Bypass': '❤️',
        'Transplantasyon': '🫀',
        'Protez': '🦴',
        'İmplant': '🦷',
        'Vida': '🔩',
        'Plak': '🦴',
        'Tel': '🔗',
        'Alçı': '🦴',
        'Atıl': '🩹',
        'Bandaj': '🩹',
        'Gazlı Bez': '🩹',
        'Pamuk': '🧻',
        'Alkol': '🧴',
        'Betadin': '🧴',
        'Merhem': '🧴',
        'Krem': '🧴',
        'Sprey': '💨',
        'Damla': '💧',
        'Şurup': '🍯',
        'Tablet': '💊',
        'Kapsül': '💊',
        'Ampul': '💉',
        'Flakon': '🧴',
        'Serum': '💉',
        'Aşı': '💉',
        'Antikor': '🛡️',
        'Vitamin': '💊',
        'Mineral': '💊',
        'Probiyotik': '🦠',
        'Prebiyotik': '🌱',
        'Enzim': '🧬',
        'Hormon': '🧬',
        'Protein': '🥩',
        'Karbonhidrat': '🍞',
        'Yağ': '🧈',
        'Lif': '🌾',
        'Su': '💧',
        'Tuz': '🧂',
        'Şeker': '🍯',
        'Baharat': '🌶️',
        'Ot': '🌿',
        'Meyve': '🍎',
        'Sebze': '🥕',
        'Et': '🥩',
        'Balık': '🐟',
        'Tavuk': '🐔',
        'Yumurta': '🥚',
        'Süt': '🥛',
        'Peynir': '🧀',
        'Yoğurt': '🥛',
        'Tereyağı': '🧈',
        'Ekmek': '🍞',
        'Makarna': '🍝',
        'Pirinç': '🍚',
        'Bulgur': '🌾',
        'Mercimek': '🫘',
        'Fasulye': '🫘',
        'Nohut': '🫘',
        'Bezelye': '🟢',
        'Soya': '🫘',
        'Fındık': '🥜',
        'Ceviz': '🥜',
        'Badem': '🥜',
        'Fıstık': '🥜',
        'Susam': '🌰',
        'Çekirdek': '🌰',
        'Zeytin': '🫒',
        'Zeytinyağı': '🫒',
        'Sirke': '🍶',
        'Limon': '🍋',
        'Portakal': '🍊',
        'Elma': '🍎',
        'Armut': '🍐',
        'Muz': '🍌',
        'Üzüm': '🍇',
        'Çilek': '🍓',
        'Kiraz': '🍒',
        'Şeftali': '🍑',
        'Kayısı': '🍑',
        'Erik': '🟣',
        'İncir': '🟤',
        'Hurma': '🟤',
        'Kivi': '🥝',
        'Ananas': '🍍',
        'Mango': '🥭',
        'Avokado': '🥑',
        'Domates': '🍅',
        'Salatalık': '🥒',
        'Biber': '🌶️',
        'Patlıcan': '🍆',
        'Kabak': '🎃',
        'Havuç': '🥕',
        'Soğan': '🧅',
        'Sarımsak': '🧄',
        'Patates': '🥔',
        'Tatlı Patates': '🍠',
        'Mısır': '🌽',
        'Brokoli': '🥦',
        'Karnabahar': '🥬',
        'Lahana': '🥬',
        'Ispanak': '🥬',
        'Marul': '🥬',
        'Roka': '🥬',
        'Maydanoz': '🌿',
        'Dereotu': '🌿',
        'Nane': '🌿',
        'Fesleğen': '🌿',
        'Kekik': '🌿',
        'Biberiye': '🌿',
        'Lavanta': '💜',
        'Gül': '🌹',
        'Papatya': '🌼',
        'Lale': '🌷',
        'Karanfil': '🌺',
        'Orkide': '🌺',
        'Zambak': '🌸',
        'Menekşe': '🟣',
        'Begonvil': '🌺',
        'Jakaranda': '💜',
        'Mimoza': '🟡',
        'Akasya': '🟡',
        'Çınar': '🌳',
        'Meşe': '🌳',
        'Çam': '🌲',
        'Kestane': '🌰',
        'Ceviz': '🌳',
        'Badem': '🌳',
        'Zeytin': '🌳',
        'İncir': '🌳',
        'Nar': '🌳',
        'Elma': '🌳',
        'Armut': '🌳',
        'Kiraz': '🌳',
        'Vişne': '🌳',
        'Şeftali': '🌳',
        'Kayısı': '🌳',
        'Erik': '🌳',
        'Dut': '🌳',
        'Hurma': '🌴',
        'Palmiye': '🌴',
        'Bambu': '🎋',
        'Kaktüs': '🌵',
        'Aloe Vera': '🌿',
        'Sarmaşık': '🌿',
        'Asma': '🍇',
        'Gül': '🌹',
        'Çiçek': '🌸',
        'Ağaç': '🌳',
        'Çim': '🌱',
        'Ot': '🌿',
        'Yaprak': '🍃',
        'Dal': '🌿',
        'Kök': '🌱',
        'Tohum': '🌰',
        'Meyve': '🍎',
        'Sebze': '🥕',
        'Çiçek': '🌸',
        'Böcek': '🐛',
        'Kelebek': '🦋',
        'Arı': '🐝',
        'Karınca': '🐜',
        'Örümcek': '🕷️',
        'Sinek': '🪰',
        'Sivrisinek': '🦟',
        'Böcek': '🐛',
        'Tırtıl': '🐛',
        'Ağustos Böceği': '🦗',
        'Çekirge': '🦗',
        'Akrep': '🦂',
        'Yılan': '🐍',
        'Kertenkele': '🦎',
        'Kaplumbağa': '🐢',
        'Kurbağa': '🐸',
        'Balık': '🐟',
        'Köpekbalığı': '🦈',
        'Yunus': '🐬',
        'Balina': '🐋',
        'Ahtapot': '🐙',
        'Mürekkep Balığı': '🦑',
        'Karides': '🦐',
        'Yengeç': '🦀',
        'İstiridye': '🦪',
        'Midye': '🐚',
        'Deniz Yıldızı': '⭐',
        'Deniz Anası': '🎐',
        'Mercan': '🪸',
        'Alg': '🌿',
        'Su': '💧',
        'Deniz': '🌊',
        'Göl': '🏞️',
        'Nehir': '🏞️',
        'Şelale': '💦',
        'Kaynak': '💧',
        'Kuyu': '🕳️',
        'Havuz': '🏊',
        'Çeşme': '⛲',
        'Fıskiye': '⛲',
        'Su Kanalı': '🌊',
        'Dere': '🏞️',
        'Çay': '🏞️',
        'Irmak': '🏞️',
        'Akarsu': '🏞️',
        'Gölet': '🏞️',
        'Bataklık': '🏞️',
        'Sulak Alan': '🏞️',
        'Mangrov': '🌿',
        'Delta': '🏞️',
        'Körfez': '🏞️',
        'Koy': '🏞️',
        'Liman': '⚓',
        'İskele': '⚓',
        'Rıhtım': '⚓',
        'Sahil': '🏖️',
        'Plaj': '🏖️',
        'Kum': '🏖️',
        'Çakıl': '🪨',
        'Kayalık': '🪨',
        'Uçurum': '🏔️',
        'Dağ': '🏔️',
        'Tepe': '⛰️',
        'Yayla': '🏔️',
        'Ova': '🌾',
        'Vadi': '🏞️',
        'Kanyon': '🏞️',
        'Mağara': '🕳️',
        'Grotto': '🕳️',
        'Stalaktit': '🪨',
        'Stalagmit': '🪨',
        'Kaya': '🪨',
        'Taş': '🪨',
        'Kum': '🏖️',
        'Çakıl': '🪨',
        'Toprak': '🌍',
        'Kil': '🌍',
        'Çamur': '🌍',
        'Balçık': '🌍',
        'Turba': '🌍',
        'Humus': '🌍',
        'Kompost': '🌍',
        'Gübre': '🌍',
        'Mineral': '💎',
        'Kristal': '💎',
        'Elmas': '💎',
        'Altın': '🥇',
        'Gümüş': '🥈',
        'Bakır': '🥉',
        'Demir': '⚙️',
        'Çelik': '⚙️',
        'Alüminyum': '⚙️',
        'Titanyum': '⚙️',
        'Platin': '💍',
        'Paladyum': '💍',
        'Rodyum': '💍',
        'İridyum': '💍',
        'Osmiyum': '💍',
        'Rutenyum': '💍',
        'Uranyum': '☢️',
        'Plütonyum': '☢️',
        'Radyum': '☢️',
        'Toryum': '☢️',
        'Sezyum': '☢️',
        'Stronsiyum': '☢️',
        'Kobalt': '☢️',
        'Nikel': '⚙️',
        'Çinko': '⚙️',
        'Kalay': '⚙️',
        'Kurşun': '⚙️',
        'Cıva': '🌡️',
        'Kadmiyum': '☠️',
        'Arsenik': '☠️',
        'Siyanür': '☠️',
        'Karbon Monoksit': '☠️',
        'Asbestos': '☠️',
        'Formaldehit': '☠️',
        'Benzen': '☠️',
        'Toluen': '☠️',
        'Ksilen': '☠️',
        'Metanol': '☠️',
        'Etanol': '🍷',
        'İzopropanol': '🧴',
        'Aseton': '🧴',
        'Eter': '🧴',
        'Kloroform': '☠️',
        'Karbon Tetraklorür': '☠️',
        'Diklorometan': '☠️',
        'Trikloroetilen': '☠️',
        'Perkloretilenı': '☠️',
        'Vinil Klorür': '☠️',
        'Etilen Oksit': '☠️',
        'Propilen Oksit': '☠️',
        'Bütan': '🔥',
        'Propan': '🔥',
        'Metan': '🔥',
        'Etan': '🔥',
        'Heksan': '🔥',
        'Heptan': '🔥',
        'Oktan': '🔥',
        'Nonan': '🔥',
        'Dekan': '🔥',
        'Benzin': '⛽',
        'Dizel': '⛽',
        'Kerosen': '⛽',
        'Fuel Oil': '⛽',
        'Mazot': '⛽',
        'LPG': '🔥',
        'CNG': '🔥',
        'Hidrojen': '⚡',
        'Oksijen': '🫁',
        'Azot': '🌬️',
        'Karbon Dioksit': '🌬️',
        'Argon': '🌬️',
        'Helyum': '🎈',
        'Neon': '💡',
        'Kripton': '💡',
        'Ksenon': '💡',
        'Radon': '☢️',
        'Flor': '☠️',
        'Klor': '☠️',
        'Brom': '☠️',
        'İyot': '💊',
        'Astat': '☢️',
        'Lityum': '🔋',
        'Sodyum': '🧂',
        'Potasyum': '🍌',
        'Rubidyum': '⚙️',
        'Sezyum': '☢️',
        'Fransiyum': '☢️',
        'Berilyum': '⚙️',
        'Magnezyum': '💊',
        'Kalsiyum': '🦴',
        'Stronsiyum': '☢️',
        'Baryum': '☠️',
        'Radyum': '☢️',
        'Skandiyum': '⚙️',
        'Titanyum': '⚙️',
        'Vanadyum': '⚙️',
        'Krom': '⚙️',
        'Manganez': '⚙️',
        'Demir': '⚙️',
        'Kobalt': '☢️',
        'Nikel': '⚙️',
        'Bakır': '⚙️',
        'Çinko': '⚙️',
        'Galyum': '⚙️',
        'Germanyum': '⚙️',
        'Arsenik': '☠️',
        'Selenyum': '💊',
        'Brom': '☠️',
        'Kripton': '💡',
        'Rubidyum': '⚙️',
        'Stronsiyum': '☢️',
        'İtriyum': '⚙️',
        'Zirkonyum': '⚙️',
        'Niyobyum': '⚙️',
        'Molibden': '⚙️',
        'Teknesyum': '☢️',
        'Rutenyum': '💍',
        'Rodyum': '💍',
        'Paladyum': '💍',
        'Gümüş': '🥈',
        'Kadmiyum': '☠️',
        'İndiyum': '⚙️',
        'Kalay': '⚙️',
        'Antimon': '⚙️',
        'Tellür': '⚙️',
        'İyot': '💊',
        'Ksenon': '💡',
        'Sezyum': '☢️',
        'Baryum': '☠️',
        'Lantan': '⚙️',
        'Seryum': '⚙️',
        'Praseodimyum': '⚙️',
        'Neodimyum': '⚙️',
        'Prometyum': '☢️',
        'Samaryum': '⚙️',
        'Evropiyum': '⚙️',
        'Gadolinyum': '⚙️',
        'Terbiyum': '⚙️',
        'Disprosyum': '⚙️',
        'Holmiyum': '⚙️',
        'Erbiyum': '⚙️',
        'Tulyum': '⚙️',
        'İterbiyum': '⚙️',
        'Lutesyum': '⚙️',
        'Hafniyum': '⚙️',
        'Tantal': '⚙️',
        'Tungsten': '⚙️',
        'Renyum': '⚙️',
        'Osmiyum': '💍',
        'İridyum': '💍',
        'Platin': '💍',
        'Altın': '🥇',
        'Cıva': '🌡️',
        'Talyum': '☠️',
        'Kurşun': '⚙️',
        'Bizmut': '⚙️',
        'Polonyum': '☢️',
        'Astat': '☢️',
        'Radon': '☢️',
        'Fransiyum': '☢️',
        'Radyum': '☢️',
        'Aktinyum': '☢️',
        'Toryum': '☢️',
        'Protaktinyum': '☢️',
        'Uranyum': '☢️',
        'Neptünyum': '☢️',
        'Plütonyum': '☢️',
        'Amerisyum': '☢️',
        'Küryum': '☢️',
        'Berkelyum': '☢️',
        'Kaliforniyum': '☢️',
        'Einstanyum': '☢️',
        'Fermiyum': '☢️',
        'Mendelevyum': '☢️',
        'Nobelyum': '☢️',
        'Lawrensiyum': '☢️',
        'Rutherfordyum': '☢️',
        'Dubniyum': '☢️',
        'Seaborgiyum': '☢️',
        'Bohriyum': '☢️',
        'Hassiyum': '☢️',
        'Meitneriyum': '☢️',
        'Darmstadtyum': '☢️',
        'Roentgenyum': '☢️',
        'Kopernikyum': '☢️',
        'Nihonyum': '☢️',
        'Flerovyum': '☢️',
        'Moskovyum': '☢️',
        'Livermoriyum': '☢️',
        'Tennessin': '☢️',
        'Oganesson': '☢️'
    }
    
    return iconMap[amenityName] || '✨'
}

const submitBooking = async () => {
    if (!isFormValid.value) return
    
    processing.value = true
    
    try {
        // First create the booking
        const bookingData = {
            villa_id: form.value.villa_id,
            check_in: form.value.check_in,
            check_out: form.value.check_out,
            guests: form.value.guests || props.villa.max_guests, // Use max_guests as default
            total_amount: totalAmount.value,
            special_requests: form.value.special_requests
        }
        
        router.post('/bookings', bookingData, {
            onSuccess: (page) => {
                // If booking created successfully, process payment
                const booking = page.props.booking || { id: Date.now() } // fallback for demo
                
                const paymentData = {
                    booking_id: booking.id,
                    payment_method: form.value.payment_method
                }
                
                if (form.value.payment_method === 'stripe') {
                    paymentData.stripe_token = 'demo_token_' + Date.now()
                }
                
                router.post('/payments', paymentData, {
                    onSuccess: () => {
                        // Redirect to booking details
                        router.visit(`/bookings/${booking.id}`)
                    },
                    onError: (errors) => {
                        console.error('Payment failed:', errors)
                        processing.value = false
                    }
                })
            },
            onError: (errors) => {
                console.error('Booking failed:', errors)
                processing.value = false
            }
        })
    } catch (error) {
        console.error('Error:', error)
        processing.value = false
    }
}

onMounted(() => {
    // Set default dates if not provided
    if (!form.value.check_in) {
        const today = new Date()
        form.value.check_in = today.toISOString().split('T')[0]
    }
    
    if (!form.value.check_out) {
        const tomorrow = new Date()
        tomorrow.setDate(tomorrow.getDate() + 1)
        form.value.check_out = tomorrow.toISOString().split('T')[0]
    }
})
</script>

<style scoped>
/* Date input custom styles */
.date-input-custom {
    color: #111827 !important;
    background-color: #ffffff !important;
    font-weight: 600 !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
}

.date-input-custom::-webkit-datetime-edit {
    color: #111827 !important;
    font-weight: 600 !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
}

.date-input-custom::-webkit-datetime-edit-text {
    color: #111827 !important;
    font-weight: 600 !important;
}

.date-input-custom::-webkit-datetime-edit-month-field {
    color: #111827 !important;
    font-weight: 600 !important;
}

.date-input-custom::-webkit-datetime-edit-day-field {
    color: #111827 !important;
    font-weight: 600 !important;
}

.date-input-custom::-webkit-datetime-edit-year-field {
    color: #111827 !important;
    font-weight: 600 !important;
}

.date-input-custom::-webkit-calendar-picker-indicator {
    color: #111827 !important;
    opacity: 1 !important;
    filter: invert(0) !important;
    background-color: transparent !important;
    cursor: pointer !important;
}

.date-input-custom:focus::-webkit-datetime-edit {
    color: #111827 !important;
    font-weight: 600 !important;
    background-color: #ffffff !important;
}

.date-input-custom:focus {
    color: #111827 !important;
    background-color: #ffffff !important;
    font-weight: 600 !important;
}
</style>