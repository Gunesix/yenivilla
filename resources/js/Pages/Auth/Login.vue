<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Giriş Yap" />

        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold bg-gradient-to-r from-rose-500 to-purple-600 bg-clip-text text-transparent mb-2">
                Hoş Geldiniz
            </h2>
            <p class="text-gray-700 text-sm">
                Hesabınıza giriş yapın
            </p>
        </div>

        <div v-if="status" class="mb-6 p-4 bg-green-100/20 border border-green-300/30 rounded-2xl backdrop-blur-xl">
            <p class="text-sm font-medium text-green-200">{{ status }}</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Email Field -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-800">
                    E-posta Adresi
                </label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full px-4 py-3 bg-white/90 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 text-gray-800 placeholder-gray-500 backdrop-blur-sm transition-all duration-200"
                    placeholder="ornek@email.com"
                />
                <InputError class="mt-2 text-red-300" :message="form.errors.email" />
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-800">
                    Şifre
                </label>
                <input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    class="w-full px-4 py-3 bg-white/90 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 text-gray-800 placeholder-gray-500 backdrop-blur-sm transition-all duration-200"
                    placeholder="••••••••"
                />
                <InputError class="mt-2 text-red-300" :message="form.errors.password" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input
                    id="remember"
                    type="checkbox"
                    v-model="form.remember"
                    class="w-4 h-4 text-rose-500 bg-white/90 border-gray-300 rounded focus:ring-rose-500 focus:ring-2"
                />
                <label for="remember" class="ml-3 text-sm text-gray-700">
                    Beni hatırla
                </label>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-gradient-to-r from-rose-500 to-purple-600 text-white py-3 px-6 rounded-xl hover:from-rose-600 hover:to-purple-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
            >
                <span v-if="form.processing">Giriş yapılıyor...</span>
                <span v-else>Giriş Yap</span>
            </button>

            <!-- Links -->
            <div class="flex flex-col space-y-3 text-center">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-gray-600 hover:text-gray-800 text-sm transition-colors duration-300"
                >
                    Şifrenizi mi unuttunuz?
                </Link>
                
                <div class="text-gray-600 text-sm">
                    Hesabınız yok mu?
                    <Link
                        :href="route('register')"
                        class="text-rose-500 hover:text-rose-600 font-medium transition-colors duration-300 ml-1"
                    >
                        Kayıt olun
                    </Link>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
