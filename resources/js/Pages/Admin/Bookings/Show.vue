<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-start">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Rezervasyon Detayı</h1>
          <p class="text-gray-600">Rezervasyon #{{ booking.id }}</p>
        </div>
        <div class="flex gap-2">
          <button
            v-if="booking.status === 'pending'"
            @click="approveBooking"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
          >
            <CheckIcon class="w-5 h-5" />
            Onayla
          </button>
          <button
            v-if="['pending', 'confirmed'].includes(booking.status)"
            @click="cancelBooking"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
          >
            <XMarkIcon class="w-5 h-5" />
            İptal Et
          </button>
        </div>
      </div>

      <!-- Status Card -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-lg font-semibold text-gray-900">Rezervasyon Durumu</h2>
            <span
              :class="getStatusClass(booking.status)"
              class="inline-flex px-3 py-1 text-sm font-semibold rounded-full mt-2"
            >
              {{ getStatusText(booking.status) }}
            </span>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-600">Oluşturulma Tarihi</p>
            <p class="text-lg font-semibold text-gray-900">{{ formatDate(booking.created_at) }}</p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Müşteri Bilgileri</h2>
          <div class="space-y-4">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-12 w-12">
                <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                  <span class="text-lg font-medium text-gray-700">
                    {{ booking.user.name.charAt(0).toUpperCase() }}
                  </span>
                </div>
              </div>
              <div class="ml-4">
                <div class="text-lg font-medium text-gray-900">{{ booking.user.name }}</div>
                <div class="text-sm text-gray-500">{{ booking.user.email }}</div>
              </div>
            </div>
            <div class="border-t pt-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-sm font-medium text-gray-600">Telefon</p>
                  <p class="text-sm text-gray-900">{{ booking.user.phone || 'Belirtilmemiş' }}</p>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-600">Üyelik Tarihi</p>
                  <p class="text-sm text-gray-900">{{ formatDate(booking.user.created_at) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Villa Information -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Villa Bilgileri</h2>
          <div class="space-y-4">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-16 w-16">
                <img
                  class="h-16 w-16 rounded-lg object-cover"
                  :src="booking.villa.main_image || '/images/villa-placeholder.jpg'"
                  :alt="booking.villa.name"
                />
              </div>
              <div class="ml-4">
                <div class="text-lg font-medium text-gray-900">{{ booking.villa.name }}</div>
                <div class="text-sm text-gray-500">{{ booking.villa.location }}</div>
              </div>
            </div>
            <div class="border-t pt-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-sm font-medium text-gray-600">Kapasite</p>
                  <p class="text-sm text-gray-900">{{ booking.villa.max_guests }} kişi</p>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-600">Yatak Odası</p>
                  <p class="text-sm text-gray-900">{{ booking.villa.bedrooms }} oda</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Booking Details -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Rezervasyon Detayları</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <p class="text-sm font-medium text-gray-600">Giriş Tarihi</p>
            <p class="text-lg font-semibold text-gray-900">{{ formatDate(booking.check_in) }}</p>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-600">Çıkış Tarihi</p>
            <p class="text-lg font-semibold text-gray-900">{{ formatDate(booking.check_out) }}</p>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-600">Gece Sayısı</p>
            <p class="text-lg font-semibold text-gray-900">{{ booking.nights }} gece</p>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-600">Misafir Sayısı</p>
            <p class="text-lg font-semibold text-gray-900">{{ booking.guests }} kişi</p>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-600">Özel İstekler</p>
            <p class="text-sm text-gray-900">{{ booking.special_requests || 'Yok' }}</p>
          </div>
        </div>
      </div>

      <!-- Payment Information -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ödeme Bilgileri</h2>
        <div class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="flex justify-between">
              <span class="text-sm text-gray-600">Villa Ücreti ({{ booking.nights }} gece)</span>
              <span class="text-sm text-gray-900">₺{{ (booking.villa.price_per_night * booking.nights)?.toLocaleString() }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-600">Temizlik Ücreti</span>
              <span class="text-sm text-gray-900">₺{{ booking.villa.cleaning_fee?.toLocaleString() || '0' }}</span>
            </div>
          </div>
          <div class="border-t pt-4">
            <div class="flex justify-between items-center">
              <span class="text-lg font-semibold text-gray-900">Toplam Tutar</span>
              <span class="text-2xl font-bold text-gray-900">₺{{ booking.total_amount?.toLocaleString() }}</span>
            </div>
          </div>
          <div v-if="booking.payment" class="border-t pt-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm font-medium text-gray-600">Ödeme Durumu</p>
                <span
                  :class="getPaymentStatusClass(booking.payment.status)"
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                >
                  {{ getPaymentStatusText(booking.payment.status) }}
                </span>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-600">Ödeme Yöntemi</p>
                <p class="text-sm text-gray-900">{{ booking.payment.payment_method || 'Belirtilmemiş' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Status History -->
      <div v-if="booking.status_logs && booking.status_logs.length > 0" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Durum Geçmişi</h2>
        <div class="space-y-4">
          <div v-for="log in booking.status_logs" :key="log.id" class="flex items-start">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
              </div>
            </div>
            <div class="ml-4 flex-1">
              <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900">
                  {{ getStatusText(log.old_status) }} → {{ getStatusText(log.new_status) }}
                </p>
                <p class="text-sm text-gray-500">{{ formatDate(log.created_at) }}</p>
              </div>
              <p v-if="log.reason" class="text-sm text-gray-600 mt-1">{{ log.reason }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ log.changed_by_name }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex justify-end gap-4">
        <Link
          :href="route('admin.bookings.index')"
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
import { CheckIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  booking: Object
})

const breadcrumbs = [
  { name: 'Rezervasyon Yönetimi', href: route('admin.bookings.index') },
  { name: `Rezervasyon #${props.booking.id}`, href: route('admin.bookings.show', props.booking.id), current: true }
]

const approveBooking = () => {
  if (confirm('Bu rezervasyonu onaylamak istediğinizden emin misiniz?')) {
    router.put(route('admin.bookings.approve', props.booking.id))
  }
}

const cancelBooking = () => {
  const reason = prompt('İptal sebebini belirtiniz:')
  if (reason !== null) {
    router.put(route('admin.bookings.cancel', props.booking.id), {
      reason: reason
    })
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('tr-TR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
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

const getPaymentStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    completed: 'bg-green-100 text-green-800',
    failed: 'bg-red-100 text-red-800',
    refunded: 'bg-gray-100 text-gray-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getPaymentStatusText = (status) => {
  const texts = {
    pending: 'Bekleyen',
    completed: 'Tamamlandı',
    failed: 'Başarısız',
    refunded: 'İade Edildi'
  }
  return texts[status] || status
}
</script>