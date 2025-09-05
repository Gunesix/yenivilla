<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Uygunluk Kaydı Düzenle</h1>
        <p class="text-gray-600">Villa uygunluk durumunu güncelleyin</p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Villa Selection -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
          <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-gray-900">Villa Seçimi</h2>
              <p class="text-sm text-gray-600">Uygunluk durumu belirlenecek villa'yı seçin</p>
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <span class="flex items-center">
                🏠 Villa *
              </span>
            </label>
            <select
              v-model="form.villa_id"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              :class="{ 'border-red-500 ring-red-200': errors.villa_id }"
            >
              <option value="">Villa seçin</option>
              <option v-for="villa in villas" :key="villa.id" :value="villa.id">
                {{ villa.title }}
              </option>
            </select>
            <p v-if="errors.villa_id" class="text-red-500 text-sm mt-1 flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
              {{ errors.villa_id }}
            </p>
          </div>
        </div>

        <!-- Date -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
          <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-gray-900">Tarih</h2>
              <p class="text-sm text-gray-600">Uygunluk durumunun geçerli olacağı tarihi belirtin</p>
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <span class="flex items-center">
                📅 Tarih *
              </span>
            </label>
            <input
              v-model="form.date"
              type="date"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              :class="{ 'border-red-500 ring-red-200': errors.date }"
            />
            <p v-if="errors.date" class="text-red-500 text-sm mt-1 flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
              {{ errors.date }}
            </p>
          </div>
        </div>

        <!-- Availability Details -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
          <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-gray-900">Uygunluk Detayları</h2>
              <p class="text-sm text-gray-600">Villa'nın uygunluk durumu ve detaylarını belirtin</p>
            </div>
          </div>
          
          <div class="space-y-6">
            <!-- Availability Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-3">
                <span class="flex items-center">
                  ✅ Uygunluk Durumu *
                </span>
              </label>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="relative flex items-center p-4 border rounded-lg cursor-pointer transition-all duration-200"
                       :class="form.is_available ? 'border-green-500 bg-green-50' : 'border-gray-300 hover:border-gray-400'">
                  <input
                    v-model="form.is_available"
                    type="radio"
                    :value="true"
                    class="sr-only"
                  />
                  <div class="flex items-center">
                    <div class="w-6 h-6 rounded-full border-2 mr-3 flex items-center justify-center"
                         :class="form.is_available ? 'border-green-500 bg-green-500' : 'border-gray-300'">
                      <svg v-if="form.is_available" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <div class="font-medium text-gray-900">Müsait</div>
                      <div class="text-sm text-gray-600">Villa rezervasyon için uygun</div>
                    </div>
                  </div>
                </label>
                
                <label class="relative flex items-center p-4 border rounded-lg cursor-pointer transition-all duration-200"
                       :class="form.is_available === false ? 'border-red-500 bg-red-50' : 'border-gray-300 hover:border-gray-400'">
                  <input
                    v-model="form.is_available"
                    type="radio"
                    :value="false"
                    class="sr-only"
                  />
                  <div class="flex items-center">
                    <div class="w-6 h-6 rounded-full border-2 mr-3 flex items-center justify-center"
                         :class="form.is_available === false ? 'border-red-500 bg-red-500' : 'border-gray-300'">
                      <svg v-if="form.is_available === false" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <div class="font-medium text-gray-900">Müsait Değil</div>
                      <div class="text-sm text-gray-600">Villa rezervasyon için uygun değil</div>
                    </div>
                  </div>
                </label>
              </div>
              <p v-if="errors.is_available" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.is_available }}
              </p>
            </div>
            
            <!-- Reason -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  🔍 Sebep
                  <span v-if="form.is_available === false" class="text-red-500 ml-1">*</span>
                </span>
              </label>
              <input
                v-model="form.reason"
                type="text"
                placeholder="Örn: Bakım, Temizlik, Özel Etkinlik"
                :required="form.is_available === false"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                :class="{ 'border-red-500 ring-red-200': errors.reason }"
              />
              <p v-if="errors.reason" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.reason }}
              </p>
            </div>
            
            <!-- Notes -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  📝 Notlar
                </span>
              </label>
              <textarea
                v-model="form.notes"
                rows="4"
                placeholder="Bu uygunluk durumu hakkında ek notlar..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                :class="{ 'border-red-500 ring-red-200': errors.notes }"
              ></textarea>
              <p v-if="errors.notes" class="text-red-500 text-sm mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.notes }}
              </p>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4">
          <Link
            :href="route('admin.villa-availability.index')"
            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium"
          >
            İptal
          </Link>
          <button
            type="submit"
            :disabled="processing"
            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors disabled:opacity-50 font-medium"
          >
            {{ processing ? 'Güncelleniyor...' : 'Uygunluk Güncelle' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  availability: Object,
  villas: Array,
  errors: Object
})

const breadcrumbs = [
  { name: 'Villa Uygunluk Yönetimi', href: route('admin.villa-availability.index') },
  { name: 'Uygunluk Düzenle', current: true }
]

const form = useForm({
  villa_id: props.availability.villa_id,
  date: props.availability.date,
  is_available: props.availability.is_available,
  reason: props.availability.reason || '',
  notes: props.availability.notes || ''
})

const submit = () => {
  form.put(route('admin.villa-availability.update', props.availability.id))
}
</script>