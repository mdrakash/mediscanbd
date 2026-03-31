import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import router from '../router'
import { useApi } from '../composables/useApi'
import { useToast } from '../composables/useToast'

export const useAuthStore = defineStore('auth', () => {
  const api = useApi()
  const toast = useToast()

  // State
  const user = ref(null)
  const token = ref(localStorage.getItem('mediscan_token'))
  const isLoading = ref(false)
  const error = ref(null)

  // Getters
  const isLoggedIn = computed(() => !!token.value && !!user.value)

  // Actions
  async function login(tokenValue, userData = null) {
    try {
      token.value = tokenValue
      localStorage.setItem('mediscan_token', tokenValue)

      if (userData) {
        user.value = userData
        localStorage.setItem('mediscan_user', JSON.stringify(userData))
      } else {
        await fetchUser()
      }

      toast.success('Successfully logged in!')
      router.push({ name: 'Dashboard' })
    } catch (err) {
      error.value = err.message
      toast.error('Login failed. Please try again.')
      throw err
    }
  }

  async function logout() {
    try {
      isLoading.value = true
      await api.logout()
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      // Clear state regardless of API call result
      user.value = null
      token.value = null
      localStorage.removeItem('mediscan_token')
      localStorage.removeItem('mediscan_user')
      isLoading.value = false
      
      toast.info('Logged out successfully')
      router.push({ name: 'Login' })
    }
  }

  async function fetchUser() {
    try {
      isLoading.value = true
      error.value = null

      const response = await api.me()
      user.value = response.data.data
      localStorage.setItem('mediscan_user', JSON.stringify(response.data.data))

      return user.value
    } catch (err) {
      error.value = err.message
      // If fetching user fails, clear auth state
      token.value = null
      localStorage.removeItem('mediscan_token')
      localStorage.removeItem('mediscan_user')
      throw err
    } finally {
      isLoading.value = false
    }
  }

  async function initialize() {
    // Check if token exists in localStorage
    const storedToken = localStorage.getItem('mediscan_token')
    const storedUser = localStorage.getItem('mediscan_user')

    if (storedToken) {
      token.value = storedToken

      // Try to use cached user data first
      if (storedUser) {
        try {
          user.value = JSON.parse(storedUser)
        } catch (err) {
          console.error('Error parsing stored user:', err)
        }
      }

      // Fetch fresh user data in background
      try {
        await fetchUser()
      } catch (err) {
        console.error('Failed to fetch user on init:', err)
        // Token might be expired, redirect to login
        if (router.currentRoute.value.meta.requiresAuth) {
          router.push({ name: 'Login' })
        }
      }
    }
  }

  return {
    // State
    user,
    token,
    isLoading,
    error,

    // Getters
    isLoggedIn,

    // Actions
    login,
    logout,
    fetchUser,
    initialize
  }
})
