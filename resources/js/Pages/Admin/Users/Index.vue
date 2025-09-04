<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Kullanıcı Yönetimi</h1>
          <p class="text-gray-600">Tüm kullanıcıları görüntüleyin ve yönetin</p>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <UsersIcon class="w-6 h-6 text-blue-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Toplam Kullanıcı</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.total }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <CheckCircleIcon class="w-6 h-6 text-green-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Aktif Kullanıcı</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.active }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
              <CalendarIcon class="w-6 h-6 text-yellow-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Bu Ay Yeni</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.new_this_month }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
              <HomeIcon class="w-6 h-6 text-purple-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Rezervasyon Yapan</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.with_bookings }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Arama</label>
            <input
              v-model="filters.search"
              type="text"
              placeholder="Ad, e-posta..."
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
            <label class="block text-sm font-medium text-gray-700 mb-2">Kayıt Tarihi</label>
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

      <!-- Users Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Kullanıcı
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  İletişim
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Kayıt Tarihi
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Son Giriş
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Rezervasyon
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
              <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700">
                          {{ user.name.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                      <div class="text-sm text-gray-500">ID: {{ user.id }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ user.email }}</div>
                  <div class="text-sm text-gray-500">{{ user.phone || 'Telefon yok' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ formatDate(user.created_at) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ user.last_login_at ? formatDate(user.last_login_at) : 'Hiç giriş yapmamış' }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ user.bookings_count || 0 }} rezervasyon</div>
                  <div class="text-sm text-gray-500">
                    ₺{{ user.total_spent?.toLocaleString() || '0' }} toplam
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="{
                      'bg-green-100 text-green-800': user.email_verified_at,
                      'bg-red-100 text-red-800': !user.email_verified_at
                    }"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ user.email_verified_at ? 'Aktif' : 'E-posta Doğrulanmamış' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center gap-2">
                    <Link
                      :href="route('admin.users.show', user.id)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      <EyeIcon class="w-4 h-4" />
                    </Link>
                    <button
                      @click="toggleUserStatus(user.id, user.email_verified_at)"
                      :class="{
                        'text-red-600 hover:text-red-900': user.email_verified_at,
                        'text-green-600 hover:text-green-900': !user.email_verified_at
                      }"
                    >
                      <component :is="user.email_verified_at ? LockClosedIcon : LockOpenIcon" class="w-4 h-4" />
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
                v-if="users.prev_page_url"
                :href="users.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Önceki
              </Link>
              <Link
                v-if="users.next_page_url"
                :href="users.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Sonraki
              </Link>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  <span class="font-medium">{{ users.from }}</span>
                  -
                  <span class="font-medium">{{ users.to }}</span>
                  arası, toplam
                  <span class="font-medium">{{ users.total }}</span>
                  sonuç
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <template v-for="link in users.links" :key="link.label">
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
  UsersIcon,
  CheckCircleIcon,
  CalendarIcon,
  HomeIcon,
  EyeIcon,
  LockClosedIcon,
  LockOpenIcon
} from '@heroicons/vue/24/outline'

defineProps({
  users: Object,
  stats: Object,
  filters: Object
})

const breadcrumbs = [
  { name: 'Kullanıcı Yönetimi', href: route('admin.users.index'), current: true }
]

const filters = reactive({
  search: '',
  status: '',
  date_range: ''
})

const applyFilters = () => {
  router.get(route('admin.users.index'), filters, {
    preserveState: true,
    preserveScroll: true
  })
}

const toggleUserStatus = (id, currentStatus) => {
  const action = currentStatus ? 'deactivate' : 'activate'
  const message = currentStatus 
    ? 'Bu kullanıcıyı deaktif etmek istediğinizden emin misiniz?' 
    : 'Bu kullanıcıyı aktif etmek istediğinizden emin misiniz?'
  
  if (confirm(message)) {
    router.put(route(`admin.users.${action}`, id))
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('tr-TR')
}
</script>