<script setup>
import { ref } from 'vue'

// Props
const props = defineProps({
  title: {
    type: String,
    required: true
  },
  icon: {
    type: String,
    default: ''
  },
  defaultOpen: {
    type: Boolean,
    default: true
  },
  headerClass: {
    type: String,
    default: ''
  },
  contentClass: {
    type: String,
    default: ''
  }
})

// State
const isOpen = ref(props.defaultOpen)

// Methods
function toggle() {
  isOpen.value = !isOpen.value
}
</script>

<template>
  <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <!-- Header -->
    <button
      type="button"
      @click="toggle"
      :class="[
        'w-full flex items-center justify-between p-4 sm:p-5 text-left transition-colors',
        'hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-teal-500',
        headerClass
      ]"
    >
      <div class="flex items-center gap-3">
        <span v-if="icon" class="text-xl">{{ icon }}</span>
        <h3 class="text-lg font-semibold text-gray-800">{{ title }}</h3>
      </div>
      
      <svg
        :class="['w-5 h-5 text-gray-500 transition-transform duration-200', isOpen ? 'rotate-180' : '']"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>
    
    <!-- Content -->
    <Transition name="collapse">
      <div v-show="isOpen" class="overflow-hidden">
        <div :class="['px-4 pb-4 sm:px-5 sm:pb-5', contentClass]">
          <slot />
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.collapse-enter-active,
.collapse-leave-active {
  transition: all 0.3s ease;
  max-height: 2000px;
}

.collapse-enter-from,
.collapse-leave-to {
  opacity: 0;
  max-height: 0;
}

/* Print: always show content */
@media print {
  .overflow-hidden {
    overflow: visible !important;
  }
}
</style>
