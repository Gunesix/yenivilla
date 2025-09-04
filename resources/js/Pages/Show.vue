<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';
import { 
    MapPinIcon,     
    StarIcon,   
    HeartIcon,
    ShareIcon,
    UserGroupIcon,
    HomeIcon,
    CheckIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    WifiIcon,
    TvIcon,
    TruckIcon,
    RectangleStackIcon,
    CalendarDaysIcon,
    UsersIcon,
    XMarkIcon,
    PhotoIcon
} from '@heroicons/vue/24/outline';
import { StarIcon as StarSolid, HeartIcon as HeartSolid } from '@heroicons/vue/24/solid';

const props = defineProps({
    villa: {
        type: Object,
        required: true
    },
    reviews: {
        type: Array,
        default: () => []
    }
});

// Sample data for demonstration
const sampleAmenities = [
    { icon: 'wifi', name: 'WiFi', description: 'Ücretsiz kablosuz internet' },
    { icon: 'tv', name: 'TV', description: '55" Smart TV' },
    { icon: 'car', name: 'Otopark', description: 'Ücretsiz otopark' },
    { icon: 'pool', name: 'Havuz', description: 'Özel havuz' },
    { icon: 'kitchen', name: 'Mutfak', description: 'Tam donanımlı mutfak' },
    { icon: 'ac', name: 'Klima', description: 'Merkezi klima sistemi' },
    { icon: 'gym', name: 'Fitness', description: 'Özel spor salonu' },
    { icon: 'garden', name: 'Bahçe', description: 'Özel bahçe alanı' }
];

const sampleReviews = [
    { id: 1, user: 'Ahmet K.', rating: 5, comment: 'Harika bir villa! Temiz, konforlu ve manzarası muhteşem.', date: '2024-01-15' },
    { id: 2, user: 'Elif S.', rating: 5, comment: 'Ailecek çok keyifli vakit geçirdik. Kesinlikle tavsiye ederim.', date: '2024-01-10' },
    { id: 3, user: 'Mehmet Y.', rating: 4, comment: 'Güzel bir konum, denize çok yakın. Ev sahibi çok yardımsever.', date: '2024-01-05' }
];

// Reactive data
const currentImageIndex = ref(0);
const showAllAmenities = ref(false);
const showImageGallery = ref(false);
const isLiked = ref(false);
const guests = ref(1);
const selectedImage = ref('');
const showImageModal = ref(false);
const availabilityMessage = ref('');
const isCheckingAvailability = ref(false);

const bookingForm = ref({
    check_in: '',
    check_out: '',
    guests: 1,
    total_nights: 0,
    total_amount: 0
});

// Computed properties
const images = computed(() => {
    const allImages = [];
    if (props.villa.images && props.villa.images.length > 0) {
        allImages.push(...props.villa.images.map(img => img.image_url));
    }
    // Fallback images if no images available
    if (allImages.length === 0) {
        allImages.push(
            `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20${props.villa.id}&image_size=landscape_16_9`,
            `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20interior%20${props.villa.id}&image_size=landscape_4_3`,
            `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20room%202&image_size=square`,
            `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20room%203&image_size=square`,
            `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20room%204&image_size=portrait_4_3`
        );
    }
    return allImages;
});

const displayedAmenities = computed(() => {
    const amenities = props.villa.amenities || [];
    return showAllAmenities.value ? amenities : amenities.slice(0, 8);
});

const averageRating = computed(() => {
    if (!props.reviews || props.reviews.length === 0) return 4.95;
    const sum = props.reviews.reduce((acc, review) => acc + review.rating, 0);
    return (sum / props.reviews.length).toFixed(2);
});

const totalReviews = computed(() => {
    return props.reviews ? props.reviews.length : 142;
});

// Methods
const nextImage = () => {
    currentImageIndex.value = (currentImageIndex.value + 1) % images.value.length;
};

const prevImage = () => {
    currentImageIndex.value = currentImageIndex.value === 0 
        ? images.value.length - 1 
        : currentImageIndex.value - 1;
};

const selectImage = (imageUrl) => {
    selectedImage.value = imageUrl;
    showImageModal.value = true;
};

const openImageModal = () => {
    selectedImage.value = images.value[0];
    showImageModal.value = true;
};

const closeImageModal = () => {
    showImageModal.value = false;
    selectedImage.value = '';
};

const toggleLike = () => {
    isLiked.value = !isLiked.value;
};

const incrementGuests = () => {
    guests.value = Math.min(props.villa.max_guests || 16, guests.value + 1);
    bookingForm.value.guests = guests.value;
};

