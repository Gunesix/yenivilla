<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Header Component -->
        <Header />
        
        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pt-20">
            <!-- Villa Title & Info -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ villa?.title || 'Villa' }}</h1>
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
            
            <!-- Hero Image Slider -->
            <div 
                class="relative h-[650px] mb-8 rounded-xl overflow-hidden bg-gray-900"
                @mouseenter="stopAutoSlide"
                @mouseleave="startAutoSlide"
            >
                <!-- Main Slider Container -->
                <div class="relative w-full h-full">
                    <!-- Current Image -->
                    <div class="absolute inset-0 transition-all duration-500 ease-in-out">
                        <img 
                            :src="currentSlideImage"
                            :alt="villa?.title || 'Villa'"
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                    </div>
                    
                    <!-- Navigation Arrows -->
                    <button 
                        @click="previousSlide"
                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300 hover:scale-110 z-10"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    
                    <button 
                        @click="nextSlide"
                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300 hover:scale-110 z-10"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    
                    <!-- Slide Indicators -->
                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                        <button 
                            v-for="(image, index) in sliderImages" 
                            :key="index"
                            @click="goToSlide(index)"
                            :class="[
                                'w-3 h-3 rounded-full transition-all duration-300',
                                currentSlideIndex === index 
                                    ? 'bg-white scale-125' 
                                    : 'bg-white/50 hover:bg-white/75'
                            ]"
                        ></button>
                    </div>
                    
                    <!-- Image Counter -->
                    <div class="absolute top-6 right-6 bg-black/50 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-medium z-10">
                        {{ currentSlideIndex + 1 }} / {{ sliderImages.length }}
                    </div>
                    
                    <!-- Fullscreen Button -->
                    <button 
                        @click="openImageModal"
                        class="absolute top-6 left-6 bg-black/50 backdrop-blur-sm hover:bg-black/70 text-white p-2 rounded-full transition-all duration-300 hover:scale-110 z-10"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Thumbnail Strip -->
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                    <div class="flex space-x-2 overflow-x-auto scrollbar-hide">
                        <button 
                            v-for="(image, index) in sliderImages" 
                            :key="index"
                            @click="goToSlide(index)"
                            :class="[
                                'flex-shrink-0 w-16 h-12 rounded-lg overflow-hidden border-2 transition-all duration-300',
                                currentSlideIndex === index 
                                    ? 'border-white scale-110' 
                                    : 'border-white/30 hover:border-white/60'
                            ]"
                        >
                            <img 
                                :src="image"
                                :alt="`Villa ${index + 1}`"
                                class="w-full h-full object-cover"
                            />
                        </button>
                    </div>
                </div>
            </div>
        
            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pt-8">
                <!-- Left Column - Villa Details -->
                <div class="lg:col-span-2 space-y-8">
            
                    <!-- Villa Host Info -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                <UserIcon class="h-6 w-6 text-gray-600" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Ev sahibi: {{ villa.host_name || 'Villa Sahibi' }}</h3>
                                <p class="text-sm text-gray-600">{{ villa.host_experience || '5 yıllık deneyim' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Villa Features -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Villa Özellikleri</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div class="text-center">
                                <UserGroupIcon class="h-8 w-8 text-rose-500 mx-auto mb-2" />
                                <div class="text-2xl font-bold text-gray-900">{{ villa.max_guests }}</div>
                                <div class="text-sm font-medium text-gray-600">Misafir</div>
                            </div>
                            <div class="text-center">
                                <HomeIcon class="h-8 w-8 text-rose-500 mx-auto mb-2" />
                                <div class="text-2xl font-bold text-gray-900">{{ villa.bedrooms }}</div>
                                <div class="text-sm font-medium text-gray-600">Yatak Odası</div>
                            </div>
                            <div class="text-center">
                                <BuildingOfficeIcon class="h-8 w-8 text-rose-500 mx-auto mb-2" />
                                <div class="text-2xl font-bold text-gray-900">{{ villa.bathrooms }}</div>
                                <div class="text-sm font-medium text-gray-600">Banyo</div>
                            </div>
                            <div class="text-center">
                                <Square3Stack3DIcon class="h-8 w-8 text-rose-500 mx-auto mb-2" />
                                <div class="text-2xl font-bold text-gray-900">{{ villa.area || '250' }}</div>
                                <div class="text-sm font-medium text-gray-600">m²</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Açıklama</h2>
                        <p class="text-gray-700 leading-relaxed">{{ villa.description }}</p>
                    </div>
                    
                    <!-- Amenities -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Özellikler</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- WiFi -->
                            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-900 font-medium">Ücretsiz WiFi</span>
                            </div>
                            
                            <!-- Klima -->
                            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="bg-cyan-100 p-2 rounded-lg mr-3">
                                    <svg class="h-5 w-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-900 font-medium">Klima</span>
                            </div>
                            
                            <!-- Havuz -->
                            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-900 font-medium">Özel Havuz</span>
                            </div>
                            
                            <!-- Otopark -->
                            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="bg-gray-100 p-2 rounded-lg mr-3">
                                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-900 font-medium">Ücretsiz Otopark</span>
                            </div>
                            
                            <!-- Mutfak -->
                            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="bg-orange-100 p-2 rounded-lg mr-3">
                                    <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4m8-4v4"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-900 font-medium">Tam Donanımlı Mutfak</span>
                            </div>
                            
                            <!-- Deniz Manzarası -->
                            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="bg-teal-100 p-2 rounded-lg mr-3">
                                    <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-900 font-medium">Deniz Manzarası</span>
                            </div>
                            
                           
                        </div>
                    </div>
                    
                    <!-- Location & Map -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Konum</h2>
                        <div class="mb-4">
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <p class="text-gray-900 font-semibold mb-1">{{ villa.location }}</p>
                                <p class="text-gray-600 text-sm">{{ villa.address || 'Detaylı adres bilgisi rezervasyon sonrası paylaşılacaktır.' }}</p>
                            </div>
                        </div>
                        <div class="bg-gray-100 h-48 rounded-lg flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <MapPinIcon class="h-12 w-12 mx-auto mb-3" />
                                <p class="font-semibold">Harita Görünümü</p>
                                <p class="text-sm">(Google Maps entegrasyonu)</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reviews -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Değerlendirmeler</h2>
                        
                        <div v-if="villa.reviews?.length" class="space-y-6">
                            <div v-for="review in villa.reviews.slice(0, 3)" :key="review.id" class="border-b border-gray-200 pb-6 last:border-b-0">
                                <div class="flex items-start space-x-4">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                        <UserIcon class="h-5 w-5 text-gray-600" />
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-semibold text-gray-900">{{ review.user?.name || 'Anonim' }}</h4>
                                            <span class="text-sm text-gray-500">{{ formatDate(review.created_at) }}</span>
                                        </div>
                                        <div class="flex items-center mb-3">
                                            <div class="flex">
                                                <StarIcon v-for="i in 5" :key="i" :class="[i <= review.rating ? 'text-yellow-400 fill-current' : 'text-gray-300', 'h-4 w-4']" />
                                            </div>
                                        </div>
                                        <p class="text-gray-700">{{ review.comment }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div v-else class="text-center py-8">
                            <ChatBubbleLeftRightIcon class="h-12 w-12 mx-auto mb-4 text-gray-400" />
                            <p class="text-gray-600 font-medium">Henüz değerlendirme bulunmuyor.</p>
                            <p class="text-gray-500 text-sm">İlk değerlendirmeyi siz yapın!</p>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Sidebar -->
                <div class="lg:col-span-1">
                    <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-2xl p-5 sticky top-20 shadow-xl">
                        <div class="text-center mb-5">
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl p-4 mb-3">
                                <div class="text-2xl font-bold mb-1">
                                    ₺{{ formatPrice(villa.price_per_night) }}
                                </div>
                                <div class="text-blue-100 text-sm">/gece</div>
                            </div>
                            <div class="flex items-center justify-center bg-white/30 rounded-lg px-3 py-2 backdrop-blur-sm">
                                <StarIcon class="h-4 w-4 text-yellow-500 mr-2" />
                                <span class="font-bold text-gray-900 text-sm">{{ villa.average_rating || '4.5' }}</span>
                                <span class="text-gray-700 ml-1 text-sm">({{ villa.reviews?.length || 0 }} değerlendirme)</span>
                            </div>
                        </div>
                        
                        <form @submit.prevent="checkAvailability" class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-800 mb-2 flex items-center">
                                        <span class="bg-gradient-to-r from-blue-500 to-cyan-500 w-1.5 h-1.5 rounded-full mr-2 animate-pulse"></span>
                                        Giriş Tarihi
                                    </label>
                                    <DatePicker 
                                        v-model="bookingForm.check_in" 
                                        placeholder="Giriş tarihi seçin"
                                        :min-date="today"
                                        class="w-full"
                                    />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-800 mb-2 flex items-center">
                                        <span class="bg-gradient-to-r from-purple-500 to-pink-500 w-1.5 h-1.5 rounded-full mr-2 animate-pulse"></span>
                                        Çıkış Tarihi
                                    </label>
                                    <DatePicker 
                                        v-model="bookingForm.check_out" 
                                        placeholder="Çıkış tarihi seçin"
                                        :min-date="bookingForm.check_in || today"
                                        class="w-full"
                                    />
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-800 mb-2">Misafir Sayısı</label>
                                <select 
                                    v-model="bookingForm.guests" 
                                    class="w-full px-3 py-2 bg-white/30 border border-white/40 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 backdrop-blur-sm font-medium text-sm"
                                    required
                                >
                                    <option value="">Seçiniz</option>
                                    <option v-for="i in villa.max_guests" :key="i" :value="i">{{ i }} Misafir</option>
                                </select>
                            </div>
                            
                            <button 
                                type="submit" 
                                :disabled="isCheckingAvailability"
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all font-bold text-sm shadow-lg disabled:opacity-50"
                            >
                                <span v-if="isCheckingAvailability">Kontrol Ediliyor...</span>
                                <span v-else>Müsaitlik Kontrol Et</span>
                            </button>
                        </form>
                        
                        <!-- Modern Availability Calendar -->
                        <div class="mt-6 bg-white/30 backdrop-blur-sm rounded-xl p-3 sm:p-4 border border-white/40">
                            <div class="flex items-center justify-between mb-3 sm:mb-4">
                                <h3 class="text-sm font-semibold text-gray-800">Uygunluk Takvimi</h3>
                                <div class="flex items-center space-x-1 sm:space-x-2">
                                    <button @click="previousMonth" class="p-1 hover:bg-white/30 rounded-lg transition-colors">
                                        <ChevronLeftIcon class="h-4 w-4 text-gray-600" />
                                    </button>
                                    <span class="text-xs sm:text-sm font-medium text-gray-800 min-w-[100px] sm:min-w-[120px] text-center">{{ currentMonthYear }}</span>
                                    <button @click="nextMonth" class="p-1 hover:bg-white/30 rounded-lg transition-colors">
                                        <ChevronRightIcon class="h-4 w-4 text-gray-600" />
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Calendar Grid -->
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <div v-for="day in ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz']" :key="day" class="text-center text-xs font-semibold text-gray-700 py-2">
                                    {{ day }}
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-7 gap-1">
                                <button
                                    v-for="date in calendarDays"
                                    :key="date.date"
                                    @click="selectDate(date)"
                                    :disabled="!date.isAvailable || !date.isCurrentMonth"
                                    :class="[
                                        'aspect-square text-xs font-semibold rounded-lg transition-all duration-200 relative flex flex-col items-center justify-center p-0.5 sm:p-1',
                                        {
                                            'text-gray-400': !date.isCurrentMonth,
                                            'text-gray-900 hover:bg-blue-100': date.isCurrentMonth && date.isAvailable && !date.isSelected && !date.isInRange,
                                            'bg-blue-500 text-white': date.isSelected,
                                            'bg-blue-200 text-blue-900': date.isInRange && !date.isSelected,
                                            'bg-red-100 text-red-700 cursor-not-allowed': !date.isAvailable && date.isCurrentMonth,
                                            'ring-2 ring-blue-400 ring-offset-1': date.isToday,
                                            'cursor-pointer': date.isAvailable && date.isCurrentMonth,
                                            'cursor-not-allowed opacity-60': !date.isAvailable || !date.isCurrentMonth
                                        }
                                    ]"
                                >
                                    <span class="font-semibold">{{ date.day }}</span>
                                    <span v-if="date.isCurrentMonth && date.isAvailable" class="text-[9px] sm:text-[11px] leading-none font-medium" :class="{
                                        'text-white': date.isSelected,
                                        'text-blue-800': date.isInRange && !date.isSelected,
                                        'text-gray-700': !date.isSelected && !date.isInRange,
                                        'font-semibold': date.isWeekend
                                    }">
                                        ₺{{ Math.round(date.price / 100) * 100 }}
                                    </span>
                                    <div v-if="date.isToday" class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-500 rounded-full"></div>
                                    <div v-if="date.isWeekend && date.isCurrentMonth" class="absolute top-0 right-0 w-2 h-2 bg-orange-400 rounded-full transform translate-x-1 -translate-y-1"></div>
                                </button>
                            </div>
                            
                            <!-- Calendar Legend -->
                            <div class="mt-4 flex flex-wrap gap-2 text-xs">
                                <div class="flex items-center space-x-1">
                                    <div class="w-3 h-3 bg-blue-500 rounded"></div>
                                    <span class="text-gray-600">Seçili</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <div class="w-3 h-3 bg-blue-200 rounded"></div>
                                    <span class="text-gray-600">Aralık</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <div class="w-3 h-3 bg-red-100 border border-red-200 rounded"></div>
                                    <span class="text-gray-600">Dolu</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <div class="w-3 h-3 bg-gray-100 border border-gray-200 rounded"></div>
                                    <span class="text-gray-600">Müsait</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <div class="w-2 h-2 bg-orange-400 rounded-full"></div>
                                    <span class="text-gray-600">Hafta sonu</span>
                                </div>
                            </div>
                            
                            <!-- Selected Dates Info -->
                            <div v-if="selectedDates.length > 0" class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="text-xs font-medium text-blue-800 mb-1">Seçilen Tarihler:</div>
                                <div class="text-xs text-blue-600">
                                    <span v-if="selectedDates.length === 1">{{ formatDate(selectedDates[0]) }}</span>
                                    <span v-else-if="selectedDates.length === 2">
                                        {{ formatDate(selectedDates[0]) }} - {{ formatDate(selectedDates[1]) }}
                                        <span class="block mt-1 font-medium">{{ nightCount }} gece</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div v-if="availabilityResult" class="mt-3 p-3 rounded-lg" :class="availabilityResult.available ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                            <div class="flex items-center">
                                <CheckCircleIcon v-if="availabilityResult.available" class="h-4 w-4 text-green-500 mr-2" />
                                <XCircleIcon v-else class="h-4 w-4 text-red-500 mr-2" />
                                <span :class="availabilityResult.available ? 'text-green-800' : 'text-red-800'" class="font-medium text-sm">
                                    {{ availabilityResult.message }}
                                </span>
                            </div>
                            
                            <div v-if="availabilityResult.available && totalAmount" class="mt-3 space-y-2">
                                <div class="flex justify-between text-xs">
                                    <span>₺{{ formatPrice(villa.price_per_night) }} x {{ nightCount }} gece</span>
                                    <span>₺{{ formatPrice(villa.price_per_night * nightCount) }}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span>Temizlik ücreti</span>
                                    <span>₺{{ formatPrice(cleaningFee) }}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span>Hizmet bedeli</span>
                                    <span>₺{{ formatPrice(serviceFee) }}</span>
                                </div>
                                <hr class="my-2">
                                <div class="flex justify-between font-semibold text-sm">
                                    <span>Toplam</span>
                                    <span>₺{{ formatPrice(totalAmount) }}</span>
                                </div>
                                
                                <button 
                                    @click="makeReservation" 
                                    class="w-full bg-green-600 text-white py-2 px-3 rounded-lg hover:bg-green-700 transition-colors font-semibold mt-3 text-sm"
                                >
                                    Rezervasyon Yap
                                </button>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-center text-xs text-gray-500">
                            <p>Rezervasyon yapmak için giriş yapmanız gerekir.</p>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
        
        <!-- Modern Image Modal -->
        <div v-if="showImageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4" @click="closeImageModal">
            <div class="relative max-w-7xl max-h-full" @click.stop>
                <img 
                    :src="selectedImage || villa.images?.[0]?.image_url"
                    :alt="villa.title"
                    class="max-w-full max-h-full object-contain rounded-lg"
                />
                <button @click="closeImageModal" class="absolute top-4 right-4 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-full p-2 transition-all duration-300">
                    <XMarkIcon class="h-6 w-6" />
                </button>
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
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import { 
    ChevronRightIcon, 
    ChevronLeftIcon,
    MapPinIcon, 
    StarIcon, 
    UserGroupIcon, 
    HomeIcon, 
    BuildingOfficeIcon,
    Square3Stack3DIcon,
    CheckIcon,
    ChatBubbleLeftRightIcon,
    CheckCircleIcon,
    XCircleIcon,
    UserIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline'
import DatePicker from '@/Components/DatePicker.vue';
import Header from '@/Components/Header.vue';

const props = defineProps({
    villa: Object
})

const page = usePage()
const selectedImage = ref(null)
const showImageModal = ref(false)
const isCheckingAvailability = ref(false)
const availabilityResult = ref(null)

// Slider state
const currentSlideIndex = ref(0)
const sliderImages = computed(() => {
    const images = []
    // Add main villa image
    images.push(props.villa.images?.[0]?.image_url || `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20${props.villa.id}&image_size=landscape_16_9`)
    
    // Add additional images
    for (let i = 1; i <= 8; i++) {
        if (props.villa.images?.[i]?.image_url) {
            images.push(props.villa.images[i].image_url)
        } else {
            const prompts = [
                'luxury%20villa%20interior',
                'luxury%20villa%20bedroom', 
                'luxury%20villa%20bathroom',
                'luxury%20villa%20pool',
                'luxury%20villa%20kitchen',
                'luxury%20villa%20living%20room',
                'luxury%20villa%20terrace',
                'luxury%20villa%20garden'
            ]
            const prompt = prompts[(i-1) % prompts.length]
            images.push(`https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=${prompt}%20${props.villa.id}&image_size=landscape_16_9`)
        }
    }
    return images
})

const currentSlideImage = computed(() => {
    return sliderImages.value[currentSlideIndex.value] || sliderImages.value[0]
})

// Calendar state
const currentDate = ref(new Date())
const selectedDates = ref([])
const bookedDates = ref([])

// Weather state
const weatherForecast = ref([
    { day: 'Bugün', icon: '☀️', description: 'Güneşli', high: 28, low: 18 },
    { day: 'Yarın', icon: '⛅', description: 'Parçalı Bulutlu', high: 26, low: 16 },
    { day: 'Çarşamba', icon: '🌤️', description: 'Az Bulutlu', high: 29, low: 19 },
    { day: 'Perşembe', icon: '🌦️', description: 'Sağanak', high: 24, low: 15 },
    { day: 'Cuma', icon: '☀️', description: 'Güneşli', high: 30, low: 20 },
    { day: 'Cumartesi', icon: '⛅', description: 'Bulutlu', high: 27, low: 17 },
    { day: 'Pazar', icon: '☀️', description: 'Güneşli', high: 31, low: 21 }
])

const bookingForm = ref({
    check_in: '',
    check_out: '',
    guests: ''
})

const today = computed(() => {
    return new Date().toISOString().split('T')[0]
})

const nightCount = computed(() => {
    if (bookingForm.value.check_in && bookingForm.value.check_out) {
        const checkIn = new Date(bookingForm.value.check_in)
        const checkOut = new Date(bookingForm.value.check_out)
        const diffTime = Math.abs(checkOut - checkIn)
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    }
    return 0
})

const cleaningFee = computed(() => {
    return Math.round(props.villa.price_per_night * 0.1) // 10% cleaning fee
})

const serviceFee = computed(() => {
    return Math.round(props.villa.price_per_night * nightCount.value * 0.05) // 5% service fee
})

const totalAmount = computed(() => {
    if (nightCount.value > 0) {
        return (props.villa.price_per_night * nightCount.value) + cleaningFee.value + serviceFee.value
    }
    return 0
})

// Calendar computed values
const currentMonthYear = computed(() => {
    const months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 
                   'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık']
    return `${months[currentDate.value.getMonth()]} ${currentDate.value.getFullYear()}`
})

const calendarDays = computed(() => {
    const year = currentDate.value.getFullYear()
    const month = currentDate.value.getMonth()
    const firstDay = new Date(year, month, 1)
    const lastDay = new Date(year, month + 1, 0)
    const startDate = new Date(firstDay)
    startDate.setDate(startDate.getDate() - (firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1))
    
    const days = []
    const today = new Date()
    
    for (let i = 0; i < 42; i++) {
        const date = new Date(startDate)
        date.setDate(startDate.getDate() + i)
        
        const dateStr = date.toISOString().split('T')[0]
        const isCurrentMonth = date.getMonth() === month
        const isToday = date.toDateString() === today.toDateString()
        const isAvailable = !bookedDates.value.includes(dateStr) && date >= today
        const isSelected = selectedDates.value.includes(dateStr)
        const isInRange = selectedDates.value.length === 2 && 
                         dateStr >= selectedDates.value[0] && 
                         dateStr <= selectedDates.value[1]
        
        // Calculate price variation (weekend premium)
        const dayOfWeek = date.getDay()
        const isWeekend = dayOfWeek === 0 || dayOfWeek === 6
        const priceMultiplier = isWeekend ? 1.2 : 1
        const dayPrice = Math.round(props.villa.price_per_night * priceMultiplier)
        
        days.push({
            date: dateStr,
            day: date.getDate(),
            isCurrentMonth,
            isToday,
            isAvailable,
            isSelected,
            isInRange,
            isWeekend,
            price: dayPrice
        })
    }
    
    return days
})

// Slider functions
const nextSlide = () => {
    currentSlideIndex.value = (currentSlideIndex.value + 1) % sliderImages.value.length
}

const previousSlide = () => {
    currentSlideIndex.value = currentSlideIndex.value === 0 
        ? sliderImages.value.length - 1 
        : currentSlideIndex.value - 1
}

const goToSlide = (index) => {
    currentSlideIndex.value = index
}

const selectImage = (imageUrl) => {
    selectedImage.value = imageUrl
}

const openImageModal = () => {
    showImageModal.value = true
    selectedImage.value = currentSlideImage.value
}

const closeImageModal = () => {
    showImageModal.value = false
}

// Auto-slide functionality
let autoSlideInterval = null

const startAutoSlide = () => {
    autoSlideInterval = setInterval(() => {
        nextSlide()
    }, 5000) // 5 seconds
}

const stopAutoSlide = () => {
    if (autoSlideInterval) {
        clearInterval(autoSlideInterval)
        autoSlideInterval = null
    }
}

// ESC tuşu ile modal'ı kapatma ve slider kontrolü
const handleKeydown = (event) => {
    if (event.key === 'Escape' && showImageModal.value) {
        closeImageModal()
    }
    if (!showImageModal.value) {
        if (event.key === 'ArrowLeft') {
            previousSlide()
        } else if (event.key === 'ArrowRight') {
            nextSlide()
        }
    }
}

// Calendar functions
const previousMonth = () => {
    const newDate = new Date(currentDate.value)
    newDate.setMonth(newDate.getMonth() - 1)
    currentDate.value = newDate
}

const nextMonth = () => {
    const newDate = new Date(currentDate.value)
    newDate.setMonth(newDate.getMonth() + 1)
    currentDate.value = newDate
}

const selectDate = (date) => {
    if (!date.isAvailable || !date.isCurrentMonth) return
    
    const dateStr = date.date
    const index = selectedDates.value.indexOf(dateStr)
    
    if (index > -1) {
        // Remove selected date
        selectedDates.value.splice(index, 1)
        // Clear booking form if no dates selected
        if (selectedDates.value.length === 0) {
            bookingForm.value.check_in = ''
            bookingForm.value.check_out = ''
        } else if (selectedDates.value.length === 1) {
            // Only one date selected, set as check_in
            bookingForm.value.check_in = selectedDates.value[0]
            bookingForm.value.check_out = ''
        }
    } else {
        if (selectedDates.value.length === 0) {
            // First date selection - check in
            selectedDates.value.push(dateStr)
            bookingForm.value.check_in = dateStr
            bookingForm.value.check_out = ''
        } else if (selectedDates.value.length === 1) {
            // Second date selection - check out
            selectedDates.value.push(dateStr)
            const sortedDates = selectedDates.value.sort()
            bookingForm.value.check_in = sortedDates[0]
            bookingForm.value.check_out = sortedDates[1]
        } else {
            // Reset and start new selection
            selectedDates.value = [dateStr]
            bookingForm.value.check_in = dateStr
            bookingForm.value.check_out = ''
        }
    }
    
    // Clear availability result when dates change
    availabilityResult.value = null
}

const checkAvailability = async () => {
    isCheckingAvailability.value = true
    availabilityResult.value = null
    
    try {
        // Use Inertia.js router.post for proper CSRF handling
        router.post('/bookings/check-availability', {
            villa_id: props.villa.id,
            check_in: bookingForm.value.check_in,
            check_out: bookingForm.value.check_out,
            guests: bookingForm.value.guests
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                // Handle successful response
                if (page?.props?.flash?.availabilityResult) {
                    availabilityResult.value = page.props.flash.availabilityResult
                } else {
                    availabilityResult.value = {
                        available: true,
                        message: 'Villa seçilen tarihler için müsait!'
                    }
                }
            },
            onError: (errors) => {
                console.error('Availability check failed:', errors)
                availabilityResult.value = {
                    available: false,
                    message: errors.message || 'Müsaitlik kontrolü sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
                }
            },
            onFinish: () => {
                isCheckingAvailability.value = false
            }
        })
    } catch (error) {
        console.error('Availability check failed:', error)
        availabilityResult.value = {
            available: false,
            message: 'Müsaitlik kontrolü sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
        }
        isCheckingAvailability.value = false
    }
}

