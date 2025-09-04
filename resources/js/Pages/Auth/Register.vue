<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Kayıt Ol" />

        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold bg-gradient-to-r from-rose-500 to-purple-600 bg-clip-text text-transparent mb-2">
                Hesap Oluşturun
            </h2>
            <p class="text-gray-700 text-sm">
                Yeni hesap oluşturun
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Name Field -->
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-800">
                    Ad Soyad
                </label>
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    class="w-full px-4 py-3 bg-white/90 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 text-gray-800 placeholder-gray-500 backdrop-blur-sm transition-all duration-200"
                    placeholder="Adınız ve soyadınız"
                />
                <InputError class="mt-2 text-red-300" :message="form.errors.name" />
            </div>

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
                    autocomplete="new-password"
                    class="w-full px-4 py-3 bg-white/90 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 text-gray-800 placeholder-gray-500 backdrop-blur-sm transition-all duration-200"
                    placeholder="En az 8 karakter"
                />
                <InputError class="mt-2 text-red-300" :message="form.errors.password" />
            </div>

            <!-- Password Confirmation Field -->
            <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-800">
                    Şifre Tekrarı
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-3 bg-white/90 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 text-gray-800 placeholder-gray-500 backdrop-blur-sm transition-all duration-200"
                    placeholder="Şifrenizi tekrar girin"
                />
                <InputError class="mt-2 text-red-300" :message="form.errors.password_confirmation" />
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-gradient-to-r from-rose-500 to-purple-600 text-white py-3 px-6 rounded-xl hover:from-rose-600 hover:to-purple-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
            >
                <span v-if="form.processing">Kayıt olunuyor...</span>
                <span v-else>Kayıt Ol</span>
            </button>

            <!-- Links -->
            <div class="text-center">
                <div class="text-gray-600 text-sm">
                    Zaten hesabınız var mı?
                    <Link
                        :href="route('login')"
                        class="text-rose-500 hover:text-rose-600 font-medium transition-colors duration-300 ml-1"
                    >
                        Giriş yapın
                    </Link>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
