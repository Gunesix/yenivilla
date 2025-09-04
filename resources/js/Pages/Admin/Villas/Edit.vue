<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Villa Düzenle</h1>
        <p class="text-gray-600">{{ villa.name }} villa bilgilerini güncelleyin</p>
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
            {{ processing ? 'Güncelleniyor...' : 'Villa Güncelle' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  villa: Object,
  availableAmenities: Array,
  errors: Object
})

const breadcrumbs = [
  { name: 'Villa Yönetimi', href: route('admin.villas.index') },
  { name: props.villa.name, href: route('admin.villas.edit', props.villa.id), current: true }
]

const form = useForm({
  name: props.villa.name,
  location: props.villa.location,
  description: props.villa.description,
  max_guests: props.villa.max_guests,
  bedrooms: props.villa.bedrooms,
  bathrooms: props.villa.bathrooms,
  price_per_night: props.villa.price_per_night,
  cleaning_fee: props.villa.cleaning_fee || 0,
  amenities: props.villa.amenities?.map(a => a.id) || [],
  main_image: null,
  gallery_images: [],
  status: props.villa.status,
  remove_main_image: false,
  remove_gallery_images: []
})

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

const submit = () => {
  form.post(route('admin.villas.update', props.villa.id), {
    forceFormData: true
  })
}
</script>