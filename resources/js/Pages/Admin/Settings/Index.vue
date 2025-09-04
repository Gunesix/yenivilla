<template>
  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Sistem Ayarları</h1>
        <p class="text-gray-600">Uygulama ayarlarını yönetin</p>
      </div>

      <!-- Settings Tabs -->
      <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="{
                'border-blue-500 text-blue-600': activeTab === tab.id,
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== tab.id
              }"
              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
              <component :is="tab.icon" class="w-5 h-5 mr-2 inline" />
              {{ tab.name }}
            </button>
          </nav>
        </div>

        <!-- General Settings -->
        <div v-if="activeTab === 'general'" class="p-6">
          <!-- Logo Upload Section (Outside of form) -->
          <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <label class="block text-sm font-medium text-gray-700 mb-2">Site Logosu</label>
            <div class="flex items-center space-x-4">
              <div v-if="settings.general.logo" class="flex-shrink-0">
                <img :src="settings.general.logo" alt="Mevcut Logo" class="h-16 w-auto object-contain border border-gray-300 rounded">
              </div>
              <div class="flex-1">
                <input
                  ref="logoInput"
                  type="file"
                  accept="image/*"
                  @change="handleLogoUpload"
                  @click.stop
                  class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                >
                <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF formatlarında maksimum 2MB</p>
              </div>
            </div>
          </div>
          
          <!-- General Settings Form -->
          <form @submit.prevent="saveSettings('general')" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Adı</label>
                <input
                  v-model="settings.general.site_name"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Site URL</label>
                <input
                  v-model="settings.general.site_url"
                  type="url"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Açıklaması</label>
                <textarea
                  v-model="settings.general.site_description"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                ></textarea>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İletişim E-posta</label>
                <input
                  v-model="settings.general.contact_email"
                  type="email"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İletişim Telefon</label>
                <input
                  v-model="settings.general.contact_phone"
                  type="tel"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Adres</label>
                <textarea
                  v-model="settings.general.address"
                  rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                ></textarea>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Para Birimi</label>
                <select
                  v-model="settings.general.currency"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="TRY">Türk Lirası (TRY)</option>
                  <option value="USD">US Dollar (USD)</option>
                  <option value="EUR">Euro (EUR)</option>
                  <option value="GBP">British Pound (GBP)</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Zaman Dilimi</label>
                <select
                  v-model="settings.general.timezone"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="Europe/Istanbul">Europe/Istanbul</option>
                  <option value="UTC">UTC</option>
                  <option value="America/New_York">America/New_York</option>
                  <option value="Europe/London">Europe/London</option>
                  <option value="Asia/Tokyo">Asia/Tokyo</option>
                </select>
              </div>
            </div>
            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="processing"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors disabled:opacity-50"
              >
                {{ processing ? 'Kaydediliyor...' : 'Kaydet' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Payment Settings -->
        <div v-if="activeTab === 'payment'" class="p-6">
          <form @submit.prevent="saveSettings('payment')" class="space-y-6">
            <div class="space-y-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ödeme Yöntemleri</h3>
                <div class="space-y-4">
                  <label class="flex items-center">
                    <input
                      v-model="settings.payment.credit_card_enabled"
                      type="checkbox"
                      class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    />
                    <span class="ml-2 text-sm text-gray-700">Kredi Kartı Ödemesi</span>
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="settings.payment.bank_transfer_enabled"
                      type="checkbox"
                      class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    />
                    <span class="ml-2 text-sm text-gray-700">Banka Havalesi</span>
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="settings.payment.cash_enabled"
                      type="checkbox"
                      class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    />
                    <span class="ml-2 text-sm text-gray-700">Nakit Ödeme</span>
                  </label>
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Komisyon Oranı (%)</label>
                  <input
                    v-model.number="settings.payment.commission_rate"
                    type="number"
                    min="0"
                    max="100"
                    step="0.1"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Para Birimi</label>
                  <select
                    v-model="settings.payment.currency"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <option value="TRY">Türk Lirası (₺)</option>
                    <option value="USD">Amerikan Doları ($)</option>
                    <option value="EUR">Euro (€)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Para Birimi Sembolü</label>
                  <input
                    v-model="settings.payment.currency_symbol"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="₺, $, € vb."
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Vergi Oranı (%)</label>
                  <input
                    v-model.number="settings.payment.tax_rate"
                    type="number"
                    min="0"
                    max="100"
                    step="0.1"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ödeme Yöntemleri</label>
                <input
                  v-model="settings.payment.payment_methods"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Kredi Kartı, Banka Havalesi, Nakit"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İade Politikası</label>
                <textarea
                  v-model="settings.payment.refund_policy"
                  rows="4"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="İade politikası detaylarını buraya yazın..."
                ></textarea>
              </div>
              <div v-if="settings.payment.bank_transfer_enabled">
                <h4 class="text-md font-medium text-gray-900 mb-3">Banka Hesap Bilgileri</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Banka Adı</label>
                    <input
                      v-model="settings.payment.bank_name"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hesap Sahibi</label>
                    <input
                      v-model="settings.payment.account_holder"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">IBAN</label>
                    <input
                      v-model="settings.payment.iban"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Şube Kodu</label>
                    <input
                      v-model="settings.payment.branch_code"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="processing"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors disabled:opacity-50"
              >
                {{ processing ? 'Kaydediliyor...' : 'Kaydet' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Email Settings -->
        <div v-if="activeTab === 'email'" class="p-6">
          <form @submit.prevent="saveSettings('email')" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Sunucu</label>
                <input
                  v-model="settings.email.smtp_host"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                <input
                  v-model.number="settings.email.smtp_port"
                  type="number"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kullanıcı Adı</label>
                <input
                  v-model="settings.email.smtp_username"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Şifre</label>
                <input
                  v-model="settings.email.smtp_password"
                  type="password"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gönderen E-posta</label>
                <input
                  v-model="settings.email.from_email"
                  type="email"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gönderen Adı</label>
                <input
                  v-model="settings.email.from_name"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
            <div class="space-y-4">
              <label class="flex items-center">
                <input
                  v-model="settings.email.encryption"
                  type="checkbox"
                  true-value="tls"
                  false-value="none"
                  class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                />
                <span class="ml-2 text-sm text-gray-700">TLS Şifreleme Kullan</span>
              </label>
            </div>
            <div class="flex justify-between">
              <button
                type="button"
                @click="testEmail"
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors"
              >
                Test E-postası Gönder
              </button>
              <button
                type="submit"
                :disabled="processing"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors disabled:opacity-50"
              >
                {{ processing ? 'Kaydediliyor...' : 'Kaydet' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Booking Settings -->
        <div v-if="activeTab === 'booking'" class="p-6">
          <form @submit.prevent="saveSettings('booking')" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Rezervasyon Süresi (gün)</label>
                <input
                  v-model.number="settings.booking.min_booking_days"
                  type="number"
                  min="1"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Maksimum Rezervasyon Süresi (gün)</label>
                <input
                  v-model.number="settings.booking.max_booking_days"
                  type="number"
                  min="1"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Önceden Rezervasyon (gün)</label>
                <input
                  v-model.number="settings.booking.advance_booking_days"
                  type="number"
                  min="0"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İptal Süresi (saat)</label>
                <input
                  v-model.number="settings.booking.cancellation_hours"
                  type="number"
                  min="0"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
            <div class="space-y-4">
              <label class="flex items-center">
                <input
                  v-model="settings.booking.auto_approve"
                  type="checkbox"
                  class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                />
                <span class="ml-2 text-sm text-gray-700">Rezervasyonları Otomatik Onayla</span>
              </label>
              <label class="flex items-center">
                <input
                  v-model="settings.booking.require_approval"
                  type="checkbox"
                  class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                />
                <span class="ml-2 text-sm text-gray-700">Admin Onayı Gerekli</span>
              </label>
              <label class="flex items-center">
                <input
                  v-model="settings.booking.send_confirmation_email"
                  type="checkbox"
                  class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                />
                <span class="ml-2 text-sm text-gray-700">Onay E-postası Gönder</span>
              </label>
            </div>
            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="processing"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors disabled:opacity-50"
              >
                {{ processing ? 'Kaydediliyor...' : 'Kaydet' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
  CogIcon,
  CurrencyDollarIcon,
  EnvelopeIcon,
  CalendarIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  settings: Object
})

const breadcrumbs = [
  { name: 'Sistem Ayarları', href: route('admin.settings.index'), current: true }
]

const activeTab = ref('general')
const processing = ref(false)

const tabs = [
  { id: 'general', name: 'Genel', icon: CogIcon },
  { id: 'payment', name: 'Ödeme', icon: CurrencyDollarIcon },
  { id: 'email', name: 'E-posta', icon: EnvelopeIcon },
  { id: 'booking', name: 'Rezervasyon', icon: CalendarIcon }
]

const settings = reactive({
  general: {
    site_name: props.settings?.general?.site_name || '',
    site_url: props.settings?.general?.site_url || '',
    site_description: props.settings?.general?.site_description || '',
    contact_email: props.settings?.general?.contact_email || '',
    contact_phone: props.settings?.general?.contact_phone || '',
    address: props.settings?.general?.address || '',
    currency: props.settings?.general?.currency || 'TRY',
    timezone: props.settings?.general?.timezone || 'Europe/Istanbul',
    logo: props.settings?.general?.logo || ''
  },
  payment: {
          credit_card_enabled: props.settings?.payment?.credit_card_enabled || false,
          bank_transfer_enabled: props.settings?.payment?.bank_transfer_enabled || false,
          cash_enabled: props.settings?.payment?.cash_enabled || false,
          commission_rate: props.settings?.payment?.commission_rate || 10,
          currency: props.settings?.payment?.currency || 'TRY',
          currency_symbol: props.settings?.payment?.currency_symbol || '₺',
          tax_rate: props.settings?.payment?.tax_rate || 18,
          payment_methods: props.settings?.payment?.payment_methods || 'Kredi Kartı, Banka Havalesi, Nakit',
          refund_policy: props.settings?.payment?.refund_policy || 'İade işlemleri 14 gün içerisinde yapılabilir.',
          bank_name: props.settings?.payment?.bank_name || '',
          account_holder: props.settings?.payment?.account_holder || '',
          iban: props.settings?.payment?.iban || '',
          branch_code: props.settings?.payment?.branch_code || ''
        },
  email: {
    smtp_host: props.settings?.email?.smtp_host || '',
    smtp_port: props.settings?.email?.smtp_port || 587,
    smtp_username: props.settings?.email?.smtp_username || '',
    smtp_password: props.settings?.email?.smtp_password || '',
    from_email: props.settings?.email?.from_email || '',
    from_name: props.settings?.email?.from_name || '',
    encryption: props.settings?.email?.encryption || 'tls'
  },
  booking: {
    min_booking_days: props.settings?.booking?.min_booking_days || 1,
    max_booking_days: props.settings?.booking?.max_booking_days || 30,
    advance_booking_days: props.settings?.booking?.advance_booking_days || 0,
    cancellation_hours: props.settings?.booking?.cancellation_hours || 24,
    auto_approve: props.settings?.booking?.auto_approve || false,
    require_approval: props.settings?.booking?.require_approval || true,
    send_confirmation_email: props.settings?.booking?.send_confirmation_email || true
  }
})

const saveSettings = (category) => {
  if (!router || !router.put) {
    console.error('Router is not available')
    return
  }
  
  processing.value = true
  router.put(route('admin.settings.update'), {
    category: category,
    settings: settings[category]
  }, {
    onFinish: () => {
      processing.value = false
    },
    onError: (errors) => {
      console.error('Settings save error:', errors)
      processing.value = false
    }
  })
}

const testEmail = () => {
  if (!router || !router.post) {
    console.error('Router is not available')
    return
  }
  
  router.post(route('admin.settings.test-email'), settings.email, {
    onError: (errors) => {
      console.error('Test email error:', errors)
    }
  })
}

const handleLogoUpload = async (event) => {
  const file = event.target.files[0]
  if (!file) return
  
  // Dosya boyutu kontrolü (2MB)
  if (file.size > 2 * 1024 * 1024) {
    alert('Dosya boyutu 2MB\'dan büyük olamaz!')
    event.target.value = '' // Clear the input
    return
  }
  
  // Dosya tipi kontrolü
  if (!file.type.startsWith('image/')) {
    alert('Sadece resim dosyaları yüklenebilir!')
    event.target.value = '' // Clear the input
    return
  }
  
  const formData = new FormData()
  formData.append('logo', file)
  formData.append('category', 'general')
  
  processing.value = true
  
  if (!router || !router.post) {
    console.error('Router is not available')
    processing.value = false
    return
  }
  
  router.post(route('admin.settings.upload-logo'), formData, {
    forceFormData: true,
    onSuccess: (response) => {
      if (response.props && response.props.settings && response.props.settings.general) {
        settings.general.logo = response.props.settings.general.logo
      }
      // Clear the file input after successful upload
      event.target.value = ''
      alert('Logo başarıyla yüklendi!')
    },
    onError: (errors) => {
      console.error('Logo upload error:', errors)
      alert('Logo yüklenirken bir hata oluştu!')
      // Clear the file input on error
      event.target.value = ''
    },
    onFinish: () => {
      processing.value = false
    }
  })
}
</script>