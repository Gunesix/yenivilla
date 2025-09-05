<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-7xl mx-auto space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Villa Uygunluk Yönetimi</h1>
          <p class="text-gray-600">Villa müsaitlik durumlarını yönetin</p>
        </div>
        <div class="flex gap-3">
          <Link
            :href="route('admin.villa-availability.calendar')"
            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium"
          >
            📅 Takvim Görünümü
          </Link>
          <Link
            :href="route('admin.villa-availability.create')"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium"
          >
            + Yeni Uygunluk
          </Link>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
        <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Villa</label>
            <select
              v-model="filters.villa_id"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Tüm Villalar</option>
              <option v-for="villa in villas" :key="villa.id" :value="villa.id">
                {{ villa.title }}
              </option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Başlangıç Tarihi</label>
            <input
              v-model="filters.start_date"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bitiş Tarihi</label>
            <input
              v-model="filters.end_date"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
            <select
              v-model="filters.status"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Tüm Durumlar</option>
              <option value="available">Müsait</option>
              <option value="unavailable">Müsait Değil</option>
            </select>
          </div>
          
          <div class="flex items-end gap-2">
            <button
              type="submit"
              class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium"
            >
              Filtrele
            </button>
            <button
              type="button"
              @click="clearFilters"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
            >
              Temizle
            </button>
          </div>
        </form>
      </div>

      <!-- Availability List -->
      <div class="bg-white rounded-lg shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-bold text-gray-900">Uygunluk Kayıtları</h2>
          <p class="text-sm text-gray-600 mt-1">Toplam {{ availabilities.total }} kayıt</p>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Villa
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tarih
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Durum
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Sebep
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Notlar
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  İşlemler
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="availabilities.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                  <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-lg font-medium">Henüz uygunluk kaydı bulunmuyor</p>
                    <p class="text-sm text-gray-400 mt-1">İlk uygunluk kaydınızı oluşturmak için "Yeni Uygunluk" butonuna tıklayın</p>
                  </div>
                </td>
              </tr>
              <tr v-for="availability in availabilities.data" :key="availability.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                      <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                      </svg>
                    </div>
                    <div>
                      <div class="text-sm font-medium text-gray-900">{{ availability.villa.title }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ formatDate(availability.date) }}</div>
                  <div class="text-xs text-gray-500">{{ formatDayName(availability.date) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="{
                      'bg-green-100 text-green-800': availability.is_available,
                      'bg-red-100 text-red-800': !availability.is_available
                    }"
                  >
                    {{ availability.is_available ? 'Müsait' : 'Müsait Değil' }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 max-w-xs truncate" :title="availability.reason">
                    {{ availability.reason || '-' }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 max-w-xs truncate" :title="availability.notes">
                    {{ availability.notes || '-' }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end gap-2">
                    <Link
                      :href="route('admin.villa-availability.edit', availability.id)"
                      class="text-blue-600 hover:text-blue-900 transition-colors"
                      title="Düzenle"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </Link>
                    <button
                      @click="deleteAvailability(availability)"
                      class="text-red-600 hover:text-red-900 transition-colors"
                      title="Sil"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div v-if="availabilities.last_page > 1" class="px-6 py-4 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
              {{ availabilities.from }}-{{ availabilities.to }} arası, toplam {{ availabilities.total }} kayıt
            </div>
            <div class="flex gap-2">
              <Link
                v-for="link in availabilities.links"
                :key="link.label"
                :href="link.url"
                :class="[
                  'px-3 py-2 text-sm rounded-lg transition-colors',
                  link.active
                    ? 'bg-blue-600 text-white'
                    : link.url
                    ? 'border border-gray-300 text-gray-700 hover:bg-gray-50'
                    : 'border border-gray-300 text-gray-400 cursor-not-allowed'
                ]"
                v-html="link.label"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  availabilities: Object,
  villas: Array,
  filters: Object
})

const breadcrumbs = [
  { name: 'Villa Uygunluk Yönetimi', current: true }
]

const filters = ref({
  villa_id: props.filters?.villa_id || '',
  start_date: props.filters?.start_date || '',
  end_date: props.filters?.end_date || '',
  status: props.filters?.status || ''
})

const applyFilters = () => {
  router.get(route('admin.villa-availability.index'), filters.value, {
    preserveState: true,
    preserveScroll: true
  })
}

const clearFilters = () => {
  filters.value = {
    villa_id: '',
    start_date: '',
    end_date: '',
    status: ''
  }
  applyFilters()
}

const deleteAvailability = (availability) => {
  if (confirm(`${availability.villa.title} - ${formatDate(availability.date)} tarihli uygunluk kaydını silmek istediğinizden emin misiniz?`)) {
    router.delete(route('admin.villa-availability.destroy', availability.id))
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('tr-TR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatDayName = (date) => {
  return new Date(date).toLocaleDateString('tr-TR', {
    weekday: 'long'
  })
}
</script>