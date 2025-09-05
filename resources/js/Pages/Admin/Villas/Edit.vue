<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-6xl mx-auto space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Villa Düzenle</h1>
        <p class="text-gray-600">{{ villa.title }} villa bilgilerini güncelleyin</p>
      </div>

      <!-- Tab Navigation -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
            <button
              @click="activeTab = 'basic'"
              :class="[
                activeTab === 'basic'
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
              ]"
              type="button"
            >
              <span class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Villa Bilgileri
              </span>
            </button>
            <button
              @click="activeTab = 'pricing'"
              :class="[
                activeTab === 'pricing'
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
              ]"
              type="button"
            >
              <span class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Fiyatlandırma
              </span>
            </button>
            <button
              @click="activeTab = 'availability'"
              :class="[
                activeTab === 'availability'
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
              ]"
              type="button"
            >
              <span class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Uygunluk Takvimi
              </span>
            </button>
          </nav>
        </div>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Tab Content -->
        <div v-show="activeTab === 'basic'" class="space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Temel Bilgiler</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Villa Adı *</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.name }"
              />
              <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Lokasyon *</label>
              <select
                v-model="form.location"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.location }"
              >
                <option value="">Lokasyon seçin</option>
                <option value="Antalya">Antalya</option>
                <option value="Bodrum">Bodrum</option>
                <option value="Çeşme">Çeşme</option>
                <option value="Kaş">Kaş</option>
                <option value="Kalkan">Kalkan</option>
                <option value="İstanbul">İstanbul</option>
                <option value="Muğla">Muğla</option>
                <option value="Fethiye">Fethiye</option>
                <option value="Marmaris">Marmaris</option>
                <option value="Datça">Datça</option>
              </select>
              <p v-if="errors.location" class="text-red-500 text-sm mt-1">{{ errors.location }}</p>
              <p class="text-xs text-gray-500 mt-1">Mevcut değer: {{ form.location || 'Boş' }}</p>
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama *</label>
              <textarea
                v-model="form.description"
                rows="4"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.description }"
              ></textarea>
              <p v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
            </div>
          </div>
        </div>

        <!-- Capacity & Features -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Kapasite ve Özellikler</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Maksimum Misafir *</label>
              <input
                v-model.number="form.max_guests"
                type="number"
                min="1"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.max_guests }"
              />
              <p v-if="errors.max_guests" class="text-red-500 text-sm mt-1">{{ errors.max_guests }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Yatak Odası *</label>
              <input
                v-model.number="form.bedrooms"
                type="number"
                min="1"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.bedrooms }"
              />
              <p v-if="errors.bedrooms" class="text-red-500 text-sm mt-1">{{ errors.bedrooms }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Banyo *</label>
              <input
                v-model.number="form.bathrooms"
                type="number"
                min="1"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.bathrooms }"
              />
              <p v-if="errors.bathrooms" class="text-red-500 text-sm mt-1">{{ errors.bathrooms }}</p>
            </div>
          </div>
        </div>

        <!-- Pricing -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Fiyatlandırma</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Gecelik Fiyat (₺) *</label>
              <input
                v-model.number="form.price_per_night"
                type="number"
                min="0"
                step="0.01"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.price_per_night }"
              />
              <p v-if="errors.price_per_night" class="text-red-500 text-sm mt-1">{{ errors.price_per_night }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Temizlik Ücreti (₺)</label>
              <input
                v-model.number="form.cleaning_fee"
                type="number"
                min="0"
                step="0.01"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.cleaning_fee }"
              />
              <p v-if="errors.cleaning_fee" class="text-red-500 text-sm mt-1">{{ errors.cleaning_fee }}</p>
            </div>
          </div>
        </div>

        <!-- Amenities -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Özellikler</h2>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <label v-for="amenity in availableAmenities" :key="amenity.id" class="flex items-center">
              <input
                v-model="form.amenities"
                :value="amenity.id"
                type="checkbox"
                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
              />
              <span class="ml-2 text-sm text-gray-700">{{ amenity.name }}</span>
            </label>
          </div>
        </div>

        <!-- Current Images -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Mevcut Fotoğraflar</h2>
          <div class="space-y-4">
            <div v-if="villa.main_image">
              <h3 class="text-sm font-medium text-gray-700 mb-2">Ana Fotoğraf</h3>
              <div class="relative inline-block">
                <img :src="villa.main_image" alt="Ana fotoğraf" class="w-32 h-24 object-cover rounded-md" />
                <button
                  @click="removeMainImage"
                  type="button"
                  class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600"
                >
                  ×
                </button>
              </div>
            </div>
            <div v-if="villa.images && villa.images.length > 0">
              <h3 class="text-sm font-medium text-gray-700 mb-2">Galeri Fotoğrafları</h3>
              <div class="grid grid-cols-4 gap-2">
                <div v-for="(image, index) in villa.images" :key="image.id" class="relative">
                  <img :src="image.url" alt="Galeri fotoğrafı" class="w-24 h-18 object-cover rounded-md" />
                  <button
                    @click="removeGalleryImage(image.id)"
                    type="button"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600"
                  >
                    ×
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- New Images -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Yeni Fotoğraflar</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Yeni Ana Fotoğraf</label>
              <input
                @change="handleMainImageUpload"
                type="file"
                accept="image/*"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <div v-if="mainImagePreview" class="mt-2">
                <img :src="mainImagePreview" alt="Ana fotoğraf önizleme" class="w-32 h-24 object-cover rounded-md" />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Yeni Galeri Fotoğrafları</label>
              <input
                @change="handleGalleryImagesUpload"
                type="file"
                accept="image/*"
                multiple
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <div v-if="galleryPreviews.length > 0" class="mt-2 grid grid-cols-4 gap-2">
                <img
                  v-for="(preview, index) in galleryPreviews"
                  :key="index"
                  :src="preview"
                  alt="Galeri önizleme"
                  class="w-24 h-18 object-cover rounded-md"
                />
              </div>
            </div>
          </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Durum</h2>
          <div>
            <label class="flex items-center">
              <input
                v-model="form.status"
                type="checkbox"
                true-value="active"
                false-value="inactive"
                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
              />
              <span class="ml-2 text-sm text-gray-700">Villa aktif olsun</span>
            </label>
          </div>
        </div>
        </div>

        <!-- Pricing Tab -->
        <div v-show="activeTab === 'pricing'" class="space-y-6">
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                </div>
                <div>
                  <h2 class="text-xl font-bold text-gray-900">Dönemsel Fiyatlandırma</h2>
                  <p class="text-sm text-gray-600">{{ villa.name }} için fiyat yönetimi</p>
                </div>
              </div>
              <button
                @click="loadPricingData"
                type="button"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Verileri Yenile
              </button>
            </div>
            
            <!-- Add New Pricing -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Yeni Fiyatlandırma Ekle</h3>
              <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-base font-semibold text-gray-800 mb-3 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      Başlangıç Tarihi *
                    </label>
                    <VueDatePicker
                      v-model="pricingForm.start_date"
                      :enable-time-picker="false"
                      format="dd/MM/yyyy"
                      model-type="yyyy-MM-dd"
                      placeholder="Başlangıç tarihini seçin"
                      :locale="'tr'"
                      auto-apply
                      :clearable="false"
                      class="w-full"
                      input-class-name="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 font-medium"
                    />
                  </div>
                  <div>
                    <label class="block text-base font-semibold text-gray-800 mb-3 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      Bitiş Tarihi *
                    </label>
                    <VueDatePicker
                      v-model="pricingForm.end_date"
                      :enable-time-picker="false"
                      format="dd/MM/yyyy"
                      model-type="yyyy-MM-dd"
                      placeholder="Bitiş tarihini seçin"
                      :locale="'tr'"
                      auto-apply
                      :clearable="false"
                      :min-date="pricingForm.start_date"
                      class="w-full"
                      input-class-name="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 font-medium"
                    />
                  </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gecelik Fiyat (₺)</label>
                    <input
                      v-model.number="pricingForm.price_per_night"
                      type="number"
                      min="0"
                      step="0.01"
                      placeholder="2000"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Konaklama (Gece)</label>
                    <input
                      v-model.number="pricingForm.minimum_stay"
                      type="number"
                      min="1"
                      placeholder="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sezon Adı</label>
                    <input
                      v-model="pricingForm.season_name"
                      type="text"
                      placeholder="Yaz Sezonu"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    />
                  </div>
                </div>
                
                <div class="flex justify-end">
                  <button
                    @click="savePricing"
                    type="button"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center"
                  >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Fiyatlandırma Kaydet
                  </button>
                </div>
              </div>
            </div>
            
            <!-- Existing Pricing List -->
            <div v-if="pricingList.length > 0">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Mevcut Fiyatlandırmalar</h3>
              <div class="space-y-3">
                <div v-for="pricing in pricingList" :key="pricing.id" class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg">
                  <div class="flex-1">
                    <div class="flex items-center space-x-4">
                      <span class="text-sm font-medium text-gray-900">{{ formatDate(pricing.start_date) }} - {{ formatDate(pricing.end_date) }}</span>
                      <span class="text-sm font-semibold text-green-600">{{ formatPrice(pricing.price_per_night) }}/gece</span>
                      <span v-if="pricing.season_name" class="text-sm text-blue-600">{{ pricing.season_name }}</span>
                      <span class="text-sm text-gray-600">Min: {{ pricing.minimum_stay }} gece</span>
                      <span :class="pricing.is_active ? 'text-green-600' : 'text-red-600'" class="text-sm font-medium">
                        {{ pricing.is_active ? 'Aktif' : 'Pasif' }}
                      </span>
                    </div>
                  </div>
                  <div class="flex items-center space-x-2">
                    <button
                      @click="editPricing(pricing)"
                      type="button"
                      class="text-blue-600 hover:text-blue-800 transition-colors"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </button>
                    <button
                      @click="deletePricing(pricing.id)"
                      type="button"
                      class="text-red-600 hover:text-red-800 transition-colors"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-8">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">Henüz fiyatlandırma yok</h3>
              <p class="mt-1 text-sm text-gray-500">Bu villa için henüz dönemsel fiyatlandırma tanımlanmamış.</p>
            </div>
          </div>
        </div>

        <!-- Availability Tab -->
        <div v-show="activeTab === 'availability'" class="space-y-6">
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                </div>
                <div>
                  <h2 class="text-xl font-bold text-gray-900">Uygunluk Takvimi</h2>
                  <p class="text-sm text-gray-600">{{ villa.name }} için uygunluk yönetimi</p>
                </div>
              </div>
              <button
                @click="loadAvailabilityData"
                type="button"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Verileri Yenile
              </button>
            </div>
            
            <!-- Add New Availability -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Yeni Uygunluk Kaydı Ekle</h3>
              <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-base font-semibold text-gray-800 mb-3 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      Başlangıç Tarihi *
                    </label>
                    <VueDatePicker
                      v-model="availabilityForm.start_date"
                      :enable-time-picker="false"
                      format="dd/MM/yyyy"
                      model-type="yyyy-MM-dd"
                      placeholder="Başlangıç tarihini seçin"
                      :locale="'tr'"
                      auto-apply
                      :clearable="false"
                      class="w-full"
                      input-class-name="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 font-medium"
                    />
                  </div>
                  <div>
                    <label class="block text-base font-semibold text-gray-800 mb-3 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      Bitiş Tarihi *
                    </label>
                    <VueDatePicker
                      v-model="availabilityForm.end_date"
                      :enable-time-picker="false"
                      format="dd/MM/yyyy"
                      model-type="yyyy-MM-dd"
                      placeholder="Bitiş tarihini seçin"
                      :locale="'tr'"
                      auto-apply
                      :clearable="false"
                      :min-date="availabilityForm.start_date"
                      class="w-full"
                      input-class-name="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 font-medium"
                    />
                  </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                    <select
                      v-model="availabilityForm.is_available"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    >
                      <option :value="true">Müsait</option>
                      <option :value="false">Müsait Değil</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sebep</label>
                    <input
                      v-model="availabilityForm.reason"
                      type="text"
                      placeholder="Bakım, özel rezervasyon vb."
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    />
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Notlar</label>
                  <textarea
                    v-model="availabilityForm.notes"
                    rows="3"
                    placeholder="Ek açıklamalar..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                  ></textarea>
                </div>
                
                <div class="flex justify-end">
                  <button
                    @click="saveAvailability"
                    type="button"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center"
                  >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Uygunluk Kaydet
                  </button>
                </div>
              </div>
            </div>
            
            <!-- Existing Availability List -->
            <div v-if="availabilityList.length > 0">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Mevcut Uygunluk Kayıtları</h3>
              <div class="space-y-3">
                <div v-for="availability in availabilityList" :key="availability.id" class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg">
                  <div class="flex-1">
                    <div class="flex items-center space-x-4">
                      <span class="text-sm font-medium text-gray-900">{{ formatDate(availability.date) }}</span>
                      <span :class="availability.is_available ? 'text-green-600' : 'text-red-600'" class="text-sm font-semibold">
                        {{ availability.is_available ? 'Müsait' : 'Müsait Değil' }}
                      </span>
                      <span v-if="availability.reason" class="text-sm text-gray-600">{{ availability.reason }}</span>
                    </div>
                    <div v-if="availability.notes" class="text-sm text-gray-500 mt-1">{{ availability.notes }}</div>
                  </div>
                  <div class="flex items-center space-x-2">
                    <button
                      @click="editAvailability(availability)"
                      type="button"
                      class="text-blue-600 hover:text-blue-800 transition-colors"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </button>
                    <button
                      @click="deleteAvailability(availability.id)"
                      type="button"
                      class="text-red-600 hover:text-red-800 transition-colors"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-8">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">Henüz uygunluk kaydı yok</h3>
              <p class="mt-1 text-sm text-gray-500">Bu villa için henüz uygunluk kaydı tanımlanmamış.</p>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-600">
              <span class="font-medium">{{ activeTab === 'basic' ? 'Villa Bilgileri' : activeTab === 'pricing' ? 'Fiyatlandırma' : 'Uygunluk Takvimi' }}</span> sekmesi aktif
            </div>
            <div class="flex gap-4">
              <Link
                :href="route('admin.villas.index')"
                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium"
              >
                İptal
              </Link>
              <button
                v-if="activeTab === 'basic'"
                type="submit"
                :disabled="form.processing"
                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors disabled:opacity-50 font-medium flex items-center"
              >
                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ form.processing ? 'Güncelleniyor...' : 'Villa Güncelle' }}
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps({
  villa: Object,
  availableAmenities: Array,
  errors: Object
})

