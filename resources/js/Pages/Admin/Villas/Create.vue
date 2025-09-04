<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Yeni Villa Ekle</h1>
        <p class="text-gray-600">Villa bilgilerini doldurun ve yeni villa oluşturun</p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
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
                <option value="antalya">Antalya</option>
                <option value="bodrum">Bodrum</option>
                <option value="cesme">Çeşme</option>
                <option value="kas">Kaş</option>
                <option value="kalkan">Kalkan</option>
              </select>
              <p v-if="errors.location" class="text-red-500 text-sm mt-1">{{ errors.location }}</p>
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

        <!-- Actions -->
        <div class="flex justify-end gap-4">
          <Link
            :href="route('admin.villas.index')"
            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
          >
            İptal
          </Link>
          <button
            type="submit"
            :disabled="processing"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors disabled:opacity-50"
          >
            {{ processing ? 'Kaydediliyor...' : 'Villa Oluştur' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted, nextTick } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({
  availableAmenities: Array,
  errors: Object
})

const breadcrumbs = [
  { name: 'Villa Yönetimi', href: route('admin.villas.index') },
  { name: 'Yeni Villa Ekle', href: route('admin.villas.create'), current: true }
]

const form = useForm({
  name: '',
  location: '',
  description: '',
  max_guests: 1,
  bedrooms: 1,
  bathrooms: 1,
  price_per_night: 0,
  cleaning_fee: 0,
  latitude: null,
  longitude: null,
  amenities: [],
  main_image: null,
  gallery_images: [],
  status: 'active'
})

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