const makeReservation = () => {
    if (!page?.props?.auth?.user) {
        router.visit('/login')
        return
    }
    
    // Redirect to booking create page with form data
    router.visit('/bookings/create', {
        method: 'get',
        data: {
            villa_id: props.villa.id,
            check_in: bookingForm.value.check_in,
            check_out: bookingForm.value.check_out,
            guests: bookingForm.value.guests
        }
    })
}

const formatPrice = (price) => {
    return new Intl.NumberFormat('tr-TR').format(price)
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('tr-TR')
}

// Watch for changes in booking form to sync with calendar
watch(() => [bookingForm.value.check_in, bookingForm.value.check_out], ([checkIn, checkOut]) => {
    if (checkIn && checkOut) {
        selectedDates.value = [checkIn, checkOut].sort()
    } else if (checkIn) {
        selectedDates.value = [checkIn]
    } else {
        selectedDates.value = []
    }
}, { immediate: true })

onMounted(() => {
    // Set default dates
    const today = new Date()
    const tomorrow = new Date()
    tomorrow.setDate(tomorrow.getDate() + 1)
    
    bookingForm.value.check_in = today.toISOString().split('T')[0]
    bookingForm.value.check_out = tomorrow.toISOString().split('T')[0]
    
    // Set some example booked dates
    const bookedExamples = []
    for (let i = 5; i < 8; i++) {
        const date = new Date()
        date.setDate(date.getDate() + i)
        bookedExamples.push(date.toISOString().split('T')[0])
    }
    for (let i = 15; i < 18; i++) {
        const date = new Date()
        date.setDate(date.getDate() + i)
        bookedExamples.push(date.toISOString().split('T')[0])
    }
    bookedDates.value = bookedExamples
    
    // Check for availability result from session
    if (page?.props?.flash?.availabilityResult) {
        availabilityResult.value = page.props.flash.availabilityResult
    }
    
    // ESC tuşu event listener'ını ekle
    document.addEventListener('keydown', handleKeydown)
    
    // Start auto-slide
    startAutoSlide()
})

onUnmounted(() => {
    // ESC tuşu event listener'ını kaldır
    document.removeEventListener('keydown', handleKeydown)
    
    // Stop auto-slide
    stopAutoSlide()
})
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