const breadcrumbs = [
  { name: 'Villa Yönetimi', href: route('admin.villas.index') },
  { name: props.villa.title, href: route('admin.villas.edit', props.villa.id), current: true }
]

// Tab management
const activeTab = ref('basic')

// Main form
const form = useForm({
  name: props.villa.title,
  location: props.villa.city,
  description: props.villa.description,
  max_guests: props.villa.max_guests,
  bedrooms: props.villa.bedrooms,
  bathrooms: props.villa.bathrooms,
  price_per_night: props.villa.price_per_night,
  cleaning_fee: props.villa.cleaning_fee || 0,
  amenities: props.villa.amenities?.map(a => a.id) || [],
  main_image: null,
  gallery_images: [],
  status: props.villa.is_active ? 'active' : 'inactive',
  remove_main_image: false,
  remove_gallery_images: []
})

// Pricing form
const pricingForm = ref({
  start_date: '',
  end_date: '',
  price_per_night: 0,
  minimum_stay: 1,
  season_name: '',
  is_active: true
})

// Availability form
const availabilityForm = ref({
  start_date: '',
  end_date: '',
  is_available: false,
  reason: '',
  notes: ''
})

// Lists for existing data
const pricingList = ref([])
const availabilityList = ref([])

const mainImagePreview = ref(null)
const galleryPreviews = ref([])

const handleMainImageUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.main_image = file
    const reader = new FileReader()
    reader.onload = (e) => {
      mainImagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const handleGalleryImagesUpload = (event) => {
  const files = Array.from(event.target.files)
  form.gallery_images = files
  
  galleryPreviews.value = []
  files.forEach(file => {
    const reader = new FileReader()
    reader.onload = (e) => {
      galleryPreviews.value.push(e.target.result)
    }
    reader.readAsDataURL(file)
  })
}

const removeMainImage = () => {
  form.remove_main_image = true
  // Optionally hide the image from UI
}

const removeGalleryImage = (imageId) => {
  if (!form.remove_gallery_images.includes(imageId)) {
    form.remove_gallery_images.push(imageId)
  }
  // Optionally hide the image from UI
}

// Pricing functions
const loadPricingData = async () => {
  try {
    const response = await fetch(route('admin.villa-pricing.index', { villa_id: props.villa.id }), {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })
    const data = await response.json()
    pricingList.value = data || []
  } catch (error) {
    console.error('Error loading pricing data:', error)
  }
}

const savePricing = async () => {
  // Validation
  if (!pricingForm.value.start_date) {
    alert('Başlangıç tarihi gereklidir')
    return
  }
  if (!pricingForm.value.end_date) {
    alert('Bitiş tarihi gereklidir')
    return
  }
  if (pricingForm.value.price_per_night <= 0) {
    alert('Gecelik fiyat 0\'dan büyük olmalıdır')
    return
  }
  if (new Date(pricingForm.value.start_date) >= new Date(pricingForm.value.end_date)) {
    alert('Bitiş tarihi başlangıç tarihinden sonra olmalıdır')
    return
  }
  
  try {
    const response = await fetch(route('admin.villa-pricing.store'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        villa_id: props.villa.id,
        ...pricingForm.value
      })
    })
    
    if (response.ok) {
      // Reset form
      pricingForm.value = {
        start_date: '',
        end_date: '',
        price_per_night: 0,
        minimum_stay: 1,
        season_name: '',
        is_active: true
      }
      // Reload data
      loadPricingData()
      // Success message
      alert('Fiyatlandırma başarıyla kaydedildi!')
    } else {
      alert('Fiyatlandırma kaydedilirken bir hata oluştu')
    }
  } catch (error) {
    console.error('Error saving pricing:', error)
    alert('Fiyatlandırma kaydedilirken bir hata oluştu')
  }
}

