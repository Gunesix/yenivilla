<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-start">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Kullanıcı Detayı</h1>
          <p class="text-gray-600">{{ user.name }} kullanıcı bilgileri</p>
        </div>
        <div class="flex gap-2">
          <button
            @click="toggleUserStatus"
            :class="{
              'bg-red-600 hover:bg-red-700': user.email_verified_at,
              'bg-green-600 hover:bg-green-700': !user.email_verified_at
            }"
            class="text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
          >
            <component :is="user.email_verified_at ? LockClosedIcon : LockOpenIcon" class="w-5 h-5" />
            {{ user.email_verified_at ? 'Deaktif Et' : 'Aktif Et' }}
          </button>
        </div>
      </div>

      <!-- User Information -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Kullanıcı Bilgileri</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-4">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-16 w-16">
                <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                  <span class="text-2xl font-medium text-gray-700">
                    {{ user.name.charAt(0).toUpperCase() }}
                  </span>
                </div>
              </div>
              <div class="ml-4">
                <div class="text-xl font-medium text-gray-900">{{ user.name }}</div>
                <div class="text-sm text-gray-500">Kullanıcı ID: {{ user.id }}</div>
              </div>
            </div>
            <div class="space-y-3">
              <div>
                <p class="text-sm font-medium text-gray-600">E-posta</p>
                <p class="text-sm text-gray-900">{{ user.email }}</p>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-600">Telefon</p>
                <p class="text-sm text-gray-900">{{ user.phone || 'Belirtilmemiş' }}</p>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-600">Durum</p>
                <span
                  :class="{
                    'bg-green-100 text-green-800': user.email_verified_at,
                    'bg-red-100 text-red-800': !user.email_verified_at
                  }"
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                >
                  {{ user.email_verified_at ? 'Aktif' : 'E-posta Doğrulanmamış' }}
                </span>
              </div>
            </div>
          </div>
          <div class="space-y-3">
            <div>
              <p class="text-sm font-medium text-gray-600">Kayıt Tarihi</p>
              <p class="text-sm text-gray-900">{{ formatDate(user.created_at) }}</p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">E-posta Doğrulama</p>
              <p class="text-sm text-gray-900">
                {{ user.email_verified_at ? formatDate(user.email_verified_at) : 'Doğrulanmamış' }}
              </p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Son Giriş</p>
              <p class="text-sm text-gray-900">
                {{ user.last_login_at ? formatDate(user.last_login_at) : 'Hiç giriş yapmamış' }}
              </p>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-600">Son Güncelleme</p>
              <p class="text-sm text-gray-900">{{ formatDate(user.updated_at) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <CalendarIcon class="w-6 h-6 text-blue-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Toplam Rezervasyon</p>
              <p class="text-2xl font-bold text-gray-900">{{ userStats.total_bookings || 0 }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <CurrencyDollarIcon class="w-6 h-6 text-green-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Toplam Harcama</p>
              <p class="text-2xl font-bold text-gray-900">₺{{ userStats.total_spent?.toLocaleString() || '0' }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
              <StarIcon class="w-6 h-6 text-purple-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Ortalama Değerlendirme</p>
              <p class="text-2xl font-bold text-gray-900">{{ user.average_rating || 'N/A' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Bookings -->
      <div v-if="user.recent_bookings && user.recent_bookings.length > 0" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Son Rezervasyonlar</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Rezervasyon
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Villa
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tarihler
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tutar
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
              <tr v-for="booking in user.recent_bookings" :key="booking.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">#{{ booking.id }}</div>
                  <div class="text-sm text-gray-500">{{ formatDate(booking.created_at) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ booking.villa.name }}</div>
                  <div class="text-sm text-gray-500">{{ booking.villa.location }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ formatDate(booking.check_in) }} - {{ formatDate(booking.check_out) }}
                  </div>
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
                  <Link
                    :href="route('admin.bookings.show', booking.id)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    <EyeIcon class="w-4 h-4" />
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Activity Log -->
      <div v-if="user.activity_logs && user.activity_logs.length > 0" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aktivite Geçmişi</h2>
        <div class="space-y-4">
          <div v-for="log in user.activity_logs" :key="log.id" class="flex items-start">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
              </div>
            </div>
            <div class="ml-4 flex-1">
              <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900">{{ log.description }}</p>
                <p class="text-sm text-gray-500">{{ formatDate(log.created_at) }}</p>
              </div>
              <p v-if="log.properties" class="text-sm text-gray-600 mt-1">
                {{ JSON.stringify(log.properties) }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex justify-end gap-4">
        <Link
          :href="route('admin.users.index')"
          class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
        >
          Geri Dön
        </Link>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
  CalendarIcon,
  CurrencyDollarIcon,
  StarIcon,
  EyeIcon,
  LockClosedIcon,
  LockOpenIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  user: Object,
  userStats: Object
})

const breadcrumbs = [
  { name: 'Kullanıcı Yönetimi', href: route('admin.users.index') },
  { name: props.user.name, href: route('admin.users.show', props.user.id), current: true }
]

const toggleUserStatus = () => {
  const action = props.user.email_verified_at ? 'deactivate' : 'activate'
  const message = props.user.email_verified_at 
    ? 'Bu kullanıcıyı deaktif etmek istediğinizden emin misiniz?' 
    : 'Bu kullanıcıyı aktif etmek istediğinizden emin misiniz?'
  
  if (confirm(message)) {
    router.put(route(`admin.users.${action}`, props.user.id))
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('tr-TR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
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