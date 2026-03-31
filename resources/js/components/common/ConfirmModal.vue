<template>
  <Teleport to="body">
    <Transition name="modal">
      <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @click.self="handleBackdropClick"
        @keydown.esc="handleEscape"
      >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <!-- Modal Card -->
        <div
          ref="modalRef"
          class="relative z-10 w-full max-w-md transform rounded-2xl bg-white p-6 shadow-xl transition-all"
          role="dialog"
          aria-modal="true"
          :aria-labelledby="ariaLabelId"
          tabindex="0"
        >
          <!-- Title -->
          <h3
            :id="ariaLabelId"
            class="text-lg font-bold text-gray-900 mb-2"
          >
            {{ title }}
          </h3>

          <!-- Message -->
          <p class="text-gray-600 mb-6">
            {{ message }}
          </p>

          <!-- Actions -->
          <div class="flex items-center justify-end gap-3">
            <!-- Cancel Button -->
            <button
              type="button"
              @click="handleCancel"
              class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300"
            >
              {{ cancelText || $t('common.cancel') }}
            </button>

            <!-- Confirm Button -->
            <button
              type="button"
              @click="handleConfirm"
              :class="[
                'px-5 py-2.5 text-sm font-medium text-white rounded-xl transition-colors focus:outline-none focus:ring-2',
                confirmClass || 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
              ]"
            >
              {{ confirmText || $t('common.confirm') }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch, nextTick, computed } from 'vue'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    required: true
  },
  message: {
    type: String,
    required: true
  },
  confirmText: {
    type: String,
    default: ''
  },
  cancelText: {
    type: String,
    default: ''
  },
  confirmClass: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['confirm', 'cancel'])

const modalRef = ref(null)
const ariaLabelId = computed(() => `modal-title-${Math.random().toString(36).substr(2, 9)}`)

// Focus trap: focus modal when opened
watch(() => props.isOpen, async (newValue) => {
  if (newValue) {
    await nextTick()
    modalRef.value?.focus()
    // Prevent body scroll
    document.body.style.overflow = 'hidden'
  } else {
    // Restore body scroll
    document.body.style.overflow = ''
  }
})

const handleConfirm = () => {
  emit('confirm')
}

const handleCancel = () => {
  emit('cancel')
}

const handleBackdropClick = () => {
  emit('cancel')
}

const handleEscape = (event) => {
  if (event.key === 'Escape') {
    emit('cancel')
  }
}
</script>

<style scoped>
/* Modal transition animations */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.25s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active .relative,
.modal-leave-active .relative {
  transition: transform 0.25s ease, opacity 0.25s ease;
}

.modal-enter-from .relative,
.modal-leave-to .relative {
  transform: scale(0.95);
  opacity: 0;
}
</style>
