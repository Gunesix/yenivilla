<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, nextTick } from 'vue';
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
    ChevronLeftIcon,
    MinusIcon,
    PlusIcon,
    BuildingOfficeIcon,
    SparklesIcon,
    CheckCircleIcon,
    GlobeAltIcon,
    PlayIcon,
    ArrowRightIcon,
    BuildingOffice2Icon,
    CurrencyDollarIcon,
    UsersIcon,
    HomeModernIcon,
    TrophyIcon
} from '@heroicons/vue/24/outline';
import { StarIcon as StarSolidIcon, HeartIcon as HeartSolidIcon } from '@heroicons/vue/24/solid';
import DatePicker from '@/Components/DatePicker.vue';
import Header from '@/Components/Header.vue';

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    featuredVillas: {
        type: Array,
        default: () => []
    }
});

// Animation and interaction states
const isLoaded = ref(false);
const currentTestimonial = ref(0);
const wishlist = ref(new Set());
const animatedStats = ref({
    villas: 0,
    guests: 0,
    destinations: 0,
    rating: 0
});
const targetStats = {
    villas: 500,
    guests: 50000,
    destinations: 25,
    rating: 4.9
};
const isStatsVisible = ref(false);
const parallaxOffset = ref(0);

// Search form state
const searchForm = ref({
    location: '',
    check_in: '',
    check_out: '',
    guests: 2
});

const showLocationDropdown = ref(false);
const guests = ref(2);

// Popular locations
const locations = [
    'Bodrum', 'Antalya', 'Kaş', 'Kalkan', 'Fethiye', 
    'Marmaris', 'Çeşme', 'Alaçatı', 'Göcek', 'Datça'
];

// Villa categories
const categories = [
    {
        id: 'luxury',
        name: 'Lüks Villalar',
        description: 'Premium konforlu villalar',
        image: 'luxury%20villa%20pool%20sunset%20mediterranean',
        count: '150+'
    },
    {
        id: 'seaside',
        name: 'Deniz Kenarı',
        description: 'Deniz manzaralı villalar',
        image: 'seaside%20villa%20beach%20turquoise%20water',
        count: '80+'
    },
    {
        id: 'mountain',
        name: 'Dağ Evi',
        description: 'Doğa içinde huzurlu tatil',
        image: 'mountain%20villa%20forest%20nature%20peaceful',
        count: '45+'
    },
    {
        id: 'historic',
        name: 'Tarihi Evler',
        description: 'Otantik taş evler',
        image: 'historic%20stone%20villa%20traditional%20architecture',
        count: '25+'
    }
];

// Category state
const selectedCategory = ref('luxury');

// Select category function
const selectCategory = (categoryId) => {
    selectedCategory.value = categoryId;
};

// Services/Features
const features = [
    {
        icon: ShieldCheckIcon,
        title: 'Güvenli Rezervasyon',
        description: 'SSL sertifikalı güvenli ödeme sistemi'
    },
    {
        icon: ClockIcon,
        title: '7/24 Destek',
        description: 'Her zaman yanınızda müşteri hizmetleri'
    },
    {
        icon: CheckCircleIcon,
        title: 'Kalite Garantisi',
        description: 'Tüm villalar kalite kontrolünden geçer'
    },
    {
        icon: HeartIcon,
        title: 'Kişiselleştirilmiş Hizmet',
        description: 'Size özel tatil deneyimi'
    }
];

// Statistics
const stats = [
    { number: '500+', label: 'Premium Villa' },
    { number: '50K+', label: 'Mutlu Misafir' },
    { number: '25+', label: 'Destinasyon' },
    { number: '4.9', label: 'Ortalama Puan' }
];

// Testimonials will be defined later with enhanced data

// Computed properties
const filteredVillas = computed(() => {
    if (!props.featuredVillas || props.featuredVillas.length === 0) return [];
    
    return props.featuredVillas.filter(villa => {
        const amenityList = villa.amenities || [];
        const amenityString = amenityList.map(a => a.amenity_type || a).join(' ').toLowerCase();
        const cityName = villa.city?.toLowerCase() || '';
        const description = villa.description?.toLowerCase() || '';
        const title = villa.title?.toLowerCase() || '';
        
        switch (selectedCategory.value) {
            case 'luxury':
                return villa.price_per_night > 1000 || 
                       amenityString.includes('luxury') || 
                       amenityString.includes('premium') ||
                       title.includes('lüks') || 
                       description.includes('lüks');
            case 'seaside':
                return amenityString.includes('sea_view') || 
                       amenityString.includes('beach') || 
                       cityName.includes('bodrum') || 
                       cityName.includes('antalya') || 
                       cityName.includes('kaş') ||
                       cityName.includes('kalkan') ||
                       cityName.includes('fethiye') ||
                       description.includes('deniz') || 
                       description.includes('sahil') ||
                       title.includes('deniz');
            case 'mountain':
                return cityName.includes('kapadokya') || 
                       cityName.includes('şirince') || 
                       cityName.includes('akyaka') ||
                       amenityString.includes('mountain') || 
                       amenityString.includes('nature') ||
                       description.includes('doğa') || 
                       description.includes('dağ') ||
                       title.includes('dağ');
            case 'historic':
                return amenityString.includes('historic') || 
                       amenityString.includes('traditional') ||
                       description.includes('tarihi') || 
                       description.includes('otantik') ||
                       title.includes('tarihi') ||
                       cityName.includes('şirince') ||
                       cityName.includes('alaçatı');
            default:
                return true;
        }
    }).slice(0, 6);
});

