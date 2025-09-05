<template>
  <AdminLayout :breadcrumbs="[{ name: 'Villa Fiyat Yönetimi', current: true }]">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Villa Fiyat Yönetimi</h1>
          <p class="text-gray-600">Villa'ların dönemsel fiyatlarını yönetin</p>
        </div>
        <Link
          :href="route('admin.villa-pricing.create')"
          class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors"
        >
          <PlusIcon class="w-5 h-5 mr-2" />
          Yeni Fiyatlandırma
        </Link>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Filtreler</h3>
        <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Villa</label>
            <select
              v-model="filterForm.villa_id"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
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
              v-model="filterForm.date_from"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bitiş Tarihi</label>
            <input
              v-model="filterForm.date_to"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
            <select
              v-model="filterForm.status"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Tümü</option>
              <option value="active">Aktif</option>
              <option value="inactive">Pasif</option>
            </select>
          </div>
          
          <div class="md:col-span-4 flex gap-2">
            <button
              type="submit"
              class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors"
            >
              Filtrele
            </button>
            <button
              type="button"
              @click="clearFilters"
              class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-md transition-colors"
            >
              Temizle
            </button>
          </div>
        </form>
      </div>

      <!-- Pricing List -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">Fiyatlandırmalar</h3>
        </div>
        
        <div v-if="pricings.data.length === 0" class="p-6 text-center text-gray-500">
          <CalendarDaysIcon class="w-12 h-12 mx-auto mb-4 text-gray-400" />
          <p>Henüz fiyatlandırma bulunmuyor.</p>
        </div>
        
        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Villa
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Sezon
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tarih Aralığı
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Gecelik Fiyat
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Min. Konaklama
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Durum
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  İşlemler
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="pricing in pricings.data" :key="pricing.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ pricing.villa.title }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ pricing.season_name || '-' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ formatDate(pricing.start_date) }} - {{ formatDate(pricing.end_date) }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ formatCurrency(pricing.price_per_night) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ pricing.minimum_stay }} gece</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    :class="{
                      'bg-green-100 text-green-800': pricing.is_active,
                      'bg-red-100 text-red-800': !pricing.is_active
                    }"
                  >
                    {{ pricing.is_active ? 'Aktif' : 'Pasif' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <Link
                      :href="route('admin.villa-pricing.edit', pricing.id)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      <PencilIcon class="w-4 h-4" />
                    </Link>
                    <button
                      @click="deletePricing(pricing)"
                      class="text-red-600 hover:text-red-900"
                    >
                      <TrashIcon class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div v-if="pricings.data.length > 0" class="px-6 py-4 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
              Toplam {{ pricings.total }} kayıt, {{ pricings.from }}-{{ pricings.to }} arası gösteriliyor
            </div>
            <div class="flex space-x-1">
              <Link
                v-for="link in pricings.links"
                :key="link.label"
                :href="link.url"
                v-html="link.label"
                class="px-3 py-2 text-sm border rounded-md transition-colors"
                :class="{
                  'bg-blue-600 text-white border-blue-600': link.active,
                  'text-gray-500 border-gray-300 hover:bg-gray-50': !link.active && link.url,
                  'text-gray-300 border-gray-300 cursor-not-allowed': !link.url
                }"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
  PlusIcon,
  CalendarDaysIcon,
  PencilIcon,
  TrashIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  pricings: Object,
  villas: Array,
  filters: Object,
  auth: Object
})

const filterForm = reactive({
  villa_id: props.filters.villa_id || '',
  date_from: props.filters.date_from || '',
  date_to: props.filters.date_to || '',
  status: props.filters.status || ''
})

const applyFilters = () => {
  router.get(route('admin.villa-pricing.index'), filterForm, {
    preserveState: true,
    preserveScroll: true
  })
}

const clearFilters = () => {
  Object.keys(filterForm).forEach(key => {
    filterForm[key] = ''
  })
  applyFilters()
}

const deletePricing = (pricing) => {
  if (confirm('Bu fiyatlandırmayı silmek istediğinizden emin misiniz?')) {
    router.delete(route('admin.villa-pricing.destroy', pricing.id))
  }
}

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('tr-TR')
}

const formatCurrency = (amount) => {
  if (!amount) return '₺0'
  return new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY'
  }).format(amount)
}
</script>