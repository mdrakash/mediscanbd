import { ref } from 'vue'
import { defineStore } from 'pinia'
import router from '../router'
import { useApi } from '../composables/useApi'
import { useToast } from '../composables/useToast'

export const useAnalysisStore = defineStore('analysis', () => {
  const api = useApi()
  const toast = useToast()

  // State
  const currentAnalysis = ref(null)
  const history = ref([])
  const pagination = ref(null)
  const isLoading = ref(false)
  const error = ref(null)

  // Actions
  async function analyze(formData) {
    try {
      isLoading.value = true
      error.value = null

      const response = await api.analyze(formData)
      currentAnalysis.value = response.data.data

      toast.success('Analysis completed successfully!')
      
      // Redirect to analysis result page
      router.push({ 
        name: 'AnalysisResult', 
        params: { id: currentAnalysis.value.id } 
      })

      return currentAnalysis.value
    } catch (err) {
      error.value = err.response?.data?.message || err.message || 'Analysis failed'
      toast.error(error.value)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  async function fetchHistory(page = 1) {
    try {
      isLoading.value = true
      error.value = null

      const response = await api.getHistory(page)
      history.value = response.data.data
      pagination.value = response.data.meta

      return history.value
    } catch (err) {
      error.value = err.response?.data?.message || err.message || 'Failed to fetch history'
      toast.error(error.value)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  async function fetchAnalysis(id) {
    try {
      isLoading.value = true
      error.value = null

      const response = await api.getAnalysis(id)
      currentAnalysis.value = response.data.data

      return currentAnalysis.value
    } catch (err) {
      error.value = err.response?.data?.message || err.message || 'Failed to fetch analysis'
      toast.error(error.value)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  async function deleteAnalysis(id) {
    try {
      isLoading.value = true
      error.value = null

      await api.deleteAnalysis(id)

      // Remove from history if present
      const index = history.value.findIndex(item => item.id === id)
      if (index > -1) {
        history.value.splice(index, 1)
      }

      // Clear current analysis if it's the deleted one
      if (currentAnalysis.value?.id === id) {
        currentAnalysis.value = null
      }

      toast.success('Analysis deleted successfully')

      return true
    } catch (err) {
      error.value = err.response?.data?.message || err.message || 'Failed to delete analysis'
      toast.error(error.value)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  function clearCurrent() {
    currentAnalysis.value = null
  }

  function clearHistory() {
    history.value = []
    pagination.value = null
  }

  return {
    // State
    currentAnalysis,
    history,
    pagination,
    isLoading,
    error,

    // Actions
    analyze,
    fetchHistory,
    fetchAnalysis,
    deleteAnalysis,
    clearCurrent,
    clearHistory
  }
})
