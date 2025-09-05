<template>
    <AdminLayout :breadcrumbs="[{ name: 'Rezervasyonlar', href: '/admin/bookings' }, 'Takvim']">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Rezervasyon Takvimi</h1>
                    <p class="text-gray-600">Tüm rezervasyonları takvim görünümünde inceleyin</p>
                </div>
                <div class="flex space-x-3">
                    <select 
                        v-model="selectedVilla" 
                        @change="loadCalendarData"
                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Tüm Villalar</option>
                        <option v-for="villa in villas" :key="villa.id" :value="villa.id">
                            {{ villa.name }}
                        </option>
                    </select>
                    <Link 
                        :href="route('admin.bookings.index')"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        <ListBulletIcon class="w-4 h-4 mr-2" />
                        Liste Görünümü
                    </Link>
                </div>
            </div>

            <!-- Calendar -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div id="calendar" class="min-h-[600px]"></div>
                </div>
            </div>

            <!-- Legend -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Açıklama</h3>
                <div class="flex space-x-6">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Onaylanmış Rezervasyonlar</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Bekleyen Rezervasyonlar</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Detail Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Rezervasyon Detayları
                                </h3>
                                <div v-if="selectedBooking" class="space-y-3">
                                    <div>
                                        <span class="font-medium text-gray-700">Villa:</span>
                                        <span class="ml-2 text-gray-900">{{ selectedBooking.extendedProps.villa_name }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Müşteri:</span>
                                        <span class="ml-2 text-gray-900">{{ selectedBooking.extendedProps.user_name }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Giriş:</span>
                                        <span class="ml-2 text-gray-900">{{ formatDate(selectedBooking.start) }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Çıkış:</span>
                                        <span class="ml-2 text-gray-900">{{ formatDate(selectedBooking.end) }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Durum:</span>
                                        <span class="ml-2" :class="{
                                            'text-green-600': selectedBooking.extendedProps.status === 'confirmed',
                                            'text-yellow-600': selectedBooking.extendedProps.status === 'pending',
                                            'text-red-600': selectedBooking.extendedProps.status === 'cancelled'
                                        }">
                                            {{ getStatusText(selectedBooking.extendedProps.status) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Toplam Tutar:</span>
                                        <span class="ml-2 text-gray-900">{{ formatCurrency(selectedBooking.extendedProps.total_amount) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <Link
                            :href="route('admin.bookings.show', selectedBooking?.extendedProps.booking_id)"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Detayları Görüntüle
                        </Link>
                        <button
                            @click="closeModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ListBulletIcon } from '@heroicons/vue/24/outline'
import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import trLocale from '@fullcalendar/core/locales/tr'

const props = defineProps({
    villas: {
        type: Array,
        default: () => []
    },
    auth: Object
})

const selectedVilla = ref('')
const calendar = ref(null)
const showModal = ref(false)
const selectedBooking = ref(null)

const loadCalendarData = async () => {
    try {
        const params = new URLSearchParams()
        if (selectedVilla.value) {
            params.append('villa_id', selectedVilla.value)
        }
        
        const response = await fetch(`/admin/bookings/calendar?${params.toString()}`)
        const data = await response.json()
        
        if (data.success && calendar.value) {
            calendar.value.removeAllEvents()
            calendar.value.addEventSource(data.data)
        }
    } catch (error) {
        console.error('Error loading calendar data:', error)
    }
}

const initializeCalendar = () => {
    const calendarEl = document.getElementById('calendar')
    if (!calendarEl) return
    
    calendar.value = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        locale: trLocale,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        height: 'auto',
        eventClick: (info) => {
            selectedBooking.value = info.event
            showModal.value = true
        },
        eventDidMount: (info) => {
            info.el.style.cursor = 'pointer'
        }
    })
    
    calendar.value.render()
    loadCalendarData()
}

const closeModal = () => {
    showModal.value = false
    selectedBooking.value = null
}

const formatDate = (date) => {
    if (!date) return ''
    return new Date(date).toLocaleDateString('tr-TR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const formatCurrency = (amount) => {
    if (!amount) return '₺0'
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
    }).format(amount)
}

const getStatusText = (status) => {
    const statusMap = {
        'pending': 'Bekliyor',
        'confirmed': 'Onaylandı',
        'cancelled': 'İptal Edildi',
        'completed': 'Tamamlandı'
    }
    return statusMap[status] || status
}

onMounted(() => {
    nextTick(() => {
        initializeCalendar()
    })
})
</script>

<style>
/* FullCalendar custom styles */
.fc-event {
    border: none !important;
    border-radius: 4px !important;
}

.fc-event-title {
    font-weight: 500 !important;
    font-size: 0.875rem !important;
}

.fc-daygrid-event {
    margin: 1px !important;
}

.fc-button {
    background-color: #3b82f6 !important;
    border-color: #3b82f6 !important;
}

.fc-button:hover {
    background-color: #2563eb !important;
    border-color: #2563eb !important;
}

.fc-button:focus {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5) !important;
}

.fc-today {
    background-color: rgba(59, 130, 246, 0.1) !important;
}
</style>