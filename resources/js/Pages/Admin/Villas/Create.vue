<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-6xl mx-auto space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Yeni Villa Ekle</h1>
        <p class="text-gray-600">Villa bilgilerini doldurun ve yeni villa oluşturun</p>
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
                Temel Bilgiler
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
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
          <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-gray-900">Temel Bilgiler</h2>
              <p class="text-sm text-gray-600">Villa'nın temel özelliklerini girin</p>
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  🏠 Villa Adı *
                </span>
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="Örn: Lüks Deniz Manzaralı Villa"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                :class="{ 'border-red-500 ring-red-200': errors.name }"
              />
              <p v-if="errors.name" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.name }}
              </p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  📍 Şehir/Lokasyon *
                </span>
              </label>
              <select
                v-model="form.location"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                :class="{ 'border-red-500 ring-red-200': errors.location }"
              >
                <option value="">Şehir seçin</option>
                <option value="Antalya">Antalya</option>
                <option value="Bodrum">Bodrum</option>
                <option value="Çeşme">Çeşme</option>
                <option value="Kaş">Kaş</option>
                <option value="Kalkan">Kalkan</option>
                <option value="Fethiye">Fethiye</option>
                <option value="Marmaris">Marmaris</option>
                <option value="Datça">Datça</option>
                <option value="Akyaka">Akyaka</option>
                <option value="İzmir">İzmir</option>
                <option value="Muğla">Muğla</option>
                <option value="Nevşehir">Nevşehir</option>
              </select>
              <p v-if="errors.location" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.location }}
              </p>
            </div>
            
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  📝 Detaylı Adres
                </span>
              </label>
              <input
                v-model="form.address"
                type="text"
                placeholder="Örn: Yalıkavak Mahallesi, Tilkicik Caddesi No:15"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                :class="{ 'border-red-500 ring-red-200': errors.address }"
              />
              <p v-if="errors.address" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.address }}
              </p>
            </div>
            
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  📄 Villa Açıklaması *
                </span>
              </label>
              <textarea
                v-model="form.description"
                rows="5"
                required
                placeholder="Villa'nızın özelliklerini, manzarasını, konumunu ve misafirlere sunduğu deneyimi detaylı bir şekilde açıklayın..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                :class="{ 'border-red-500 ring-red-200': errors.description }"
              ></textarea>
              <div class="flex justify-between items-center mt-2">
                <p v-if="errors.description" class="text-red-500 text-sm flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                  </svg>
                  {{ errors.description }}
                </p>
                <span class="text-sm text-gray-500">{{ form.description?.length || 0 }}/1000 karakter</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Capacity & Features -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
          <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-gray-900">Kapasite ve Özellikler</h2>
              <p class="text-sm text-gray-600">Villa'nın fiziksel özelliklerini belirtin</p>
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  👥 Maksimum Misafir *
                </span>
              </label>
              <div class="relative">
                <input
                  v-model.number="form.max_guests"
                  type="number"
                  min="1"
                  max="50"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  :class="{ 'border-red-500 ring-red-200': errors.max_guests }"
                />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                  <span class="text-gray-500 text-sm">kişi</span>
                </div>
              </div>
              <p v-if="errors.max_guests" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.max_guests }}
              </p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  🛏️ Yatak Odası *
                </span>
              </label>
              <div class="relative">
                <input
                  v-model.number="form.bedrooms"
                  type="number"
                  min="1"
                  max="20"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  :class="{ 'border-red-500 ring-red-200': errors.bedrooms }"
                />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                  <span class="text-gray-500 text-sm">oda</span>
                </div>
              </div>
              <p v-if="errors.bedrooms" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.bedrooms }}
              </p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  🚿 Banyo *
                </span>
              </label>
              <div class="relative">
                <input
                  v-model.number="form.bathrooms"
                  type="number"
                  min="1"
                  max="15"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  :class="{ 'border-red-500 ring-red-200': errors.bathrooms }"
                />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                  <span class="text-gray-500 text-sm">banyo</span>
                </div>
              </div>
              <p v-if="errors.bathrooms" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.bathrooms }}
              </p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  📐 Alan (m²)
                </span>
              </label>
              <div class="relative">
                <input
                  v-model.number="form.area_sqm"
                  type="number"
                  min="0"
                  max="10000"
                  placeholder="200"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  :class="{ 'border-red-500 ring-red-200': errors.area_sqm }"
                />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                  <span class="text-gray-500 text-sm">m²</span>
                </div>
              </div>
              <p v-if="errors.area_sqm" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.area_sqm }}
              </p>
            </div>
          </div>
          
          <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-start">
              <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
              </svg>
              <div>
                <h4 class="text-sm font-medium text-blue-900 mb-1">💡 İpucu</h4>
                <p class="text-sm text-blue-800">Doğru kapasite bilgileri misafirlerin villa seçiminde kritik öneme sahiptir. Gerçek değerleri girin.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Basic Pricing -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
          <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-gray-900">Temel Fiyatlandırma</h2>
              <p class="text-sm text-gray-600">Villa'nın genel fiyat bilgilerini girin</p>
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  💰 Gecelik Fiyat (₺) *
                </span>
              </label>
              <input
                v-model.number="form.price_per_night"
                type="number"
                min="0"
                step="0.01"
                required
                placeholder="1500"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                :class="{ 'border-red-500 ring-red-200': errors.price_per_night }"
              />
              <p v-if="errors.price_per_night" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.price_per_night }}
              </p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  🧹 Temizlik Ücreti (₺)
                </span>
              </label>
              <input
                v-model.number="form.cleaning_fee"
                type="number"
                min="0"
                step="0.01"
                placeholder="200"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                :class="{ 'border-red-500 ring-red-200': errors.cleaning_fee }"
              />
              <p v-if="errors.cleaning_fee" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.cleaning_fee }}
              </p>
            </div>
          </div>
          
          <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-start">
              <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
              </svg>
              <div>
                <h4 class="text-sm font-medium text-blue-900 mb-1">💡 İpucu</h4>
                <p class="text-sm text-blue-800">Bu temel fiyatlardır. Dönemsel fiyatlandırma için "Fiyatlandırma" sekmesini kullanın.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Location Coordinates -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Harita Koordinatları</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Enlem (Latitude)</label>
              <input
                v-model.number="form.latitude"
                type="number"
                step="0.000001"
                placeholder="Örn: 37.0625"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.latitude }"
                @input="updateMapMarker"
              />
              <p v-if="errors.latitude" class="text-red-500 text-sm mt-1">{{ errors.latitude }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Boylam (Longitude)</label>
              <input
                v-model.number="form.longitude"
                type="number"
                step="0.000001"
                placeholder="Örn: 27.4305"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="{ 'border-red-500': errors.longitude }"
                @input="updateMapMarker"
              />
              <p v-if="errors.longitude" class="text-red-500 text-sm mt-1">{{ errors.longitude }}</p>
            </div>
          </div>
          
          <!-- Interactive Map -->
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Harita Üzerinde Konum Seçin</label>
            <div id="map" class="w-full h-96 border border-gray-300 rounded-md"></div>
            <p class="text-sm text-gray-600 mt-2">
              💡 <strong>İpucu:</strong> Harita üzerinde bir noktaya tıklayarak koordinatları otomatik olarak ayarlayabilirsiniz.
            </p>
          </div>
        </div>

        <!-- Amenities -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Villa Özellikleri</h2>
          <p class="text-sm text-gray-600 mb-6">Villanızın sahip olduğu özellikleri seçin. Bu bilgiler misafirlerin villa seçiminde önemli rol oynar.</p>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <label 
              v-for="amenity in availableAmenities" 
              :key="amenity.id" 
              class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-300 hover:bg-blue-50"
              :class="{
                'border-blue-500 bg-blue-50': form.amenities.includes(amenity.id),
                'border-gray-200 bg-white': !form.amenities.includes(amenity.id)
              }"
            >
              <input
                v-model="form.amenities"
                :value="amenity.id"
                type="checkbox"
                class="sr-only"
              />
              <div class="flex items-center space-x-3 w-full">
                <div class="text-2xl">{{ amenity.icon }}</div>
                <div class="flex-1">
                  <div class="text-sm font-medium text-gray-900">{{ amenity.name }}</div>
                </div>
                <div 
                  class="w-5 h-5 rounded border-2 flex items-center justify-center transition-colors duration-200"
                  :class="{
                    'border-blue-500 bg-blue-500': form.amenities.includes(amenity.id),
                    'border-gray-300 bg-white': !form.amenities.includes(amenity.id)
                  }"
                >
                  <svg 
                    v-if="form.amenities.includes(amenity.id)" 
                    class="w-3 h-3 text-white" 
                    fill="currentColor" 
                    viewBox="0 0 20 20"
                  >
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
            </label>
          </div>
          
          <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">
              <strong>Seçilen özellikler:</strong> 
              <span v-if="form.amenities.length === 0" class="text-gray-400">Henüz özellik seçilmedi</span>
              <span v-else class="text-blue-600">{{ form.amenities.length }} özellik seçildi</span>
            </p>
          </div>
        </div>

        <!-- Images -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Fotoğraflar</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Ana Fotoğraf</label>
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
              <label class="block text-sm font-medium text-gray-700 mb-2">Galeri Fotoğrafları</label>
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
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
          <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-gray-900">Villa Durumu</h2>
              <p class="text-sm text-gray-600">Villa'nın yayın durumunu belirleyin</p>
            </div>
          </div>
          
          <div class="space-y-4">
            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-300 hover:bg-blue-50"
                   :class="{
                     'border-blue-500 bg-blue-50': form.status === 'active',
                     'border-gray-200 bg-white': form.status !== 'active'
                   }">
              <input
                v-model="form.status"
                type="checkbox"
                true-value="active"
                false-value="inactive"
                class="sr-only"
              />
              <div class="flex items-center space-x-3 w-full">
                <div 
                  class="w-5 h-5 rounded border-2 flex items-center justify-center transition-colors duration-200"
                  :class="{
                    'border-blue-500 bg-blue-500': form.status === 'active',
                    'border-gray-300 bg-white': form.status !== 'active'
                  }"
                >
                  <svg 
                    v-if="form.status === 'active'" 
                    class="w-3 h-3 text-white" 
                    fill="currentColor" 
                    viewBox="0 0 20 20"
                  >
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="flex-1">
                  <div class="text-sm font-medium text-gray-900">Villa aktif olsun</div>
                  <div class="text-sm text-gray-600">Aktif villalar arama sonuçlarında görünür ve rezervasyon alabilir</div>
                </div>
              </div>
            </label>
          </div>
        </div>
        </div>

        <!-- Pricing Tab -->
        <div v-show="activeTab === 'pricing'" class="space-y-6">
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-6">
              <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-bold text-gray-900">Dönemsel Fiyatlandırma</h2>
                <p class="text-sm text-gray-600">Farklı dönemler için özel fiyatlar belirleyin</p>
              </div>
            </div>
            
            <div class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
              
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                  @click="addPricing"
                  type="button"
                  class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Fiyatlandırma Ekle
                </button>
              </div>
            </div>
            
            <!-- Pricing List -->
            <div v-if="pricingList.length > 0" class="mt-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Eklenen Fiyatlandırmalar</h3>
              <div class="space-y-3">
                <div v-for="(pricing, index) in pricingList" :key="index" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                  <div class="flex-1">
                    <div class="flex items-center space-x-4">
                      <span class="text-sm font-medium text-gray-900">{{ formatDate(pricing.start_date) }} - {{ formatDate(pricing.end_date) }}</span>
                      <span class="text-sm font-semibold text-green-600">{{ formatPrice(pricing.price_per_night) }}/gece</span>
                      <span v-if="pricing.season_name" class="text-sm text-blue-600">{{ pricing.season_name }}</span>
                      <span class="text-sm text-gray-600">Min: {{ pricing.minimum_stay }} gece</span>
                    </div>
                  </div>
                  <button
                    @click="removePricing(index)"
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
        </div>

        <!-- Availability Tab -->
        <div v-show="activeTab === 'availability'" class="space-y-6">
          <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-6">
              <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-bold text-gray-900">Uygunluk Takvimi</h2>
                <p class="text-sm text-gray-600">Villa'nın müsait olmadığı tarihleri belirleyin</p>
              </div>
            </div>
            
            <div class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                  @click="addAvailability"
                  type="button"
                  class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Uygunluk Ekle
                </button>
              </div>
            </div>
            
            <!-- Availability List -->
            <div v-if="availabilityList.length > 0" class="mt-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Eklenen Uygunluk Kayıtları</h3>
              <div class="space-y-3">
                <div v-for="(availability, index) in availabilityList" :key="index" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                  <div class="flex-1">
                    <div class="flex items-center space-x-4">
                      <span class="text-sm font-medium text-gray-900">{{ formatDate(availability.start_date) }} - {{ formatDate(availability.end_date) }}</span>
                      <span :class="availability.is_available ? 'text-green-600' : 'text-red-600'" class="text-sm font-semibold">
                        {{ availability.is_available ? 'Müsait' : 'Müsait Değil' }}
                      </span>
                      <span v-if="availability.reason" class="text-sm text-gray-600">{{ availability.reason }}</span>
                    </div>
                    <div v-if="availability.notes" class="text-sm text-gray-500 mt-1">{{ availability.notes }}</div>
                  </div>
                  <button
                    @click="removeAvailability(index)"
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
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-600">
              <span class="font-medium">{{ activeTab === 'basic' ? 'Temel Bilgiler' : activeTab === 'pricing' ? 'Fiyatlandırma' : 'Uygunluk Takvimi' }}</span> sekmesi aktif
            </div>
            <div class="flex gap-4">
              <Link
                :href="route('admin.villas.index')"
                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium"
              >
                İptal
              </Link>
              <button
                type="submit"
                :disabled="processing"
                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors disabled:opacity-50 font-medium flex items-center"
              >
                <svg v-if="processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ processing ? 'Kaydediliyor...' : 'Villa Oluştur' }}
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted, nextTick } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

