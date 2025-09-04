<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profil" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Profil Ayarları
            </h2>
            <p class="text-gray-600 mt-2">Hesap bilgilerinizi yönetin ve güncelleyin</p>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-4xl space-y-8 px-4 sm:px-6 lg:px-8">
                <!-- Profile Information -->
                <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-8 shadow-2xl hover:shadow-3xl transition-all duration-500">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Profil Bilgileri</h3>
                            <p class="text-gray-600 text-sm">Hesap bilgilerinizi ve e-posta adresinizi güncelleyin</p>
                        </div>
                    </div>
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-2xl"
                    />
                </div>

                <!-- Password Update -->
                <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-8 shadow-2xl hover:shadow-3xl transition-all duration-500">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-blue-100 rounded-2xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Şifre Güncelleme</h3>
                            <p class="text-gray-600 text-sm">Hesabınızın güvenliği için güçlü bir şifre kullanın</p>
                        </div>
                    </div>
                    <UpdatePasswordForm class="max-w-2xl" />
                </div>

                <!-- Delete Account -->
                <div class="backdrop-blur-xl bg-red-50/30 border border-red-200/50 rounded-3xl p-8 shadow-2xl hover:shadow-3xl transition-all duration-500">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-orange-100 rounded-2xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-red-800">Hesabı Sil</h3>
                            <p class="text-red-600 text-sm">Hesabınızı kalıcı olarak silin - bu işlem geri alınamaz</p>
                        </div>
                    </div>
                    <DeleteUserForm class="max-w-2xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
