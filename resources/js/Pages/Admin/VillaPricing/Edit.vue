<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Fiyatlandırma Düzenle</h1>
        <p class="text-gray-600">Villa fiyatlandırmasını güncelleyin</p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Villa Selection -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
          <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-gray-900">Villa Seçimi</h2>
              <p class="text-sm text-gray-600">Fiyatlandırma yapılacak villa'yı seçin</p>
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <span class="flex items-center">
                🏠 Villa *
              </span>
            </label>
            <select
              v-model="form.villa_id"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              :class="{ 'border-red-500 ring-red-200': errors.villa_id }"
              @change="updateDefaultPrice"
            >
              <option value="">Villa seçin</option>
              <option v-for="villa in villas" :key="villa.id" :value="villa.id">
                {{ villa.title }} (Varsayılan: {{ formatCurrency(villa.price_per_night) }})
              </option>
            </select>
            <p v-if="errors.villa_id" class="text-red-500 text-sm mt-1 flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
              {{ errors.villa_id }}
            </p>
          </div>
        </div>

        <!-- Date Range -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
          <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-gray-900">Tarih Aralığı</h2>
              <p class="text-sm text-gray-600">Fiyatlandırmanın geçerli olacağı tarih aralığını belirtin</p>
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  📅 Başlangıç Tarihi *
                </span>
              </label>
              <input
                v-model="form.start_date"
                type="date"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                :class="{ 'border-red-500 ring-red-200': errors.start_date }"
              />
              <p v-if="errors.start_date" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.start_date }}
              </p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  📅 Bitiş Tarihi *
                </span>
              </label>
              <input
                v-model="form.end_date"
                type="date"
                required
                :min="form.start_date"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                :class="{ 'border-red-500 ring-red-200': errors.end_date }"
              />
              <p v-if="errors.end_date" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.end_date }}
              </p>
            </div>
          </div>
          
          <div v-if="errors.date_range" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-600 text-sm flex items-center">
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
              {{ errors.date_range }}
            </p>
          </div>
        </div>

        <!-- Pricing Details -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
          <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-gray-900">Fiyat Detayları</h2>
              <p class="text-sm text-gray-600">Dönemsel fiyat ve koşulları belirtin</p>
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  💰 Gecelik Fiyat (₺) *
                </span>
              </label>
              <div class="relative">
                <input
                  v-model.number="form.price_per_night"
                  type="number"
                  min="0"
                  step="0.01"
                  required
                  placeholder="1500.00"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  :class="{ 'border-red-500 ring-red-200': errors.price_per_night }"
                />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                  <span class="text-gray-500 text-sm">₺</span>
                </div>
              </div>
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
                  🌙 Minimum Konaklama *
                </span>
              </label>
              <div class="relative">
                <input
                  v-model.number="form.minimum_stay"
                  type="number"
                  min="1"
                  max="30"
                  required
                  placeholder="3"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  :class="{ 'border-red-500 ring-red-200': errors.minimum_stay }"
                />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                  <span class="text-gray-500 text-sm">gece</span>
                </div>
              </div>
              <p v-if="errors.minimum_stay" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.minimum_stay }}
              </p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  🏷️ Sezon Adı
                </span>
              </label>
              <input
                v-model="form.season_name"
                type="text"
                placeholder="Örn: Yaz Sezonu, Kış Sezonu"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                :class="{ 'border-red-500 ring-red-200': errors.season_name }"
              />
              <p v-if="errors.season_name" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.season_name }}
              </p>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  ✅ Durum
                </span>
              </label>
              <div class="flex items-center">
                <input
                  v-model="form.is_active"
                  type="checkbox"
                  true-value="1"
                  false-value="0"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                />
                <span class="ml-2 text-sm text-gray-700">Fiyatlandırma aktif olsun</span>
              </div>
            </div>
          </div>
          
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <span class="flex items-center">
                📝 Notlar
              </span>
            </label>
            <textarea
              v-model="form.notes"
              rows="3"
              placeholder="Bu fiyatlandırma hakkında notlar..."
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
              :class="{ 'border-red-500 ring-red-200': errors.notes }"
            ></textarea>
            <p v-if="errors.notes" class="text-red-500 text-sm mt-1 flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
              {{ errors.notes }}
            </p>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4">
          <Link
            :href="route('admin.villa-pricing.index')"
            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium"
          >
            İptal
          </Link>
          <button
            type="submit"
            :disabled="processing"
            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors disabled:opacity-50 font-medium"
          >
            {{ processing ? 'Güncelleniyor...' : 'Fiyatlandırma Güncelle' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  villas: Array,
  pricing: Object,
  errors: Object
})

const breadcrumbs = [
  { name: 'Villa Fiyat Yönetimi', href: route('admin.villa-pricing.index') },
  { name: 'Fiyatlandırma Düzenle', current: true }
]

const form = useForm({
  villa_id: props.pricing.villa_id,
  start_date: props.pricing.start_date,
  end_date: props.pricing.end_date,
  price_per_night: props.pricing.price_per_night,
  minimum_stay: props.pricing.minimum_stay,
  season_name: props.pricing.season_name || '',
  notes: props.pricing.notes || '',
  is_active: props.pricing.is_active
})

const updateDefaultPrice = () => {
  const selectedVilla = props.villas.find(villa => villa.id == form.villa_id)
  if (selectedVilla) {
    form.price_per_night = selectedVilla.price_per_night
  }
}

const submit = () => {
  form.put(route('admin.villa-pricing.update', props.pricing.id))
}

const formatCurrency = (amount) => {
  if (!amount) return '₺0'
  return new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY'
  }).format(amount)
}
</script>