// Methods
const incrementGuests = () => {
    guests.value = Math.min(16, guests.value + 1);
    searchForm.value.guests = guests.value;
};

const decrementGuests = () => {
    guests.value = Math.max(1, guests.value - 1);
    searchForm.value.guests = guests.value;
};

const selectLocation = (location) => {
    searchForm.value.location = location;
    showLocationDropdown.value = false;
};

const searchVillas = () => {
    const params = new URLSearchParams({
        location: searchForm.value.location,
        check_in: searchForm.value.check_in,
        check_out: searchForm.value.check_out,
        guests: searchForm.value.guests
    });
    
    window.location.href = `/villas?${params.toString()}`;
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
    }).format(price);
};

// Animation functions
const toggleWishlist = (villaId) => {
    if (wishlist.value.has(villaId)) {
        wishlist.value.delete(villaId);
    } else {
        wishlist.value.add(villaId);
    }
};

const animateStats = () => {
    const duration = 2000;
    const steps = 60;
    const stepDuration = duration / steps;
    
    Object.keys(targetStats).forEach(key => {
        let currentValue = 0;
        const targetValue = targetStats[key];
        const increment = targetValue / steps;
        
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= targetValue) {
                currentValue = targetValue;
                clearInterval(timer);
            }
            animatedStats.value[key] = key === 'rating' ? 
                Math.round(currentValue * 10) / 10 : 
                Math.floor(currentValue);
        }, stepDuration);
    });
};

const nextTestimonial = () => {
    currentTestimonial.value = (currentTestimonial.value + 1) % testimonials.length;
};

const prevTestimonial = () => {
    currentTestimonial.value = currentTestimonial.value === 0 ? 
        testimonials.length - 1 : currentTestimonial.value - 1;
};

const handleScroll = () => {
    const scrolled = window.pageYOffset;
    parallaxOffset.value = scrolled * 0.5;
    
    // Check if stats section is visible
    const statsElement = document.getElementById('stats-section');
    if (statsElement && !isStatsVisible.value) {
        const rect = statsElement.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
            isStatsVisible.value = true;
            animateStats();
        }
    }
};

// Enhanced data
const testimonials = [
    {
        id: 1,
        name: 'Ayşe Demir',
        location: 'İstanbul',
        rating: 5,
        comment: 'Muhteşem bir deneyimdi! Villa çok temiz ve konforlu, manzara nefes kesiciydi. Kesinlikle tekrar geleceğiz.',
        avatar: 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=professional%20woman%20portrait%20smiling%20turkish&image_size=square',
        date: '2024-01-15'
    },
    {
        id: 2,
        name: 'Mehmet Kaya',
        location: 'Ankara',
        rating: 5,
        comment: 'Ailecek harika vakit geçirdik. Çocuklar havuzu çok sevdi, biz de huzurlu bir tatil yaptık. Hizmet kalitesi mükemmel.',
        avatar: 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=professional%20man%20portrait%20smiling%20turkish&image_size=square',
        date: '2024-01-10'
    },
    {
        id: 3,
        name: 'Zeynep Özkan',
        location: 'İzmir',
        rating: 5,
        comment: 'Balayımız için mükemmel bir seçimdi. Romantik atmosfer, özel hizmet ve unutulmaz anılar. Teşekkürler!',
        avatar: 'https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=elegant%20woman%20portrait%20smiling%20turkish&image_size=square',
        date: '2024-01-05'
    }
];

const premiumServices = [
    {
        icon: ShieldCheckIcon,
        title: 'Güvenli Rezervasyon',
        description: 'SSL şifrelemesi ve güvenli ödeme sistemi ile %100 güvenli rezervasyon',
        features: ['256-bit SSL', 'PCI DSS Uyumlu', 'Anında Onay']
    },
    {
        icon: ClockIcon,
        title: '7/24 Premium Destek',
        description: 'Uzman ekibimiz her an yanınızda, sorunlarınızı anında çözüyoruz',
        features: ['Canlı Destek', 'WhatsApp Hattı', 'Acil Durum Hizmeti']
    },
    {
        icon: TrophyIcon,
        title: 'Kalite Garantisi',
        description: 'Tüm villalarımız kalite standartlarımızı karşılar, memnuniyetiniz garanti',
        features: ['Kalite Kontrolü', 'Temizlik Garantisi', 'Memnuniyet Garantisi']
    },
    {
        icon: SparklesIcon,
        title: 'Kişiselleştirilmiş Hizmet',
        description: 'Size özel öneriler ve kişiselleştirilmiş tatil deneyimi',
        features: ['Özel Öneriler', 'Concierge Hizmeti', 'VIP Deneyim']
    }
];

