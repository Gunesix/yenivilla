<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Ödeme Yönetimi</h1>
          <p class="text-gray-600">Tüm ödemeleri görüntüleyin ve yönetin</p>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <CurrencyDollarIcon class="w-6 h-6 text-blue-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Toplam Gelir</p>
              <p class="text-2xl font-bold text-gray-900">₺{{ stats.total_revenue?.toLocaleString() }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <CheckCircleIcon class="w-6 h-6 text-green-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Tamamlanan</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.completed }}</p>
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
              <p class="text-2xl font-bold text-gray-900">{{ stats.pending }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-red-100 rounded-lg">
              <ArrowUturnLeftIcon class="w-6 h-6 text-red-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">İade Edilen</p>
              <p class="text-2xl font-bold text-gray-900">₺{{ stats.refunded_amount?.toLocaleString() }}</p>
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
              placeholder="Ödeme ID, müşteri adı..."
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
              <option value="completed">Tamamlandı</option>
              <option value="failed">Başarısız</option>
              <option value="refunded">İade Edildi</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Ödeme Yöntemi</label>
            <select
              v-model="filters.payment_method"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Tümü</option>
              <option value="credit_card">Kredi Kartı</option>
              <option value="bank_transfer">Banka Havalesi</option>
              <option value="cash">Nakit</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tarih Aralığı</label>
            <select
              v-model="filters.date_range"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Tümü</option>
              <option value="today">Bugün</option>
              <option value="week">Bu Hafta</option>
              <option value="month">Bu Ay</option>
              <option value="year">Bu Yıl</option>
            </select>
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

      <!-- Payments Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Ödeme
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Rezervasyon
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Müşteri
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tutar
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Yöntem
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Durum
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tarih
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  İşlemler
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="payment in payments.data" :key="payment.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">#{{ payment.id }}</div>
                  <div class="text-sm text-gray-500">{{ payment.transaction_id || 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <Link
                    :href="route('admin.bookings.show', payment.booking.id)"
                    class="text-sm font-medium text-blue-600 hover:text-blue-900"
                  >
                    #{{ payment.booking.id }}
                  </Link>
                  <div class="text-sm text-gray-500">{{ payment.booking.villa.name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                      <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-xs font-medium text-gray-700">
                          {{ payment.booking.user.name.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium text-gray-900">{{ payment.booking.user.name }}</div>
                      <div class="text-sm text-gray-500">{{ payment.booking.user.email }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">
                    ₺{{ payment.amount?.toLocaleString() }}
                  </div>
                  <div v-if="payment.refunded_amount > 0" class="text-sm text-red-600">
                    İade: ₺{{ payment.refunded_amount?.toLocaleString() }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ getPaymentMethodText(payment.payment_method) }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="getStatusClass(payment.status)"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ getStatusText(payment.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ formatDate(payment.created_at) }}</div>
                  <div v-if="payment.completed_at" class="text-sm text-gray-500">
                    Tamamlandı: {{ formatDate(payment.completed_at) }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center gap-2">
                    <button
                      @click="viewPayment(payment.id)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      <EyeIcon class="w-4 h-4" />
                    </button>
                    <button
                      v-if="payment.status === 'completed' && payment.refunded_amount < payment.amount"
                      @click="refundPayment(payment.id)"
                      class="text-red-600 hover:text-red-900"
                      title="İade Et"
                    >
                      <ArrowUturnLeftIcon class="w-4 h-4" />
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
                v-if="payments.prev_page_url"
                :href="payments.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Önceki
              </Link>
              <Link
                v-if="payments.next_page_url"
                :href="payments.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Sonraki
              </Link>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  <span class="font-medium">{{ payments.from }}</span>
                  -
                  <span class="font-medium">{{ payments.to }}</span>
                  arası, toplam
                  <span class="font-medium">{{ payments.total }}</span>
                  sonuç
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <template v-for="link in payments.links" :key="link.label">
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

    <!-- Payment Detail Modal -->
    <div v-if="selectedPayment" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click="closeModal">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" @click.stop>
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Ödeme Detayı</h3>
            <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
              <XMarkIcon class="w-6 h-6" />
            </button>
          </div>
          <div class="space-y-3">
            <div>
              <p class="text-sm font-medium text-gray-600">Ödeme ID</p>
              <p class="text-sm text-gray-900">#{{ selectedPayment.id }}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">İşlem ID</p>
              <p class="text-sm text-gray-900">{{ selectedPayment.transaction_id || 'N/A' }}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Tutar</p>
              <p class="text-sm text-gray-900">₺{{ selectedPayment.amount?.toLocaleString() }}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Durum</p>
              <span
                :class="getStatusClass(selectedPayment.status)"
                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
              >
                {{ getStatusText(selectedPayment.status) }}
              </span>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Ödeme Yöntemi</p>
              <p class="text-sm text-gray-900">{{ getPaymentMethodText(selectedPayment.payment_method) }}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Oluşturulma Tarihi</p>
              <p class="text-sm text-gray-900">{{ formatDate(selectedPayment.created_at) }}</p>
            </div>
            <div v-if="selectedPayment.completed_at">
              <p class="text-sm font-medium text-gray-600">Tamamlanma Tarihi</p>
              <p class="text-sm text-gray-900">{{ formatDate(selectedPayment.completed_at) }}</p>
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
  CurrencyDollarIcon,
  CheckCircleIcon,
  ClockIcon,
  ArrowUturnLeftIcon,
  EyeIcon,
  XMarkIcon
} from '@heroicons/vue/24/outline'

defineProps({
  payments: Object,
  stats: Object,
  filters: Object
})

const breadcrumbs = [
  { name: 'Ödeme Yönetimi', href: route('admin.payments.index'), current: true }
]

const filters = reactive({
  search: '',
  status: '',
  payment_method: '',
  date_range: ''
})

const selectedPayment = ref(null)

const applyFilters = () => {
  router.get(route('admin.payments.index'), filters, {
    preserveState: true,
    preserveScroll: true
  })
}

const viewPayment = (id) => {
  // Find payment in current data or fetch from API
  const payment = payments.data.find(p => p.id === id)
  selectedPayment.value = payment
}

const closeModal = () => {
  selectedPayment.value = null
}

const refundPayment = (id) => {
  const amount = prompt('İade edilecek tutarı girin (₺):')
  if (amount && !isNaN(amount) && parseFloat(amount) > 0) {
    const reason = prompt('İade sebebini belirtiniz:')
    if (reason !== null) {
      router.post(route('admin.payments.refund', id), {
        amount: parseFloat(amount),
        reason: reason
      })
    }
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('tr-TR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    completed: 'bg-green-100 text-green-800',
    failed: 'bg-red-100 text-red-800',
    refunded: 'bg-gray-100 text-gray-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  const texts = {
    pending: 'Bekleyen',
    completed: 'Tamamlandı',
    failed: 'Başarısız',
    refunded: 'İade Edildi'
  }
  return texts[status] || status
}

const getPaymentMethodText = (method) => {
  const methods = {
    credit_card: 'Kredi Kartı',
    bank_transfer: 'Banka Havalesi',
    cash: 'Nakit'
  }
  return methods[method] || method
}
</script>