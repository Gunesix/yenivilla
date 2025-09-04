<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Yeni Kullanıcı Oluştur</h1>
        <p class="text-gray-600">Kullanıcı bilgilerini girin</p>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form @submit.prevent="submitForm" class="p-6 space-y-6">
          <!-- Personal Information -->
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Kişisel Bilgiler</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Name -->
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                  Ad Soyad *
                </label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Kullanıcının tam adını girin"
                />
                <div v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name }}</div>
              </div>

              <!-- Email -->
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                  E-posta Adresi *
                </label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="ornek@email.com"
                />
                <div v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email }}</div>
              </div>

              <!-- Phone -->
              <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                  Telefon Numarası
                </label>
                <input
                  id="phone"
                  v-model="form.phone"
                  type="tel"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="+90 555 123 45 67"
                />
                <div v-if="errors.phone" class="text-red-600 text-sm mt-1">{{ errors.phone }}</div>
              </div>
            </div>
          </div>

          <!-- Password Information -->
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Şifre Bilgileri</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Password -->
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                  Şifre *
                </label>
                <input
                  id="password"
                  v-model="form.password"
                  type="password"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="En az 8 karakter"
                />
                <div v-if="errors.password" class="text-red-600 text-sm mt-1">{{ errors.password }}</div>
                <p class="text-gray-500 text-sm mt-1">
                  Şifre en az 8 karakter olmalıdır
                </p>
              </div>

              <!-- Password Confirmation -->
              <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                  Şifre Tekrarı *
                </label>
                <input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  type="password"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Şifreyi tekrar girin"
                />
                <div v-if="errors.password_confirmation" class="text-red-600 text-sm mt-1">{{ errors.password_confirmation }}</div>
              </div>
            </div>
          </div>

          <!-- Additional Information -->
          <div class="bg-blue-50 p-4 rounded-lg">
            <h4 class="text-sm font-medium text-blue-800 mb-2">Bilgilendirme</h4>
            <ul class="text-sm text-blue-700 space-y-1">
              <li>• Admin tarafından oluşturulan kullanıcılar otomatik olarak doğrulanmış sayılır</li>
              <li>• Kullanıcı giriş yapabilir ve rezervasyon oluşturabilir</li>
              <li>• E-posta adresi benzersiz olmalıdır</li>
            </ul>
          </div>

          <!-- Form Actions -->
          <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <Link
              :href="route('admin.users.index')"
              class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
              İptal
            </Link>
            <button
              type="submit"
              :disabled="processing"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="processing">Oluşturuluyor...</span>
              <span v-else>Kullanıcı Oluştur</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  breadcrumbs: {
    type: Array,
    default: () => []
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const form = reactive({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: ''
})

const processing = ref(false)

const submitForm = () => {
  processing.value = true
  
  router.post(route('admin.users.store'), form, {
    onSuccess: () => {
      // Success handled by backend redirect
    },
    onError: () => {
      processing.value = false
    },
    onFinish: () => {
      processing.value = false
    }
  })
}
</script>