const editPricing = (pricing) => {
  pricingForm.value = { ...pricing }
}

const deletePricing = async (pricingId) => {
  if (confirm('Bu fiyatlandırmayı silmek istediğinizden emin misiniz?')) {
    try {
      const response = await fetch(route('admin.villa-pricing.destroy', pricingId), {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      
      const data = await response.json()
      
      if (response.ok && data.success) {
        alert('Fiyatlandırma başarıyla silindi!')
        loadPricingData()
      } else {
        alert(data.message || 'Fiyatlandırma silinirken bir hata oluştu')
      }
    } catch (error) {
      console.error('Error deleting pricing:', error)
      alert('Fiyatlandırma silinirken bir hata oluştu')
    }
  }
}

// Availability functions
const loadAvailabilityData = async () => {
  try {
    console.log('Loading availability data for villa:', props.villa.id)
    
    const response = await fetch(route('admin.villa-availability.index', { villa_id: props.villa.id }), {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })
    
    console.log('Availability response status:', response.status)
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }
    
    const data = await response.json()
    console.log('Raw availability data:', data)
    
    // Map the data to match expected format
    const mappedData = data.map(item => ({
      ...item,
      is_available: item.status === 'available' || item.is_available === true
    }))
    
    console.log('Mapped availability data:', mappedData)
    availabilityList.value = mappedData || []
  } catch (error) {
    console.error('Error loading availability data:', error)
    alert('Uygunluk verileri yüklenirken hata oluştu: ' + error.message)
  }
}

const saveAvailability = async () => {
  // Validation
  if (!availabilityForm.value.start_date) {
    alert('Başlangıç tarihi gereklidir')
    return
  }
  if (!availabilityForm.value.end_date) {
    alert('Bitiş tarihi gereklidir')
    return
  }
  if (new Date(availabilityForm.value.start_date) >= new Date(availabilityForm.value.end_date)) {
    alert('Bitiş tarihi başlangıç tarihinden sonra olmalıdır')
    return
  }
  
  try {
    const response = await fetch(route('admin.villa-availability.store'), {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        villa_id: props.villa.id,
        start_date: availabilityForm.value.start_date,
        end_date: availabilityForm.value.end_date,
        is_available: availabilityForm.value.is_available,
        reason: availabilityForm.value.reason,
        notes: availabilityForm.value.notes
      })
    })
    
    const data = await response.json()
    
    if (response.ok && data.success) {
      // Reset form
      availabilityForm.value = {
        start_date: '',
        end_date: '',
        is_available: false,
        reason: '',
        notes: ''
      }
      // Reload data
      await loadAvailabilityData()
      // Success message
      alert(data.message || 'Uygunluk kaydı başarıyla kaydedildi!')
    } else {
      console.error('Server response:', data)
      alert(data.message || 'Uygunluk kaydı kaydedilirken bir hata oluştu')
    }
  } catch (error) {
    console.error('Error saving availability:', error)
    alert('Uygunluk kaydı kaydedilirken bir hata oluştu: ' + error.message)
  }
}

