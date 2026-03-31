<script setup>
import { onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useToast } from '../composables/useToast'
import LoadingSpinner from '../components/common/LoadingSpinner.vue'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()
const toast = useToast()

onMounted(async () => {
  const token = route.query.token
  const user = route.query.user ? JSON.parse(decodeURIComponent(route.query.user)) : null

  if (token) {
    try {
      await authStore.login(token, user)
      // Success handled by auth store (redirect to dashboard)
    } catch (error) {
      console.error('Login failed:', error)
      toast.error('Login failed. Please try again.')
      router.push({ name: 'Login' })
    }
  } else {
    toast.error('Invalid login callback. Please try again.')
    router.push({ name: 'Login' })
  }
})
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-600 via-teal-500 to-amber-400 relative overflow-hidden">
    <!-- Animated background circles -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-20 left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute bottom-20 right-20 w-96 h-96 bg-amber-300/20 rounded-full blur-3xl animate-pulse"></div>
    </div>

    <!-- Content -->
    <div class="text-center relative z-10 bg-white/10 backdrop-blur-md rounded-2xl p-12 border border-white/20 shadow-2xl">
      <LoadingSpinner size="lg" color="white" />
      <p class="mt-6 text-white text-lg font-medium">Processing login...</p>
      <p class="mt-2 text-teal-100 text-sm">Please wait while we log you in</p>
    </div>
  </div>
</template>
