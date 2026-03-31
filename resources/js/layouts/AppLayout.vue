<script setup>
import { RouterView } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import AppHeader from '../components/common/AppHeader.vue'

const authStore = useAuthStore()
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-teal-50/30 to-amber-50/20 relative">
    <!-- Medical pattern background -->
    <div class="absolute inset-0 opacity-[0.02] pointer-events-none medical-pattern"></div>
    
    <AppHeader />
    
    <main class="container mx-auto px-4 py-8 max-w-7xl relative">
      <RouterView />
    </main>

    <footer class="mt-auto py-6 border-t border-teal-100 bg-white/50 backdrop-blur-sm relative">
      <div class="container mx-auto px-4 text-center text-sm text-gray-600">
        <p>&copy; {{ new Date().getFullYear() }} MediScan BD. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.medical-pattern {
  background-image: 
    repeating-linear-gradient(0deg, currentColor 0px, currentColor 2px, transparent 2px, transparent 40px),
    repeating-linear-gradient(90deg, currentColor 0px, currentColor 2px, transparent 2px, transparent 40px),
    repeating-linear-gradient(0deg, currentColor 0px, currentColor 2px, transparent 2px, transparent 20px),
    repeating-linear-gradient(90deg, currentColor 0px, currentColor 2px, transparent 2px, transparent 20px);
  background-size: 40px 40px, 40px 40px, 20px 20px, 20px 20px;
  background-position: 0 0, 0 0, 10px 10px, 10px 10px;
  color: #0d9488;
}
</style>
