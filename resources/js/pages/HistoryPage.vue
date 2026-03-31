<template>
  <div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">
        {{ $t('history.title') }}
      </h1>
      <p v-if="totalCount > 0" class="text-gray-600">
        {{ $t('history.total_analyses') }}: {{ totalCountDisplay }}
      </p>
    </div>

    <!-- Filter Bar -->
    <div class="mb-6 flex items-center gap-3">
      <button
        v-for="filter in filters"
        :key="filter.value"
        @click="selectedFilter = filter.value"
        :class="[
          'px-5 py-2.5 rounded-xl font-medium transition-all',
          selectedFilter === filter.value
            ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/30'
            : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'
        ]"
      >
        {{ filter.label }}
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <LoadingSpinner size="lg" />
    </div>

    <!-- Empty State -->
    <div
      v-else-if="analyses.length === 0"
      class="bg-white rounded-2xl shadow-lg p-12 text-center"
    >
      <!-- Empty Illustration -->
      <div class="mb-6 flex justify-center">
        <svg
          class="w-32 h-32 text-gray-300"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.5"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
          />
        </svg>
      </div>
      <h2 class="text-2xl font-bold text-gray-800 mb-2">
        {{ $t('history.empty') }}
      </h2>
      <p class="text-gray-600 mb-6">
        {{ $t('history.empty_desc') }}
      </p>
      <router-link
        to="/upload"
        class="inline-flex items-center gap-2 px-6 py-3 bg-teal-600 text-white font-medium rounded-xl hover:bg-teal-700 transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 4v16m8-8H4"
          />
        </svg>
        {{ $t('history.upload_first') }}
      </router-link>
    </div>

    <!-- Analysis Cards List -->
    <div v-else class="space-y-4">
      <TransitionGroup name="list">
        <div
          v-for="analysis in analyses"
          :key="analysis.id"
          class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6"
        >
          <div class="flex items-start gap-4">
            <!-- Icon -->
            <div
              :class="[
                'flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center',
                getUploadType(analysis) === 'prescription'
                  ? 'bg-teal-100 text-teal-600'
                  : 'bg-purple-100 text-purple-600'
              ]"
            >
              <!-- Prescription Icon -->
              <svg
                v-if="getUploadType(analysis) === 'prescription'"
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                />
              </svg>
              <!-- Test Report Icon -->
              <svg
                v-else
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
              <!-- Type Badge -->
              <div class="flex items-center gap-2 mb-2">
                <span
                  :class="[
                    'inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium',
                    getUploadType(analysis) === 'prescription'
                      ? 'bg-teal-100 text-teal-700'
                      : 'bg-purple-100 text-purple-700'
                  ]"
                >
                  {{
                    getUploadType(analysis) === 'prescription'
                      ? $t('upload.prescription')
                      : $t('upload.test_report')
                  }}
                </span>
                <!-- Language Badge -->
                <span
                  class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700"
                >
                  {{ analysis.language === 'bn' ? 'বাংলা' : 'English' }}
                </span>
              </div>

              <!-- File Name -->
              <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate">
                {{ getUploadFilename(analysis) }}
              </h3>

              <!-- Date -->
              <p class="text-sm text-gray-500 mb-3">
                {{ formatRelative(analysis.created_at) }}
              </p>

              <!-- Details -->
              <div class="flex items-center gap-4 text-sm">
                <!-- For Prescription: Medicine count -->
                <div
                  v-if="getUploadType(analysis) === 'prescription' && analysis.medicines"
                  class="flex items-center gap-1 text-gray-600"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                    />
                  </svg>
                  <span>{{ getMedicineCount(analysis) }}</span>
                </div>

                <!-- For Test Report: Report type and status -->
                <div
                  v-if="getUploadType(analysis) === 'test_report'"
                  class="flex items-center gap-3"
                >
                  <span v-if="analysis.report_type" class="text-gray-600">
                    {{ analysis.report_type }}
                  </span>
                  <span
                    v-if="analysis.parameters && analysis.parameters.overall_status"
                    :class="[
                      'inline-flex items-center px-2 py-1 rounded-md text-xs font-medium',
                      getStatusClass(analysis.parameters.overall_status)
                    ]"
                  >
                    {{ getStatusLabel(analysis.parameters.overall_status) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 flex-shrink-0">
              <!-- View Button -->
              <router-link
                :to="`/analysis/${analysis.id}`"
                class="inline-flex items-center gap-1 px-4 py-2 bg-teal-600 text-white font-medium rounded-xl hover:bg-teal-700 transition-colors text-sm"
              >
                {{ $t('history.view_details') }}
              </router-link>

              <!-- Delete Button -->
              <button
                @click="confirmDelete(analysis)"
                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors"
                :title="$t('history.delete_analysis')"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </TransitionGroup>
    </div>

    <!-- Pagination -->
    <div
      v-if="!loading && totalPages > 1"
      class="mt-8 flex items-center justify-between"
    >
      <!-- Info Text -->
      <p class="text-sm text-gray-600">
        {{ getPaginationInfo() }}
      </p>

      <!-- Pagination Controls -->
      <div class="flex items-center gap-2">
        <!-- Previous Button -->
        <button
          @click="goToPage(currentPage - 1)"
          :disabled="currentPage === 1"
          :class="[
            'px-4 py-2 rounded-lg font-medium text-sm transition-colors',
            currentPage === 1
              ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
              : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'
          ]"
        >
          {{ $t('history.previous') }}
        </button>

        <!-- Page Numbers -->
        <div class="flex items-center gap-1">
          <button
            v-for="page in visiblePages"
            :key="page"
            @click="goToPage(page)"
            :class="[
              'w-10 h-10 rounded-lg font-medium text-sm transition-colors',
              page === currentPage
                ? 'bg-teal-600 text-white'
                : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'
            ]"
          >
            {{ formatPageNumber(page) }}
          </button>
        </div>

        <!-- Next Button -->
        <button
          @click="goToPage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          :class="[
            'px-4 py-2 rounded-lg font-medium text-sm transition-colors',
            currentPage === totalPages
              ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
              : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'
          ]"
        >
          {{ $t('history.next') }}
        </button>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <ConfirmModal
      :is-open="showDeleteModal"
      :title="$t('history.delete_confirm_title')"
      :message="$t('history.delete_confirm_msg')"
      :confirm-text="$t('common.delete')"
      confirm-class="bg-red-600 hover:bg-red-700 focus:ring-red-500"
      @confirm="handleDelete"
      @cancel="cancelDelete"
    />
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'
import { useApi } from '../composables/useApi'
import { useToast } from '../composables/useToast'
import { useRelativeTime } from '../composables/useRelativeTime'
import { useLanguageStore } from '../stores/language'
import LoadingSpinner from '../components/common/LoadingSpinner.vue'
import ConfirmModal from '../components/common/ConfirmModal.vue'