const decrementGuests = () => {
    guests.value = Math.max(1, guests.value - 1);
    bookingForm.value.guests = guests.value;
};

const calculateTotal = () => {
    if (!bookingForm.value.check_in || !bookingForm.value.check_out) return;
    
    const checkIn = new Date(bookingForm.value.check_in);
    const checkOut = new Date(bookingForm.value.check_out);
    const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
    
    bookingForm.value.total_nights = nights;
    bookingForm.value.total_amount = nights * (props.villa.price_per_night || 0);
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('tr-TR').format(price);
};

const makeReservation = () => {
    // Handle reservation logic
    console.log('Reservation data:', bookingForm.value);
};

const getAmenityIcon = (amenityType) => {
    const iconMap = {
        'wifi': WifiIcon,
        'tv': TvIcon,
        'parking': TruckIcon,
        'pool': RectangleStackIcon,
        'kitchen': HomeIcon
    };
    return iconMap[amenityType] || CheckIcon;
};

const checkAvailability = async () => {
    if (!bookingForm.value.check_in || !bookingForm.value.check_out) {
        availabilityMessage.value = 'Lütfen giriş ve çıkış tarihlerini seçin.';
        return;
    }

    isCheckingAvailability.value = true;
    availabilityMessage.value = '';

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        const response = await fetch('/bookings/check-availability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                villa_id: props.villa.id,
                check_in: bookingForm.value.check_in,
                check_out: bookingForm.value.check_out,
                guests: bookingForm.value.guests
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned non-JSON response');
        }

        const data = await response.json();
        
        if (data.available) {
            availabilityMessage.value = 'Villa seçilen tarihlerde müsait!';
        } else {
            availabilityMessage.value = 'Villa seçilen tarihlerde müsait değil.';
        }
    } catch (error) {
        console.error('Availability check failed:', error);
        availabilityMessage.value = 'Müsaitlik kontrolü sırasında bir hata oluştu.';
    } finally {
        isCheckingAvailability.value = false;
    }
};

// Watch for date changes
watch([() => bookingForm.value.check_in, () => bookingForm.value.check_out], () => {
    calculateTotal();
    availabilityMessage.value = '';
});

// Lifecycle
onMounted(() => {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    bookingForm.value.check_in = today.toISOString().split('T')[0];
    bookingForm.value.check_out = tomorrow.toISOString().split('T')[0];
    bookingForm.value.guests = guests.value;
    
    calculateTotal();
});
</script>

