<script setup>
import { onMounted } from 'vue'
import { RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'
import ToastNotification from './components/common/ToastNotification.vue'

const authStore = useAuthStore()

onMounted(() => {
  authStore.initialize()
})
</script>

<template>
  <div id="mediscan-app" class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-amber-50">
    <RouterView />
    <ToastNotification />
  </div>
</template>