const { t, locale } = useI18n()
const router = useRouter()
const api = useApi()
const toast = useToast()
const { formatRelative } = useRelativeTime()
const languageStore = useLanguageStore()

// State
const loading = ref(true)
const analyses = ref([])
const currentPage = ref(1)
const totalPages = ref(1)
const totalCount = ref(0)
const perPage = ref(10)
const selectedFilter = ref('all')
const showDeleteModal = ref(false)
const analysisToDelete = ref(null)

// Filters
const filters = computed(() => [
  { value: 'all', label: t('history.filter_all') },
  { value: 'prescription', label: t('history.filter_prescription') },
  { value: 'test_report', label: t('history.filter_test_report') }
])

// Computed: Total count with Bangla numbers
const totalCountDisplay = computed(() => {
  if (locale.value === 'bn' || languageStore.current === 'bn') {
    return toBanglaNumber(totalCount.value)
  }
  return totalCount.value
})

// Computed: Visible page numbers (max 5)
const visiblePages = computed(() => {
  const pages = []
  const maxVisible = 5
  const halfVisible = Math.floor(maxVisible / 2)
  
  let startPage = Math.max(1, currentPage.value - halfVisible)
  let endPage = Math.min(totalPages.value, startPage + maxVisible - 1)
  
  // Adjust start if we're near the end
  if (endPage - startPage < maxVisible - 1) {
    startPage = Math.max(1, endPage - maxVisible + 1)
  }
  
  for (let i = startPage; i <= endPage; i++) {
    pages.push(i)
  }
  
  return pages
})

// Convert number to Bangla digits
const toBanglaNumber = (num) => {
  const banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯']
  return String(num)
    .split('')
    .map((digit) => (isNaN(digit) ? digit : banglaDigits[parseInt(digit)]))
    .join('')
}

// Format page number based on locale
const formatPageNumber = (num) => {
  if (locale.value === 'bn' || languageStore.current === 'bn') {
    return toBanglaNumber(num)
  }
  return num
}

// Get upload type safely (handles both nested and flat structure)
const getUploadType = (analysis) => {
  return analysis?.upload?.type || analysis?.type || 'prescription'
}

