<script setup>
import { ref, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useAnalysisStore } from '../stores/analysis'
import { useRelativeTime } from '../composables/useRelativeTime'
import LoadingSpinner from '../components/common/LoadingSpinner.vue'

const { t } = useI18n()
const authStore = useAuthStore()
const analysisStore = useAnalysisStore()
const { formatRelative } = useRelativeTime()

// State
const isLoading = ref(true)
const stats = ref({
  total: 0,
  prescriptions: 0,
  test_reports: 0
})

// Computed
const userName = computed(() => {
  return authStore.user?.name?.split(' ')[0] || t('auth.welcome')
})

const recentAnalyses = computed(() => {
  return analysisStore.history.slice(0, 3)
})

const hasAnalyses = computed(() => {
  return analysisStore.history.length > 0
})

// Methods
async function loadDashboardData() {
  try {
    isLoading.value = true
    await analysisStore.fetchHistory(1)
    
    // Calculate stats from history
    const history = analysisStore.history
    stats.value.total = analysisStore.pagination?.total || history.length
    stats.value.prescriptions = history.filter(a => a.type === 'prescription').length
    stats.value.test_reports = history.filter(a => a.type === 'test_report').length
  } catch (error) {
    console.error('Failed to load dashboard data:', error)
  } finally {
    isLoading.value = false
  }
}

function getTypeLabel(type) {
  return type === 'prescription' ? t('upload.prescription') : t('upload.test_report')
}

function getStatusClass(status) {
  switch (status) {
    case 'completed':
      return 'bg-green-100 text-green-700'
    case 'processing':
      return 'bg-amber-100 text-amber-700'
    case 'failed':
      return 'bg-red-100 text-red-700'
    default:
      return 'bg-gray-100 text-gray-700'
  }
}

function getStatusLabel(status) {
  switch (status) {
    case 'completed':
      return t('common.completed')
    case 'processing':
      return t('common.processing')
    case 'failed':
      return t('common.failed')
    default:
      return status
  }
}

// Lifecycle
onMounted(() => {
  loadDashboardData()
})
</script>