defineProps({
  availableAmenities: Array,
  errors: Object
})

const breadcrumbs = [
  { name: 'Villa Yönetimi', href: route('admin.villas.index') },
  { name: 'Yeni Villa Ekle', href: route('admin.villas.create'), current: true }
]

// Tab management
const activeTab = ref('basic')

// Main form
const form = useForm({
  name: '',
  location: '',
  address: '',
  description: '',
  max_guests: 1,
  bedrooms: 1,
  bathrooms: 1,
  area_sqm: 0,
  price_per_night: 0,
  cleaning_fee: 0,
  latitude: null,
  longitude: null,
  amenities: [],
  main_image: null,
  gallery_images: [],
  status: 'active',
  pricing_data: [],
  availability_data: []
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

// Lists for added items
const pricingList = ref([])
const availabilityList = ref([])

const mainImagePreview = ref(null)
const galleryPreviews = ref([])
const map = ref(null)
const marker = ref(null)

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

// Pricing functions
const addPricing = () => {
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
  
  // Add to list
  pricingList.value.push({ 
    ...pricingForm.value,
    id: Date.now() // Unique ID for tracking
  })
  form.pricing_data = pricingList.value
  
  // Reset form
  pricingForm.value = {
    start_date: '',
    end_date: '',
    price_per_night: 0,
    minimum_stay: 1,
    season_name: '',
    is_active: true
  }
  
  // Success message
  alert('Fiyatlandırma başarıyla eklendi!')
}

const removePricing = (index) => {
  pricingList.value.splice(index, 1)
  form.pricing_data = pricingList.value
}

// Availability functions
const addAvailability = () => {
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
  
  // Generate date range
  const startDate = new Date(availabilityForm.value.start_date)
  const endDate = new Date(availabilityForm.value.end_date)
  
  const dateRange = []
  const currentDate = new Date(startDate)
  
  while (currentDate <= endDate) {
    dateRange.push({
      date: new Date(currentDate).toISOString().split('T')[0],
      is_available: availabilityForm.value.is_available,
      reason: availabilityForm.value.reason,
      notes: availabilityForm.value.notes
    })
    currentDate.setDate(currentDate.getDate() + 1)
  }
  
  // Add to list
  availabilityList.value.push({
    id: Date.now(), // Unique ID for tracking
    start_date: availabilityForm.value.start_date,
    end_date: availabilityForm.value.end_date,
    is_available: availabilityForm.value.is_available,
    reason: availabilityForm.value.reason,
    notes: availabilityForm.value.notes,
    dates: dateRange
  })
  
  form.availability_data = availabilityList.value
  
  // Reset form
  availabilityForm.value = {
    start_date: '',
    end_date: '',
    is_available: false,
    reason: '',
    notes: ''
  }
  
  // Success message
  alert('Uygunluk kaydı başarıyla eklendi!')
}

const removeAvailability = (index) => {
  availabilityList.value.splice(index, 1)
  form.availability_data = availabilityList.value
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
  form.post(route('admin.villas.store'))
}

// Google Maps Integration
const initMap = () => {
  // Default center (Turkey)
  const defaultCenter = { lat: 39.9334, lng: 32.8597 }
  const center = (form.latitude && form.longitude) 
    ? { lat: form.latitude, lng: form.longitude }
    : defaultCenter

  map.value = new google.maps.Map(document.getElementById('map'), {
    zoom: form.latitude && form.longitude ? 15 : 6,
    center: center,
    mapTypeId: 'roadmap'
  })

  // Add marker if coordinates exist
  if (form.latitude && form.longitude) {
    addMarker(center)
  }

  // Add click listener to map
  map.value.addListener('click', (event) => {
    const lat = event.latLng.lat()
    const lng = event.latLng.lng()
    
    form.latitude = lat
    form.longitude = lng
    
    addMarker({ lat, lng })
  })
}

const addMarker = (position) => {
  // Remove existing marker
  if (marker.value) {
    marker.value.setMap(null)
  }
  
  // Add new marker
  marker.value = new google.maps.Marker({
    position: position,
    map: map.value,
    title: 'Villa Konumu',
    draggable: true
  })
  
  // Add drag listener to marker
  marker.value.addListener('dragend', (event) => {
    const lat = event.latLng.lat()
    const lng = event.latLng.lng()
    
    form.latitude = lat
    form.longitude = lng
  })
}

const updateMapMarker = () => {
  if (map.value && form.latitude && form.longitude) {
    const position = { lat: form.latitude, lng: form.longitude }
    map.value.setCenter(position)
    map.value.setZoom(15)
    addMarker(position)
  }
}

const loadGoogleMaps = () => {
  if (window.google && window.google.maps) {
    nextTick(() => {
      initMap()
    })
    return
  }
  
  const script = document.createElement('script')
  script.src = `https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initGoogleMap`
  script.async = true
  script.defer = true
  
  window.initGoogleMap = () => {
    nextTick(() => {
      initMap()
    })
  }
  
  document.head.appendChild(script)
}

onMounted(() => {
  loadGoogleMaps()
})
</script>

<style scoped>
.tab-button {
  @apply px-6 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 transition-colors duration-200;
}

.tab-button.active {
  @apply text-blue-600 border-blue-600;
}

.tab-content {
  @apply mt-6;
}

.form-group {
  @apply mb-4;
}

.form-label {
  @apply block text-sm font-medium text-gray-700 mb-2;
}

.form-input {
  @apply w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500;
}

.form-textarea {
  @apply w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500;
  resize: vertical;
}

.form-select {
  @apply w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500;
}

.btn {
  @apply px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200;
}

.btn-primary {
  @apply bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
}

.btn-secondary {
  @apply bg-gray-600 text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2;
}

.btn-danger {
  @apply bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2;
}

.btn-success {
  @apply bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2;
}

.card {
  @apply bg-white shadow rounded-lg;
}

.card-header {
  @apply px-6 py-4 border-b border-gray-200;
}

.card-body {
  @apply p-6;
}

.pricing-item, .availability-item {
  @apply bg-gray-50 p-4 rounded-lg border border-gray-200 mb-3;
}

.image-preview {
  @apply w-32 h-32 object-cover rounded-lg border border-gray-200;
}

.gallery-preview {
  @apply w-24 h-24 object-cover rounded-lg border border-gray-200;
}

#map {
  @apply w-full h-64 rounded-lg border border-gray-200;
}



.flex-between {
  @apply flex justify-between items-center;
}

.flex-center {
  @apply flex justify-center items-center;
}

.text-error {
  @apply text-red-600 text-sm mt-1;
}

.text-success {
  @apply text-green-600 text-sm mt-1;
}

.text-muted {
  @apply text-gray-500 text-sm;
}
</style>