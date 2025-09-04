<template>
  <AuthenticatedLayout>
    <Head title="Rezervasyonlarım" />
    
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-6">
              <h1 class="text-2xl font-bold text-gray-900">Rezervasyonlarım</h1>
            </div>

            <!-- Rezervasyon Listesi -->
            <div v-if="bookings.data.length > 0" class="space-y-6">
              <div 
                v-for="booking in bookings.data" 
                :key="booking.id"
                class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow"
              >
                <div class="p-6">
                  <div class="flex justify-between items-start">
                    <div class="flex-1">
                      <div class="flex items-center space-x-4">
                        <img 
                          v-if="booking.villa.images && booking.villa.images.length > 0"
                          :src="booking.villa.images[0].image_url" 
                          :alt="booking.villa.title"
                          class="w-20 h-20 object-cover rounded-lg"
                        >
                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center" v-else>
                          <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                          </svg>
                        </div>
                        <div>
                          <h3 class="text-lg font-semibold text-gray-900">{{ booking.villa.title }}</h3>
                          <p class="text-sm text-gray-600">{{ booking.villa.address }}</p>
                          <div class="flex items-center space-x-4 mt-2">
                            <span class="text-sm text-gray-500">
                              {{ formatDate(booking.check_in) }} - {{ formatDate(booking.check_out) }}
                            </span>
                            <span class="text-sm text-gray-500">
                              {{ booking.guests }} misafir
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="text-right">
                      <div class="flex items-center space-x-2 mb-2">
                        <span 
                          :class="{
                            'bg-yellow-100 text-yellow-800': booking.status === 'pending',
                            'bg-green-100 text-green-800': booking.status === 'confirmed',
                            'bg-red-100 text-red-800': booking.status === 'cancelled',
                            'bg-blue-100 text-blue-800': booking.status === 'completed'
                          }"
                          class="px-2 py-1 text-xs font-medium rounded-full"
                        >
                          {{ getStatusText(booking.status) }}
                        </span>
                      </div>
                      <div class="text-lg font-bold text-gray-900">
                        ₺{{ booking.total_amount?.toLocaleString() }}
                      </div>
                      <div class="mt-2 space-x-2">
                        <Link 
                          :href="route('bookings.show', booking.id)"
                          class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                          Detay
                        </Link>
                        <Link 
                          v-if="booking.status === 'pending'"
                          :href="route('bookings.edit', booking.id)"
                          class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                          Düzenle
                        </Link>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Boş Durum -->
            <div v-else class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">Henüz rezervasyonunuz yok</h3>
              <p class="mt-1 text-sm text-gray-500">Villa aramaya başlayın ve ilk rezervasyonunuzu yapın.</p>
              <div class="mt-6">
                <Link 
                  :href="route('villas.index')"
                  class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  Villa Ara
                </Link>
              </div>
            </div>

            <!-- Pagination -->
            <div v-if="bookings.data.length > 0" class="mt-6">
              <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                <div class="-mt-px flex w-0 flex-1">
                  <Link 
                    v-if="bookings.prev_page_url"
                    :href="bookings.prev_page_url"
                    class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                  >
                    <svg class="mr-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0L2.586 11a2 2 0 010-2.828L6.293 4.465a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l3.293 3.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Önceki
                  </Link>
                </div>
                <div class="hidden md:-mt-px md:flex">
                  <span class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500">
                    Sayfa {{ bookings.current_page }} / {{ bookings.last_page }}
                  </span>
                </div>
                <div class="-mt-px flex w-0 flex-1 justify-end">
                  <Link 
                    v-if="bookings.next_page_url"
                    :href="bookings.next_page_url"
                    class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                  >
                    Sonraki
                    <svg class="ml-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4.293 4.293a2 2 0 010 2.828l-4.293 4.293a1 1 0 01-1.414-1.414L15.586 11H3a1 1 0 110-2h12.586l-3.293-3.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </Link>
                </div>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineProps({
  bookings: Object
})

function formatDate(date) {
  return new Date(date).toLocaleDateString('tr-TR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

function getStatusText(status) {
  const statusMap = {
    'pending': 'Beklemede',
    'confirmed': 'Onaylandı',
    'cancelled': 'İptal Edildi',
    'completed': 'Tamamlandı'
  }
  return statusMap[status] || status
}
</script>