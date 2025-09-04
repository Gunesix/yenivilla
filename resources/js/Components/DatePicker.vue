<template>
    <div class="relative">
        <!-- Date Input Display -->
        <div 
            @click="toggleCalendar" 
            class="relative group cursor-pointer bg-gradient-to-r from-white/60 to-white/40 border-2 border-white/60 rounded-xl backdrop-blur-xl shadow-lg hover:shadow-xl transition-all duration-300 w-full h-12"
        >
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
            <div class="relative flex items-center justify-center h-full px-3">
                <CalendarIcon class="h-5 w-5 text-blue-600 mr-2 group-hover:scale-110 transition-all duration-300 drop-shadow-sm" />
                <span class="text-gray-800 font-medium text-sm truncate">
                    {{ displayValue || placeholder }}
                </span>
            </div>
        </div>

        <!-- Calendar Popup -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div v-if="showCalendar" class="absolute top-full left-0 mt-2 w-72 bg-white/95 backdrop-blur-xl border border-white/30 rounded-xl shadow-2xl z-50 p-4">
                <!-- Calendar Header -->
                <div class="flex items-center justify-between mb-4">
                    <button 
                        @click="previousMonth" 
                        class="p-1.5 hover:bg-blue-100/50 rounded-lg transition-all duration-200 hover:scale-110"
                    >
                        <ChevronLeftIcon class="h-4 w-4 text-gray-700" />
                    </button>
                    <h3 class="text-base font-bold text-gray-800">
                        {{ monthNames[currentMonth] }} {{ currentYear }}
                    </h3>
                    <button 
                        @click="nextMonth" 
                        class="p-1.5 hover:bg-blue-100/50 rounded-lg transition-all duration-200 hover:scale-110"
                    >
                        <ChevronRightIcon class="h-4 w-4 text-gray-700" />
                    </button>
                </div>

                <!-- Days of Week -->
                <div class="grid grid-cols-7 gap-1 mb-2">
                    <div v-for="day in dayNames" :key="day" class="text-center text-xs font-semibold text-gray-600 py-1">
                        {{ day }}
                    </div>
                </div>
                
                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-1 mb-4">
                    <button
                        v-for="day in calendarDays"
                        :key="day.date"
                        @click="!day.disabled && selectDate(day.date)"
                        :disabled="day.disabled"
                        :class="[
                            'h-8 w-8 rounded-lg text-xs font-medium transition-all duration-200 flex items-center justify-center',
                            day.isCurrentMonth 
                                ? 'text-gray-800 hover:bg-blue-100/70 hover:scale-105' 
                                : 'text-gray-400',
                            day.isSelected 
                                ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg transform scale-105' 
                                : '',
                            day.isToday 
                                ? 'ring-2 ring-blue-400/50 bg-blue-50/50' 
                                : '',
                            day.disabled 
                                ? 'opacity-50 cursor-not-allowed' 
                                : 'cursor-pointer'
                        ]"
                    >
                        {{ day.day }}
                    </button>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button 
                        @click="selectToday" 
                        class="flex-1 px-3 py-1.5 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-200 font-medium text-xs shadow-lg hover:shadow-xl transform hover:scale-105"
                    >
                        Bugün
                    </button>
                    <button 
                        @click="clearDate" 
                        class="flex-1 px-3 py-1.5 bg-gray-200/80 text-gray-700 rounded-lg hover:bg-gray-300/80 transition-all duration-200 font-medium text-xs hover:scale-105"
                    >
                        Temizle
                    </button>
                </div>
            </div>
        </Transition>

        <!-- Backdrop -->
        <div 
            v-if="showCalendar" 
            @click="closeCalendar"
            class="fixed inset-0 z-40"
        ></div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { CalendarIcon, ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Tarih seçin'
    },
    minDate: {
        type: String,
        default: null
    }
})

const emit = defineEmits(['update:modelValue'])

const showCalendar = ref(false)
const currentDate = new Date()
const currentMonth = ref(currentDate.getMonth())
const currentYear = ref(currentDate.getFullYear())

const monthNames = [
    'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran',
    'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'
]

const dayNames = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz']

