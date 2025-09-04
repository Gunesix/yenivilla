<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
        <!-- Header Component -->
        <Header />
        
        <!-- Hero Section with Search -->
        <section class="relative pt-24 pb-16 bg-gradient-to-br from-blue-600 to-purple-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                        Lüks Villa<br>
                        <span class="text-blue-300">Koleksiyonu</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto">
                        Türkiye'nin en güzel destinasyonlarında premium villa deneyimi
                    </p>
                </div>
                
                <!-- Glassmorphism Search Form -->
                <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-3xl p-6 md:p-8 shadow-2xl max-w-7xl mx-auto">
                    <!-- Main Search Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                        <!-- Location -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-white/95 tracking-wide">Destinasyon</label>
                            <div class="relative group">
                                <MapPinIcon class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-white/70 group-focus-within:text-white transition-colors" />
                                <input 
                                    v-model="filters.location" 
                                    type="text" 
                                    placeholder="Şehir veya bölge seçin"
                                    class="w-full pl-12 pr-4 py-3.5 bg-white/15 border border-white/25 rounded-xl focus:outline-none focus:ring-2 focus:ring-white/40 focus:border-white/50 text-white placeholder-white/60 backdrop-blur-sm transition-all duration-200 hover:bg-white/20"
                                />
                            </div>
                        </div>
                        
                        <!-- Check-in Date -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-white/95 tracking-wide">Giriş Tarihi</label>
                            <div class="relative group">
                                <CalendarIcon class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-white/70 group-focus-within:text-white transition-colors" />
                                <input 
                                    v-model="filters.check_in" 
                                    type="date" 
                                    class="w-full pl-12 pr-4 py-3.5 bg-white/15 border border-white/25 rounded-xl focus:outline-none focus:ring-2 focus:ring-white/40 focus:border-white/50 text-white backdrop-blur-sm transition-all duration-200 hover:bg-white/20"
                                />
                            </div>
                        </div>
                        
                        <!-- Check-out Date -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-white/95 tracking-wide">Çıkış Tarihi</label>
                            <div class="relative group">
                                <CalendarIcon class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-white/70 group-focus-within:text-white transition-colors" />
                                <input 
                                    v-model="filters.check_out" 
                                    type="date" 
                                    class="w-full pl-12 pr-4 py-3.5 bg-white/15 border border-white/25 rounded-xl focus:outline-none focus:ring-2 focus:ring-white/40 focus:border-white/50 text-white backdrop-blur-sm transition-all duration-200 hover:bg-white/20"
                                />
                            </div>
                        </div>
                        
                        <!-- Guests -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-white/95 tracking-wide">Misafir Sayısı</label>
                            <div class="relative group">
                                <UserGroupIcon class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-white/70 group-focus-within:text-white transition-colors" />
                                <select 
                                    v-model="filters.guests" 
                                    class="w-full pl-12 pr-4 py-3.5 bg-white/15 border border-white/25 rounded-xl focus:outline-none focus:ring-2 focus:ring-white/40 focus:border-white/50 text-white backdrop-blur-sm transition-all duration-200 hover:bg-white/20 appearance-none"
                                >
                                    <option value="" class="text-gray-800 bg-white">Misafir sayısı seçin</option>
                                    <option v-for="i in 12" :key="i" :value="i" class="text-gray-800 bg-white">{{ i }} Misafir</option>
                                </select>
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <svg class="h-4 w-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Divider -->
                    <div class="my-6 border-t border-white/20"></div>
                    
                    <!-- Additional Filters -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                        <!-- Price Range -->
                        <div class="space-y-2 lg:col-span-2">
                            <label class="block text-sm font-semibold text-white/95 tracking-wide">Fiyat Aralığı (₺/gece)</label>
                            <div class="flex items-center space-x-3">
                                <div class="flex-1">
                                    <input 
                                        v-model="filters.min_price" 
                                        type="number" 
                                        placeholder="Min fiyat"
                                        class="w-full px-4 py-3 bg-white/15 border border-white/25 rounded-xl focus:outline-none focus:ring-2 focus:ring-white/40 focus:border-white/50 text-white placeholder-white/60 backdrop-blur-sm transition-all duration-200 hover:bg-white/20"
                                    />
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="text-white/70 font-medium px-2">-</span>
                                </div>
                                <div class="flex-1">
                                    <input 
                                        v-model="filters.max_price" 
                                        type="number" 
                                        placeholder="Max fiyat"
                                        class="w-full px-4 py-3 bg-white/15 border border-white/25 rounded-xl focus:outline-none focus:ring-2 focus:ring-white/40 focus:border-white/50 text-white placeholder-white/60 backdrop-blur-sm transition-all duration-200 hover:bg-white/20"
                                    />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sort -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-white/95 tracking-wide">Sıralama</label>
                            <div class="relative group">
                                <select 
                                    v-model="filters.sort" 
                                    class="w-full px-4 py-3 bg-white/15 border border-white/25 rounded-xl focus:outline-none focus:ring-2 focus:ring-white/40 focus:border-white/50 text-white backdrop-blur-sm transition-all duration-200 hover:bg-white/20 appearance-none"
                                >
                                    <option value="" class="text-gray-800 bg-white">Varsayılan sıralama</option>
                                    <option value="price_asc" class="text-gray-800 bg-white">Fiyat (Düşük → Yüksek)</option>
                                    <option value="price_desc" class="text-gray-800 bg-white">Fiyat (Yüksek → Düşük)</option>
                                    <option value="rating_desc" class="text-gray-800 bg-white">Puan (Yüksek → Düşük)</option>
                                    <option value="created_desc" class="text-gray-800 bg-white">En Yeni Eklenenler</option>
                                </select>
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <svg class="h-4 w-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Search Button -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-transparent tracking-wide">Ara</label>
                            <button 
                                type="submit" 
                                @click="searchVillas"
                                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-3 px-6 rounded-xl transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-transparent"
                            >
                                <MagnifyingGlassIcon class="h-5 w-5 inline mr-2" />
                                Villa Ara
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Results Header -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        {{ villas.total }} Villa Bulundu
                    </h2>
                    <p class="text-gray-600">Size uygun lüks villaları keşfedin</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="flex border border-gray-300 rounded-xl overflow-hidden shadow-sm">
                        <button 
                            @click="viewMode = 'grid'" 
                            :class="[viewMode === 'grid' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50', 'px-4 py-2 transition-colors']"
                        >
                            <Squares2X2Icon class="h-5 w-5" />
                        </button>
                        <button 
                            @click="viewMode = 'list'" 
                            :class="[viewMode === 'list' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50', 'px-4 py-2 transition-colors']"
                        >
                            <ListBulletIcon class="h-5 w-5" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Villa Grid/List -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="villa in villas.data" :key="villa.id" class="group bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                    <div class="relative overflow-hidden">
                        <img 
                            :src="villa.images?.[0]?.image_url || `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20${villa.id}&image_size=landscape_4_3`"
                            :alt="villa.title"
                            class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                            ₺{{ formatPrice(villa.price_per_night) }}/gece
                        </div>
                        <div v-if="villa.featured" class="absolute top-4 left-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                            ⭐ Öne Çıkan
                        </div>
                        <div class="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="flex items-center text-white text-sm">
                                <StarIcon class="h-4 w-4 text-yellow-400 mr-1" />
                                <span class="font-medium">{{ villa.average_rating || '4.8' }}</span>
                                <span class="ml-2 text-white/80">({{ Math.floor(Math.random() * 50) + 10 }} değerlendirme)</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">{{ villa.title }}</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <MapPinIcon class="h-4 w-4 mr-2 text-blue-500" />
                            {{ villa.city }}, {{ villa.country || 'Türkiye' }}
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
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ villa.description }}</p>
                        
                        <div class="flex flex-wrap gap-1 mb-6">
                            <span v-for="amenity in villa.amenities?.slice(0, 3)" :key="amenity.id" class="bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-full border border-blue-200">
                                {{ amenity.name }}
                            </span>
                            <span v-if="villa.amenities?.length > 3" class="bg-gray-50 text-gray-600 text-xs px-3 py-1 rounded-full border border-gray-200">
                                +{{ villa.amenities.length - 3 }} daha
                            </span>
                        </div>
                        
                        <Link 
                            :href="route('villas.show', villa.id)" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-6 rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-semibold inline-block text-center shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                        >
                            Detayları Keşfet
                        </Link>
                    </div>
                </div>
            </div>
            
            <!-- List View -->
            <div v-else class="space-y-8">
                <div v-for="villa in villas.data" :key="villa.id" class="group bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                    <div class="md:flex">
                        <div class="md:w-1/3">
                            <div class="relative h-64 md:h-full overflow-hidden">
                                <img 
                                    :src="villa.images?.[0]?.image_url || `https://trae-api-sg.mchost.guru/api/ide/v1/text_to_image?prompt=luxury%20villa%20${villa.id}&image_size=landscape_4_3`"
                                    :alt="villa.title"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                    ₺{{ formatPrice(villa.price_per_night) }}/gece
                                </div>
                                <div v-if="villa.featured" class="absolute top-4 left-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    ⭐ Öne Çıkan
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:w-2/3 p-8">
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex-1">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">{{ villa.title }}</h3>
                                    <p class="text-gray-600 mb-4 flex items-center">
                                        <MapPinIcon class="h-5 w-5 mr-2 text-blue-500" />
                                        {{ villa.city }}, {{ villa.country || 'Türkiye' }}
                                    </p>
                                    <div class="flex items-center space-x-6 text-sm text-gray-500 mb-6">
                                        <span class="flex items-center bg-gray-50 px-4 py-2 rounded-full">
                                            <UserGroupIcon class="h-4 w-4 mr-2 text-blue-500" />
                                            {{ villa.max_guests }} Kişi
                                        </span>
                                        <span class="flex items-center bg-gray-50 px-4 py-2 rounded-full">
                                            🛏️ {{ villa.bedrooms }} Oda
                                        </span>
                                        <span class="flex items-center bg-gray-50 px-4 py-2 rounded-full">
                                            🛁 {{ villa.bathrooms }} Banyo
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center ml-6">
                                    <StarIcon class="h-5 w-5 text-yellow-400 mr-1" />
                                    <span class="text-lg font-semibold text-gray-700">{{ villa.average_rating || '4.8' }}</span>
                                    <span class="text-sm text-gray-500 ml-2">({{ Math.floor(Math.random() * 50) + 10 }})</span>
                                </div>
                            </div>
                            
                            <p class="text-gray-600 mb-6 leading-relaxed">{{ villa.description }}</p>
                            
                            <div class="flex flex-wrap gap-2 mb-6">
                                <span v-for="amenity in villa.amenities?.slice(0, 5)" :key="amenity.id" class="bg-blue-50 text-blue-700 text-sm px-4 py-2 rounded-full border border-blue-200">
                                    {{ amenity.name }}
                                </span>
                                <span v-if="villa.amenities?.length > 5" class="bg-gray-50 text-gray-600 text-sm px-4 py-2 rounded-full border border-gray-200">
                                    +{{ villa.amenities.length - 5 }} daha
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="text-3xl font-bold text-blue-600">
                                    ₺{{ formatPrice(villa.price_per_night) }}
                                    <span class="text-lg font-normal text-gray-500">/gece</span>
                                </div>
                                <Link 
                                    :href="route('villas.show', villa.id)" 
                                    class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                                >
                                    Detayları Keşfet
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div v-if="villas.last_page > 1" class="mt-12 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <Link 
                        v-if="villas.prev_page_url" 
                        :href="villas.prev_page_url" 
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                    >
                        Önceki
                    </Link>
                    
                    <span v-for="page in paginationPages" :key="page">
                        <Link 
                            v-if="page !== '...'"
                            :href="getPageUrl(page)" 
                            :class="[page === villas.current_page ? 'bg-blue-600 text-white' : 'bg-white text-gray-500 hover:bg-gray-50', 'px-3 py-2 text-sm font-medium border border-gray-300 rounded-md']"
                        >
                            {{ page }}
                        </Link>
                        <span v-else class="px-3 py-2 text-sm font-medium text-gray-500">
                            ...
                        </span>
                    </span>
                    
                    <Link 
                        v-if="villas.next_page_url" 
                        :href="villas.next_page_url" 
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                    >
                        Sonraki
                    </Link>
                </nav>
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
import { ref, computed, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { 
    MagnifyingGlassIcon, 
    Squares2X2Icon, 
    ListBulletIcon,
    MapPinIcon,
    CalendarIcon,
    UserGroupIcon,
    StarIcon
} from '@heroicons/vue/24/outline'
import Header from '@/Components/Header.vue'

const props = defineProps({
    villas: Object,
    filters: Object
})

const viewMode = ref('grid')
const filters = ref({
    location: props.filters?.location || '',
    check_in: props.filters?.check_in || '',
    check_out: props.filters?.check_out || '',
    guests: props.filters?.guests || '',
    min_price: props.filters?.min_price || '',
    max_price: props.filters?.max_price || '',
    amenities: props.filters?.amenities || [],
    sort: props.filters?.sort || ''
})

const availableAmenities = ref([
    'Havuz', 'WiFi', 'Klima', 'Mutfak', 'Çamaşır Makinesi', 'TV', 'Balkon', 'Bahçe', 'Jakuzi', 'Sauna'
])

const searchVillas = () => {
    router.get(route('villas.index'), filters.value, {
        preserveState: true,
        preserveScroll: true
    })
}

const formatPrice = (price) => {
    return new Intl.NumberFormat('tr-TR').format(price)
}

const paginationPages = computed(() => {
    const pages = []
    const current = props.villas.current_page
    const last = props.villas.last_page
    
    if (last <= 7) {
        for (let i = 1; i <= last; i++) {
            pages.push(i)
        }
    } else {
        if (current <= 4) {
            for (let i = 1; i <= 5; i++) {
                pages.push(i)
            }
            pages.push('...')
            pages.push(last)
        } else if (current >= last - 3) {
            pages.push(1)
            pages.push('...')
            for (let i = last - 4; i <= last; i++) {
                pages.push(i)
            }
        } else {
            pages.push(1)
            pages.push('...')
            for (let i = current - 1; i <= current + 1; i++) {
                pages.push(i)
            }
            pages.push('...')
            pages.push(last)
        }
    }
    
    return pages
})

const getPageUrl = (page) => {
    const url = new URL(window.location)
    url.searchParams.set('page', page)
    return url.toString()
}

onMounted(() => {
    // Debug: Log props data
    console.log('Villas Index - Props:', props)
    console.log('Villas data:', props.villas)
    console.log('Filters:', props.filters)
    
    // Set default dates if not provided
    if (!filters.value.check_in) {
        const today = new Date()
        filters.value.check_in = today.toISOString().split('T')[0]
    }
    if (!filters.value.check_out) {
        const tomorrow = new Date()
        tomorrow.setDate(tomorrow.getDate() + 1)
        filters.value.check_out = tomorrow.toISOString().split('T')[0]
    }
})
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>