const editAvailability = (availability) => {
  availabilityForm.value = { ...availability }
}

const deleteAvailability = async (availabilityId) => {
  if (confirm('Bu uygunluk kaydını silmek istediğinizden emin misiniz?')) {
    try {
      console.log('=== DELETE AVAILABILITY START ===')
      console.log('Deleting availability with ID:', availabilityId)
      console.log('Request URL:', route('admin.villa-availability.destroy', availabilityId))
      
      const response = await fetch(route('admin.villa-availability.destroy', availabilityId), {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      
      console.log('Delete response status:', response.status)
      console.log('Delete response ok:', response.ok)
      console.log('Delete response headers:', Object.fromEntries(response.headers.entries()))
      
      let data
      try {
        data = await response.json()
        console.log('Delete response data:', data)
      } catch (jsonError) {
        console.error('Failed to parse JSON response:', jsonError)
        console.log('Raw response text:', await response.text())
        throw new Error('Invalid JSON response from server')
      }
      
      if (response.ok && data.success) {
        console.log('Delete successful, reloading data...')
        alert(data.message || 'Uygunluk kaydı başarıyla silindi!')
        await loadAvailabilityData()
        console.log('Data reloaded after delete')
      } else {
        console.error('Delete failed - Response not ok or success false:', {
          responseOk: response.ok,
          dataSuccess: data.success,
          data: data
        })
        alert(data.message || 'Uygunluk kaydı silinirken bir hata oluştu')
      }
      
      console.log('=== DELETE AVAILABILITY END ===')
    } catch (error) {
      console.error('=== DELETE AVAILABILITY ERROR ===')
      console.error('Error deleting availability:', error)
      console.error('Error stack:', error.stack)
      alert('Uygunluk kaydı silinirken bir hata oluştu: ' + error.message)
    }
  }
}

// Utility functions
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('tr-TR')
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY'
  }).format(price)
}

const submit = () => {
  form.put(route('admin.villas.update', props.villa.id), {
    forceFormData: true
  })
}

// Load data on mount
onMounted(() => {
  loadPricingData()
  loadAvailabilityData()
})
</script>