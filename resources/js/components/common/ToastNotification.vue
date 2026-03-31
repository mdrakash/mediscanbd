<script setup>
import { computed } from 'vue'
import { useToast } from '../../composables/useToast'

const toast = useToast()

const iconMap = {
  success: {
    color: 'text-green-500',
    bg: 'bg-green-50',
    border: 'border-green-200',
    icon: 'M5 13l4 4L19 7'
  },
  error: {
    color: 'text-red-500',
    bg: 'bg-red-50',
    border: 'border-red-200',
    icon: 'M6 18L18 6M6 6l12 12'
  },
  warning: {
    color: 'text-amber-500',
    bg: 'bg-amber-50',
    border: 'border-amber-200',
    icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
  },
  info: {
    color: 'text-blue-500',
    bg: 'bg-blue-50',
    border: 'border-blue-200',
    icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
  }
}

const getConfig = (type) => iconMap[type] || iconMap.info
</script>

<template>
  <div class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 max-w-sm">
    <TransitionGroup name="toast">
      <div
        v-for="item in toast.toasts.value"
        :key="item.id"
        :class="[
          'flex items-start gap-3 p-4 rounded-lg shadow-lg border backdrop-blur-sm',
          getConfig(item.type).bg,
          getConfig(item.type).border
        ]"
      >
        <!-- Icon -->
        <div :class="['flex-shrink-0', getConfig(item.type).color]">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getConfig(item.type).icon" />
          </svg>
        </div>

        <!-- Message -->
        <div class="flex-1 pt-0.5">
          <p class="text-sm font-medium text-gray-800">{{ item.message }}</p>
        </div>

        <!-- Close button -->
        <button
          @click="toast.remove(item.id)"
          class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%) scale(0.8);
}
</style>
