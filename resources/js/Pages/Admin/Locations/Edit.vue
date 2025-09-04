<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Lokasyon Düzenle</h1>
        <p class="text-gray-600">{{ location.name }} lokasyonu bilgilerini güncelleyin</p>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form @submit.prevent="submitForm" class="p-6 space-y-6">
          <!-- Location Name -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
              Lokasyon Adı *
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Örn: Bodrum, Antalya, Kaş"
              @input="generateSlug"
            />
            <div v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name }}</div>
          </div>

          <!-- Slug -->
          <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
              URL Slug *
            </label>
            <input
              id="slug"
              v-model="form.slug"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Örn: bodrum, antalya, kas"
            />
            <p class="text-gray-500 text-sm mt-1">
              URL'de görünecek kısa ad
            </p>
            <div v-if="errors.slug" class="text-red-600 text-sm mt-1">{{ errors.slug }}</div>
          </div>

          <!-- Description -->
          <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
              Açıklama *
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="4"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Lokasyon hakkında kısa bir açıklama yazın..."
            ></textarea>
            <div v-if="errors.description" class="text-red-600 text-sm mt-1">{{ errors.description }}</div>
          </div>

          <!-- Current Image -->
          <div v-if="location.image">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Mevcut Görsel
            </label>
            <div class="mb-4">
              <img :src="location.image" :alt="location.name" class="h-32 w-48 object-cover border border-gray-300 rounded">
            </div>
          </div>

          <!-- Image Upload -->
          <div>
            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
              {{ location.image ? 'Yeni Görsel Yükle' : 'Lokasyon Görseli' }}
            </label>
            <div class="flex items-center space-x-4">
              <!-- New Image Preview -->
              <div v-if="imagePreview" class="flex-shrink-0">
                <img :src="imagePreview" alt="Yeni Önizleme" class="h-20 w-32 object-cover border border-gray-300 rounded">
              </div>
              
              <!-- Upload Input -->
              <div class="flex-1">
                <input
                  ref="imageInput"
                  type="file"
                  accept="image/*"
                  @change="handleImageUpload"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
                <p class="text-gray-500 text-sm mt-1">
                  JPG, PNG, GIF formatları desteklenir (Max: 2MB)
                  {{ location.image ? ' - Boş bırakırsanız mevcut görsel korunur' : '' }}
                </p>
              </div>
            </div>
            <div v-if="errors.image" class="text-red-600 text-sm mt-1">{{ errors.image }}</div>
          </div>

          <!-- Villa Count Info -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-sm font-medium text-gray-700 mb-2">İstatistikler</h4>
            <p class="text-sm text-gray-600">
              Bu lokasyonda <strong>{{ location.villa_count }}</strong> villa bulunmaktadır.
            </p>
          </div>

          <!-- Form Actions -->
          <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <Link
              :href="route('admin.locations.index')"
              class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
              İptal
            </Link>
            <button
              type="submit"
              :disabled="processing"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="processing">Güncelleniyor...</span>
              <span v-else>Lokasyonu Güncelle</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  location: {
    type: Object,
    required: true
  },
  breadcrumbs: {
    type: Array,
    default: () => []
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const form = reactive({
  name: '',
  slug: '',
  description: '',
  image: null
})

const processing = ref(false)
const imagePreview = ref(null)
const imageInput = ref(null)

// Initialize form with location data
onMounted(() => {
  form.name = props.location.name
  form.slug = props.location.slug
  form.description = props.location.description
})

const generateSlug = () => {
  if (form.name) {
    form.slug = form.name
      .toLowerCase()
      .replace(/ğ/g, 'g')
      .replace(/ü/g, 'u')
      .replace(/ş/g, 's')
      .replace(/ı/g, 'i')
      .replace(/ö/g, 'o')
      .replace(/ç/g, 'c')
      .replace(/[^a-z0-9]/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-|-$/g, '')
  }
}

const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.image = file
    
    // Create preview
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const submitForm = () => {
  processing.value = true
  
  const formData = new FormData()
  formData.append('_method', 'PUT')
  formData.append('name', form.name)
  formData.append('slug', form.slug)
  formData.append('description', form.description)
  if (form.image) {
    formData.append('image', form.image)
  }

  router.post(route('admin.locations.update', props.location.id), formData, {
    onSuccess: () => {
      // Success handled by backend redirect
    },
    onError: () => {
      processing.value = false
    },
    onFinish: () => {
      processing.value = false
    }
  })
}
</script>