<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { 
    MapPinIcon, 
    CalendarIcon, 
    UserGroupIcon, 
    MagnifyingGlassIcon,
    HomeIcon,
    StarIcon,
    ShieldCheckIcon,
    ClockIcon,
    HeartIcon,
    ChevronRightIcon,
    MinusIcon,
    PlusIcon,
    BuildingOfficeIcon
} from '@heroicons/vue/24/outline';
import DatePicker from '@/Components/DatePicker.vue';
import Header from '@/Components/Header.vue';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    featuredVillas: {
        type: Array,
        default: () => []
    }
});

const LOCATIONS = [
    'Antalya', 'Bodrum', 'Fethiye', 'Kaş', 'Kalkan', 
    'Marmaris', 'Göcek', 'Çeşme', 'Alaçatı', 'İzmir'
];

const CATEGORIES = [
    { key: 'sea', label: 'Deniz Manzaralı', icon: '🏖️' },
    { key: 'nature', label: 'Doğa İçinde', icon: '🌲' },
    { key: 'city', label: 'Şehir Merkezi', icon: '🏙️' },
    { key: 'honeymoon', label: 'Balayı', icon: '💕' },
    { key: 'heated', label: 'Isıtmalı Havuz', icon: '🏊' }
];

const category = ref('sea');
const selectedCategory = ref('sea');
const query = ref('');
const locInput = ref('');
const showLocList = ref(false);
const guests = ref(2);

const searchForm = ref({
    location: '',
    check_in: '',
    check_out: '',
    guests: 2
});

const filteredVillasByCategory = computed(() => {
    if (!props.featuredVillas || props.featuredVillas.length === 0) return [];
    return props.featuredVillas.filter(villa => {
        const amenityList = villa.amenities || [];
        const amenityString = amenityList.map(a => a.amenity_type || a).join(' ').toLowerCase();
        const cityName = villa.city?.toLowerCase() || '';
        const description = villa.description?.toLowerCase() || '';
        
        if (selectedCategory.value === 'sea') {
            return amenityString.includes('sea_view') || amenityString.includes('pool') || 
                   cityName.includes('bodrum') || cityName.includes('antalya') || cityName.includes('kaş') || 
                   description.includes('deniz') || description.includes('sahil');
        }
        if (selectedCategory.value === 'nature') {
            return cityName.includes('kapadokya') || cityName.includes('şirince') || cityName.includes('akyaka') ||
                   amenityString.includes('garden') || amenityString.includes('vineyard') || 
                   description.includes('doğa') || description.includes('dağ');
        }
        if (selectedCategory.value === 'city') {
            return cityName.includes('merkez') || cityName.includes('marmaris') || 
                   description.includes('marina') || description.includes('merkez');
        }
        if (selectedCategory.value === 'honeymoon') {
            return villa.max_guests <= 4 && (amenityString.includes('pool') || amenityString.includes('sea_view')) &&
                   (cityName.includes('kaş') || cityName.includes('kalkan') || cityName.includes('datça'));
        }
        if (selectedCategory.value === 'heated') {
            return amenityString.includes('pool') || description.includes('havuz');
        }
        return true;
    }).slice(0, 6);
});

const getCategoryLabel = (categoryKey) => {
    const cat = CATEGORIES.find(c => c.key === categoryKey);
    return cat ? cat.label : 'Lüks Villa';
};

const filteredLocations = computed(() => {
    const q = locInput.value.trim().toLowerCase();
    if (!q) return LOCATIONS;
    return LOCATIONS.filter(l => l.toLowerCase().includes(q));
});

const searchVillas = () => {
    const params = new URLSearchParams({
        location: locInput.value || searchForm.value.location,
        check_in: searchForm.value.check_in,
        check_out: searchForm.value.check_out,
        guests: guests.value,
        category: category.value,
        q: query.value
    });
    
    window.location.href = `/villas?${params.toString()}`;
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
    }).format(price);
};

const incrementGuests = () => {
    guests.value = Math.min(16, guests.value + 1);
    searchForm.value.guests = guests.value;
};

const decrementGuests = () => {
    guests.value = Math.max(1, guests.value - 1);
    searchForm.value.guests = guests.value;
};

