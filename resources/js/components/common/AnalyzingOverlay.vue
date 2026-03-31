<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useLanguageStore } from '../../stores/language'

const { t, locale } = useI18n()
const languageStore = useLanguageStore()

// Props
defineProps({
  show: {
    type: Boolean,
    default: false
  }
})

// Progress messages
const messagesBn = [
  'ছবি প্রক্রিয়া করা হচ্ছে...',
  'AI বিশ্লেষণ চলছে...',
  'ফলাফল প্রস্তুত হচ্ছে...'
]

const messagesEn = [
  'Processing image...',
  'AI analyzing...',
  'Preparing results...'
]

// State
const currentMessageIndex = ref(0)
let messageInterval = null

// Computed
const currentMessage = computed(() => {
  const currentLocale = locale.value || languageStore.current
  const messages = currentLocale === 'bn' ? messagesBn : messagesEn
  return messages[currentMessageIndex.value]
})

const waitMessage = computed(() => {
  return t('analyzing.please_wait')
})

// Methods
function cycleMessages() {
  messageInterval = setInterval(() => {
    currentMessageIndex.value = (currentMessageIndex.value + 1) % 3
  }, 2000)
}

function stopCycling() {
  if (messageInterval) {
    clearInterval(messageInterval)
    messageInterval = null
  }
}

// Lifecycle
onMounted(() => {
  cycleMessages()
})

onUnmounted(() => {
  stopCycling()
})
</script>

<template>
  <Teleport to="body">
    <Transition name="fade">
      <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
      >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm"></div>

        <!-- Content Card -->
        <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center animate-scaleIn">
          <!-- Animated Medical Icon -->
          <div class="relative w-24 h-24 mx-auto mb-6">
            <!-- Outer rotating ring -->
            <div class="absolute inset-0 rounded-full border-4 border-teal-200 animate-spin-slow"></div>
            
            <!-- Inner pulsing circle -->
            <div class="absolute inset-2 bg-gradient-to-br from-teal-400 to-teal-600 rounded-full animate-pulse-slow flex items-center justify-center">
              <!-- Medical Cross Icon -->
              <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
              </svg>
            </div>

            <!-- Orbiting dots -->
            <div class="absolute inset-0 animate-spin-reverse">
              <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1 w-2 h-2 bg-teal-500 rounded-full"></div>
            </div>
            <div class="absolute inset-0 animate-spin-slow">
              <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1 w-2 h-2 bg-amber-500 rounded-full"></div>
            </div>
          </div>

          <!-- Progress Message -->
          <div class="min-h-[60px]">
            <p class="text-lg font-semibold text-gray-800 mb-2 transition-opacity duration-300">
              {{ currentMessage }}
            </p>
            <p class="text-sm text-gray-500">
              {{ waitMessage }}
            </p>
          </div>

          <!-- Progress Dots -->
          <div class="flex justify-center gap-2 mt-6">
            <span
              v-for="i in 3"
              :key="i"
              :class="[
                'w-2 h-2 rounded-full transition-all duration-300',
                currentMessageIndex === i - 1
                  ? 'bg-teal-500 scale-125'
                  : 'bg-gray-300'
              ]"
            ></span>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
/* Fade transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Scale in animation for card */
@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-scaleIn {
  animation: scaleIn 0.3s ease-out;
}

/* Slow spin animation */
@keyframes spin-slow {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin-slow {
  animation: spin-slow 3s linear infinite;
}

/* Reverse spin animation */
@keyframes spin-reverse {
  from {
    transform: rotate(360deg);
  }
  to {
    transform: rotate(0deg);
  }
}

.animate-spin-reverse {
  animation: spin-reverse 2s linear infinite;
}

/* Slow pulse animation */
@keyframes pulse-slow {
  0%, 100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.05);
    opacity: 0.9;
  }
}

.animate-pulse-slow {
  animation: pulse-slow 2s ease-in-out infinite;
}
</style>