<template>
    <div class="min-h-screen bg-white">
        <Head :title="villa.title || 'Villa Detayı'" />
        
        <!-- Modern Header -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <Link :href="route('home')" class="text-2xl font-bold text-rose-500">
                        VillaCation
                    </Link>
                    
                    <!-- Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <Link :href="route('home')" class="text-gray-700 hover:text-gray-900 font-medium transition-colors">
                            Ana Sayfa
                        </Link>
                        <Link :href="route('villas.index')" class="text-gray-700 hover:text-gray-900 font-medium transition-colors">
                            Villalar
                        </Link>
                        <a href="#" class="text-gray-700 hover:text-gray-900 font-medium transition-colors">
                            Deneyimler
                        </a>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <button class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                            <HeartIcon class="h-5 w-5 text-gray-600" />
                        </button>
                        <button class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                            <ShareIcon class="h-5 w-5 text-gray-600" />
                        </button>
                        <Link :href="route('login')" class="bg-rose-500 text-white px-4 py-2 rounded-full hover:bg-rose-600 transition-colors font-medium">
                            Giriş Yap
                        </Link>
                    </div>
                </div>
            </nav>
        </header>
        
        <!-- Breadcrumb -->
        <div class="pt-20 pb-3">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex justify-center" aria-label="Breadcrumb">
                    <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-xl px-4 py-2 shadow-lg">
                        <ol class="flex items-center space-x-3">
                            <li>
                                <Link :href="route('home')" class="text-gray-700 hover:text-blue-600 transition font-medium">
                                    Ana Sayfa
                                </Link>
                            </li>
                            <li>
                                <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                            </li>
                            <li>
                                <Link :href="route('villas.index')" class="text-gray-700 hover:text-blue-600 transition font-medium">
                                    Villalar
                                </Link>
                            </li>
                            <li>
                                <ChevronRightIcon class="h-4 w-4 text-gray-400" />
                            </li>
                            <li class="text-blue-600 font-semibold">
                                {{ villa.title }}
                            </li>
                        </ol>
                    </div>
                </nav>
            </div>
        </div>
        
        <!-- Hero Image -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="w-full h-64 md:h-80 lg:h-96 rounded-xl overflow-hidden shadow-lg">
                <img 
                    :src="villa.images?.[0]?.image_url || `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20${villa.id}&image_size=landscape_16_9`"
                    :alt="villa.title"
                    class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform duration-300"
                    @click="openImageModal"
                />
            </div>
        </div>
        
        <!-- Villa Header -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="bg-white border border-gray-200 rounded-lg p-2 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-2">
                    <div class="flex-1">
                        <h1 class="text-lg lg:text-xl font-bold text-gray-900 mb-1">{{ villa.title }}</h1>
                        <div class="flex items-center gap-2 text-gray-600 text-xs">
                            <div class="flex items-center gap-1">
                                <StarIcon class="h-4 w-4 text-yellow-400 fill-current" />
                                <span class="font-medium">{{ villa.rating || '4.8' }}</span>
                                <span>({{ villa.reviews_count || '24' }} değerlendirme)</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <MapPinIcon class="h-4 w-4 text-gray-400" />
                                <span>{{ villa.location }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-2">
                        <div class="text-right">
                            <div class="text-lg lg:text-xl font-bold text-gray-900">
                                ₺{{ formatPrice(villa.price_per_night) }}
                                <span class="text-xs text-gray-600 font-normal">/gece</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Villa Details -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Villa Özellikleri -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Villa Özellikleri</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="flex items-center bg-blue-50 rounded-lg p-3">
                                <WifiIcon class="h-5 w-5 text-blue-600 mr-2 flex-shrink-0" />
                                <span class="text-gray-800 font-medium text-sm">WiFi</span>
                            </div>
                            <div class="flex items-center bg-green-50 rounded-lg p-3">
                                <CheckIcon class="h-5 w-5 text-green-600 mr-2 flex-shrink-0" />
                                <span class="text-gray-800 font-medium text-sm">Klima</span>
                            </div>
                            <div class="flex items-center bg-purple-50 rounded-lg p-3">
                                <SwatchBookIcon class="h-5 w-5 text-purple-600 mr-2 flex-shrink-0" />
                                <span class="text-gray-800 font-medium text-sm">Özel Havuz</span>
                            </div>
                            <div class="flex items-center bg-orange-50 rounded-lg p-3">
                                <CarIcon class="h-5 w-5 text-orange-600 mr-2 flex-shrink-0" />
                                <span class="text-gray-800 font-medium text-sm">Ücretsiz Otopark</span>
                            </div>
                            <div class="flex items-center bg-red-50 rounded-lg p-3">
                                <HomeIcon class="h-5 w-5 text-red-600 mr-2 flex-shrink-0" />
                                <span class="text-gray-800 font-medium text-sm">Tam Donanımlı Mutfak</span>
                            </div>
                            <div class="flex items-center bg-teal-50 rounded-lg p-3">
                                <MapPinIcon class="h-5 w-5 text-teal-600 mr-2 flex-shrink-0" />
                                <span class="text-gray-800 font-medium text-sm">Deniz Manzarası</span>
                            </div>
                            <div class="flex items-center bg-indigo-50 rounded-lg p-3">
                                <CheckIcon class="h-5 w-5 text-indigo-600 mr-2 flex-shrink-0" />
                                <span class="text-gray-800 font-medium text-sm">24/7 Güvenlik</span>
                            </div>
                            <div class="flex items-center bg-pink-50 rounded-lg p-3">
                                <CheckIcon class="h-5 w-5 text-pink-600 mr-2 flex-shrink-0" />
                                <span class="text-gray-800 font-medium text-sm">Günlük Temizlik</span>
                            </div>
                            <div class="flex items-center bg-yellow-50 rounded-lg p-3">
                                <CheckIcon class="h-5 w-5 text-yellow-600 mr-2 flex-shrink-0" />
                                <span class="text-gray-800 font-medium text-sm">Jakuzi</span>
                            </div>
                            <div class="flex items-center bg-emerald-50 rounded-lg p-3">
                                <CheckIcon class="h-5 w-5 text-emerald-600 mr-2 flex-shrink-0" />
                                <span class="text-gray-800 font-medium text-sm">Özel Bahçe</span>
                            </div>
                            <div class="flex items-center bg-cyan-50 rounded-lg p-3">
                                <CheckIcon class="h-5 w-5 text-cyan-600 mr-2 flex-shrink-0" />
                                <span class="text-gray-800 font-medium text-sm">Barbekü Alanı</span>
                            </div>
                            <div class="flex items-center bg-violet-50 rounded-lg p-3">
                                <CheckIcon class="h-5 w-5 text-violet-600 mr-2 flex-shrink-0" />
                                <span class="text-gray-800 font-medium text-sm">Çamaşır Makinesi</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Villa Description -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Villa Hakkında</h3>
                        <p class="text-gray-600 leading-relaxed">
                            {{ villa.description || 'Bu muhteşem villa, deniz manzaralı konumu ve lüks olanaklarıyla unutulmaz bir tatil deneyimi sunuyor. Modern tasarımı ve konforlu yaşam alanlarıyla aileniz ve sevdiklerinizle kaliteli zaman geçirebileceğiniz mükemmel bir seçim.' }}
                        </p>
                    </div>
                    
                    <!-- Guest Capacity -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Misafir Kapasitesi</h3>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <UserGroupIcon class="h-5 w-5 text-gray-400" />
                                <span class="text-gray-600">Maksimum {{ villa.max_guests || 8 }} misafir</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <HomeIcon class="h-5 w-5 text-gray-400" />
                                <span class="text-gray-600">{{ villa.bedrooms || 4 }} yatak odası</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <CheckIcon class="h-5 w-5 text-gray-400" />
                                <span class="text-gray-600">{{ villa.bathrooms || 3 }} banyo</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Booking Form -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                            <div class="mb-6">
                                <div class="text-2xl font-bold text-gray-900">
                                    ₺{{ formatPrice(villa.price_per_night) }}
                                    <span class="text-base text-gray-600 font-normal">/gece</span>
                                </div>
                            </div>
                            
                            <form @submit.prevent="makeReservation" class="space-y-4">
                                <!-- Check-in Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Giriş Tarihi</label>
                                    <input 
                                        type="date" 
                                        v-model="bookingForm.check_in"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required
                                    >
                                </div>
                                
                                <!-- Check-out Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Çıkış Tarihi</label>
                                    <input 
                                        type="date" 
                                        v-model="bookingForm.check_out"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required
                                    >
                                </div>
                                
                                <!-- Guests -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Misafir Sayısı</label>
                                    <div class="flex items-center justify-between border border-gray-300 rounded-lg px-3 py-2">
                                        <button 
                                            type="button" 
                                            @click="decrementGuests"
                                            class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition"
                                        >
                                            -
                                        </button>
                                        <span class="font-medium">{{ guests }} misafir</span>
                                        <button 
                                            type="button" 
                                            @click="incrementGuests"
                                            class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition"
                                        >
                                            +
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Availability Check -->
                                <button 
                                    type="button"
                                    @click="checkAvailability"
                                    :disabled="isCheckingAvailability"
                                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition font-medium disabled:opacity-50"
                                >
                                    {{ isCheckingAvailability ? 'Kontrol Ediliyor...' : 'Müsaitlik Kontrolü' }}
                                </button>
                                
                                <!-- Availability Message -->
                                <div v-if="availabilityMessage" class="text-sm text-center" :class="{
                                    'text-green-600': availabilityMessage.includes('müsait!'),
                                    'text-red-600': availabilityMessage.includes('müsait değil') || availabilityMessage.includes('hata'),
                                    'text-orange-600': availabilityMessage.includes('seçin')
                                }">
                                    {{ availabilityMessage }}
                                </div>
                                
                                <!-- Price Breakdown -->
                                <div v-if="bookingForm.total_nights > 0" class="border-t pt-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span>₺{{ formatPrice(villa.price_per_night) }} x {{ bookingForm.total_nights }} gece</span>
                                        <span>₺{{ formatPrice(bookingForm.total_amount) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>Temizlik ücreti</span>
                                        <span>₺{{ formatPrice(500) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>Hizmet bedeli</span>
                                        <span>₺{{ formatPrice(300) }}</span>
                                    </div>
                                    <div class="border-t pt-2 flex justify-between font-bold">
                                        <span>Toplam</span>
                                        <span>₺{{ formatPrice(bookingForm.total_amount + 800) }}</span>
                                    </div>
                                </div>
                                
                                <!-- Reserve Button -->
                                <button 
                                    type="submit"
                                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-purple-700 transition font-medium shadow-lg"
                                >
                                    Rezervasyon Yap
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Image Modal -->
        <div v-if="showImageModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" @click="closeImageModal">
            <div class="relative max-w-4xl max-h-full p-4">
                <img 
                    :src="selectedImage"
                    :alt="villa.title"
                    class="max-w-full max-h-full object-contain rounded-lg"
                    @click.stop
                />
                <button 
                    @click="closeImageModal"
                    class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75 transition"
                >
                    ✕
                </button>
            </div>
        </div>
    </div>
</template>