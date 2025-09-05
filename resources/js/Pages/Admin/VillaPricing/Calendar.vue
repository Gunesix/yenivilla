<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-7xl mx-auto space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Villa Fiyat Takvimi</h1>
          <p class="text-gray-600">Villa fiyatlandırmalarını takvim görünümünde yönetin</p>
        </div>
        <Link
          :href="route('admin.villa-pricing.create')"
          class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium"
        >
          + Yeni Fiyatlandırma
        </Link>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Villa</label>
            <select
              v-model="filters.villa_id"
              @change="applyFilters"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Tüm Villalar</option>
              <option v-for="villa in villas" :key="villa.id" :value="villa.id">
                {{ villa.title }}
              </option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Ay/Yıl</label>
            <input
              v-model="filters.month"
              type="month"
              @change="applyFilters"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
            <select
              v-model="filters.status"
              @change="applyFilters"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Tüm Durumlar</option>
              <option value="active">Aktif</option>
              <option value="inactive">Pasif</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Calendar View -->
      <div class="bg-white rounded-lg shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900">{{ currentMonthYear }}</h2>
            <div class="flex gap-2">
              <button
                @click="previousMonth"
                class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
              >
                ← Önceki
              </button>
              <button
                @click="nextMonth"
                class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
              >
                Sonraki →
              </button>
            </div>
          </div>
        </div>
        
        <div class="p-6">
          <!-- Calendar Header -->
          <div class="grid grid-cols-7 gap-1 mb-4">
            <div v-for="day in weekDays" :key="day" class="p-3 text-center font-medium text-gray-700 bg-gray-50 rounded">
              {{ day }}
            </div>
          </div>
          
          <!-- Calendar Body -->
          <div class="grid grid-cols-7 gap-1">
            <div
              v-for="(day, index) in calendarDays"
              :key="index"
              class="min-h-[120px] border border-gray-200 rounded-lg p-2"
              :class="{
                'bg-gray-50': !day.isCurrentMonth,
                'bg-blue-50 border-blue-200': day.isToday
              }"
            >
              <div class="flex justify-between items-start mb-2">
                <span
                  class="text-sm font-medium"
                  :class="{
                    'text-gray-400': !day.isCurrentMonth,
                    'text-blue-600': day.isToday,
                    'text-gray-900': day.isCurrentMonth && !day.isToday
                  }"
                >
                  {{ day.date }}
                </span>
              </div>
              
              <!-- Pricing Items -->
              <div class="space-y-1">
                <div
                  v-for="pricing in day.pricings"
                  :key="pricing.id"
                  class="text-xs p-1 rounded cursor-pointer transition-colors"
                  :class="{
                    'bg-green-100 text-green-800 hover:bg-green-200': pricing.is_active,
                    'bg-red-100 text-red-800 hover:bg-red-200': !pricing.is_active
                  }"
                  @click="editPricing(pricing)"
                  :title="`${pricing.villa_title} - ${formatCurrency(pricing.price_per_night)} (${pricing.season_name || 'Sezon belirtilmemiş'})`"
                >
                  <div class="font-medium truncate">{{ pricing.villa_title }}</div>
                  <div>{{ formatCurrency(pricing.price_per_night) }}</div>
                  <div v-if="pricing.season_name" class="text-xs opacity-75">{{ pricing.season_name }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Legend -->
      <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Açıklamalar</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="flex items-center">
            <div class="w-4 h-4 bg-green-100 border border-green-200 rounded mr-2"></div>
            <span class="text-sm text-gray-700">Aktif Fiyatlandırma</span>
          </div>
          <div class="flex items-center">
            <div class="w-4 h-4 bg-red-100 border border-red-200 rounded mr-2"></div>
            <span class="text-sm text-gray-700">Pasif Fiyatlandırma</span>
          </div>
          <div class="flex items-center">
            <div class="w-4 h-4 bg-blue-50 border border-blue-200 rounded mr-2"></div>
            <span class="text-sm text-gray-700">Bugün</span>
          </div>
          <div class="flex items-center">
            <div class="w-4 h-4 bg-gray-50 border border-gray-200 rounded mr-2"></div>
            <span class="text-sm text-gray-700">Diğer Aylar</span>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  villas: Array,
  pricings: Array,
  filters: Object
})

const breadcrumbs = [
  { name: 'Villa Fiyat Yönetimi', href: route('admin.villa-pricing.index') },
  { name: 'Fiyat Takvimi', current: true }
]

const currentDate = ref(new Date())
const filters = ref({
  villa_id: props.filters?.villa_id || '',
  month: props.filters?.month || getCurrentMonth(),
  status: props.filters?.status || ''
})

const weekDays = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz']

function getCurrentMonth() {
  const now = new Date()
  return `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`
}

const currentMonthYear = computed(() => {
  const [year, month] = filters.value.month.split('-')
  const date = new Date(year, month - 1)
  return date.toLocaleDateString('tr-TR', { year: 'numeric', month: 'long' })
})

const calendarDays = computed(() => {
  const [year, month] = filters.value.month.split('-')
  const firstDay = new Date(year, month - 1, 1)
  const lastDay = new Date(year, month, 0)
  const startDate = new Date(firstDay)
  
  // Pazartesi'den başlayacak şekilde ayarla
  const dayOfWeek = firstDay.getDay()
  const mondayOffset = dayOfWeek === 0 ? 6 : dayOfWeek - 1
  startDate.setDate(firstDay.getDate() - mondayOffset)
  
  const days = []
  const today = new Date()
  
  for (let i = 0; i < 42; i++) { // 6 hafta x 7 gün
    const currentDay = new Date(startDate)
    currentDay.setDate(startDate.getDate() + i)
    
    const dayPricings = props.pricings.filter(pricing => {
      const startDate = new Date(pricing.start_date)
      const endDate = new Date(pricing.end_date)
      return currentDay >= startDate && currentDay <= endDate
    })
    
    days.push({
      date: currentDay.getDate(),
      fullDate: currentDay.toISOString().split('T')[0],
      isCurrentMonth: currentDay.getMonth() === firstDay.getMonth(),
      isToday: currentDay.toDateString() === today.toDateString(),
      pricings: dayPricings
    })
  }
  
  return days
})

const previousMonth = () => {
  const [year, month] = filters.value.month.split('-')
  const date = new Date(year, month - 1, 1)
  date.setMonth(date.getMonth() - 1)
  filters.value.month = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`
  applyFilters()
}

const nextMonth = () => {
  const [year, month] = filters.value.month.split('-')
  const date = new Date(year, month - 1, 1)
  date.setMonth(date.getMonth() + 1)
  filters.value.month = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`
  applyFilters()
}

const applyFilters = () => {
  router.get(route('admin.villa-pricing.calendar'), filters.value, {
    preserveState: true,
    preserveScroll: true
  })
}

const editPricing = (pricing) => {
  router.visit(route('admin.villa-pricing.edit', pricing.id))
}

const formatCurrency = (amount) => {
  if (!amount) return '₺0'
  return new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount)
}

onMounted(() => {
  if (!filters.value.month) {
    filters.value.month = getCurrentMonth()
  }
})
</script>