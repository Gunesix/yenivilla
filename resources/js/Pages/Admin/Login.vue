<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-blue-100">
                    <BuildingOfficeIcon class="h-8 w-8 text-blue-600" />
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Admin Paneli
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Villa kiralama sistemi yönetim paneli
                </p>
            </div>
            
            <form class="mt-8 space-y-6" @submit.prevent="submit">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">E-posta adresi</label>
                        <input
                            id="email"
                            v-model="form.email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            :class="{ 'border-red-300': form.errors.email }"
                            placeholder="E-posta adresi"
                        />
                        <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                            {{ form.errors.email }}
                        </div>
                    </div>
                    <div>
                        <label for="password" class="sr-only">Şifre</label>
                        <input
                            id="password"
                            v-model="form.password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            :class="{ 'border-red-300': form.errors.password }"
                            placeholder="Şifre"
                        />
                        <div v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                            {{ form.errors.password }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember-me"
                            v-model="form.remember"
                            name="remember-me"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            Beni hatırla
                        </label>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <LockClosedIcon class="h-5 w-5 text-blue-500 group-hover:text-blue-400" />
                        </span>
                        <span v-if="form.processing">Giriş yapılıyor...</span>
                        <span v-else>Giriş Yap</span>
                    </button>
                </div>

                <!-- Demo credentials -->
                <div class="mt-6 p-4 bg-blue-50 rounded-md">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">Demo Giriş Bilgileri:</h3>
                    <p class="text-sm text-blue-700">
                        <strong>E-posta:</strong> admin@villakiralama.com<br>
                        <strong>Şifre:</strong> password
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { BuildingOfficeIcon, LockClosedIcon } from '@heroicons/vue/24/outline'

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post('/admin/login', {
        onFinish: () => form.reset('password'),
    })
}
</script>