<template>
  <div class="max-w-4xl mx-auto space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white">
      <div class="flex items-center gap-4">
        <!-- Avatar -->
        <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center overflow-hidden">
          <img
            v-if="authStore.user?.avatar"
            :src="authStore.user.avatar"
            :alt="authStore.user.name"
            class="w-full h-full object-cover"
          />
          <svg v-else class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>
        
        <div>
          <p class="text-teal-100 text-sm">{{ t('dashboard.greeting') }}</p>
          <h1 class="text-2xl font-bold">
            {{ t('dashboard.welcome') }}, {{ userName }}!
          </h1>
          <p class="text-teal-100 text-sm mt-1">{{ t('app.tagline') }}</p>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 gap-4">
      <!-- Total Analyses -->
      <div class="bg-white rounded-xl shadow-md p-4 text-center">
        <div class="w-10 h-10 mx-auto mb-2 bg-teal-100 rounded-full flex items-center justify-center">
          <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ stats.total }}</p>
        <p class="text-xs text-gray-500">{{ t('dashboard.total_analyses') }}</p>
      </div>

      <!-- Prescriptions -->
      <div class="bg-white rounded-xl shadow-md p-4 text-center">
        <div class="w-10 h-10 mx-auto mb-2 bg-blue-100 rounded-full flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
          </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ stats.prescriptions }}</p>
        <p class="text-xs text-gray-500">{{ t('dashboard.total_prescriptions') }}</p>
      </div>

      <!-- Test Reports -->
      <div class="bg-white rounded-xl shadow-md p-4 text-center">
        <div class="w-10 h-10 mx-auto mb-2 bg-amber-100 rounded-full flex items-center justify-center">
          <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21a48.309 48.309 0 01-8.135-.687c-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
          </svg>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ stats.test_reports }}</p>
        <p class="text-xs text-gray-500">{{ t('dashboard.total_reports') }}</p>
      </div>
    </div>

    <!-- Quick Action Cards -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ t('dashboard.quick_upload') }}</h2>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Upload Prescription -->
        <RouterLink
          to="/upload?type=prescription"
          class="group flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 hover:border-teal-400 hover:bg-teal-50 transition-all duration-200"
        >
          <div class="w-12 h-12 rounded-xl bg-teal-100 group-hover:bg-teal-500 flex items-center justify-center transition-colors">
            <svg class="w-6 h-6 text-teal-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
            </svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-800 group-hover:text-teal-700">{{ t('dashboard.upload_prescription') }}</h3>
            <p class="text-sm text-gray-500">{{ t('upload.prescription_desc') }}</p>
          </div>
          <svg class="w-5 h-5 text-gray-400 group-hover:text-teal-500 ml-auto transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </RouterLink>

        <!-- Upload Test Report -->
        <RouterLink
          to="/upload?type=test_report"
          class="group flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 hover:border-amber-400 hover:bg-amber-50 transition-all duration-200"
        >
          <div class="w-12 h-12 rounded-xl bg-amber-100 group-hover:bg-amber-500 flex items-center justify-center transition-colors">
            <svg class="w-6 h-6 text-amber-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21a48.309 48.309 0 01-8.135-.687c-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
            </svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-800 group-hover:text-amber-700">{{ t('dashboard.upload_test_report') }}</h3>
            <p class="text-sm text-gray-500">{{ t('upload.test_report_desc') }}</p>
          </div>
          <svg class="w-5 h-5 text-gray-400 group-hover:text-amber-500 ml-auto transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </RouterLink>
      </div>
    </div>

    <!-- Recent Analyses -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-800">{{ t('dashboard.recent_analyses') }}</h2>
        <RouterLink
          v-if="hasAnalyses"
          to="/history"
          class="text-sm text-teal-600 hover:text-teal-700 font-medium"
        >
          {{ t('dashboard.view_all_history') }} →
        </RouterLink>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="flex justify-center py-8">
        <LoadingSpinner />
      </div>

      <!-- Empty State -->
      <div v-else-if="!hasAnalyses" class="text-center py-12">
        <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
          <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <p class="text-gray-600 font-medium mb-1">{{ t('dashboard.no_analyses_yet') }}</p>
        <p class="text-sm text-gray-500 mb-4">{{ t('dashboard.start_first_analysis') }}</p>
        <RouterLink
          to="/upload"
          class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          {{ t('upload.title') }}
        </RouterLink>
      </div>

      <!-- Recent Analyses List -->
      <div v-else class="space-y-3">
        <RouterLink
          v-for="analysis in recentAnalyses"
          :key="analysis.id"
          :to="`/analysis/${analysis.id}`"
          class="group flex items-center gap-4 p-4 rounded-xl border border-gray-200 hover:border-teal-300 hover:bg-teal-50/50 transition-all"
        >
          <!-- Type Icon -->
          <div
            :class="[
              'w-10 h-10 rounded-lg flex items-center justify-center',
              analysis.type === 'prescription' ? 'bg-blue-100' : 'bg-amber-100'
            ]"
          >
            <svg
              v-if="analysis.type === 'prescription'"
              class="w-5 h-5 text-blue-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
            </svg>
            <svg
              v-else
              class="w-5 h-5 text-amber-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21a48.309 48.309 0 01-8.135-.687c-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
            </svg>
          </div>

          <!-- Info -->
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
              <p class="font-medium text-gray-800 truncate">{{ getTypeLabel(analysis.type) }}</p>
              <span
                :class="[
                  'text-xs px-2 py-0.5 rounded-full',
                  getStatusClass(analysis.status)
                ]"
              >
                {{ getStatusLabel(analysis.status) }}
              </span>
            </div>
            <p class="text-sm text-gray-500">{{ formatRelative(analysis.created_at) }}</p>
          </div>

          <!-- Arrow -->
          <svg class="w-5 h-5 text-gray-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </RouterLink>
      </div>
    </div>
  </div>
</template>