onMounted(() => {
    // Set default dates
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    searchForm.value.check_in = today.toISOString().split('T')[0];
    searchForm.value.check_out = tomorrow.toISOString().split('T')[0];
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-gray-800">
        <Head :title="$page.props.settings.general.site_name + ' - Lüks Villa Kiralama'" />
        
        <!-- Header Component -->
        <Header />

        <!-- Hero Section with Video Background -->
        <section class="relative h-screen flex items-center justify-center overflow-hidden">
            <!-- Video Background -->
            <video 
                autoplay 
                muted 
                loop 
                class="absolute inset-0 w-full h-full object-cover z-0"
            >
                <source src="https://player.vimeo.com/external/342333493.sd.mp4?s=47eabb1f9bab2c7d7b8ed8d9c8b8c8b8&profile_id=164&oauth2_token_id=57447761" type="video/mp4">
            </video>
            
            <!-- Overlay with Background Image -->
            <div class="absolute inset-0 z-10" style="background-image: url('https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20resort%20aerial%20view%20turquoise%20pool%20mediterranean%20coast%20sunset%20golden%20hour&image_size=landscape_16_9'); background-size: cover; background-position: center; background-blend-mode: overlay; background-color: rgba(0,0,0,0.4);"></div>
            
            <!-- Content -->
            <div class="relative z-20 text-center text-white px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-6 sm:mb-8 leading-tight">
                    <span class="block sm:inline">Türkiyenin en güzel</span><br class="hidden sm:block">
                    <span class="text-blue-400 block sm:inline">Villa Koleksiyonu</span>
                </h1>
                <p class="text-lg sm:text-xl md:text-2xl mb-8 sm:mb-12 text-gray-200 max-w-4xl mx-auto leading-relaxed">
                    {{ $page.props.settings.general.site_description }}
                </p>
                
                <!-- Enhanced Search Form -->
                <div class="relative max-w-7xl mx-auto">
                    <!-- Background Glow Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/30 via-purple-500/30 to-pink-500/30 rounded-3xl blur-3xl opacity-80 animate-pulse"></div>
                    
                    <!-- Main Form Container -->
                    <div class="relative backdrop-blur-2xl bg-gradient-to-br from-white/20 to-white/10 border border-white/30 rounded-3xl p-8 shadow-2xl">
                        <!-- Form Title -->
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-white mb-2">🏖️ Hayalinizdeki Villayı Bulun</h3>
                            <p class="text-white/80 text-lg">500+ premium villa arasından size en uygun olanı seçin</p>
                        </div>
                        
                        <!-- Search Fields Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <!-- Location Field -->
                            <div class="lg:col-span-2 relative">
                                <label class="block text-sm font-semibold text-white mb-3 flex items-center">
                                    <MapPinIcon class="w-5 h-5 text-blue-300 mr-2" />
                                    Nereye gitmek istiyorsunuz?
                                </label>
                                <div class="relative">
                                    <div class="relative bg-white/25 border-2 border-white/40 rounded-2xl backdrop-blur-sm overflow-hidden hover:bg-white/30 transition-all duration-200">
                                        <MapPinIcon class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-blue-300 z-10" />
                                        <input 
                                            v-model="locInput"
                                            @focus="showLocList = true"
                                            @blur="setTimeout(() => showLocList = false, 200)"
                                            type="text" 
                                            placeholder="Örn: Bodrum, Antalya, Kaş..."
                                            class="w-full pl-12 pr-4 py-4 bg-transparent text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-blue-400/60 text-base font-medium"
                                        >
                                    </div>
                                    <!-- Location Dropdown -->
                                    <div v-if="showLocList && filteredLocations.length" class="absolute top-full left-0 right-0 mt-1 bg-white/95 backdrop-blur-xl border border-white/30 rounded-lg shadow-xl z-50 max-h-48 overflow-y-auto">
                                        <div 
                                            v-for="location in filteredLocations" 
                                            :key="location"
                                            @click="locInput = location; showLocList = false"
                                            class="px-3 py-2 hover:bg-blue-50/80 cursor-pointer transition-colors flex items-center space-x-2 border-b border-gray-100/50 last:border-b-0"
                                        >
                                            <MapPinIcon class="h-3 w-3 text-blue-500" />
                                            <span class="text-gray-800 text-sm">{{ location }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Date Fields -->
                            <div class="relative">
                                <label class="block text-sm font-semibold text-white mb-3 flex items-center">
                                    <CalendarIcon class="w-5 h-5 text-emerald-300 mr-2" />
                                    Giriş Tarihi
                                </label>
                                <div class="relative">
                                    <DatePicker 
                                        v-model="searchForm.check_in"
                                        placeholder="Giriş tarihi seçin"
                                        :min-date="new Date().toISOString().split('T')[0]"
                                        class="w-full py-4 px-4 bg-white/25 border-2 border-white/40 rounded-2xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-emerald-400/60 text-base font-medium hover:bg-white/30 transition-all duration-200"
                                    />
                                </div>
                            </div>
                            
                            <div class="relative">
                                <label class="block text-sm font-semibold text-white mb-3 flex items-center">
                                    <CalendarIcon class="w-5 h-5 text-purple-300 mr-2" />
                                    Çıkış Tarihi
                                </label>
                                <div class="relative">
                                    <DatePicker 
                                        v-model="searchForm.check_out"
                                        placeholder="Çıkış tarihi seçin"
                                        :min-date="searchForm.check_in || new Date().toISOString().split('T')[0]"
                                        class="w-full py-4 px-4 bg-white/25 border-2 border-white/40 rounded-2xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-purple-400/60 text-base font-medium hover:bg-white/30 transition-all duration-200"
                                    />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Guest Counter and Search Button Row -->
                        <div class="flex flex-col lg:flex-row gap-6 items-end">
                            <!-- Guest Counter -->
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-white mb-3 flex items-center">
                                    <UserGroupIcon class="w-5 h-5 text-orange-300 mr-2" />
                                    Kaç kişi kalacaksınız?
                                </label>
                                <div class="relative bg-white/25 border-2 border-white/40 rounded-2xl backdrop-blur-sm px-6 py-4 hover:bg-white/30 transition-all duration-200">
                                    <div class="flex items-center justify-between">
                                        <button 
                                            @click="decrementGuests"
                                            class="w-10 h-10 rounded-xl bg-white/30 border-2 border-white/40 text-white hover:bg-white/40 transition-all duration-200 flex items-center justify-center hover:scale-105 transform"
                                        >
                                            <MinusIcon class="h-5 w-5" />
                                        </button>
                                        <div class="flex-1 text-center px-4">
                                            <span class="text-white font-bold text-xl">{{ guests }}</span>
                                            <div class="text-white/80 text-sm font-medium">Misafir</div>
                                        </div>
                                        <button 
                                            @click="incrementGuests"
                                            class="w-10 h-10 rounded-xl bg-white/30 border-2 border-white/40 text-white hover:bg-white/40 transition-all duration-200 flex items-center justify-center hover:scale-105 transform"
                                        >
                                            <PlusIcon class="h-5 w-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Search Button -->
                            <div class="lg:w-auto w-full">
                                <Link 
                                    :href="route('villas.index')"
                                    class="inline-flex items-center justify-center px-8 py-5 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-2xl transition-all duration-300 shadow-2xl hover:shadow-orange-500/25 transform hover:-translate-y-1 hover:scale-105 text-lg w-full lg:w-auto min-w-[200px] group"
                                >
                                    <MagnifyingGlassIcon class="h-6 w-6 mr-3 group-hover:scale-110 transition-transform" />
                                    <span>🔍 Villa Ara</span>
                                </Link>
                            </div>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="mt-4 pt-4 border-t border-white/15">
                            <div class="flex flex-wrap justify-center gap-4 text-center">
                                <div class="flex items-center space-x-1.5">
                                    <div class="w-1.5 h-1.5 bg-blue-400 rounded-full"></div>
                                    <span class="text-white/70 text-xs">500+ Villa</span>
                                </div>
                                <div class="flex items-center space-x-1.5">
                                    <div class="w-1.5 h-1.5 bg-green-400 rounded-full"></div>
                                    <span class="text-white/70 text-xs">7/24 Destek</span>
                                </div>
                                <div class="flex items-center space-x-1.5">
                                    <div class="w-1.5 h-1.5 bg-purple-400 rounded-full"></div>
                                    <span class="text-white/70 text-xs">Hızlı Rezervasyon</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Category Filters -->
        <section class="py-16 bg-gradient-to-br from-gray-50 to-blue-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Premium Villa Kategorileri
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Tatil tarzınıza uygun villa kategorisini seçin ve size özel hazırlanmış koleksiyonumuzu keşfedin
                    </p>
                </div>
                
                <!-- Category Tabs -->
                <div class="flex flex-wrap justify-center gap-2 sm:gap-4 mb-12 px-4">
                    <button 
                        v-for="cat in CATEGORIES" 
                        :key="cat.key"
                        @click="selectedCategory = cat.key"
                        :class="[
                            'px-4 sm:px-6 py-2 sm:py-3 rounded-2xl font-semibold transition-all duration-300 text-sm sm:text-base whitespace-nowrap',
                            selectedCategory === cat.key 
                                ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg transform scale-105' 
                                : 'bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 hover:scale-105'
                        ]"
                    >
                        <span class="text-lg sm:text-xl mr-1 sm:mr-2">{{ cat.icon }}</span>
                        <span class="hidden sm:inline">{{ cat.label }}</span>
                        <span class="sm:hidden">{{ cat.label.split(' ')[0] }}</span>
                    </button>
                </div>
                
                <!-- Category Villas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div 
                        v-for="villa in filteredVillasByCategory" 
                        :key="villa.id" 
                        class="group bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border border-gray-100 hover:border-blue-200"
                    >
                        <div class="relative overflow-hidden">
                            <img 
                                :src="villa.primary_image || 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20exterior%20with%20pool%20modern%20architecture&image_size=landscape_4_3'" 
                                :alt="villa.title"
                                class="w-full h-72 object-cover group-hover:scale-110 transition-transform duration-700"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Price Badge -->
                            <div class="absolute top-4 right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-2xl text-sm font-bold shadow-lg backdrop-blur-sm">
                                💰 {{ formatPrice(villa.price_per_night) }}/gece
                            </div>
                            
                            <!-- Category Badge -->
                            <div class="absolute top-4 left-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-3 py-1 rounded-2xl text-xs font-semibold shadow-lg backdrop-blur-sm">
                                ⭐ {{ getCategoryLabel(villa.category) }}
                            </div>
                            
                            <!-- Heart Icon -->
                            <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-lg hover:bg-white transition-colors">
                                    <HeartIcon class="h-5 w-5 text-red-500 hover:fill-current transition-colors" />
                                </button>
                            </div>
                            
                            <!-- Rating -->
                            <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="bg-white/90 backdrop-blur-sm px-3 py-1 rounded-2xl shadow-lg flex items-center">
                                    <StarIcon class="h-4 w-4 text-yellow-400 fill-current mr-1" />
                                    <span class="text-sm font-semibold text-gray-800">4.8</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">{{ villa.title }}</h3>
                            
                            <div class="flex items-center text-gray-600 mb-4">
                                <MapPinIcon class="h-5 w-5 mr-2 text-blue-500" />
                                <span class="font-medium">{{ villa.city }}, {{ villa.country }}</span>
                            </div>
                            
                            <!-- Features Grid -->
                            <div class="grid grid-cols-3 gap-3 mb-6">
                                <div class="flex items-center justify-center bg-blue-50 px-3 py-2 rounded-xl">
                                    <UserGroupIcon class="h-4 w-4 mr-1 text-blue-600" />
                                    <span class="text-sm font-semibold text-blue-800">{{ villa.max_guests }}</span>
                                </div>
                                <div class="flex items-center justify-center bg-green-50 px-3 py-2 rounded-xl">
                                    <span class="text-lg mr-1">🛏️</span>
                                    <span class="text-sm font-semibold text-green-800">{{ villa.bedrooms }}</span>
                                </div>
                                <div class="flex items-center justify-center bg-purple-50 px-3 py-2 rounded-xl">
                                    <span class="text-lg mr-1">🛁</span>
                                    <span class="text-sm font-semibold text-purple-800">{{ villa.bathrooms }}</span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <Link 
                                    :href="`/villas/${villa.id}`" 
                                    class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-4 rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-semibold text-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 group/btn"
                                >
                                    <span class="group-hover/btn:scale-105 transition-transform inline-block">🏖️ Detayları Gör</span>
                                </Link>
                                <button class="bg-gray-100 hover:bg-gray-200 p-3 rounded-2xl transition-colors">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Show More Button -->
                <div class="text-center mt-16">
                    <div class="relative inline-block">
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-3xl blur-xl opacity-30 animate-pulse"></div>
                        <Link 
                            :href="route('villas.index', { category: selectedCategory })" 
                            class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-blue-700 text-white px-12 py-5 rounded-3xl hover:from-blue-700 hover:via-purple-700 hover:to-blue-800 transition-all duration-300 font-bold text-lg shadow-2xl hover:shadow-blue-500/25 transform hover:-translate-y-2 hover:scale-105 inline-flex items-center group"
                        >
                            <span class="mr-3 text-2xl group-hover:animate-bounce">🏖️</span>
                            <span>{{ getCategoryLabel(selectedCategory) }} Kategorisindeki Tüm Villaları Keşfet</span>
                            <ChevronRightIcon class="h-6 w-6 ml-3 group-hover:translate-x-1 transition-transform" />
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Villas Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Editörün Seçimi
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Uzmanlarımız tarafından özenle seçilen, en yüksek standartlara sahip premium villalarımız
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="villa in props.featuredVillas" :key="villa.id" class="group bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <div class="relative overflow-hidden">
                            <img 
                                :src="villa.primary_image || 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20exterior%20with%20pool%20modern%20architecture&image_size=landscape_4_3'" 
                                :alt="villa.title"
                                class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                {{ formatPrice(villa.price_per_night) }}/gece
                            </div>
                            <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="flex items-center text-white text-sm">
                                    <StarIcon class="h-4 w-4 text-yellow-400 mr-1" />
                                    <span class="font-medium">4.8</span>
                                    <span class="ml-2 text-white/80">(24 değerlendirme)</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">{{ villa.title }}</h3>
                            <p class="text-gray-600 mb-4 flex items-center">
                                <MapPinIcon class="h-4 w-4 mr-2 text-blue-500" />
                                {{ villa.city }}, {{ villa.country }}
                            </p>
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-6">
                                <span class="flex items-center bg-gray-50 px-3 py-1 rounded-full">
                                    <UserGroupIcon class="h-4 w-4 mr-1 text-blue-500" />
                                    {{ villa.max_guests }} Kişi
                                </span>
                                <span class="flex items-center bg-gray-50 px-3 py-1 rounded-full">
                                    🛏️ {{ villa.bedrooms }} Oda
                                </span>
                                <span class="flex items-center bg-gray-50 px-3 py-1 rounded-full">
                                    🛁 {{ villa.bathrooms }} Banyo
                                </span>
                            </div>
                            <Link 
                                :href="`/villas/${villa.id}`" 
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-6 rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-semibold inline-block text-center shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                            >
                                Detayları Keşfet
                            </Link>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-16">
                    <div class="relative inline-block">
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-blue-500 rounded-3xl blur-xl opacity-30 animate-pulse"></div>
                        <Link 
                            :href="route('villas.index')" 
                            class="relative bg-gradient-to-r from-emerald-600 via-blue-600 to-emerald-700 text-white px-12 py-5 rounded-3xl hover:from-emerald-700 hover:via-blue-700 hover:to-emerald-800 transition-all duration-300 font-bold text-lg shadow-2xl hover:shadow-emerald-500/25 transform hover:-translate-y-2 hover:scale-105 inline-flex items-center group"
                        >
                            <span class="mr-3 text-2xl group-hover:animate-bounce">⭐</span>
                            <span>Premium Villa Koleksiyonunu Keşfet</span>
                            <ChevronRightIcon class="h-6 w-6 ml-3 group-hover:translate-x-1 transition-transform" />
                        </Link>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Popular Destinations -->
        <section class="py-16 bg-gradient-to-br from-blue-600 to-purple-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        Premium Villa Deneyiminiz Başlasın
                    </h2>
                    <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                        Türkiye'nin en prestijli lokasyonlarında, lüks ve konforu bir arada yaşayın
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div 
                        v-for="location in LOCATIONS.slice(0, 8)" 
                        :key="location"
                        class="group relative overflow-hidden rounded-2xl cursor-pointer transform hover:scale-105 transition-all duration-300"
                    >
                        <div class="aspect-w-16 aspect-h-12 bg-gradient-to-br from-blue-400 to-purple-500">
                            <img 
                                :src="`https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=${encodeURIComponent(location + ' Turkey beautiful landscape villa destination')}&image_size=landscape_4_3`"
                                :alt="location"
                                class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500"
                            >
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <h3 class="text-xl font-bold text-white mb-1">{{ location }}</h3>
                            <p class="text-white/80 text-sm">{{ Math.floor(Math.random() * 20) + 5 }} villa mevcut</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Premium Hizmet Avantajları
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Sektörde 15 yıllık deneyimimizle, size mükemmel villa kiralama deneyimi sunuyoruz
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="group text-center p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <MapPinIcon class="h-10 w-10 text-white" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Seçkin Lokasyonlar</h3>
                        <p class="text-gray-600 leading-relaxed">Türkiye'nin en prestijli bölgelerinde, deniz manzaralı ve özel konumlardaki villalar</p>
                    </div>
                    
                    <div class="group text-center p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="bg-gradient-to-br from-green-500 to-green-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <CalendarIcon class="h-10 w-10 text-white" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Anında Rezervasyon</h3>
                        <p class="text-gray-600 leading-relaxed">Gelişmiş teknoloji altyapımızla güvenli ödeme ve anında rezervasyon onayı</p>
                    </div>
                    
                    <div class="group text-center p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <UserGroupIcon class="h-10 w-10 text-white" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Concierge Hizmeti</h3>
                        <p class="text-gray-600 leading-relaxed">Tatil öncesi ve süresince özel concierge desteği ile VIP deneyim</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Misafir Deneyimleri
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Premium villalarımızda konaklayan değerli misafirlerimizin gerçek deneyimleri
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <StarIcon class="h-5 w-5" />
                                <StarIcon class="h-5 w-5" />
                                <StarIcon class="h-5 w-5" />
                                <StarIcon class="h-5 w-5" />
                                <StarIcon class="h-5 w-5" />
                            </div>
                        </div>
                        <p class="text-gray-700 mb-6 italic leading-relaxed">
                            "Bodrum'daki villada geçirdiğimiz hafta gerçekten mükemmeldi. Villa'nın konumu, deniz manzarası ve özellikle concierge hizmetleri beklentilerimizi aştı. Profesyonel ekip sayesinde hiçbir konuda sıkıntı yaşamadık."
                        </p>
                        <div class="flex items-center">
                            <img 
                                src="https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=professional%20portrait%20woman%20smiling%20happy%20customer&image_size=square"
                                alt="Ayşe Demir"
                                class="w-12 h-12 rounded-full object-cover mr-4"
                            >
                            <div>
                                <h4 class="font-semibold text-gray-900">Ayşe Demir</h4>
                                <p class="text-gray-600 text-sm">İstanbul</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <StarIcon class="h-5 w-5" />
                                <StarIcon class="h-5 w-5" />
                                <StarIcon class="h-5 w-5" />
                                <StarIcon class="h-5 w-5" />
                                <StarIcon class="h-5 w-5" />
                            </div>
                        </div>
                        <p class="text-gray-700 mb-6 italic leading-relaxed">
                            "Kalkan'daki villada geçirdiğimiz balayı tatili unutulmazdı. Villa'nın tasarımı, infinity havuzu ve özel hizmetler sayesinde rüya gibi bir deneyim yaşadık. Kesinlikle tavsiye ederim."
                        </p>
                        <div class="flex items-center">
                            <img 
                                src="https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=professional%20portrait%20man%20smiling%20happy%20customer&image_size=square"
                                alt="Mehmet Yılmaz"
                                class="w-12 h-12 rounded-full object-cover mr-4"
                            >
                            <div>
                                <h4 class="font-semibold text-gray-900">Mehmet Yılmaz</h4>
                                <p class="text-gray-600 text-sm">Ankara</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <StarIcon class="h-5 w-5" />
                                <StarIcon class="h-5 w-5" />
                                <StarIcon class="h-5 w-5" />
                                <StarIcon class="h-5 w-5" />
                                <StarIcon class="h-5 w-5" />
                            </div>
                        </div>
                        <p class="text-gray-700 mb-6 italic leading-relaxed">
                            "Çeşme'deki villa ailecek geçirdiğimiz en güzel tatildi. Villa'nın konumu, modern tasarımı ve özellikle çocuk dostu amenitileri mükemmeldi. Rezervasyondan check-out'a kadar her şey kusursuzdu."
                        </p>
                        <div class="flex items-center">
                            <img 
                                src="https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=professional%20portrait%20woman%20smiling%20happy%20customer%20brunette&image_size=square"
                                alt="Zeynep Kaya"
                                class="w-12 h-12 rounded-full object-cover mr-4"
                            >
                            <div>
                                <h4 class="font-semibold text-gray-900">Zeynep Kaya</h4>
                                <p class="text-gray-600 text-sm">İzmir</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
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
