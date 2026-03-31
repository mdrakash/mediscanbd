<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useAuthStore } from '../../stores/auth'
import { 
  HomeIcon, 
  CloudArrowUpIcon, 
  ClockIcon,
  UserCircleIcon,
  ArrowRightOnRectangleIcon,
  Bars3Icon,
  XMarkIcon
} from '@heroicons/vue/24/outline'
import LanguageToggle from './LanguageToggle.vue'

const { t } = useI18n()
const authStore = useAuthStore()

const showMobileMenu = ref(false)
const showUserDropdown = ref(false)

const handleLogout = () => {
  showUserDropdown.value = false
  authStore.logout()
}

const closeMobileMenu = () => {
  showMobileMenu.value = false
}
</script>

<template>
  <header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-teal-100">
    <div class="container mx-auto px-4 max-w-7xl">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <RouterLink 
          to="/dashboard" 
          class="flex items-center gap-3 group"
          @click="closeMobileMenu"
        >
          <div class="w-10 h-10 bg-gradient-to-br from-teal-600 to-teal-500 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-all group-hover:scale-105">
            <!-- Heart + Document icon -->
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
          </div>
          <span class="text-xl font-bold bg-gradient-to-r from-teal-600 to-teal-500 bg-clip-text text-transparent hidden sm:block">
            {{ t('app.name') }}
          </span>
        </RouterLink>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-1">
          <RouterLink 
            to="/dashboard" 
            class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-700 hover:text-teal-600 hover:bg-teal-50 font-medium transition-all"
            active-class="text-teal-600 bg-teal-50"
          >
            <HomeIcon class="w-5 h-5" />
            {{ t('nav.dashboard') }}
          </RouterLink>
          <RouterLink 
            to="/upload" 
            class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-700 hover:text-teal-600 hover:bg-teal-50 font-medium transition-all"
            active-class="text-teal-600 bg-teal-50"
          >
            <CloudArrowUpIcon class="w-5 h-5" />
            {{ t('nav.upload') }}
          </RouterLink>
          <RouterLink 
            to="/history" 
            class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-700 hover:text-teal-600 hover:bg-teal-50 font-medium transition-all"
            active-class="text-teal-600 bg-teal-50"
          >
            <ClockIcon class="w-5 h-5" />
            {{ t('nav.history') }}
          </RouterLink>
        </nav>

        <!-- Right side -->
        <div class="flex items-center gap-3">
          <LanguageToggle />

          <!-- User menu (desktop) -->
          <div v-if="authStore.user" class="hidden md:block relative">
            <button
              @click="showUserDropdown = !showUserDropdown"
              class="flex items-center gap-3 hover:bg-teal-50 rounded-xl p-2 pr-4 transition-colors"
            >
              <img 
                v-if="authStore.user.avatar" 
                :src="authStore.user.avatar" 
                :alt="authStore.user.name"
                class="w-9 h-9 rounded-full border-2 border-teal-500 object-cover"
              />
              <div v-else class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center text-white font-semibold text-sm shadow-md">
                {{ authStore.user.name.charAt(0).toUpperCase() }}
              </div>
              
              <div class="text-left hidden lg:block">
                <p class="text-sm font-semibold text-gray-800 leading-tight">{{ authStore.user.name }}</p>
                <p class="text-xs text-gray-500 leading-tight">{{ authStore.user.email }}</p>
              </div>

              <!-- Dropdown arrow -->
              <svg 
                class="w-4 h-4 text-gray-500 transition-transform"
                :class="showUserDropdown ? 'rotate-180' : ''"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Dropdown menu -->
            <Transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div 
                v-show="showUserDropdown"
                @click.away="showUserDropdown = false"
                class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50"
              >
                <div class="px-4 py-3 border-b border-gray-100">
                  <p class="text-sm font-semibold text-gray-800">{{ authStore.user.name }}</p>
                  <p class="text-xs text-gray-500 mt-0.5">{{ authStore.user.email }}</p>
                </div>

                <RouterLink
                  to="/profile"
                  @click="showUserDropdown = false"
                  class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition-colors"
                >
                  <UserCircleIcon class="w-5 h-5" />
                  <span class="text-sm font-medium">{{ t('nav.profile') }}</span>
                </RouterLink>

                <button
                  @click="handleLogout"
                  class="w-full flex items-center gap-3 px-4 py-2.5 text-red-600 hover:bg-red-50 transition-colors border-t border-gray-100 mt-1"
                >
                  <ArrowRightOnRectangleIcon class="w-5 h-5" />
                  <span class="text-sm font-medium">{{ t('auth.logout') }}</span>
                </button>
              </div>
            </Transition>
          </div>

          <!-- Mobile menu button -->
          <button
            @click="showMobileMenu = !showMobileMenu"
            class="md:hidden p-2 text-gray-700 hover:bg-teal-50 rounded-lg transition-colors"
          >
            <Bars3Icon v-if="!showMobileMenu" class="w-6 h-6" />
            <XMarkIcon v-else class="w-6 h-6" />
          </button>
        </div>
      </div>

      <!-- Mobile menu -->
      <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="transform opacity-0 -translate-y-2"
        enter-to-class="transform opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="transform opacity-100 translate-y-0"
        leave-to-class="transform opacity-0 -translate-y-2"
      >
        <div v-show="showMobileMenu" class="md:hidden border-t border-teal-100 py-4">
          <!-- User info -->
          <div v-if="authStore.user" class="flex items-center gap-3 px-4 py-3 mb-3 bg-teal-50 rounded-lg mx-4">
            <img 
              v-if="authStore.user.avatar" 
              :src="authStore.user.avatar" 
              :alt="authStore.user.name"
              class="w-10 h-10 rounded-full border-2 border-teal-500"
            />
            <div v-else class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center text-white font-semibold">
              {{ authStore.user.name.charAt(0).toUpperCase() }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-gray-800 truncate">{{ authStore.user.name }}</p>
              <p class="text-xs text-gray-600 truncate">{{ authStore.user.email }}</p>
            </div>
          </div>

          <!-- Navigation links -->
          <nav class="space-y-1 px-2">
            <RouterLink 
              to="/dashboard" 
              @click="closeMobileMenu"
              class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:text-teal-600 hover:bg-teal-50 font-medium transition-all"
              active-class="text-teal-600 bg-teal-50"
            >
              <HomeIcon class="w-5 h-5" />
              {{ t('nav.dashboard') }}
            </RouterLink>
            <RouterLink 
              to="/upload" 
              @click="closeMobileMenu"
              class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:text-teal-600 hover:bg-teal-50 font-medium transition-all"
              active-class="text-teal-600 bg-teal-50"
            >
              <CloudArrowUpIcon class="w-5 h-5" />
              {{ t('nav.upload') }}
            </RouterLink>
            <RouterLink 
              to="/history" 
              @click="closeMobileMenu"
              class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:text-teal-600 hover:bg-teal-50 font-medium transition-all"
              active-class="text-teal-600 bg-teal-50"
            >
              <ClockIcon class="w-5 h-5" />
              {{ t('nav.history') }}
            </RouterLink>
            <RouterLink 
              to="/profile" 
              @click="closeMobileMenu"
              class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:text-teal-600 hover:bg-teal-50 font-medium transition-all"
              active-class="text-teal-600 bg-teal-50"
            >
              <UserCircleIcon class="w-5 h-5" />
              <span class="text-sm font-medium">{{ t('nav.profile') }}</span>
            </RouterLink>
          </nav>

          <!-- Logout button -->
          <div class="mt-4 px-2">
            <button
              @click="handleLogout"
              class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg font-medium transition-colors"
            >
              <ArrowRightOnRectangleIcon class="w-5 h-5" />
              {{ t('auth.logout') }}
            </button>
          </div>
        </div>
      </Transition>
    </div>
  </header>
</template>
