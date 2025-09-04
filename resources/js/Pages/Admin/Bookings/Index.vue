<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Rezervasyon Yönetimi</h1>
          <p class="text-gray-600">Tüm rezervasyonları görüntüleyin ve yönetin</p>
        </div>
        <div class="flex gap-2">
          <Link
            :href="route('admin.bookings.calendar')"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
          >
            <CalendarIcon class="w-5 h-5" />
            Takvim Görünümü
          </Link>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <CalendarIcon class="w-6 h-6 text-blue-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Toplam Rezervasyon</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats?.total || 0 }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
              <ClockIcon class="w-6 h-6 text-yellow-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Bekleyen</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats?.pending || 0 }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <CheckCircleIcon class="w-6 h-6 text-green-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Onaylı</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats?.confirmed || 0 }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-red-100 rounded-lg">
              <XCircleIcon class="w-6 h-6 text-red-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">İptal</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats?.cancelled || 0 }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Arama</label>
            <input
              v-model="filters.search"
              type="text"
              placeholder="Rezervasyon ID, müşteri adı..."
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
            <select
              v-model="filters.status"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Tümü</option>
              <option value="pending">Bekleyen</option>
              <option value="confirmed">Onaylı</option>
              <option value="cancelled">İptal</option>
              <option value="completed">Tamamlandı</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Başlangıç Tarihi</label>
            <input
              v-model="filters.start_date"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bitiş Tarihi</label>
            <input
              v-model="filters.end_date"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <div class="flex items-end">
            <button
              @click="applyFilters"
              class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors"
            >
              Filtrele
            </button>
          </div>
        </div>
      </div>

      <!-- Bookings Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Rezervasyon
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Müşteri
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Villa
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tarihler
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Misafir
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Toplam
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Durum
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  İşlemler
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="booking in bookings?.data || []" :key="booking.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">#{{ booking.id }}</div>
                  <div class="text-sm text-gray-500">{{ formatDate(booking.created_at) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700">
                          {{ booking.user.name.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ booking.user.name }}</div>
                      <div class="text-sm text-gray-500">{{ booking.user.email }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ booking.villa.name }}</div>
                  <div class="text-sm text-gray-500">{{ booking.villa.location }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ formatDate(booking.check_in) }} - {{ formatDate(booking.check_out) }}
                  </div>
                  <div class="text-sm text-gray-500">{{ booking.nights }} gece</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ booking.guests }} kişi</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">
                    ₺{{ booking.total_amount?.toLocaleString() }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="getStatusClass(booking.status)"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ getStatusText(booking.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center gap-2">
                    <Link
                      :href="route('admin.bookings.show', booking.id)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      <EyeIcon class="w-4 h-4" />
                    </Link>
                    <button
                      v-if="booking.status === 'pending'"
                      @click="approveBooking(booking.id)"
                      class="text-green-600 hover:text-green-900"
                      title="Onayla"
                    >
                      <CheckIcon class="w-4 h-4" />
                    </button>
                    <button
                      v-if="['pending', 'confirmed'].includes(booking.status)"
                      @click="cancelBooking(booking.id)"
                      class="text-red-600 hover:text-red-900"
                      title="İptal Et"
                    >
                      <XMarkIcon class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
          <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <Link
                v-if="bookings?.prev_page_url"
                :href="bookings.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Önceki
              </Link>
              <Link
                v-if="bookings?.next_page_url"
                :href="bookings.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Sonraki
              </Link>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  <span class="font-medium">{{ bookings?.from || 0 }}</span>
                  -
                  <span class="font-medium">{{ bookings?.to || 0 }}</span>
                  arası, toplam
                  <span class="font-medium">{{ bookings?.total || 0 }}</span>
                  sonuç
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <template v-for="link in bookings?.links || []" :key="link.label">
                    <Link
                      v-if="link.url"
                      :href="link.url"
                      :class="{
                        'bg-blue-50 border-blue-500 text-blue-600': link.active,
                        'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active
                      }"
                      class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                      v-html="link.label"
                    />
                    <span
                      v-else
                      :class="{
                        'bg-blue-50 border-blue-500 text-blue-600': link.active,
                        'bg-white border-gray-300 text-gray-500': !link.active
                      }"
                      class="relative inline-flex items-center px-4 py-2 border text-sm font-medium cursor-not-allowed"
                      v-html="link.label"
                    />
                  </template>
                </nav>
              </div>
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
  CalendarIcon,
  ClockIcon,
  CheckCircleIcon,
  XCircleIcon,
  EyeIcon,
  CheckIcon,
  XMarkIcon
} from '@heroicons/vue/24/outline'

defineProps({
  bookings: Object,
  stats: Object,
  filters: Object
})

const breadcrumbs = [
  { name: 'Rezervasyon Yönetimi', href: route('admin.bookings.index'), current: true }
]

const filters = reactive({
  search: '',
  status: '',
  start_date: '',
  end_date: ''
})

const applyFilters = () => {
  router.get(route('admin.bookings.index'), filters, {
    preserveState: true,
    preserveScroll: true
  })
}

const approveBooking = (id) => {
  if (confirm('Bu rezervasyonu onaylamak istediğinizden emin misiniz?')) {
    router.put(route('admin.bookings.approve', id))
  }
}

const cancelBooking = (id) => {
  if (confirm('Bu rezervasyonu iptal etmek istediğinizden emin misiniz?')) {
    router.put(route('admin.bookings.cancel', id))
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('tr-TR')
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    confirmed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
    completed: 'bg-blue-100 text-blue-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  const texts = {
    pending: 'Bekleyen',
    confirmed: 'Onaylı',
    cancelled: 'İptal',
    completed: 'Tamamlandı'
  }
  return texts[status] || status
}
</script>