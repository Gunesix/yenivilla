<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Villa Yönetimi</h1>
          <p class="text-gray-600">Tüm villaları görüntüleyin ve yönetin</p>
        </div>
        <Link
          :href="route('admin.villas.create')"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
        >
          <PlusIcon class="w-5 h-5" />
          Yeni Villa Ekle
        </Link>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Arama</label>
            <input
              v-model="filters.search"
              type="text"
              placeholder="Villa adı veya lokasyon..."
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
              <option value="active">Aktif</option>
              <option value="inactive">Pasif</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasyon</label>
            <select
              v-model="filters.location"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Tümü</option>
              <option value="antalya">Antalya</option>
              <option value="bodrum">Bodrum</option>
              <option value="cesme">Çeşme</option>
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

      <!-- Villas Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Villa
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Lokasyon
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Kapasite
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Fiyat
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
              <tr v-for="villa in villas.data" :key="villa.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                      <img
                        class="h-12 w-12 rounded-lg object-cover"
                        :src="villa.main_image || '/images/villa-placeholder.jpg'"
                        :alt="villa.name"
                      />
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ villa.name }}</div>
                      <div class="text-sm text-gray-500">ID: {{ villa.id }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ villa.location }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ villa.max_guests }} kişi • {{ villa.bedrooms }} yatak odası
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">
                    ₺{{ villa.price_per_night?.toLocaleString() }}/gece
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="{
                      'bg-green-100 text-green-800': villa.status === 'active',
                      'bg-red-100 text-red-800': villa.status === 'inactive'
                    }"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ villa.status === 'active' ? 'Aktif' : 'Pasif' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center gap-2">
                    <Link
                      :href="route('admin.villas.show', villa.id)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      <EyeIcon class="w-4 h-4" />
                    </Link>
                    <Link
                      :href="route('admin.villas.edit', villa.id)"
                      class="text-indigo-600 hover:text-indigo-900"
                    >
                      <PencilIcon class="w-4 h-4" />
                    </Link>
                    <button
                      @click="deleteVilla(villa.id)"
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
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
          <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <Link
                v-if="villas.prev_page_url"
                :href="villas.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Önceki
              </Link>
              <Link
                v-if="villas.next_page_url"
                :href="villas.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Sonraki
              </Link>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  <span class="font-medium">{{ villas.from }}</span>
                  -
                  <span class="font-medium">{{ villas.to }}</span>
                  arası, toplam
                  <span class="font-medium">{{ villas.total }}</span>
                  sonuç
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <template v-for="link in villas.links" :key="link.label">
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
import { PlusIcon, EyeIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'

defineProps({
  villas: Object,
  filters: Object
})

const breadcrumbs = [
  { name: 'Villa Yönetimi', href: route('admin.villas.index'), current: true }
]

const filters = reactive({
  search: '',
  status: '',
  location: ''
})

const applyFilters = () => {
  router.get(route('admin.villas.index'), filters, {
    preserveState: true,
    preserveScroll: true
  })
}

const deleteVilla = (id) => {
  if (confirm('Bu villayı silmek istediğinizden emin misiniz?')) {
    router.delete(route('admin.villas.destroy', id))
  }
}
</script>