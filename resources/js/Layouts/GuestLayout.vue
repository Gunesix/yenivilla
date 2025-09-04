<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Glassmorphism Navigation Header -->
        <header class="w-full fixed top-0 z-50 flex justify-center pointer-events-none">
            <nav class="mt-4 px-6 py-3 rounded-full backdrop-blur-xl shadow-lg border border-white/40 pointer-events-auto flex items-center gap-6 bg-white/70 text-gray-800">
                <Link :href="route('home')" class="font-medium hover:text-blue-600 transition">
                    Ana Sayfa
                </Link>
                <Link :href="route('villas.index')" class="font-medium hover:text-blue-600 transition">
                    Villalar
                </Link>
                <Link :href="route('locations')" class="font-medium hover:text-blue-600 transition">
                    Kategoriler
                </Link>
                <div class="flex items-center gap-4">
                    <Link :href="route('login')" class="font-medium hover:text-blue-600 transition">
                        Giriş
                    </Link>
                    <Link :href="route('register')" class="bg-rose-500 text-white px-4 py-2 rounded-full hover:bg-rose-600 transition">
                        Kayıt
                    </Link>
                </div>
            </nav>
        </header>

        <!-- Background Video -->
        <div class="absolute inset-0 w-full h-full overflow-hidden">
            <video 
                autoplay 
                muted 
                loop 
                class="w-full h-full object-cover opacity-30"
            >
                <source src="https://player.vimeo.com/external/342333493.sd.mp4?s=47eabb1d7b8f2e1c0b1b1b1b1b1b1b1b1b1b1b1b&profile_id=164" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 via-purple-900/10 to-blue-800/20"></div>
        </div>
        
        <!-- Content -->
        <div class="relative z-10 w-full max-w-md mx-auto pt-24 pb-16 px-4 flex flex-col min-h-screen">
            <!-- Logo -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-block">
                    <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-rose-500 to-purple-600 bg-clip-text text-transparent">
                            VillaRent
                        </h1>
                    </div>
                </Link>
            </div>

            <!-- Auth Form Container -->
            <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-3xl p-8 shadow-2xl flex-1">
                <slot />
            </div>
            
            <!-- Back to Home Link -->
            <div class="text-center mt-6">
                <Link 
                    href="/" 
                    class="text-gray-700 hover:text-gray-900 text-sm transition-colors duration-300 backdrop-blur-xl bg-white/80 border border-white/40 rounded-full px-4 py-2 hover:bg-white/90"
                >
                    ← Ana Sayfaya Dön
                </Link>
            </div>
        </div>

        <!-- Modern Footer -->
        <footer class="bg-gray-50 border-t border-gray-200 mt-auto relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="md:col-span-2">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 mr-2 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                            <span class="text-xl font-bold text-gray-900">VillaRent</span>
                        </div>
                        <p class="text-gray-600 mb-6 max-w-md">
                            Türkiye'nin en güzel destinasyonlarında unutulmaz tatil deneyimleri yaşayın. Premium villa koleksiyonumuzla hayalinizdeki tatili gerçekleştirin.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-rose-500 transition-colors">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-rose-500 transition-colors">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-rose-500 transition-colors">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4">Keşfet</h4>
                        <ul class="space-y-3">
                            <li><Link :href="route('home')" class="text-gray-600 hover:text-gray-900 transition-colors">Ana Sayfa</Link></li>
                            <li><Link :href="route('villas.index')" class="text-gray-600 hover:text-gray-900 transition-colors">Villalar</Link></li>
                            <li><Link :href="route('locations')" class="text-gray-600 hover:text-gray-900 transition-colors">Lokasyonlar</Link></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Hakkımızda</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4">Destek</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Yardım Merkezi</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">İletişim</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Güvenlik</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Gizlilik</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 mt-8 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-gray-500 text-sm">
                            © 2024 VillaRent. Tüm hakları saklıdır.
                        </p>
                        <div class="flex space-x-6 mt-4 md:mt-0">
                            <a href="#" class="text-gray-500 hover:text-gray-900 text-sm transition-colors">Gizlilik Politikası</a>
                            <a href="#" class="text-gray-500 hover:text-gray-900 text-sm transition-colors">Kullanım Şartları</a>
                            <a href="#" class="text-gray-500 hover:text-gray-900 text-sm transition-colors">Çerez Politikası</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
