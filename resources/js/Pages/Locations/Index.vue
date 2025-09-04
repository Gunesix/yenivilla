<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Header Component -->
        <Header />
        
        <!-- Hero Section with Video Background -->
        <div class="relative min-h-screen flex items-center justify-center overflow-hidden">
            <!-- Video Background -->
            <div class="absolute inset-0 w-full h-full">
                <video 
                    autoplay 
                    muted 
                    loop 
                    class="w-full h-full object-cover"
                >
                    <source src="https://player.vimeo.com/external/342333493.sd.mp4?s=47eabb1d7b8f2e1c0b1b1b1b1b1b1b1b1b1b1b1b&profile_id=164" type="video/mp4">
                </video>
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/70 via-purple-900/50 to-blue-800/70"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 text-center text-white px-4 sm:px-6 lg:px-8 pt-20">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                    Popüler Destinasyonlar
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-12 max-w-3xl mx-auto">
                    Türkiye'nin en güzel tatil bölgelerindeki lüks villalarımızı keşfedin
                </p>
                
                <!-- Glassmorphism Search Form -->
                <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-3xl p-8 shadow-2xl max-w-2xl mx-auto">
                    <div class="relative">
                        <label class="block text-sm font-medium text-white/90 mb-3">Destinasyon Ara</label>
                        <div class="relative">
                            <MapPinIcon class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-white/70" />
                            <input 
                                v-model="searchQuery" 
                                type="text" 
                                placeholder="Şehir veya bölge arayın..."
                                class="w-full pl-12 pr-4 py-4 bg-white/15 border border-white/25 rounded-xl focus:outline-none focus:ring-2 focus:ring-white/40 focus:border-white/50 text-white placeholder-white/60 backdrop-blur-sm transition-all duration-200"
                                @keyup.enter="searchLocation"
                            />
                            <button 
                                @click="searchLocation"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors"
                            >
                                <MagnifyingGlassIcon class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Popular Destinations -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-6">
                    Popüler Destinasyonlar
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    En çok tercih edilen tatil bölgelerimizde unutulmaz anılar biriktirin
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
                <div v-for="destination in popularDestinations" :key="destination.name" class="group cursor-pointer" @click="goToDestination(destination.name)">
                    <div class="relative overflow-hidden rounded-3xl backdrop-blur-xl bg-white/10 border border-white/20 shadow-2xl group-hover:shadow-3xl transition-all duration-500 group-hover:scale-105">
                        <div class="relative h-80">
                            <img 
                                :src="destination.image"
                                :alt="destination.name"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute top-4 right-4">
                                <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-full px-3 py-1 text-white text-sm font-medium">
                                    {{ destination.villa_count }} Villa
                                </div>
                            </div>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                            <h3 class="text-2xl font-bold mb-2 group-hover:text-blue-300 transition-colors">
                                {{ destination.name }}
                            </h3>
                            <p class="text-blue-100 mb-4 text-sm leading-relaxed">
                                {{ destination.description }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-full px-3 py-1">
                                    <span class="text-sm font-medium">{{ destination.price_range }}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <StarIcon class="h-4 w-4 text-yellow-400" />
                                    <span class="text-sm font-medium">4.8</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Interactive Map Section -->
        <div class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Harita Üzerinde Keşfet</h2>
                    <p class="text-lg text-gray-600">Villalarımızı harita üzerinde görüntüleyin ve konumlarını keşfedin</p>
                </div>
                
                <!-- Google Maps Container -->
                <div class="bg-gray-200 h-96 rounded-lg overflow-hidden mb-8">
                    <div id="google-map" class="w-full h-full"></div>
                </div>
                
                <!-- Map Filters -->
                <div class="flex flex-wrap justify-center gap-4">
                    <button 
                        v-for="filter in mapFilters" 
                        :key="filter.key"
                        @click="activeMapFilter = filter.key"
                        :class="[activeMapFilter === filter.key ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50', 'px-4 py-2 rounded-lg border border-gray-300 transition-colors']"
                    >
                        {{ filter.label }}
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Regions Section -->
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Bölgelere Göre Keşfet</h2>
                    <p class="text-lg text-gray-600">Türkiye'nin farklı bölgelerindeki villa seçeneklerimiz</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="region in regions" :key="region.name" class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow cursor-pointer" @click="goToRegion(region.name)">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <MapPinIcon class="h-8 w-8 text-blue-600" />
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ region.name }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ region.description }}</p>
                            <div class="space-y-2">
                                <div class="text-2xl font-bold text-blue-600">{{ region.villa_count }}</div>
                                <div class="text-sm text-gray-500">Villa Seçeneği</div>
                                <div class="text-sm text-gray-600">{{ region.price_range }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Featured Cities -->
        <div class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Öne Çıkan Şehirler</h2>
                    <p class="text-lg text-gray-600">En popüler şehirlerimizdeki villa seçenekleri</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div v-for="city in featuredCities" :key="city.name" class="group cursor-pointer" @click="goToDestination(city.name)">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden group-hover:shadow-xl transition-shadow">
                            <div class="md:flex">
                                <div class="md:w-1/2">
                                    <img 
                                        :src="city.image"
                                        :alt="city.name"
                                        class="w-full h-48 md:h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    />
                                </div>
                                <div class="md:w-1/2 p-6">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ city.name }}</h3>
                                    <p class="text-gray-600 mb-4">{{ city.description }}</p>
                                    <div class="space-y-2 mb-4">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Villa Sayısı:</span>
                                            <span class="font-semibold">{{ city.villa_count }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Fiyat Aralığı:</span>
                                            <span class="font-semibold">{{ city.price_range }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Ortalama Puan:</span>
                                            <div class="flex items-center">
                                                <StarIcon class="h-4 w-4 text-yellow-400 mr-1" />
                                                <span class="font-semibold">{{ city.rating }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <span v-for="feature in city.features" :key="feature" class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                            {{ feature }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- CTA Section -->
        <div class="relative py-20 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-blue-800"></div>
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Hayalinizdeki Tatili Bulun
                </h2>
                <p class="text-xl text-blue-100 mb-12 max-w-3xl mx-auto">
                    Türkiye'nin en güzel lokasyonlarında unutulmaz anılar biriktirin
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <Link 
                        :href="route('villas.index')" 
                        class="backdrop-blur-xl bg-white/20 border border-white/30 text-white px-8 py-4 rounded-2xl hover:bg-white/30 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                    >
                        Tüm Villaları Gör
                    </Link>
                    <button class="backdrop-blur-xl bg-blue-500/30 border border-blue-300/30 text-white px-8 py-4 rounded-2xl hover:bg-blue-400/40 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Özel Teklifler
                    </button>
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
                            <span class="text-xl font-bold text-gray-900">{{ $page.props.settings?.site_name || $page.props.settings?.general?.site_name || 'VillaRent' }}</span>
                        </div>
                        <p class="text-gray-600 mb-6 max-w-md">
                            Türkiye'nin en güzel destinasyonlarında unutulmaz tatil deneyimleri yaşayın. Premium villa koleksiyonumuzla hayalinizdeki tatili gerçekleştirin.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-rose-500 transition-colors">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-rose-500 transition-colors">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-rose-500 transition-colors">
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
                            © 2024 {{ $page.props.settings?.site_name || $page.props.settings?.general?.site_name || 'VillaRent' }}. Tüm hakları saklıdır.
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
import { ref, onMounted, nextTick } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { MagnifyingGlassIcon, MapPinIcon, StarIcon } from '@heroicons/vue/24/outline'
import Header from '@/Components/Header.vue'
import axios from 'axios'

const searchQuery = ref('')
const activeMapFilter = ref('all')
const map = ref(null)
const markers = ref([])
const villas = ref([])

const popularDestinations = ref([
    {
        name: 'Bodrum',
        description: 'Ege\'nin incisi, tarihi ve doğal güzellikleri',
        villa_count: 45,
        price_range: '₺2,000 - ₺8,000',
        image: 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=bodrum%20turkey%20coastal%20view%20luxury%20villas&image_size=landscape_4_3'
    },
    {
        name: 'Antalya',
        description: 'Akdeniz\'in parlayan yıldızı',
        villa_count: 38,
        price_range: '₺1,800 - ₺7,500',
        image: 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=antalya%20turkey%20mediterranean%20coast%20luxury%20resort&image_size=landscape_4_3'
    },
    {
        name: 'Kaş',
        description: 'Sakin ve huzurlu tatil cenneti',
        villa_count: 22,
        price_range: '₺2,200 - ₺6,000',
        image: 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=kas%20turkey%20small%20coastal%20town%20turquoise%20sea&image_size=landscape_4_3'
    },
    {
        name: 'Çeşme',
        description: 'Ege\'nin rüzgar ve sörf merkezi',
        villa_count: 31,
        price_range: '₺1,900 - ₺5,500',
        image: 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=cesme%20turkey%20aegean%20coast%20windsurfing%20beach&image_size=landscape_4_3'
    },
    {
        name: 'Fethiye',
        description: 'Doğa harikası koylar ve plajlar',
        villa_count: 28,
        price_range: '₺2,100 - ₺7,000',
        image: 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=fethiye%20turkey%20blue%20lagoon%20paragliding%20mountains&image_size=landscape_4_3'
    },
    {
        name: 'Kalkan',
        description: 'Butik tatil köyü atmosferi',
        villa_count: 19,
        price_range: '₺2,500 - ₺8,500',
        image: 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=kalkan%20turkey%20hillside%20village%20mediterranean%20luxury&image_size=landscape_4_3'
    }
])

const mapLocations = ref([
    { name: 'Bodrum', count: 45 },
    { name: 'Antalya', count: 38 },
    { name: 'Kaş', count: 22 },
    { name: 'Çeşme', count: 31 }
])

const mapFilters = ref([
    { key: 'all', label: 'Tümü' },
    { key: 'beach', label: 'Plaj' },
    { key: 'mountain', label: 'Dağ' },
    { key: 'city', label: 'Şehir' },
    { key: 'countryside', label: 'Kırsal' }
])

const regions = ref([
    {
        name: 'Ege Bölgesi',
        description: 'Tarihi zenginlik ve doğal güzellik',
        villa_count: 89,
        price_range: '₺1,800 - ₺8,000'
    },
    {
        name: 'Akdeniz Bölgesi',
        description: 'Sıcak iklim ve berrak deniz',
        villa_count: 76,
        price_range: '₺2,000 - ₺7,500'
    },
    {
        name: 'Marmara Bölgesi',
        description: 'Şehir hayatı ve doğa bir arada',
        villa_count: 34,
        price_range: '₺2,200 - ₺9,000'
    },
    {
        name: 'Karadeniz Bölgesi',
        description: 'Yeşil doğa ve serin iklim',
        villa_count: 18,
        price_range: '₺1,500 - ₺4,500'
    }
])

const featuredCities = ref([
    {
        name: 'Bodrum',
        description: 'Ege Denizi\'nin incisi Bodrum, tarihi Halikarnas Mozolesi\'nden modern marina yaşamına kadar geniş bir yelpazede tatil imkanı sunar.',
        villa_count: 45,
        price_range: '₺2,000 - ₺8,000',
        rating: '4.8',
        features: ['Deniz Manzarası', 'Tarihi Yerler', 'Gece Hayatı', 'Marina'],
        image: 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=bodrum%20castle%20marina%20luxury%20yachts%20sunset&image_size=landscape_4_3'
    },
    {
        name: 'Antalya',
        description: 'Akdeniz\'in parlayan yıldızı Antalya, antik şehirlerden modern tatil köylerine kadar her türlü tatil deneyimi sunar.',
        villa_count: 38,
        price_range: '₺1,800 - ₺7,500',
        rating: '4.7',
        features: ['Antik Şehirler', 'Şelaleler', 'Golf', 'Aqua Park'],
        image: 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=antalya%20old%20town%20harbor%20mediterranean%20architecture&image_size=landscape_4_3'
    }
])

const searchLocation = () => {
    if (searchQuery.value.trim()) {
        router.visit(route('villas.index', { location: searchQuery.value.trim() }))
    }
}

const goToDestination = (destination) => {
    router.visit(route('villas.index', { location: destination }))
}

const goToRegion = (region) => {
    router.visit(route('villas.index', { region: region }))
}

// Google Maps Functions
const initializeMap = () => {
    if (typeof google === 'undefined') {
        console.error('Google Maps API not loaded')
        return
    }
    
    const mapOptions = {
        zoom: 6,
        center: { lat: 39.9334, lng: 32.8597 }, // Turkey center
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: [
            {
                featureType: 'water',
                elementType: 'geometry',
                stylers: [{ color: '#e9e9e9' }, { lightness: 17 }]
            },
            {
                featureType: 'landscape',
                elementType: 'geometry',
                stylers: [{ color: '#f5f5f5' }, { lightness: 20 }]
            }
        ]
    }
    
    map.value = new google.maps.Map(document.getElementById('google-map'), mapOptions)
    loadVillas()
}

const loadVillas = async () => {
    try {
        const response = await axios.get('/api/locations/villas-map')
        villas.value = response.data
        addMarkersToMap()
    } catch (error) {
        console.error('Error loading villas:', error)
    }
}

const addMarkersToMap = () => {
    // Clear existing markers
    markers.value.forEach(marker => marker.setMap(null))
    markers.value = []
    
    villas.value.forEach(villa => {
        if (villa.latitude && villa.longitude) {
            const marker = new google.maps.Marker({
                position: { lat: parseFloat(villa.latitude), lng: parseFloat(villa.longitude) },
                map: map.value,
                title: villa.title,
                icon: {
                    url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="16" cy="16" r="12" fill="#2563eb" stroke="white" stroke-width="3"/>
                            <path d="M16 10L18 14H22L19 17L20 22L16 19L12 22L13 17L10 14H14L16 10Z" fill="white"/>
                        </svg>
                    `),
                    scaledSize: new google.maps.Size(32, 32)
                }
            })
            
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div class="p-3 max-w-xs">
                        <h3 class="font-bold text-lg mb-2">${villa.title}</h3>
                        <p class="text-gray-600 mb-2">${villa.city}, ${villa.country}</p>
                        <p class="text-blue-600 font-semibold mb-3">₺${villa.price_per_night}/gece</p>
                        ${villa.primary_image ? `<img src="${villa.primary_image}" alt="${villa.title}" class="w-full h-24 object-cover rounded mb-2">` : ''}
                        <a href="${villa.url}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition-colors inline-block">Detayları Gör</a>
                    </div>
                `
            })
            
            marker.addListener('click', () => {
                infoWindow.open(map.value, marker)
            })
            
            markers.value.push(marker)
        }
    })
}

// Initialize map when component is mounted
onMounted(() => {
    nextTick(() => {
        // Wait for Google Maps API to load
        const checkGoogleMaps = () => {
            if (typeof google !== 'undefined' && google.maps) {
                initializeMap()
            } else {
                setTimeout(checkGoogleMaps, 100)
            }
        }
        checkGoogleMaps()
    })
})
</script>