// Lifecycle
onMounted(() => {
    // Set default dates
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    searchForm.value.check_in = today.toISOString().split('T')[0];
    searchForm.value.check_out = tomorrow.toISOString().split('T')[0];
    
    // Initialize animations
    nextTick(() => {
        isLoaded.value = true;
        window.addEventListener('scroll', handleScroll);
        
        // Auto-rotate testimonials
        setInterval(() => {
            nextTestimonial();
        }, 5000);
    });
});

// Cleanup
const cleanup = () => {
    window.removeEventListener('scroll', handleScroll);
};

// Watch for component unmount
if (typeof window !== 'undefined') {
    window.addEventListener('beforeunload', cleanup);
}
</script>

<template>
    <div class="min-h-screen bg-white" :class="{ 'animate-fade-in': isLoaded }">
        <Head :title="$page.props.settings?.general?.site_name + ' - Premium Villa Kiralama'" />
        
        <!-- Header -->
        <Header />

        <!-- Premium Hero Section with Parallax -->
        <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
            <!-- Parallax Background -->
            <div 
                class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-1000 ease-out"
                :style="{
                    backgroundImage: `url('https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20infinity%20pool%20mediterranean%20sunset%20aerial%20view%20turquoise%20water%20cinematic&image_size=landscape_16_9')`,
                    transform: `translateY(${parallaxOffset}px)`
                }"
            ></div>
            
            <!-- Gradient Overlays -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900/60 via-blue-900/40 to-slate-900/60"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
            
            <!-- Animated Content -->
            <div class="relative z-10 text-center text-white px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <div class="transform transition-all duration-1000 ease-out" :class="isLoaded ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <h1 class="text-6xl sm:text-7xl lg:text-8xl font-bold mb-8 leading-tight">
                        <span class="block animate-slide-up" style="animation-delay: 0.2s">Türkiye'nin En Güzel</span>
                        <span class="block bg-gradient-to-r from-blue-400 via-cyan-400 to-blue-500 bg-clip-text text-transparent animate-slide-up" style="animation-delay: 0.4s">
                            Villa Koleksiyonu
                        </span>
                    </h1>
                    
                    <p class="text-xl sm:text-2xl mb-12 text-gray-200 max-w-4xl mx-auto leading-relaxed animate-slide-up" style="animation-delay: 0.6s">
                        Lüks villalarımızda unutulmaz tatil deneyimleri yaşayın. Premium konfordan ödün vermeden, hayalinizdeki tatili gerçekleştirin.
                    </p>
                </div>
                
                <!-- Premium Search Form -->
                <div class="transform transition-all duration-1000 ease-out animate-slide-up" style="animation-delay: 0.8s">
                    <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border border-white/30 max-w-6xl mx-auto hover:bg-white/20 transition-all duration-300">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Location with Icon -->
                            <div class="md:col-span-1 group">
                                <label class="block text-sm font-semibold text-white mb-3 flex items-center">
                                    <MapPinIcon class="w-4 h-4 mr-2 text-blue-300" />
                                    Konum
                                </label>
                                <div class="relative">
                                    <select 
                                        v-model="searchForm.location" 
                                        class="w-full px-4 py-4 rounded-xl bg-white/25 border border-white/40 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent focus:bg-white/30 transition-all duration-300 group-hover:bg-white/30"
                                    >
                                        <option value="" class="text-gray-800">Konum Seçin</option>
                                        <option v-for="location in locations" :key="location" :value="location" class="text-gray-800">
                                            {{ location }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Check-in with Icon -->
                            <div class="md:col-span-1 group">
                                <label class="block text-sm font-semibold text-white mb-3 flex items-center">
                                    <CalendarIcon class="w-4 h-4 mr-2 text-green-300" />
                                    Giriş Tarihi
                                </label>
                                <input 
                                    type="date" 
                                    v-model="searchForm.check_in"
                                    class="w-full px-4 py-4 rounded-xl bg-white/25 border border-white/40 text-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent focus:bg-white/30 transition-all duration-300 group-hover:bg-white/30"
                                >
                            </div>
                            
                            <!-- Check-out with Icon -->
                            <div class="md:col-span-1 group">
                                <label class="block text-sm font-semibold text-white mb-3 flex items-center">
                                    <CalendarIcon class="w-4 h-4 mr-2 text-purple-300" />
                                    Çıkış Tarihi
                                </label>
                                <input 
                                    type="date" 
                                    v-model="searchForm.check_out"
                                    class="w-full px-4 py-4 rounded-xl bg-white/25 border border-white/40 text-white focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent focus:bg-white/30 transition-all duration-300 group-hover:bg-white/30"
                                >
                            </div>
                            
                            <!-- Guests with Icon -->
                            <div class="md:col-span-1 group">
                                <label class="block text-sm font-semibold text-white mb-3 flex items-center">
                                    <UserGroupIcon class="w-4 h-4 mr-2 text-orange-300" />
                                    Misafir
                                </label>
                                <select 
                                    v-model="searchForm.guests" 
                                    class="w-full px-4 py-4 rounded-xl bg-white/25 border border-white/40 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent focus:bg-white/30 transition-all duration-300 group-hover:bg-white/30"
                                >
                                    <option v-for="n in 16" :key="n" :value="n" class="text-gray-800">{{ n }} Kişi</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Premium Search Button -->
                        <div class="mt-8 text-center">
                            <button 
                                @click="searchVillas"
                                class="group bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 hover:from-blue-700 hover:via-blue-800 hover:to-blue-900 text-white px-16 py-5 rounded-2xl font-bold text-xl transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-blue-500/25 relative overflow-hidden"
                            >
                                <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                                <MagnifyingGlassIcon class="w-6 h-6 inline mr-3 group-hover:scale-110 transition-transform duration-300" />
                                <span class="relative z-10">Premium Villa Ara</span>
                            </button>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="mt-6 flex justify-center space-x-8 text-white/80 text-sm">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-blue-400 rounded-full mr-2 animate-pulse"></div>
                                <span>500+ Lüks Villa</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse" style="animation-delay: 0.5s"></div>
                                <span>Anında Rezervasyon</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-purple-400 rounded-full mr-2 animate-pulse" style="animation-delay: 1s"></div>
                                <span>7/24 Destek</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Premium Villa Categories -->
        <section class="py-24 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(59,130,246,0.3) 1px, transparent 0); background-size: 50px 50px;"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center mb-20">
                    <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold mb-6">
                        <SparklesIcon class="w-4 h-4 mr-2" />
                        Premium Koleksiyonlar
                    </div>
                    <h2 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Villa <span class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">Kategorileri</span>
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        Her biri özenle seçilmiş, size en uygun lüks villa deneyimini keşfedin
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div
                        v-for="(category, index) in categories"
                        :key="category.id"
                        class="group cursor-pointer transform transition-all duration-700 hover:scale-105"
                        :class="{
                            'ring-4 ring-blue-500 ring-opacity-50 scale-105': selectedCategory === category.id
                        }"
                        :style="{ animationDelay: `${index * 0.1}s` }"
                        @click="selectCategory(category.id)"
                    >
                        <div class="relative overflow-hidden rounded-3xl shadow-xl group-hover:shadow-2xl transition-all duration-500 bg-white"
                             :class="{
                                 'shadow-blue-500/25 shadow-2xl': selectedCategory === category.id
                             }">
                            <!-- Category Image -->
                            <div class="relative h-80 overflow-hidden">
                                <img
                                    :src="`https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=${category.image}&image_size=landscape_4_3`"
                                    :alt="category.name"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                
                                <!-- Hover Overlay -->
                                <div class="absolute inset-0 bg-blue-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            </div>
                            
                            <!-- Content -->
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-2xl font-bold text-white group-hover:text-blue-200 transition-colors duration-300"
                                        :class="{
                                            'text-blue-200': selectedCategory === category.id
                                        }">
                                        {{ category.name }}
                                    </h3>
                                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 group-hover:bg-blue-500/30 transition-all duration-300"
                                         :class="{
                                             'bg-blue-500/40 ring-2 ring-white/50': selectedCategory === category.id
                                         }">
                                        <BuildingOfficeIcon class="w-5 h-5 text-white" />
                                    </div>
                                </div>
                                <p class="text-white/90 text-sm mb-2"
                                   :class="{
                                       'text-blue-100 font-semibold': selectedCategory === category.id
                                   }">{{ category.count }} Premium Villa</p>
                                <p class="text-white/80 text-sm"
                                   :class="{
                                       'text-blue-100': selectedCategory === category.id
                                   }">{{ category.description }}</p>
                                
                                <!-- Active Category Indicator -->
                                <div v-if="selectedCategory === category.id" 
                                     class="mt-3 flex items-center text-blue-200 text-sm font-semibold">
                                    <CheckCircleIcon class="w-4 h-4 mr-2" />
                                    Seçili Kategori
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Premium Featured Villas -->
        <section class="py-24 bg-gradient-to-br from-white via-gray-50 to-white relative overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl animate-blob"></div>
                <div class="absolute top-40 right-10 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-2000"></div>
                <div class="absolute bottom-20 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-4000"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center mb-20">
                    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800 rounded-full text-sm font-semibold mb-6">
                        <StarIcon class="w-4 h-4 mr-2 text-yellow-500" />
                        Öne Çıkan Premium Villalar
                    </div>
                    <h2 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Lüks Villa <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Koleksiyonu</span>
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        Her biri özenle seçilmiş, eşsiz konfor ve lüks sunan premium villalarımızı keşfedin
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    <div
                        v-for="(villa, index) in filteredVillas"
                        :key="villa.id"
                        class="group bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-700 transform hover:-translate-y-2"
                        :style="{ animationDelay: `${index * 0.1}s` }"
                    >
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img
                                :src="villa.images?.[0]?.image_url || `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20modern%20villa%20pool%20sunset%20${villa.id}&image_size=landscape_4_3`"
                                :alt="villa.title || 'Premium Villa'"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            >
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <!-- Wishlist Button -->
                            <div class="absolute top-4 right-4">
                                <button 
                                    @click="toggleWishlist(villa.id)"
                                    class="p-3 bg-white/90 backdrop-blur-sm rounded-full hover:bg-white transition-all duration-300 transform hover:scale-110 shadow-lg"
                                    :class="wishlist.has(villa.id) ? 'text-red-500' : 'text-gray-600'"
                                >
                                    <component :is="wishlist.has(villa.id) ? HeartSolidIcon : HeartIcon" class="h-5 w-5" />
                                </button>
                            </div>
                            
                            <!-- Premium Badge -->
                            <div class="absolute top-4 left-4">
                                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center">
                                    <SparklesIcon class="w-3 h-3 mr-1" />
                                    PREMIUM
                                </div>
                            </div>
                            
                            <!-- Quick View Button -->
                            <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-4 group-hover:translate-y-0">
                                <Link
                                    :href="`/villas/${villa.id}`"
                                    class="w-full bg-white/95 backdrop-blur-sm text-gray-900 py-3 px-6 rounded-xl font-semibold text-center block hover:bg-white transition-all duration-300 shadow-lg"
                                >
                                    Hızlı Görüntüle
                                </Link>
                            </div>
                        </div>
                        
                        <div class="p-8">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-2xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300">{{ villa.title || 'Lüks Premium Villa' }}</h3>
                                <div class="flex items-center space-x-1 bg-yellow-50 px-2 py-1 rounded-lg">
                                    <StarIcon class="h-4 w-4 text-yellow-500 fill-current" />
                                    <span class="text-sm font-bold text-yellow-700">{{ villa.rating || '4.9' }}</span>
                                </div>
                            </div>
                            
                            <p class="text-gray-600 mb-4 flex items-center">
                                <MapPinIcon class="h-4 w-4 mr-2 text-blue-500" />
                                {{ villa.location || villa.city || 'Türkiye, Premium Lokasyon' }}
                            </p>
                            
                            <!-- Villa Features -->
                            <div class="flex items-center justify-between mb-6 text-sm">
                                <div class="flex items-center space-x-4 text-gray-600">
                                    <span class="flex items-center bg-gray-100 px-3 py-1 rounded-lg">
                                        <UserGroupIcon class="h-4 w-4 mr-1 text-blue-500" />
                                        {{ villa.max_guests || '6' }} kişi
                                    </span>
                                    <span class="flex items-center bg-gray-100 px-3 py-1 rounded-lg">
                                        <HomeModernIcon class="h-4 w-4 mr-1 text-green-500" />
                                        {{ villa.bedrooms || '3' }} oda
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Amenities Preview -->
                            <div class="flex items-center space-x-2 mb-6">
                                <div class="flex items-center text-xs text-gray-600 bg-blue-50 px-2 py-1 rounded-full">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-1"></div>
                                    Özel Havuz
                                </div>
                                <div class="flex items-center text-xs text-gray-600 bg-green-50 px-2 py-1 rounded-full">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                                    Deniz Manzarası
                                </div>
                                <div class="flex items-center text-xs text-gray-600 bg-purple-50 px-2 py-1 rounded-full">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mr-1"></div>
                                    WiFi
                                </div>
                            </div>
                            
                            <!-- Price and CTA -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex items-baseline space-x-1">
                                        <span class="text-3xl font-bold text-gray-900">{{ formatPrice(villa.price_per_night || 2500) }}</span>
                                        <span class="text-gray-600 text-sm">/gece</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Vergiler dahil</p>
                                </div>
                                <Link
                                    :href="`/villas/${villa.id}`"
                                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-blue-500/25"
                                >
                                    Rezervasyon
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-16">
                    <Link
                        href="/villas"
                        class="group inline-flex items-center space-x-3 bg-gradient-to-r from-gray-900 to-gray-800 hover:from-gray-800 hover:to-gray-700 text-white px-12 py-4 rounded-2xl font-bold text-lg transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-gray-500/25"
                    >
                        <span>Tüm Premium Villaları Keşfet</span>
                        <ArrowRightIcon class="h-6 w-6 group-hover:translate-x-1 transition-transform duration-300" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- Premium Services -->
        <section class="py-24 bg-gradient-to-br from-gray-50 via-white to-gray-50 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(59,130,246,0.4) 1px, transparent 0); background-size: 40px 40px;"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center mb-20">
                    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-100 to-blue-100 text-green-800 rounded-full text-sm font-semibold mb-6">
                        <ShieldCheckIcon class="w-4 h-4 mr-2" />
                        Premium Hizmetlerimiz
                    </div>
                    <h2 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Neden <span class="bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">Bizi Seçmelisiniz?</span>
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        Size en üst düzey hizmet ve unutulmaz tatil deneyimi sunmak için buradayız
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div
                        v-for="(service, index) in premiumServices"
                        :key="service.title"
                        class="group text-center bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-700 transform hover:-translate-y-2 border border-gray-100"
                        :style="{ animationDelay: `${index * 0.1}s` }"
                    >
                        <div class="relative mb-8">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-500">
                                <component :is="service.icon" class="h-10 w-10 text-blue-600 group-hover:text-purple-600 transition-colors duration-500" />
                            </div>
                            <!-- Floating Animation -->
                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-ping"></div>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-blue-600 transition-colors duration-300">{{ service.title }}</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">{{ service.description }}</p>
                        
                        <!-- Feature List -->
                        <div class="space-y-2">
                            <div 
                                v-for="feature in service.features"
                                :key="feature"
                                class="flex items-center justify-center text-sm text-gray-600"
                            >
                                <CheckCircleIcon class="w-4 h-4 text-green-500 mr-2" />
                                {{ feature }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Trust Indicators -->
                <div class="mt-20 text-center">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-3">
                                <ShieldCheckIcon class="w-8 h-8 text-green-600" />
                            </div>
                            <p class="text-sm font-semibold text-gray-900">SSL Güvenlik</p>
                            <p class="text-xs text-gray-600">256-bit Şifreleme</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-3">
                                <ClockIcon class="w-8 h-8 text-blue-600" />
                            </div>
                            <p class="text-sm font-semibold text-gray-900">7/24 Destek</p>
                            <p class="text-xs text-gray-600">Anında Yardım</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-3">
                                <TrophyIcon class="w-8 h-8 text-purple-600" />
                            </div>
                            <p class="text-sm font-semibold text-gray-900">Kalite Garantisi</p>
                            <p class="text-xs text-gray-600">%100 Memnuniyet</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-3">
                                <SparklesIcon class="w-8 h-8 text-yellow-600" />
                            </div>
                            <p class="text-sm font-semibold text-gray-900">Premium Deneyim</p>
                            <p class="text-xs text-gray-600">Lüks Hizmet</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Animated Statistics -->
        <section id="stats-section" class="py-24 bg-gradient-to-br from-blue-600 via-blue-700 to-purple-800 relative overflow-hidden">
            <!-- Background Effects -->
            <div class="absolute inset-0">
                <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-600/50 to-purple-600/50"></div>
                <div class="absolute top-20 left-20 w-96 h-96 bg-white/5 rounded-full mix-blend-overlay filter blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-cyan-400/10 rounded-full mix-blend-overlay filter blur-3xl animate-pulse" style="animation-delay: 1s"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-semibold mb-6">
                        <TrophyIcon class="w-4 h-4 mr-2" />
                        Başarılarımız
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                        Rakamlarla <span class="text-cyan-300">Güvenilirlik</span>
                    </h2>
                    <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                        Binlerce mutlu misafirimizin tercihi olmaktan gurur duyuyoruz
                    </p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div
                        v-for="(stat, index) in stats"
                        :key="stat.label"
                        class="text-center text-white group"
                        :style="{ animationDelay: `${index * 0.2}s` }"
                    >
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 hover:bg-white/20 transition-all duration-500 transform hover:scale-105 border border-white/20">
                            <div class="text-5xl md:text-6xl font-bold mb-4 text-cyan-300 group-hover:text-white transition-colors duration-500">
                                {{ isStatsVisible ? (animatedStats[stat.label.toLowerCase().replace(/[^a-z]/g, '')] || stat.number) : '0' }}
                                <span v-if="stat.label.includes('+')">+</span>
                            </div>
                            <div class="text-blue-100 text-lg font-semibold group-hover:text-white transition-colors duration-300">{{ stat.label }}</div>
                            
                            <!-- Progress Bar -->
                            <div class="mt-4 w-full bg-white/20 rounded-full h-2 overflow-hidden">
                                <div 
                                    class="h-full bg-gradient-to-r from-cyan-400 to-blue-400 rounded-full transition-all duration-2000 ease-out"
                                    :style="{ width: isStatsVisible ? '100%' : '0%', transitionDelay: `${index * 0.2}s` }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Achievement Badges -->
                <div class="mt-16 flex flex-wrap justify-center gap-6">
                    <div class="flex items-center bg-white/15 backdrop-blur-sm rounded-full px-6 py-3 text-white">
                        <StarIcon class="w-5 h-5 text-yellow-400 mr-2" />
                        <span class="text-sm font-semibold">Türkiye'nin En İyi Villa Platformu</span>
                    </div>
                    <div class="flex items-center bg-white/15 backdrop-blur-sm rounded-full px-6 py-3 text-white">
                        <ShieldCheckIcon class="w-5 h-5 text-green-400 mr-2" />
                        <span class="text-sm font-semibold">Güvenli Ödeme Garantisi</span>
                    </div>
                    <div class="flex items-center bg-white/15 backdrop-blur-sm rounded-full px-6 py-3 text-white">
                        <TrophyIcon class="w-5 h-5 text-purple-400 mr-2" />
                        <span class="text-sm font-semibold">Mükemmellik Ödülü 2024</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Premium Testimonials Carousel -->
        <section class="py-24 bg-gradient-to-br from-white via-gray-50 to-white relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(139,69,19,0.3) 1px, transparent 0); background-size: 60px 60px;"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center mb-20">
                    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-100 to-yellow-100 text-orange-800 rounded-full text-sm font-semibold mb-6">
                        <UsersIcon class="w-4 h-4 mr-2" />
                        Misafir Deneyimleri
                    </div>
                    <h2 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Mutlu <span class="bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">Misafirlerimiz</span>
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        Binlerce misafirimizin unutulmaz tatil deneyimlerini keşfedin
                    </p>
                </div>
                
                <!-- Testimonials Carousel -->
                <div class="relative max-w-6xl mx-auto">
                    <!-- Main Testimonial Display -->
                    <div class="bg-white rounded-3xl shadow-2xl p-12 border border-gray-100 relative overflow-hidden">
                        <!-- Background Decoration -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-full -translate-y-16 translate-x-16 opacity-50"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-blue-100 to-purple-100 rounded-full translate-y-12 -translate-x-12 opacity-50"></div>
                        
                        <div class="relative z-10">
                            <!-- Quote Icon -->
                            <div class="flex justify-center mb-8">
                                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Testimonial Content -->
                            <div class="text-center">
                                <p class="text-2xl md:text-3xl text-gray-800 font-medium leading-relaxed mb-8 italic">
                                    "{{ testimonials[currentTestimonial]?.comment }}"
                                </p>
                                
                                <!-- Rating Stars -->
                                <div class="flex justify-center mb-8">
                                    <div class="flex items-center space-x-1">
                                        <StarIcon
                                            v-for="i in 5"
                                            :key="i"
                                            class="h-6 w-6 transition-colors duration-300"
                                            :class="i <= (testimonials[currentTestimonial]?.rating || 5) ? 'text-yellow-400 fill-current' : 'text-gray-300'"
                                        />
                                    </div>
                                </div>
                                
                                <!-- Customer Info -->
                                <div class="flex items-center justify-center space-x-4">
                                    <img
                                        :src="testimonials[currentTestimonial]?.avatar"
                                        :alt="testimonials[currentTestimonial]?.name"
                                        class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-lg"
                                    >
                                    <div class="text-left">
                                        <h4 class="text-xl font-bold text-gray-900">{{ testimonials[currentTestimonial]?.name }}</h4>
                                        <p class="text-gray-600 flex items-center">
                                            <MapPinIcon class="w-4 h-4 mr-1" />
                                            {{ testimonials[currentTestimonial]?.location }}
                                        </p>
                                        <p class="text-sm text-gray-500">{{ testimonials[currentTestimonial]?.date }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div class="flex justify-center items-center mt-12 space-x-6">
                        <button
                            @click="prevTestimonial"
                            class="group bg-white hover:bg-gray-50 border-2 border-gray-200 hover:border-orange-300 rounded-full p-4 transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-xl"
                        >
                            <ChevronLeftIcon class="w-6 h-6 text-gray-600 group-hover:text-orange-600 transition-colors duration-300" />
                        </button>
                        
                        <!-- Dots Indicator -->
                        <div class="flex space-x-3">
                            <button
                                v-for="(testimonial, index) in testimonials"
                                :key="testimonial.id"
                                @click="currentTestimonial = index"
                                class="w-3 h-3 rounded-full transition-all duration-300"
                                :class="index === currentTestimonial ? 'bg-orange-500 scale-125' : 'bg-gray-300 hover:bg-gray-400'"
                            ></button>
                        </div>
                        
                        <button
                            @click="nextTestimonial"
                            class="group bg-white hover:bg-gray-50 border-2 border-gray-200 hover:border-orange-300 rounded-full p-4 transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-xl"
                        >
                            <ChevronRightIcon class="w-6 h-6 text-gray-600 group-hover:text-orange-600 transition-colors duration-300" />
                        </button>
                    </div>
                    
                    <!-- Testimonial Thumbnails -->
                    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            v-for="(testimonial, index) in testimonials"
                            :key="testimonial.id"
                            @click="currentTestimonial = index"
                            class="cursor-pointer bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-2"
                            :class="index === currentTestimonial ? 'border-orange-300 bg-orange-50' : 'border-gray-100 hover:border-gray-200'"
                        >
                            <div class="flex items-center mb-3">
                                <img
                                    :src="testimonial.avatar"
                                    :alt="testimonial.name"
                                    class="w-10 h-10 rounded-full object-cover mr-3"
                                >
                                <div>
                                    <h5 class="font-semibold text-gray-900 text-sm">{{ testimonial.name }}</h5>
                                    <p class="text-gray-600 text-xs">{{ testimonial.location }}</p>
                                </div>
                            </div>
                            <div class="flex items-center mb-2">
                                <StarIcon
                                    v-for="i in testimonial.rating"
                                    :key="i"
                                    class="h-3 w-3 text-yellow-400 fill-current"
                                />
                            </div>
                            <p class="text-gray-700 text-sm line-clamp-2">"{{ testimonial.comment }}"</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Premium Newsletter & CTA -->
        <section class="py-24 bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 relative overflow-hidden">
            <!-- Background Effects -->
            <div class="absolute inset-0">
                <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-900/50 to-purple-900/50"></div>
                <div class="absolute top-20 left-20 w-96 h-96 bg-blue-500/10 rounded-full mix-blend-overlay filter blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-purple-500/10 rounded-full mix-blend-overlay filter blur-3xl animate-pulse" style="animation-delay: 1s"></div>
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0); background-size: 60px 60px;"></div>
                </div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
                <!-- Main CTA Content -->
                <div class="mb-16">
                    <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-semibold mb-8">
                        <SparklesIcon class="w-4 h-4 mr-2" />
                        Özel Fırsatlar
                    </div>
                    
                    <h2 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
                        Hayalinizdeki Tatili <br>
                        <span class="bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">Şimdi Rezerve Edin</span>
                    </h2>
                    
                    <p class="text-xl text-blue-100 mb-12 max-w-3xl mx-auto leading-relaxed">
                        Premium villa koleksiyonumuzdan size en uygun olanı seçin ve unutulmaz bir tatil deneyimi yaşayın. 
                        Özel indirimler ve erken rezervasyon avantajları için e-bültenimize abone olun.
                    </p>
                </div>
                
                <!-- Newsletter Signup -->
                <div class="max-w-2xl mx-auto mb-16">
                    <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-8 border border-white/30">
                        <h3 class="text-2xl font-bold text-white mb-4">Özel Fırsatlardan İlk Siz Haberdar Olun</h3>
                        <p class="text-blue-100 mb-6">%20'ye varan indirimler, erken rezervasyon fırsatları ve özel kampanyalar</p>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input
                                type="email"
                                placeholder="E-posta adresinizi girin"
                                class="flex-1 px-6 py-4 rounded-2xl border-0 bg-white/25 backdrop-blur-sm text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:bg-white/30 transition-all duration-300"
                            >
                            <button class="group bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white px-8 py-4 rounded-2xl font-bold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-cyan-500/25 relative overflow-hidden">
                                <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                                <span class="relative z-10">Abone Ol</span>
                            </button>
                        </div>
                        
                        <!-- Benefits -->
                        <div class="mt-6 flex flex-wrap justify-center gap-4 text-sm text-blue-100">
                            <div class="flex items-center">
                                <CheckCircleIcon class="w-4 h-4 text-green-400 mr-2" />
                                Özel İndirimler
                            </div>
                            <div class="flex items-center">
                                <CheckCircleIcon class="w-4 h-4 text-green-400 mr-2" />
                                Erken Rezervasyon
                            </div>
                            <div class="flex items-center">
                                <CheckCircleIcon class="w-4 h-4 text-green-400 mr-2" />
                                VIP Kampanyalar
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                    <Link
                        href="/villas"
                        class="group bg-gradient-to-r from-white to-gray-100 hover:from-gray-100 hover:to-white text-gray-900 px-12 py-5 rounded-2xl font-bold text-xl transition-all duration-500 transform hover:scale-105 shadow-2xl hover:shadow-white/25 relative overflow-hidden"
                    >
                        <span class="absolute inset-0 bg-gradient-to-r from-transparent via-blue-500/10 to-transparent transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                        <span class="relative z-10 flex items-center">
                            <MagnifyingGlassIcon class="w-6 h-6 mr-3 group-hover:scale-110 transition-transform duration-300" />
                            Villa Aramaya Başla
                        </span>
                    </Link>
                    
                    <Link
                        href="/contact"
                        class="group border-2 border-white/50 hover:border-white text-white hover:bg-white/10 px-12 py-5 rounded-2xl font-bold text-xl transition-all duration-300 transform hover:scale-105 backdrop-blur-sm"
                    >
                        <span class="flex items-center">
                            <UsersIcon class="w-6 h-6 mr-3 group-hover:scale-110 transition-transform duration-300" />
                            Uzman Desteği Al
                        </span>
                    </Link>
                </div>
                
                <!-- Trust Indicators -->
                <div class="mt-16 flex flex-wrap justify-center items-center gap-8 text-white/80">
                    <div class="flex items-center">
                        <ShieldCheckIcon class="w-5 h-5 text-green-400 mr-2" />
                        <span class="text-sm">Güvenli Ödeme</span>
                    </div>
                    <div class="flex items-center">
                        <ClockIcon class="w-5 h-5 text-blue-400 mr-2" />
                        <span class="text-sm">Anında Onay</span>
                    </div>
                    <div class="flex items-center">
                        <TrophyIcon class="w-5 h-5 text-yellow-400 mr-2" />
                        <span class="text-sm">Mükemmellik Garantisi</span>
                    </div>
                    <div class="flex items-center">
                        <StarIcon class="w-5 h-5 text-purple-400 mr-2" />
                        <span class="text-sm">4.9/5 Müşteri Memnuniyeti</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
/* Premium Animations */
@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}

@keyframes slide-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}

.animate-slide-up {
  animation: slide-up 0.8s ease-out forwards;
}

.animate-fade-in {
  animation: fade-in 1s ease-out forwards;
}

/* Line clamp utility */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Custom scrollbar for better UX */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Smooth transitions for all interactive elements */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style>
