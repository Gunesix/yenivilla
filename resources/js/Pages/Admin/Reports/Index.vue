<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Raporlar</h1>
          <p class="text-gray-600">İşletme performansını analiz edin</p>
        </div>
        <div class="flex gap-2">
          <button
            @click="exportReport('pdf')"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
          >
            <DocumentArrowDownIcon class="w-5 h-5" />
            PDF İndir
          </button>
          <button
            @click="exportReport('excel')"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
          >
            <DocumentArrowDownIcon class="w-5 h-5" />
            Excel İndir
          </button>
        </div>
      </div>

      <!-- Date Range Filter -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Hızlı Seçim</label>
            <select
              v-model="filters.quick_range"
              @change="applyQuickRange"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Özel Tarih</option>
              <option value="today">Bugün</option>
              <option value="week">Bu Hafta</option>
              <option value="month">Bu Ay</option>
              <option value="quarter">Bu Çeyrek</option>
              <option value="year">Bu Yıl</option>
            </select>
          </div>
          <div class="flex items-end">
            <button
              @click="loadReports"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors"
            >
              Rapor Oluştur
            </button>
          </div>
        </div>
      </div>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <CurrencyDollarIcon class="w-6 h-6 text-blue-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Toplam Gelir</p>
              <p class="text-2xl font-bold text-gray-900">₺{{ summary?.total_revenue?.toLocaleString() || '0' }}</p>
              <p class="text-sm text-gray-500">{{ summary?.revenue_change || '0' }}% önceki döneme göre</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <CalendarIcon class="w-6 h-6 text-green-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Toplam Rezervasyon</p>
              <p class="text-2xl font-bold text-gray-900">{{ summary?.total_bookings || '0' }}</p>
              <p class="text-sm text-gray-500">{{ summary?.bookings_change || '0' }}% önceki döneme göre</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
              <ChartBarIcon class="w-6 h-6 text-purple-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Ortalama Rezervasyon</p>
              <p class="text-2xl font-bold text-gray-900">₺{{ summary?.average_booking?.toLocaleString() || '0' }}</p>
              <p class="text-sm text-gray-500">{{ summary?.average_change || '0' }}% önceki döneme göre</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
              <HomeIcon class="w-6 h-6 text-yellow-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Doluluk Oranı</p>
              <p class="text-2xl font-bold text-gray-900">{{ summary?.occupancy_rate || '0' }}%</p>
              <p class="text-sm text-gray-500">{{ summary?.occupancy_change || '0' }}% önceki döneme göre</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Gelir Trendi</h2>
          <div class="h-80">
            <canvas ref="revenueChart"></canvas>
          </div>
        </div>

        <!-- Bookings Chart -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Rezervasyon Trendi</h2>
          <div class="h-80">
            <canvas ref="bookingsChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Top Performing Villas -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">En Çok Tercih Edilen Villalar</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Villa
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Rezervasyon
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Gelir
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Doluluk
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Ortalama Değerlendirme
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="villa in topVillas" :key="villa.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <img
                        class="h-10 w-10 rounded-lg object-cover"
                        :src="villa.main_image || '/images/villa-placeholder.jpg'"
                        :alt="villa.name"
                      />
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ villa.name }}</div>
                      <div class="text-sm text-gray-500">{{ villa.location }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ villa.bookings_count }} rezervasyon</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">
                    ₺{{ villa.total_revenue?.toLocaleString() }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ villa.occupancy_rate }}%</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <StarIcon class="w-4 h-4 text-yellow-400 mr-1" />
                    <span class="text-sm text-gray-900">{{ villa.average_rating || 'N/A' }}</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Monthly Breakdown -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aylık Detay</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Ay
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Rezervasyon
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Gelir
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Ortalama Rezervasyon
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Doluluk Oranı
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="month in monthlyData" :key="month.month" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ month.month_name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ month.bookings_count }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">
                    ₺{{ month.revenue?.toLocaleString() }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    ₺{{ month.average_booking?.toLocaleString() }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ month.occupancy_rate }}%</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted, nextTick } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
  CurrencyDollarIcon,
  CalendarIcon,
  ChartBarIcon,
  HomeIcon,
  DocumentArrowDownIcon,
  StarIcon
} from '@heroicons/vue/24/outline'
import Chart from 'chart.js/auto'

const props = defineProps({
  summary: Object,
  topVillas: Array,
  monthlyData: Array,
  chartData: Object
})

const breadcrumbs = [
  { name: 'Raporlar', href: route('admin.reports.index'), current: true }
]

const filters = reactive({
  start_date: '',
  end_date: '',
  quick_range: 'month'
})

const revenueChart = ref(null)
const bookingsChart = ref(null)
let revenueChartInstance = null
let bookingsChartInstance = null

onMounted(() => {
  setDefaultDates()
  nextTick(() => {
    initCharts()
  })
})

const setDefaultDates = () => {
  const today = new Date()
  const firstDay = new Date(today.getFullYear(), today.getMonth(), 1)
  const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0)
  
  filters.start_date = firstDay.toISOString().split('T')[0]
  filters.end_date = lastDay.toISOString().split('T')[0]
}

const applyQuickRange = () => {
  const today = new Date()
  let startDate, endDate
  
  switch (filters.quick_range) {
    case 'today':
      startDate = endDate = today
      break
    case 'week':
      startDate = new Date(today.setDate(today.getDate() - today.getDay()))
      endDate = new Date(today.setDate(today.getDate() - today.getDay() + 6))
      break
    case 'month':
      startDate = new Date(today.getFullYear(), today.getMonth(), 1)
      endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0)
      break
    case 'quarter':
      const quarter = Math.floor(today.getMonth() / 3)
      startDate = new Date(today.getFullYear(), quarter * 3, 1)
      endDate = new Date(today.getFullYear(), quarter * 3 + 3, 0)
      break
    case 'year':
      startDate = new Date(today.getFullYear(), 0, 1)
      endDate = new Date(today.getFullYear(), 11, 31)
      break
    default:
      return
  }
  
  filters.start_date = startDate.toISOString().split('T')[0]
  filters.end_date = endDate.toISOString().split('T')[0]
}

const loadReports = () => {
  router.get(route('admin.reports.index'), filters, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      nextTick(() => {
        updateCharts()
      })
    }
  })
}

const exportReport = (format) => {
  const params = { ...filters, format }
  window.open(route('admin.reports.export', params))
}

const initCharts = () => {
  if (props.chartData) {
    createRevenueChart()
    createBookingsChart()
  }
}

const updateCharts = () => {
  if (revenueChartInstance) {
    revenueChartInstance.destroy()
  }
  if (bookingsChartInstance) {
    bookingsChartInstance.destroy()
  }
  initCharts()
}

const createRevenueChart = () => {
  const ctx = revenueChart.value.getContext('2d')
  revenueChartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: props.chartData.revenue.labels,
      datasets: [{
        label: 'Gelir (₺)',
        data: props.chartData.revenue.data,
        borderColor: 'rgb(59, 130, 246)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return '₺' + value.toLocaleString()
            }
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  })
}

const createBookingsChart = () => {
  const ctx = bookingsChart.value.getContext('2d')
  bookingsChartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: props.chartData.bookings.labels,
      datasets: [{
        label: 'Rezervasyon Sayısı',
        data: props.chartData.bookings.data,
        backgroundColor: 'rgba(34, 197, 94, 0.8)',
        borderColor: 'rgb(34, 197, 94)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  })
}
</script>