// Get upload filename safely
const getUploadFilename = (analysis) => {
  return analysis?.upload?.original_filename || analysis?.original_filename || 'Unknown file'
}

// Get medicine count
const getMedicineCount = (analysis) => {
  const count = Array.isArray(analysis.medicines) ? analysis.medicines.length : 0
  const countDisplay = locale.value === 'bn' || languageStore.current === 'bn' 
    ? toBanglaNumber(count) 
    : count
  return t('history.medicines', { count: countDisplay })
}

// Get status class for test reports
const getStatusClass = (status) => {
  const statusMap = {
    normal: 'bg-green-100 text-green-700',
    mildly_abnormal: 'bg-yellow-100 text-yellow-700',
    moderately_abnormal: 'bg-orange-100 text-orange-700',
    severely_abnormal: 'bg-red-100 text-red-700'
  }
  return statusMap[status] || 'bg-gray-100 text-gray-700'
}

// Get status label
const getStatusLabel = (status) => {
  const statusMap = {
    normal: t('analysis.normal'),
    mildly_abnormal: t('analysis.status_mildly_abnormal'),
    moderately_abnormal: t('analysis.status_moderately_abnormal'),
    severely_abnormal: t('analysis.status_severely_abnormal')
  }
  return statusMap[status] || status
}

// Get pagination info text
const getPaginationInfo = () => {
  const from = (currentPage.value - 1) * perPage.value + 1
  const to = Math.min(currentPage.value * perPage.value, totalCount.value)
  const total = totalCount.value

  const fromDisplay = locale.value === 'bn' || languageStore.current === 'bn'
    ? toBanglaNumber(from)
    : from
  const toDisplay = locale.value === 'bn' || languageStore.current === 'bn'
    ? toBanglaNumber(to)
    : to
  const totalDisplay = locale.value === 'bn' || languageStore.current === 'bn'
    ? toBanglaNumber(total)
    : total

  return t('history.showing', { from: fromDisplay, to: toDisplay, total: totalDisplay })
}

// Fetch analyses from API
const fetchAnalyses = async () => {
  loading.value = true
  try {
    const params = {
      page: currentPage.value
    }

    // Add filter if not 'all'
    if (selectedFilter.value !== 'all') {
      params.type = selectedFilter.value
    }

    const response = await api.getHistory(params)
    
    // Debug: Log the response structure
    console.log('API Response:', response)
    console.log('Response data:', response.data)

    // Laravel paginated response structure
    const data = response.data
    analyses.value = data.data || []
    currentPage.value = data.meta?.current_page || data.current_page || 1
    totalPages.value = data.meta?.last_page || data.last_page || 1
    totalCount.value = data.meta?.total || data.total || 0
    
    console.log('Analyses loaded:', analyses.value)
  } catch (error) {
    console.error('Failed to fetch analyses:', error)
    console.error('Error response:', error.response)
    toast.error(t('errors.load_failed'))
  } finally {
    loading.value = false
  }
}

// Go to specific page
const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

// Confirm delete
const confirmDelete = (analysis) => {
  analysisToDelete.value = analysis
  showDeleteModal.value = true
}

// Cancel delete
const cancelDelete = () => {
  showDeleteModal.value = false
  analysisToDelete.value = null
}

// Handle delete
const handleDelete = async () => {
  if (!analysisToDelete.value) return

  try {
    await api.deleteAnalysis(analysisToDelete.value.id)

    toast.success(t('history.deleted_success'))
    
    // Remove from list
    analyses.value = analyses.value.filter(a => a.id !== analysisToDelete.value.id)
    totalCount.value--

    // If current page is empty and not the first page, go to previous page
    if (analyses.value.length === 0 && currentPage.value > 1) {
      currentPage.value--
      await fetchAnalyses()
    }
  } catch (error) {
    console.error('Failed to delete analysis:', error)
    toast.error(t('history.delete_failed'))
  } finally {
    showDeleteModal.value = false
    analysisToDelete.value = null
  }
}

// Watch for filter changes
watch(selectedFilter, () => {
  currentPage.value = 1
  fetchAnalyses()
})

// Watch for page changes
watch(currentPage, () => {
  fetchAnalyses()
})

// Initial fetch
onMounted(() => {
  fetchAnalyses()
})
</script>

<style scoped>
/* List transition animations */
.list-enter-active,
.list-leave-active {
  transition: all 0.3s ease;
}

.list-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}

.list-leave-to {
  opacity: 0;
  transform: translateX(-20px);
}

.list-move {
  transition: transform 0.3s ease;
}
</style>
