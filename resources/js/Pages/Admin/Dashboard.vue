<template>
    <AdminLayout :breadcrumbs="['Dashboard']">
        <div class="space-y-6">
            <!-- Page header -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Villa kiralama sistemi genel bakış
                </p>
            </div>

            <!-- Stats cards -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="stat in stats"
                    :key="stat.name"
                    class="bg-white overflow-hidden shadow rounded-lg"
                >
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <component
                                    :is="stat.icon"
                                    class="h-6 w-6"
                                    :class="stat.iconColor"
                                />
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ stat.name }}
                                    </dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900">
                                            {{ stat.value }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <span class="font-medium" :class="stat.changeColor">
                                {{ stat.change }}
                            </span>
                            <span class="text-gray-500"> son aydan</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Revenue chart -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Aylık Gelir Trendi</h3>
                    </div>
                    <div class="p-6">
                        <canvas ref="revenueChart" class="w-full h-64"></canvas>
                    </div>
                </div>

                <!-- Booking status distribution -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Rezervasyon Durumu</h3>
                    </div>
                    <div class="p-6">
                        <canvas ref="statusChart" class="w-full h-64"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent bookings -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Son Rezervasyonlar</h3>
                </div>
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Villa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Müşteri
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tarihler
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Durum
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tutar
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="booking in recentBookings" :key="booking.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ booking.villa_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ booking.user_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(booking.check_in) }} - {{ formatDate(booking.check_out) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                        :class="getStatusColor(booking.status)"
                                    >
                                        {{ getStatusText(booking.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ formatCurrency(booking.total_amount) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
    BuildingOfficeIcon,
    CalendarDaysIcon,
    CurrencyDollarIcon,
    UsersIcon,
} from '@heroicons/vue/24/outline'
import Chart from 'chart.js/auto'

const props = defineProps({
    dashboardData: {
        type: Object,
        required: true
    }
})

const revenueChart = ref(null)
const statusChart = ref(null)

const stats = computed(() => [
    {
        name: 'Toplam Villa',
        value: props.dashboardData.stats.total_villas,
        icon: BuildingOfficeIcon,
        iconColor: 'text-blue-600',
        change: '+12%',
        changeColor: 'text-green-600'
    },
    {
        name: 'Aktif Rezervasyon',
        value: props.dashboardData.stats.active_bookings,
        icon: CalendarDaysIcon,
        iconColor: 'text-green-600',
        change: '+8%',
        changeColor: 'text-green-600'
    },
    {
        name: 'Aylık Gelir',
        value: formatCurrency(props.dashboardData.stats.monthly_revenue),
        icon: CurrencyDollarIcon,
        iconColor: 'text-yellow-600',
        change: '+15%',
        changeColor: 'text-green-600'
    },
    {
        name: 'Toplam Kullanıcı',
        value: props.dashboardData.stats.total_users,
        icon: UsersIcon,
        iconColor: 'text-purple-600',
        change: '+5%',
        changeColor: 'text-green-600'
    },
])

const recentBookings = computed(() => props.dashboardData.recent_bookings || [])

onMounted(() => {
    initRevenueChart()
    initStatusChart()
})

const initRevenueChart = () => {
    const ctx = revenueChart.value.getContext('2d')
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: props.dashboardData.monthly_revenue_chart.map(item => item.month),
            datasets: [{
                label: 'Gelir (₺)',
                data: props.dashboardData.monthly_revenue_chart.map(item => item.revenue),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₺' + value.toLocaleString('tr-TR')
                        }
                    }
                }
            }
        }
    })
}

const initStatusChart = () => {
    const ctx = statusChart.value.getContext('2d')
    const distribution = props.dashboardData.booking_status_distribution
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(distribution).map(status => getStatusText(status)),
            datasets: [{
                data: Object.values(distribution),
                backgroundColor: [
                    '#10b981', // confirmed - green
                    '#f59e0b', // pending - yellow
                    '#ef4444', // cancelled - red
                    '#6b7280', // completed - gray
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    })
}

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
    }).format(amount)
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('tr-TR')
}

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800',
        confirmed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
        completed: 'bg-gray-100 text-gray-800'
    }
    return colors[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
    const texts = {
        pending: 'Beklemede',
        confirmed: 'Onaylandı',
        cancelled: 'İptal Edildi',
        completed: 'Tamamlandı'
    }
    return texts[status] || status
}
</script>