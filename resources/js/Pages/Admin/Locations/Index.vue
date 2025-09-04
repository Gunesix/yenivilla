<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-7xl mx-auto space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Lokasyon Yönetimi</h1>
          <p class="text-gray-600">Villa lokasyonlarını yönetin</p>
        </div>
        <Link
          :href="route('admin.locations.create')"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center"
        >
          <PlusIcon class="w-5 h-5 mr-2" />
          Yeni Lokasyon
        </Link>
      </div>

      <!-- Locations Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="location in locations"
          :key="location.id"
          class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
        >
          <!-- Location Image -->
          <div class="aspect-video bg-gray-200">
            <img
              :src="location.image"
              :alt="location.name"
              class="w-full h-full object-cover"
            />
          </div>

          <!-- Location Info -->
          <div class="p-6">
            <div class="flex justify-between items-start mb-3">
              <h3 class="text-lg font-semibold text-gray-900">{{ location.name }}</h3>
              <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                {{ location.villa_count }} Villa
              </span>
            </div>
            
            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
              {{ location.description }}
            </p>

            <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
              <span>Slug: {{ location.slug }}</span>
              <span>{{ formatDate(location.updated_at) }}</span>
            </div>

            <!-- Actions -->
            <div class="flex space-x-2">
              <Link
                :href="route('admin.locations.edit', location.id)"
                class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-200 transition-colors text-center text-sm font-medium"
              >
                Düzenle
              </Link>
              <button
                @click="deleteLocation(location.id)"
                class="flex-1 bg-red-100 text-red-700 px-3 py-2 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium"
              >
                Sil
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="locations.length === 0" class="text-center py-12">
        <MapPinIcon class="w-16 h-16 text-gray-400 mx-auto mb-4" />
        <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz lokasyon yok</h3>
        <p class="text-gray-600 mb-6">İlk lokasyonunuzu oluşturmak için başlayın</p>
        <Link
          :href="route('admin.locations.create')"
          class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center"
        >
          <PlusIcon class="w-5 h-5 mr-2" />
          İlk Lokasyonu Oluştur
        </Link>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { PlusIcon, MapPinIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  locations: {
    type: Array,
    default: () => []
  },
  breadcrumbs: {
    type: Array,
    default: () => []
  }
})

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('tr-TR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const deleteLocation = (locationId) => {
  if (confirm('Bu lokasyonu silmek istediğinizden emin misiniz?')) {
    router.delete(route('admin.locations.destroy', locationId), {
      onSuccess: () => {
        // Success message will be handled by the backend
      }
    })
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>