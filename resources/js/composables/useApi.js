import axios from 'axios'
import router from '../router'

// Create axios instance
const api = axios.create({
  baseURL: '/api',
  withCredentials: true,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
})

// Request interceptor - Add token to all requests
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('mediscan_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor - Handle errors globally
api.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    // Handle 401 Unauthorized - logout user
    if (error.response?.status === 401) {
      localStorage.removeItem('mediscan_token')
      localStorage.removeItem('mediscan_user')
      router.push({ name: 'Login' })
    }

    // Handle network errors
    if (!error.response) {
      error.message = 'Network error. Please check your connection.'
    }

    return Promise.reject(error)
  }
)

export function useApi() {
  return {
    // Auth endpoints
    login: (credentials) => api.post('/login', credentials),
    register: (userData) => api.post('/register', userData),
    logout: () => api.post('/logout'),
    me: () => api.get('/me'),

    // Analysis endpoints
    analyze: (formData) => api.post('/analyze', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    }),
    getHistory: (page = 1) => api.get('/history', { params: { page } }),
    getAnalysis: (id) => api.get(`/analyses/${id}`),
    deleteAnalysis: (id) => api.delete(`/analyses/${id}`),

    // Raw axios instance for custom requests
    raw: api
  }
}