const displayValue = computed(() => {
    if (!props.modelValue) return ''
    const date = new Date(props.modelValue)
    return date.toLocaleDateString('tr-TR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
})

const daysInMonth = computed(() => {
    return new Date(currentYear.value, currentMonth.value + 1, 0).getDate()
})

const firstDayOfMonth = computed(() => {
    const day = new Date(currentYear.value, currentMonth.value, 1).getDay()
    return day === 0 ? 6 : day - 1 // Convert Sunday (0) to 6, Monday (1) to 0, etc.
})

const previousMonthDays = computed(() => {
    const prevMonth = new Date(currentYear.value, currentMonth.value, 0).getDate()
    const days = []
    for (let i = firstDayOfMonth.value - 1; i >= 0; i--) {
        days.push(prevMonth - i)
    }
    return days
})

const currentMonthDays = computed(() => {
    const days = []
    for (let i = 1; i <= daysInMonth.value; i++) {
        days.push(i)
    }
    return days
})

const nextMonthDays = computed(() => {
    const totalCells = 42 // 6 rows × 7 days
    const usedCells = previousMonthDays.value.length + currentMonthDays.value.length
    const remainingCells = totalCells - usedCells
    const days = []
    for (let i = 1; i <= remainingCells; i++) {
        days.push(i)
    }
    return days
})

const calendarDays = computed(() => {
    const days = []
    
    // Previous month days
    previousMonthDays.value.forEach(day => {
        const date = new Date(currentYear.value, currentMonth.value - 1, day)
        days.push({
            day,
            date: date.toISOString().split('T')[0],
            isCurrentMonth: false,
            isSelected: false,
            isToday: false,
            disabled: isPastDate(day, currentMonth.value - 1)
        })
    })
    
    // Current month days
    currentMonthDays.value.forEach(day => {
        const date = new Date(currentYear.value, currentMonth.value, day)
        days.push({
            day,
            date: date.toISOString().split('T')[0],
            isCurrentMonth: true,
            isSelected: isSelected(day),
            isToday: isToday(day),
            disabled: isPastDate(day)
        })
    })
    
    // Next month days
    nextMonthDays.value.forEach(day => {
        const date = new Date(currentYear.value, currentMonth.value + 1, day)
        days.push({
            day,
            date: date.toISOString().split('T')[0],
            isCurrentMonth: false,
            isSelected: false,
            isToday: false,
            disabled: false
        })
    })
    
    return days
})

const toggleCalendar = () => {
    showCalendar.value = !showCalendar.value
}

const closeCalendar = () => {
    showCalendar.value = false
}

const previousMonth = () => {
    if (currentMonth.value === 0) {
        currentMonth.value = 11
        currentYear.value--
    } else {
        currentMonth.value--
    }
}

const nextMonth = () => {
    if (currentMonth.value === 11) {
        currentMonth.value = 0
        currentYear.value++
    } else {
        currentMonth.value++
    }
}

const selectDate = (dateString) => {
    const selectedDate = new Date(dateString)
    if (selectedDate < new Date().setHours(0, 0, 0, 0) && !props.minDate) return
    if (props.minDate && selectedDate < new Date(props.minDate)) return
    
    emit('update:modelValue', dateString)
    closeCalendar()
}

const selectToday = () => {
    const today = new Date()
    const formattedDate = today.toISOString().split('T')[0]
    emit('update:modelValue', formattedDate)
    closeCalendar()
}

const clearDate = () => {
    emit('update:modelValue', '')
    closeCalendar()
}

const isToday = (day) => {
    const today = new Date()
    return today.getDate() === day && 
           today.getMonth() === currentMonth.value && 
           today.getFullYear() === currentYear.value
}

const isSelected = (day) => {
    if (!props.modelValue) return false
    const selectedDate = new Date(props.modelValue)
    return selectedDate.getDate() === day && 
           selectedDate.getMonth() === currentMonth.value && 
           selectedDate.getFullYear() === currentYear.value
}

const isPastDate = (day, month = currentMonth.value) => {
    if (!props.minDate) {
        const today = new Date()
        const checkDate = new Date(currentYear.value, month, day)
        today.setHours(0, 0, 0, 0)
        checkDate.setHours(0, 0, 0, 0)
        return checkDate < today
    }
    
    const minDate = new Date(props.minDate)
    const checkDate = new Date(currentYear.value, month, day)
    return checkDate < minDate
}

// Close calendar when clicking outside
const handleClickOutside = (event) => {
    if (!event.target.closest('.relative')) {
        closeCalendar()
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})

// Initialize current month/year based on selected date
watch(() => props.modelValue, (newValue) => {
    if (newValue) {
        const date = new Date(newValue)
        currentMonth.value = date.getMonth()
        currentYear.value = date.getFullYear()
    }
}, { immediate: true })
</script>